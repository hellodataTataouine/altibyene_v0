<?php

namespace Modules\BasicPayment\app\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\BankInformationRequest;
use App\Jobs\DefaultMailJob;
use App\Mail\DefaultMail;
use App\Models\Course;
use App\Models\CoursSession;
use App\Traits\GetGlobalInformationTrait;
use App\Traits\MailSenderTrait;
use Closure;
use Exception;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Modules\BasicPayment\app\Enums\BasicPaymentSupportedCurrencyListEnum;
use Modules\BasicPayment\app\Http\Controllers\FrontPaymentController;
use Modules\GlobalSetting\app\Models\EmailTemplate;
use Modules\Order\app\Models\Enrollment;
use Modules\Order\app\Models\Order;
use Modules\Order\app\Models\OrderItem;
use Razorpay\Api\Api;

class PaymentController extends Controller {
    use GetGlobalInformationTrait, MailSenderTrait;
    private $paymentService;
    public function __construct() {
        $this->paymentService = app(\Modules\BasicPayment\app\Services\PaymentMethodService::class);
        $this->middleware(function (Request $request, Closure $next) {
            if (session()->has('order') || Route::is('payment') || Route::is('place.order') || Route::is('pay-via-free-gateway')) {
                return $next($request);
            }
            return redirect()->back()->with(['messege' => __('Not Found!'), 'alert-type' => 'error']);
        });
    }
    public function placeOrder($method) {
        $user = userAuth();

        $activeGateways = array_keys($this->paymentService->getActiveGatewaysWithDetails());
        if (!in_array($method, $activeGateways)) {
            return response()->json(['status' => false, 'messege' => __('The selected payment method is now inactive.')]);
        }
        if (!$this->paymentService->isCurrencySupported($method)) {
            $supportedCurrencies = $this->paymentService->getSupportedCurrencies($method);
            return response()->json(['status' => false, 'messege' => __('You are trying to use unsupported currency'), 'supportCurrency' => sprintf(
                '%s %s: %s',
                strtoupper($method),
                __('supports only these types of currencies'),
                implode(', ', $supportedCurrencies)
            )]);
        }
        $carts = $user->carts()->with('course:id,title,slug,price,discount')->get(['id', 'user_id', 'course_id']);

        try {
            $payable_amount = Session::get('payable_amount');
            $calculatePayableCharge = $this->paymentService->getPayableAmount($method, $payable_amount);

            DB::beginTransaction();

            $paid_amount = $calculatePayableCharge?->payable_amount + $calculatePayableCharge?->gateway_charge;

            if (in_array($method, ['Razorpay', 'Stripe'])) {
                $allCurrencyCodes = BasicPaymentSupportedCurrencyListEnum::getStripeSupportedCurrencies();

                if (in_array(Str::upper($calculatePayableCharge?->currency_code), $allCurrencyCodes['non_zero_currency_codes'])) {
                    $paid_amount = $paid_amount;
                } elseif (in_array(Str::upper($calculatePayableCharge?->currency_code), $allCurrencyCodes['three_digit_currency_codes'])) {
                    $paid_amount = (int) rtrim(strval($paid_amount), '0');
                } else {
                    $paid_amount = floatval($paid_amount / 100);
                }
            }

            $order = Order::create([
                'invoice_id'              => Str::random(10),
                'buyer_id'                => $user->id,
                'has_coupon'              => Session::has('coupon_code') ? 1 : 0,
                'coupon_code'             => Session::get('coupon_code'),
                'coupon_discount_percent' => Session::get('offer_percentage'),
                'coupon_discount_amount'  => Session::get('coupon_discount_amount'),
                'payment_method'          => $method,
                'payment_status'          => 'pending',
                'payable_amount'          => $payable_amount,
                'gateway_charge'          => $calculatePayableCharge?->gateway_charge,
                'payable_with_charge'     => $calculatePayableCharge?->payable_with_charge,
                'paid_amount'             => $paid_amount,
                'payable_currency'        => $calculatePayableCharge?->currency_code,
                'conversion_rate'         => Session::get('currency_rate', 1),
                'commission_rate'         => Cache::get('setting')->commission_rate,
            ]);

            $data_layer_order_items = [];
            $session_id = session()->get('session_id');
            foreach ($carts as $item) {
                $order_item = [
                    'order_id'        => $order->id,
                    'price'           => $item->course->price,
                    'course_id'       => $item->course->id,
                    'commission_rate' => Cache::get('setting')->commission_rate,
                    'session_id'      => $session_id,
                ];
                OrderItem::create([
                    'order_id'        => $order->id,
                    'price'           => $item->course->price,
                    'course_id'       => $item->course->id,
                    'commission_rate' => Cache::get('setting')->commission_rate,
                    'session_id'      => $session_id,
                ]);
                $data_layer_order_items[] = [
                    'course_name' => $item->course->title,
                    'price'       => currency($item->course->price),
                    'url'         => route('course.show', $item->course->slug),
                ];

                // insert instructor commission to his wallet
                $commissionAmount = $item->course->price * ($order->commission_rate / 100);
                $amountAfterCommission = $item->course->price - $commissionAmount;
                $instructor = Course::find($item->course->id)->instructor;
                $instructor->increment('wallet_balance', $amountAfterCommission);

            }

            DB::commit();
            $user->carts()->delete();

            $settings = cache()->get('setting');
            $marketingSettings = cache()->get('marketing_setting');
            if ($user && $settings->google_tagmanager_status == 'active' && $marketingSettings->order_success) {
                $order_success = [
                    'invoice_id'       => $order->invoice_id,
                    'transaction_id'   => $order->transaction_id,
                    'payment_method'   => $order->payment_method,
                    'payable_currency' => $order->payable_currency,
                    'paid_amount'      => $order->paid_amount,
                    'payment_status'   => $order->payment_status,
                    'order_items'      => $data_layer_order_items,
                    'student_info'     => [
                        'name'  => $user->name,
                        'email' => $user->email,
                    ],
                ];
                session()->put('enrollSuccess', $order_success);
            }
            // send mail
            $this->handleMailSending([
                'email'          => $user->email,
                'name'           => $user->name,
                'order_id'       => $order->invoice_id,
                'paid_amount'    => $order->paid_amount . ' ' . $order->payable_currency,
                'payment_method' => $order->payment_method,
            ]);

            return response()->json(['success' => true, 'invoice_id' => $order?->invoice_id]);
        } catch (Exception $e) {
            DB::rollBack();
            $data_layer_order_items = [];
            foreach ($carts as $item) {
                $data_layer_order_items[] = [
                    'course_name' => $item->course->name,
                    'price'       => currency($item->course->price),
                    'url'         => route('course.show', $item->course->slug),
                ];
            }

            $settings = cache()->get('setting');
            $marketingSettings = cache()->get('marketing_setting');
            if ($settings->google_tagmanager_status == 'active' && $marketingSettings->order_failed) {
                $user = userAuth();
                $order_failed = [
                    'payable_currency' => session('payable_currency', getSessionCurrency()),
                    'paid_amount'      => session('paid_amount', null),
                    'payment_status'   => 'Failed',
                    'order_items'      => $data_layer_order_items,
                    'student_info'     => [
                        'name'  => $user->name,
                        'email' => $user->email,
                    ],
                ];
                session()->put('enrollFailed', $order_failed);
            }
            info($e->getMessage());
            return response()->json(['status' => false, 'messege' => __('Payment Failed')]);
        }

    }
    public function index() {
        $invoice_id = request('invoice_id', null);
        $user = userAuth();

        $order = $user?->orders()
            ->where('invoice_id', $invoice_id)
            ->where('status', 'pending')->first();

        if (!$order) {
            $notification = [
                'messege'    => __('Not Found!'),
                'alert-type' => 'error',
            ];
            return redirect()->back()->with($notification);
        }
        $paymentMethod = $order->payment_method;
        if (!$this->paymentService->isActive($paymentMethod)) {
            $notification = [
                'messege'    => __('The selected payment method is now inactive.'),
                'alert-type' => 'error',
            ];
            return redirect()->back()->with($notification);
        }

        $calculatePayableCharge = $this->paymentService->getPayableAmount($paymentMethod, $order?->payable_amount, $order?->payable_currency);

        Session::put('order', $order);
        Session::put('payable_currency', $order?->payable_currency);
        Session::put('paid_amount', $calculatePayableCharge?->payable_with_charge);

        $paymentService = $this->paymentService;
        $view = $this->paymentService->getBladeView($paymentMethod);
        return view($view, compact('order', 'paymentService', 'paymentMethod'));
    }

    public function pay_via_bank(Request $request)
    {
        // Validate inputs according to your blade form
        $request->validate([
            'installments' => 'required|integer|min:1|max:4',
            'payment_amounts' => 'required|array',
            'payment_amounts.*' => 'required|numeric|min:0.01',
            'payment_dates' => 'required|array',
            'payment_dates.*' => 'required|date',
            'accept_terms' => 'accepted',
            'comment' => 'nullable|string|max:1000',
        ]);

        // Build the payment details array based on what the blade sends
        $bankDetailsArray = [
            'method' => 'bank_transfer',
            'installments' => $request->installments,
            'payments' => [],
            'comment' => $request->comment,
            'transaction' => 'bank_txn_' . now()->timestamp . '_' . uniqid(),
        ];

        for ($i = 0; $i < $request->installments; $i++) {
            $bankDetailsArray['payments'][] = [
                'amount' => $request->payment_amounts[$i],
                'date' => $request->payment_dates[$i],
            ];
        }

        $bankDetailsJson = json_encode($bankDetailsArray);

        // You can check for duplicate transaction if needed, but with unique txn id, usually no need

        Session::put('after_success_transaction', $bankDetailsArray['transaction']);
        Session::put('payment_details', $bankDetailsJson);

        return $this->payment_success();
    }

    public function pay_via_cash(Request $request)
    {
        // Validation
        $request->validate([
            'installments'      => 'required|integer|min:1|max:4',
            'payment_amounts'   => 'required|array',
            'payment_amounts.*' => 'required|numeric|min:0.01',
            'payment_dates'     => 'required|array',
            'payment_dates.*'   => 'required|date',
            'accept_terms'      => 'accepted',
            'comment'           => 'nullable|string|max:1000',
        ]);

        $installments = $request->input('installments');
        $amounts = $request->input('payment_amounts');
        $dates = $request->input('payment_dates');
        $comment = $request->input('comment');

        // Vérifier que le nombre de montants et de dates correspond au nombre d'échéances choisi
        if (count($amounts) !== $installments || count($dates) !== $installments) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['payment' => 'Le nombre de montants et de dates doit correspondre au nombre de paiements sélectionnés.']);
        }

        // Construire le détail complet du paiement cash
        $cashDetailsArray = [
            'method'        => 'cash_payment',
            'transaction'   => 'cash_txn_' . now()->timestamp . '_' . uniqid(), // identifiant unique
            'installments'  => $installments,
            'payments'      => [], // chaque paiement aura montant + date
            'comment'       => $comment,
        ];

        for ($i = 0; $i < $installments; $i++) {
            $cashDetailsArray['payments'][] = [
                'amount' => $amounts[$i],
                'date'   => $dates[$i],
            ];
        }

        $cashDetails = json_encode($cashDetailsArray);

        // Ici on peut vérifier doublons si tu as une logique plus fiable,
        // mais avec un transaction id unique généré à la volée ça n'a pas trop de sens.

        // Stocker en session pour traitement post paiement
        Session::put('after_success_transaction', $cashDetailsArray['transaction']);
        Session::put('payment_details', $cashDetails);

        // Appeler la méthode de succès qui finalise la commande
        return $this->payment_success();
    }


    public function pay_via_cheque(Request $request)
    {

        // ✅ Étape 1 : Validation des champs
        $request->validate([
            'holder_name'      => 'required|string|max:255',
            'installments'     => 'required|integer|min:1|max:4',
            'payment_amounts'  => 'required|array|min:1',
            'payment_amounts.*'=> 'required|numeric|min:0.01',
            'payment_dates'    => 'required|array|min:1',
            'payment_dates.*'  => 'required|date|after_or_equal:today',
            'cheque_count'     => 'required|integer|min:1',
            'comment'          => 'nullable|string|max:1000',
        ]);

        // ✅ Étape 2 : Créer un tableau structuré des paiements
        $payments = [];
        foreach ($request->payment_amounts as $index => $amount) {
            $payments[] = [
                'amount' => $amount,
                'date'   => $request->payment_dates[$index] ?? null,
            ];
        }

        // ✅ Étape 3 : Vérifier si une transaction similaire existe (par référence)
        if (!empty($request->reference)) {
            $duplicate = Order::whereNotNull('payment_details')
                ->get()
                ->first(function ($order) use ($request) {
                    $details = json_decode($order->payment_details, true);
                    return isset($details['reference']) && $details['reference'] == $request->reference;
                });

            if ($duplicate) {
                $notification = ['messege' => __('Payment failed, a transaction with the same reference already exists.'), 'alert-type' => 'error'];
                return redirect()->back()->with($notification);
            }
        }

        // ✅ Étape 4 : Enregistrer les informations de paiement dans la session
        $chequeDetails = json_encode([
            'holder_name'  => $request->holder_name,
            'installments' => $request->installments,
            'cheque_count' => $request->cheque_count,
            'payments'     => $payments, // tableau avec montants & dates
            'comment'      => $request->comment ?? null,
        ]);

        Session::put('after_success_transaction', $request->reference ?? ('cheque_' . now()->timestamp));
        Session::put('payment_details', $chequeDetails);

        // ✅ Étape 5 : Continuer le traitement après le paiement
        return $this->payment_success();
    }

    public function pay_via_free_gateway() {
        $user = userAuth();
        if (!$user->cart_total || $user->cart_total != 0) {
            $notification = trans('Payment faild, please try again');
            $notification = ['messege' => $notification, 'alert-type' => 'error'];
            return redirect()->back()->with($notification);
        }
        Session::put('after_success_transaction', Str::random(10));


        try {
            $payable_amount = Session::get('payable_amount');

            DB::beginTransaction();

            $order = Order::create([
                'invoice_id'          => Str::random(10),
                'buyer_id'            => $user->id,
                'payment_method'      => 'Free',
                'status'      => 'completed',
                'payment_status'      => 'paid',
                'payable_amount'      => $payable_amount,
                'gateway_charge'      => 0,
                'payable_with_charge' => $payable_amount,
                'paid_amount'         => $payable_amount,
                'payable_currency'    => getSessionCurrency(),
                'transaction_id'    => Str::random(10),
            ]);
            $carts = $user->carts()->with('course:id,title,slug,price,discount')->get(['id', 'user_id', 'course_id']);

            foreach ($carts as $item) {
                OrderItem::create([
                    'order_id'        => $order->id,
                    'price'           => $item->course->price,
                    'course_id'       => $item->course->id,
                ]);
                Enrollment::create([
                    'order_id'   => $order->id,
                    'user_id'    => $user->id,
                    'course_id'  => $item->course->id,
                    'has_access' => 1,
                ]);

            }

            DB::commit();
            $user->carts()->delete();

            $notification = trans('Payment Success.');
            $notification = ['messege' => $notification, 'alert-type' => 'success'];
            return view('frontend.pages.order-success')->with($notification);
        } catch (Exception $e) {
            DB::rollBack();
            info($e->getMessage());
            $notification = __('Payment faild, please try again');
            $notification = ['messege' => $notification, 'alert-type' => 'error'];
            return redirect()->back()->with($notification);

        }
    }
    public function pay_via_paypal() {
        $basic_payment = $this->get_basic_payment_info();
        $paypal_credentials = (object) [
            'paypal_client_id'    => $basic_payment->paypal_client_id,
            'paypal_secret_key'   => $basic_payment->paypal_secret_key,
            'paypal_app_id'       => $basic_payment->paypal_app_id,
            'paypal_account_mode' => $basic_payment->paypal_account_mode,
        ];

        $after_success_url = route('payment-success');
        $after_failed_url = route('payment-failed');

        $paypal_payment = new FrontPaymentController();

        return $paypal_payment->pay_with_paypal($paypal_credentials, $after_success_url, $after_failed_url);
    }
    public function pay_via_stripe() {
        $basic_payment = $this->get_basic_payment_info();
        $order = session()->get('order');
        $orderItem = OrderItem::where('order_id',$order->id)->first();
        $course = Course::findOrFail($orderItem->course_id);

        // Set your Stripe API secret key
        \Stripe\Stripe::setApiKey($basic_payment?->stripe_secret);

        $after_failed_url = route('payment-failed');

        session()->put('after_failed_url', $after_failed_url);

        $payable_currency = session()->get('payable_currency');
        $paid_amount = session()->get('paid_amount');

        $allCurrencyCodes = $this->paymentService->getSupportedCurrencies($this->paymentService::STRIPE);

        if (in_array(Str::upper($payable_currency), $allCurrencyCodes['non_zero_currency_codes'])) {
            $payable_with_charge = $paid_amount;
        } elseif (in_array(Str::upper($payable_currency), $allCurrencyCodes['three_digit_currency_codes'])) {
            $convertedCharge = (string) $paid_amount . '0';
            $payable_with_charge = (int) $convertedCharge;
        } else {
            $payable_with_charge = (int) ($paid_amount * 100);
        }

        // Create a checkout session
        $checkoutSession = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items'           => [[
                'price_data' => [
                    'currency'     => $payable_currency,
                    'unit_amount'  => $payable_with_charge,
                    'product_data' => [
                        'name' => cache()->get('setting')->app_name.': '.$course->title,
                    ],
                ],
                'quantity'   => 1,
            ]],
            'mode'                 => 'payment',
            'success_url'          => route('stripe-success',) . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url'           => $after_failed_url,
        ]);

        // Redirect to the checkout session URL
        return redirect()->away($checkoutSession->url);

    }
    public function stripe_success(Request $request) {

        $after_success_url = route('payment-success');
        $basic_payment = $this->get_basic_payment_info();

        // Assuming the Checkout Session ID is passed as a query parameter
        $session_id = $request->query('session_id');

        if ($session_id) {
            \Stripe\Stripe::setApiKey($basic_payment->stripe_secret);

            $session = \Stripe\Checkout\Session::retrieve($session_id);

            $paymentDetails = [
                'transaction_id' => $session->payment_intent,
                'amount'         => $session->amount_total,
                'currency'       => $session->currency,
                'payment_status' => $session->payment_status,
                'created'        => $session->created,
            ];
            session()->put('after_success_url', $after_success_url);
            session()->put('after_success_transaction', $session->payment_intent);
            session()->put('payment_details', $paymentDetails);

            return redirect($after_success_url);
        }

        $after_failed_url = session()->get('after_failed_url');
        return redirect($after_failed_url);
    }
    public function pay_via_razorpay(Request $request) {
        $payment_setting = $this->get_payment_gateway_info();

        $after_success_url = route('payment-success');
        $after_failed_url = route('payment-failed');

        $razorpay_credentials = (object) [
            'razorpay_key'    => $payment_setting->razorpay_key,
            'razorpay_secret' => $payment_setting->razorpay_secret,
        ];

        return $this->pay_with_razorpay($request, $razorpay_credentials, $request->payable_amount, $after_success_url, $after_failed_url);

    }
    public function pay_with_razorpay(Request $request, $razorpay_credentials, $payable_amount, $after_success_url, $after_failed_url) {
        $input = $request->all();
        $api = new Api($razorpay_credentials->razorpay_key, $razorpay_credentials->razorpay_secret);
        $payment = $api->payment->fetch($input['razorpay_payment_id']);
        if (count($input) && !empty($input['razorpay_payment_id'])) {
            try {
                $response = $api->payment->fetch($input['razorpay_payment_id'])->capture(['amount' => $payment['amount']]);

                $paymentDetails = [
                    'transaction_id' => $response->id,
                    'amount'         => $response->amount,
                    'currency'       => $response->currency,
                    'fee'            => $response->fee,
                    'description'    => $response->description,
                    'payment_method' => $response->method,
                    'status'         => $response->status,
                ];

                Session::put('after_success_url', $after_success_url);
                Session::put('after_failed_url', $after_failed_url);
                Session::put('after_success_transaction', $response->id);
                Session::put('payment_details', $paymentDetails);

                return redirect($after_success_url);

            } catch (Exception $e) {
                info($e->getMessage());
                return redirect($after_failed_url);
            }
        } else {
            return redirect($after_failed_url);
        }

    }
    public function flutterwave_payment(Request $request) {
        $payment_setting = $this->get_payment_gateway_info();
        $curl = curl_init();
        $tnx_id = $request->tnx_id;
        $url = "https://api.flutterwave.com/v3/transactions/$tnx_id/verify";
        $token = $payment_setting?->flutterwave_secret_key;
        curl_setopt_array($curl, [
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => '',
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => 'GET',
            CURLOPT_HTTPHEADER     => [
                'Content-Type: application/json',
                "Authorization: Bearer $token",
            ],
        ]);

        $response = curl_exec($curl);

        curl_close($curl);
        $response = json_decode($response);
        if ($response->status == 'success') {
            $paymentDetails = [
                'status'            => $response->status,
                'trx_id'            => $tnx_id,
                'amount'            => $response?->data?->amount,
                'amount_settled'    => $response?->data?->amount_settled,
                'currency'          => $response?->data?->currency,
                'charged_amount'    => $response?->data?->charged_amount,
                'app_fee'           => $response?->data?->app_fee,
                'merchant_fee'      => $response?->data?->merchant_fee,
                'card_last_4digits' => $response?->data?->card?->last_4digits,
            ];
            Session::put('payment_details', $paymentDetails);
            Session::put('after_success_transaction', $tnx_id);

            return response()->json(['messege' => 'Payment Success.']);

        } else {
            $notification = __('Payment faild, please try again');
            return response()->json(['messege' => $notification], 403);
        }

    }
    public function paystack_payment(Request $request) {
        $payment_setting = $this->get_payment_gateway_info();

        $reference = $request->reference;
        $transaction = $request->tnx_id;
        $secret_key = $payment_setting?->paystack_secret_key;
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL            => "https://api.paystack.co/transaction/verify/$reference",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => '',
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => 'GET',
            CURLOPT_HTTPHEADER     => [
                "Authorization: Bearer $secret_key",
                'Cache-Control: no-cache',
            ],
        ]);
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        $final_data = json_decode($response);
        if ($final_data->status == true) {
            $paymentDetails = [
                'status'             => $final_data?->data?->status,
                'transaction_id'     => $transaction,
                'requested_amount'   => $final_data?->data->requested_amount,
                'amount'             => $final_data?->data?->amount,
                'currency'           => $final_data?->data?->currency,
                'gateway_response'   => $final_data?->data?->gateway_response,
                'paid_at'            => $final_data?->data?->paid_at,
                'card_last_4_digits' => $final_data?->data->authorization?->last4,
            ];
            Session::put('payment_details', $paymentDetails);
            Session::put('after_success_transaction', $transaction);
            return response()->json(['messege' => 'Payment Success.']);
        } else {
            $notification = __('Payment faild, please try again');
            return response()->json(['messege' => $notification], 403);
        }
    }
    public function pay_via_mollie() {
        $after_success_url = route('payment-success');
        $after_failed_url = route('payment-failed');

        session()->put('after_success_url', $after_success_url);
        session()->put('after_failed_url', $after_failed_url);

        $payment_setting = $this->get_payment_gateway_info();

        $mollie_credentials = (object) [
            'mollie_key' => $payment_setting->mollie_key,
        ];

        return $this->pay_with_mollie($mollie_credentials);
    }
    public function pay_with_mollie($mollie_credentials) {
        $payable_currency = session()->get('payable_currency');
        $paid_amount = session()->get('paid_amount');

        try {
            $mollie = new \Mollie\Api\MollieApiClient();
            $mollie->setApiKey($mollie_credentials->mollie_key);

            $payment = $mollie->payments->create([
                "amount" => [
                    "currency" => "$payable_currency",
                    "value" => "$paid_amount",
                ],
                "description" => userAuth()?->name,
                "redirectUrl" => route('mollie-payment-success'),
            ]);
            $payment = $mollie->payments->get($payment->id);

            session()->put('payment_id', $payment->id);
            session()->put('mollie_credentials', $mollie_credentials);

            return redirect($payment->getCheckoutUrl(), 303);

        } catch (Exception $ex) {
            info($ex);
            info($ex->getMessage());
            $notification = __('Payment faild, please try again');
            $notification = ['messege' => $notification, 'alert-type' => 'error'];

            return redirect()->back()->with($notification);
        }

    }

    public function mollie_payment_success() {
        $mollie_credentials = Session::get('mollie_credentials');

        $mollie = new \Mollie\Api\MollieApiClient();
        $mollie->setApiKey($mollie_credentials->mollie_key);
        $payment = $mollie->payments->get(session()->get('payment_id'));

        if ($payment->isPaid()) {
            $paymentDetails = [
                'transaction_id' => $payment->id,
                'amount'         => $payment->amount->value,
                'currency'       => $payment->amount->currency,
                'fee'            => $payment->settlementAmount->value . ' ' . $payment->settlementAmount->currency,
                'description'    => $payment->description,
                'payment_method' => $payment->method,
                'status'         => $payment->status,
                'paid_at'        => $payment->paidAt,
            ];

            Session::put('payment_details', $paymentDetails);
            Session::put('after_success_transaction', session()->get('payment_id'));

            $after_success_url = Session::get('after_success_url');

            return redirect($after_success_url);

        } else {
            $after_failed_url = Session::get('after_failed_url');
            return redirect($after_failed_url);
        }
    }
    public function pay_via_instamojo() {
        $after_success_url = route('payment-success');
        $after_failed_url = route('payment-failed');

        session()->put('after_success_url', $after_success_url);
        session()->put('after_failed_url', $after_failed_url);

        $payment_setting = $this->get_payment_gateway_info();

        $instamojo_credentials = (object) [
            'instamojo_api_key'    => $payment_setting->instamojo_api_key,
            'instamojo_auth_token' => $payment_setting->instamojo_auth_token,
            'account_mode'         => $payment_setting->instamojo_account_mode,
        ];

        return $this->pay_with_instamojo($instamojo_credentials);
    }
    public function pay_with_instamojo($instamojo_credentials) {
        $payable_currency = session()->get('payable_currency');
        $paid_amount = session()->get('paid_amount');

        $environment = $instamojo_credentials->account_mode;
        $api_key = $instamojo_credentials->instamojo_api_key;
        $auth_token = $instamojo_credentials->instamojo_auth_token;

        if ($environment == 'Sandbox') {
            $url = 'https://test.instamojo.com/api/1.1/';
        } else {
            $url = 'https://www.instamojo.com/api/1.1/';
        }

        try {
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $url . 'payment-requests/');
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER,
                ["X-Api-Key:$api_key",
                    "X-Auth-Token:$auth_token"]);
            $payload = [
                'purpose'                 => env('APP_NAME'),
                'amount'                  => $paid_amount,
                'phone'                   => '918160651749',
                'buyer_name'              => userAuth()?->name,
                'redirect_url'            => route('instamojo-success'),
                'send_email'              => true,
                'webhook'                 => 'http://www.example.com/webhook/',
                'send_sms'                => true,
                'email'                   => userAuth()?->email,
                'allow_repeated_payments' => false,
            ];
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($payload));
            $response = curl_exec($ch);
            curl_close($ch);
            $response = json_decode($response);
            session()->put('instamojo_credentials', $instamojo_credentials);


            if(!empty($response?->payment_request?->longurl)){
                return redirect($response?->payment_request?->longurl);
            }else{
                return redirect()->route('student.orders.index')->with(['messege' => __('Payment faild, please try again'), 'alert-type' => 'error']);
            }

        } catch (Exception $ex) {
            info($ex->getMessage());
            $notification = __('Payment faild, please try again');
            $notification = ['messege' => $notification, 'alert-type' => 'error'];

            return redirect()->back()->with($notification);
        }

    }
    public function instamojo_success(Request $request) {

        $instamojo_credentials = Session::get('instamojo_credentials');

        $input = $request->all();
        $environment = $instamojo_credentials->account_mode;
        $api_key = $instamojo_credentials->instamojo_api_key;
        $auth_token = $instamojo_credentials->instamojo_auth_token;

        if ($environment == 'Sandbox') {
            $url = 'https://test.instamojo.com/api/1.1/';
        } else {
            $url = 'https://www.instamojo.com/api/1.1/';
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url . 'payments/' . $request->get('payment_id'));
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER,
            ["X-Api-Key:$api_key",
                "X-Auth-Token:$auth_token"]);
        $response = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);

        if ($err) {
            $after_failed_url = Session::get('after_failed_url');

            return redirect($after_failed_url);
        } else {
            $data = json_decode($response);
        }

        if ($data->success == true) {
            if ($data->payment->status == 'Credit') {
                Session::put('after_success_transaction', $request->get('payment_id'));
                Session::put('paid_amount', $data->payment->amount);
                $after_success_url = Session::get('after_success_url');

                return redirect($after_success_url);
            }
        } else {
            $after_failed_url = Session::get('after_failed_url');

            return redirect($after_failed_url);
        }
    }

    public function payment_success()
    {
        $order = session()->get('order');
        $after_success_transaction = session()->get('after_success_transaction', null);
        $payment_details = session()->get('payment_details', null);

        try {
            $order->transaction_id = $after_success_transaction;

            // Gestion du statut selon le mode de paiement
            if (in_array($order->payment_method, [
                $this->paymentService::BANK_PAYMENT,
                $this->paymentService::CHEQUE,
                $this->paymentService::CASH_PAYMENT,
            ])) {
                // Paiement par banque ou chèque : en attente de validation manuelle
                $order->payment_status = 'pending';
            } elseif (in_array($order->payment_method, [
                $this->paymentService::STRIPE,
                $this->paymentService::PAYPAL
            ])) {
                // Paiement Stripe ou PayPal : paiement validé
                $order->payment_status = 'paid';
            } else {
                // Autres méthodes, par défaut payé (adapter si besoin)
                $order->payment_status = 'paid';
            }

            $order->status = 'completed';

            // Stockage des détails de paiement
            if (in_array($order->payment_method, [
                $this->paymentService::BANK_PAYMENT,
                $this->paymentService::CHEQUE,
                $this->paymentService::CASH_PAYMENT,
            ])) {
                // Banques/chèque stocké en JSON string déjà
                $order->payment_details = $payment_details;
            } else {
                // Stripe/PayPal (ou autres) stocker en JSON encodé (au cas où)
                $order->payment_details = is_string($payment_details) ? $payment_details : json_encode($payment_details);
            }

            $order->save();

            // Attribution accès pour les paiements immédiats (Stripe, PayPal, autres sauf banque et chèque)
            if ($order->payment_status == 'paid') {
                foreach ($order->orderItems as $item) {
                    Enrollment::create([
                        'order_id' => $order->id,
                        'user_id' => $order->buyer_id,
                        'course_id' => $item->course_id,
                        'session_id' => session()->get('session_id', null), // Si applicable
                        'has_access' => 1,
                    ]);
                }
            }

            // Envoi mail de confirmation
            try {
                $user = auth()->user();
                $this->sendingPaymentStatusMail([
                    'email' => $user->email,
                    'name' => $user->name,
                    'order_id' => $order->invoice_id,
                    'paid_amount' => $order->paid_amount . ' ' . $order->payable_currency,
                    'payment_status' => $order->payment_status,
                ]);
            } catch (Exception $e) {
                info($e->getMessage());
            }

            // Nettoyage des sessions de paiement
            $this->paymentService->removeSessions();

            $notification = __('Payment Success.');
            $notification = ['messege' => $notification, 'alert-type' => 'success'];
            return view('frontend.pages.order-success')->with($notification);

        } catch (Exception $e) {
            $notification = trans('Payment faild, please try again');
            $notification = ['messege' => $notification, 'alert-type' => 'error'];

            return redirect()->route('order-fail')->with($notification);
        }
    }

    public function payment_success_cheque() {
        $order = session()->get('order');
        $after_success_transaction = session()->get('after_success_transaction', null);
        $payment_details = session()->get('payment_details', null);
        $session_id= session()->get('session_id');
        try {
            $order->transaction_id = $after_success_transaction;
            $order->payment_status = $order->payment_method == $this->paymentService::CHEQUE ? 'pending' : 'paid';
            $order->status = 'completed';
            $order->payment_details = $order->payment_method == $this->paymentService::CHEQUE ? $payment_details : json_encode($payment_details);
            $order->save();

            if ($order->payment_method != $this->paymentService::CHEQUE) {
                foreach ($order->orderItems as $item) {
                    Enrollment::create([
                        'order_id'   => $order->id,
                        'user_id'    => $order->buyer_id,
                        'course_id'  => $item->course_id,
                        'has_access' => 1,
                        'session_id' => $session_id,
                    ]);
                    $coursSession = CoursSession::findOrFail($session_id);
                    $coursSession->enrolled_students= $coursSession->enrolled_students+1;
                }
            }
            $user = userAuth();
            // send mail
            $this->sendingPaymentStatusMail([
                'email'          => $user->email,
                'name'           => $user->name,
                'order_id'       => $order->invoice_id,
                'paid_amount'    => $order->paid_amount . ' ' . $order->payable_currency,
                'payment_status' => $order->payment_status,
            ]);

            $this->orderSessionForget();

            $notification = __('Payment Success.');
            $notification = ['messege' => $notification, 'alert-type' => 'success'];

            return view('frontend.pages.order-success')->with($notification);
        } catch (Exception $e) {
            $notification = trans('Payment faild, please try again');
            $notification = ['messege' => $notification, 'alert-type' => 'error'];

            return redirect()->route('order-fail')->with($notification);
        }
    }
    function payment_failed() {
        $order = session()->get('order');
        $order->payment_status = 'cancelled';
        $order->save();

        $user = userAuth();
        // send mail
        $this->sendingPaymentStatusMail([
            'email'          => $user->email,
            'name'           => $user->name,
            'order_id'       => $order->invoice_id,
            'paid_amount'    => $order->paid_amount . ' ' . $order->payable_currency,
            'payment_status' => $order->payment_status,
        ]);

        $this->orderSessionForget();

        $notification = trans('Payment faild, please try again');
        $notification = ['messege' => $notification, 'alert-type' => 'error'];
        return view('frontend.pages.order-fail')->with($notification);
    }

    function handleMailSending(array $mailData) {
        try {
            self::setMailConfig();

            // Get email template
            $template = EmailTemplate::where('name', 'order_completed')->firstOrFail();
            $mailData['subject'] = $template->subject;

            // Prepare email content
            $message = str_replace('{{name}}', $mailData['name'], $template->message);
            $message = str_replace('{{order_id}}', $mailData['order_id'], $message);
            $message = str_replace('{{paid_amount}}', $mailData['paid_amount'], $message);
            $message = str_replace('{{payment_method}}', $mailData['payment_method'], $message);

            if (self::isQueable()) {
                DefaultMailJob::dispatch($mailData['email'], $mailData, $message);
            } else {
                Mail::to($mailData['email'])->send(new DefaultMail($mailData, $message));
            }
        } catch (Exception $e) {
            info($e->getMessage());
        }
    }
    function sendingPaymentStatusMail(array $mailData) {
        try {
            self::setMailConfig();

            // Get email template
            $template = EmailTemplate::where('name', 'payment_status')->firstOrFail();
            $mailData['subject'] = $template->subject;

            // Prepare email content
            $message = str_replace('{{name}}', $mailData['name'], $template->message);
            $message = str_replace('{{order_id}}', $mailData['order_id'], $message);
            $message = str_replace('{{paid_amount}}', $mailData['paid_amount'], $message);
            $message = str_replace('{{payment_status}}', $mailData['payment_status'], $message);

            if (self::isQueable()) {
                DefaultMailJob::dispatch($mailData['email'], $mailData, $message);
            } else {
                Mail::to($mailData['email'])->send(new DefaultMail($mailData, $message));
            }
        } catch (Exception $e) {
            info($e->getMessage());
        }
    }
    private function orderSessionForget() {
        session()->forget(
            [
                'after_success_url',
                'after_failed_url',
                'order',
                'payable_amount',
                'gateway_charge',
                'after_success_gateway',
                'after_success_transaction',
                'subscription_plan_id',
                'payable_with_charge',
                'payable_currency',
                'subscription_plan_id',
                'paid_amount',
                'payment_details',
                'cart',
                'coupon_code',
                'offer_percentage',
                'coupon_discount_amount',
                'gateway_charge_in_usd',
            ]);
    }

}
