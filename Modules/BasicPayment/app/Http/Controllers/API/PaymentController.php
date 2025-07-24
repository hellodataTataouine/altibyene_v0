<?php

namespace Modules\BasicPayment\app\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\BankInformationRequest;
use App\Jobs\DefaultMailJob;
use App\Mail\DefaultMail;
use App\Models\Cart;
use App\Models\Course;
use App\Traits\GetGlobalInformationTrait;
use App\Traits\MailSenderTrait;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Modules\BasicPayment\app\Enums\BasicPaymentSupportedCurrencyListEnum;
use Modules\GlobalSetting\app\Models\EmailTemplate;
use Modules\Order\app\Models\Enrollment;
use Modules\Order\app\Models\Order;
use Modules\Order\app\Models\OrderItem;
use Mollie\Laravel\Facades\Mollie;
use Razorpay\Api\Api;

class PaymentController extends Controller {
    use GetGlobalInformationTrait, MailSenderTrait;
    private $paymentService;
    public function __construct() {
        $this->paymentService = app(\Modules\BasicPayment\app\Services\PaymentMethodService::class);
    }
    public function all_payment(): JsonResponse {
        $data = $this->paymentService->getActiveGatewaysWithDetails();
        if ($data) {
            return response()->json(['status' => 'success', 'data' => $data], 200);
        }
        return response()->json(['status' => 'error', 'message' => 'Not Found!'], 404);
    }
    public function pay_via_cheque(Request $request)
    {
        // ✅ Étape 1 : Valider les champs du formulaire
        $request->validate([
            'holder_name'      => 'required|string|max:255',
            'installments'     => 'required|integer|min:1|max:4',
            'payment_amounts'  => 'required|array|min:1|max:4',
            'payment_amounts.*'=> 'required|numeric|min:0.01',
            'payment_dates'    => 'required|array|min:1|max:4',
            'payment_dates.*'  => 'required|date|after_or_equal:today',
            'cheque_count'     => 'required|integer|min:1',
            'reference'        => 'nullable|string|max:255',
        ]);

        // ✅ Étape 2 : Construire les paiements en tableau structuré
        $payments = [];
        foreach ($request->payment_amounts as $index => $amount) {
            $payments[] = [
                'amount' => $amount,
                'date'   => $request->payment_dates[$index] ?? null,
            ];
        }

        // ✅ Étape 3 : Vérifier s’il y a un doublon de référence
        if (!empty($request->reference)) {
            $duplicate = Order::whereNotNull('payment_details')->get()
                ->first(function ($order) use ($request) {
                    $details = json_decode($order->payment_details, true);
                    return isset($details['reference']) && $details['reference'] === $request->reference;
                });

            if ($duplicate) {
                // 🔁 Redirection vers l’échec si la référence est déjà utilisée
                $after_failed_url = route('payment-api.webview-failed-payment');
                return redirect($after_failed_url)
                    ->with('messege', 'Cette référence est déjà utilisée pour un paiement par chèque.')
                    ->with('alert-type', 'error');
            }
        }

        // ✅ Étape 4 : Enregistrement des données du paiement dans la session
        $chequeDetails = json_encode([
            'holder_name'  => $request->holder_name,
            'installments' => $request->installments,
            'cheque_count' => $request->cheque_count,
            'reference'    => $request->reference,
            'payments'     => $payments, // montant et date par paiement
        ]);

        Session::put('after_success_transaction', $request->reference ?? 'cheque_' . now()->timestamp);
        Session::put('payment_details', $chequeDetails);

        // ✅ Étape 5 : Redirection vers la page de succès
        $after_success_url = route('payment-api.webview-success-payment', [
            'bearer_token' => request()->bearerToken(),
        ]);

        return redirect($after_success_url)
            ->with('messege', 'Votre paiement par chèque a été pris en compte.')
            ->with('alert-type', 'success');
    }



    public function pay_via_bank(Request $request)
    {
        // Validate inputs matching your blade form (if not done in BankInformationRequest)
        $request->validate([
            'installments' => 'required|integer|min:1|max:4',
            'payment_amounts' => 'required|array',
            'payment_amounts.*' => 'required|numeric|min:0.01',
            'payment_dates' => 'required|array',
            'payment_dates.*' => 'required|date',
            'accept_terms' => 'accepted',
            'comment' => 'nullable|string|max:1000',
        ]);

        // Build payment details array with unique transaction id
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

        // Optionally check duplicate transaction (usually unnecessary since txn id is unique)
        $exists = Order::whereNotNull('payment_details')->get()->contains(function ($payment) use ($bankDetailsArray) {
            $details = json_decode($payment->payment_details, true);
            return isset($details['transaction']) && $details['transaction'] === $bankDetailsArray['transaction'];
        });

        if ($exists) {
            $after_failed_url = route('payment-api.webview-failed-payment');
            return redirect($after_failed_url);
        }

        // Store session data for after payment success processing
        Session::put('after_success_transaction', $bankDetailsArray['transaction']);
        Session::put('payment_details', $bankDetailsJson);

        // Redirect to success URL with bearer token
        $after_success_url = route('payment-api.webview-success-payment', ['bearer_token' => $request->bearer_token]);
        return redirect($after_success_url);
    }

    public function pay_via_cash(Request $request)
    {
        // Validation des données reçues
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
            return response()->json([
                'success' => false,
                'message' => 'Le nombre de montants et de dates doit correspondre au nombre de paiements sélectionnés.'
            ], 422);
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

        // Ici, tu pourrais vérifier les doublons si besoin

        // Stocker en session pour traitement post paiement (ou autre stockage adapté à ton API)
        Session::put('after_success_transaction', $cashDetailsArray['transaction']);
        Session::put('payment_details', $cashDetails);

        // Appeler la méthode de succès qui finalise la commande (adapter si nécessaire pour API)
        $result = $this->payment_success();

        // Renvoi d’une réponse JSON
        return response()->json([
            'success' => true,
            'message' => 'Paiement comptant enregistré avec succès.',
            'transaction_id' => $cashDetailsArray['transaction'],
            'data' => $result, // si tu souhaites retourner des infos supplémentaires
        ]);
    }


    public function placeOrder($paymentMethod) {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'UnAuthenticated'], 401);
        }

        $activeGateways = array_keys($this->paymentService->getActiveGatewaysWithDetails());
        if (!in_array($paymentMethod, $activeGateways)) {
            return response()->json(['status' => 'error', 'message' => 'The selected payment method is now inactive.'], 400);
        }

        $payable_currency = strtoupper(request()->query('currency', 'USD'));

        if (!$this->paymentService->isCurrencySupported($paymentMethod, $payable_currency)) {
            $supportedCurrencies = $this->paymentService->getSupportedCurrencies($paymentMethod);
            return response()->json(['status' => 'error', 'message' => 'You are trying to use unsupported currency', 'supportCurrency' => sprintf(
                '%s %s: %s',
                strtoupper($paymentMethod),
                'supports only these types of currencies',
                implode(', ', $supportedCurrencies)
            )], 400);
        }

        if ($user->cart_count == 0) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Please add some courses in your cart.',
            ], 404);

        }

        try {
            $payable_amount = $user->cart_total;
            $calculatePayableCharge = $this->paymentService->getPayableAmount($paymentMethod, $payable_amount, $payable_currency);

            DB::beginTransaction();

            $paid_amount = $calculatePayableCharge?->payable_amount + $calculatePayableCharge?->gateway_charge;

            if (in_array($paymentMethod, ['Razorpay', 'Stripe'])) {
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
                'payment_method'          => $paymentMethod,
                'payment_status'          => 'pending',
                'payable_amount'          => $payable_amount,
                'gateway_charge'          => $calculatePayableCharge?->gateway_charge,
                'payable_with_charge'     => $calculatePayableCharge?->payable_with_charge,
                'paid_amount'             => $paid_amount,
                'payable_currency'        => $calculatePayableCharge?->currency_code,
                'conversion_rate'         => $calculatePayableCharge?->currency_rate,
                'commission_rate'         => Cache::get('setting')->commission_rate,
            ]);
            $data_layer_order_items = [];

            $carts = $user->carts()->with('course:id,title,slug,price,discount')->get(['id', 'user_id', 'course_id']);
            $session_id = Session::get('session_id', null);
            foreach ($carts as $item) {
                $order_item = [
                    'order_id'        => $order->id,
                    'price'           => $item->course->price,
                    'course_id'       => $item->course->id,
                    'commission_rate' => Cache::get('setting')->commission_rate,
                ];
                OrderItem::create([
                    'order_id'        => $order->id,
                    'price'           => $item->course->price,
                    'course_id'       => $item->course->id,
                    'commission_rate' => Cache::get('setting')->commission_rate,
                    'session_id'    => $session_id,
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

            $order_id = $order?->invoice_id;
            $newToken = $user->createToken('extra-token', ['extra'], now()->addWeek())->plainTextToken;

            return response()->json(['status' => 'success', 'url' => route('payment-api.payment', ['token' => $newToken, 'order_id' => $order_id])], 200);
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
            return to_route('payment-api.webview-failed-payment');
        }
    }
    public function pay_via_free_gateway() {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'UnAuthenticated'], 401);
        }
        $payable_amount = $user->cart_total;
        if ($payable_amount != 0) {
            return response()->json(['status' => 'error', 'message' => 'Payment failed, please try again'], 400);
        }
        try {
            DB::beginTransaction();
            $order = Order::create([
                'invoice_id'          => Str::random(10),
                'buyer_id'            => $user->id,
                'payment_method'      => 'Free',
                'status'              => 'completed',
                'payment_status'      => 'paid',
                'payable_amount'      => $payable_amount,
                'gateway_charge'      => 0,
                'payable_with_charge' => $payable_amount,
                'paid_amount'         => $payable_amount,
                'payable_currency'    => getSessionCurrency(),
                'transaction_id'      => Str::random(10),
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

            return response()->json(['status' => 'success', 'message' => 'Your order has been placed'], 200);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => 'Your order has been fail'], 400);
        }
    }
    public function payment(Request $request) {
        $token = $request?->token ?? null;
        $request->headers->set('Authorization', 'Bearer ' . $token);
        $user = auth()->user();
        if (!$user) {
            abort(401);
        }
        $order_id = $request?->order_id ?? null;
        $order = $user?->orders()->where('invoice_id', $order_id)->where('status', 'pending')->first();
        if (!$order) {
            abort(404);
        }
        $paymentMethod = $order->payment_method;
        if (!$this->paymentService->isActive($paymentMethod)) {
            return response()->json(['status' => 'error', 'message' => 'The selected payment method is now inactive.'], 400);
        }

        $calculatePayableCharge = $this->paymentService->getPayableAmount($paymentMethod, $order?->payable_amount, $order?->payable_currency);

        Session::put('order', $order);
        Session::put('payable_currency', $order?->payable_currency);
        Session::put('paid_amount', $calculatePayableCharge?->payable_with_charge);

        $paymentService = $this->paymentService;
        $view = $this->paymentService->getBladeView($paymentMethod);
        return view($view, compact('order', 'paymentService', 'paymentMethod', 'user', 'token', 'order_id'));
    }
    public function payment_success()
    {

        $order = session()->get('order');
        $session_id = session()->get('session_id', null);
        $after_success_transaction = session()->get('after_success_transaction', null);
        $payment_details = session()->get('payment_details', null);

        try {
            $order->transaction_id = $after_success_transaction;

            // Gestion du statut selon le mode de paiement
            if (in_array($order->payment_method, [
                $this->paymentService::BANK_PAYMENT,
                $this->paymentService::CHEQUE
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
                $this->paymentService::CHEQUE
            ])) {
                // Banques/chèque stocké en JSON string déjà
                $order->payment_details = $payment_details;
            } else {
                // Stripe/PayPal (ou autres) stocker en JSON encodé (au cas où)
                $order->payment_details = is_string($payment_details) ? $payment_details : json_encode($payment_details);
            }

            $order->save();

            // Attribution accès pour les paiements immédiats (Stripe, PayPal, autres sauf banque et chèque)
            //if ($order->payment_status == 'paid') {
                foreach ($order->orderItems as $item) {
                    Enrollment::create([
                        'order_id' => $order->id,
                        'user_id' => $order->buyer_id,
                        'course_id' => $item->course_id,
                        'session_id' => $session_id,
                        'has_access' => 1,
                    ]);
                }
            //}

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

            $image = 'success.png';
            $title = 'Your order has been placed';
            $sub_title = __('For check more details you can go to your dashboard');
            return view('basicpayment::app_order_notification', compact('image', 'title', 'sub_title'));

        } catch (Exception $e) {
            info($e->getMessage());

            $image = 'fail.png';
            $title = 'Your order has failed';
            $sub_title = __('Please try again or contact support for more details');
            return view('basicpayment::app_order_notification', compact('image', 'title', 'sub_title'));
        }
    }

    public function payment_failed() {
        $order = session()->get('order');
        if ($order) {
            $order->payment_status = 'cancelled';
            $order->save();
        }

        try {
            $user = auth()->user();
            $this->sendingPaymentStatusMail([
                'email'          => $user->email,
                'name'           => $user->name,
                'order_id'       => $order->invoice_id,
                'paid_amount'    => $order->paid_amount . ' ' . $order->payable_currency,
                'payment_status' => $order->payment_status,
            ]);
        } catch (Exception $e) {
            info($e->getMessage());
        }

        $this->paymentService->removeSessions();
        $image = 'fail.png';
        $title = 'Your order has been fail';
        $sub_title = __('Please try again for more details connect with us');
        return view('basicpayment::app_order_notification', compact('image', 'title', 'sub_title'));
    }
    public function stripe_pay() {
        $basic_payment = $this->get_basic_payment_info();
        \Stripe\Stripe::setApiKey($basic_payment?->stripe_secret);

        $after_failed_url = route('payment-api.webview-failed-payment');
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
                        'name' => cache()->get('setting')->app_name,
                    ],
                ],
                'quantity'   => 1,
            ]],
            'mode'                 => 'payment',
            'success_url'          => url('/webview-success-payment') . '?session_id={CHECKOUT_SESSION_ID}&bearer_token=' . request()->bearer_token,
            'cancel_url'           => $after_failed_url,
        ]);
        // Redirect to the checkout session URL
        return redirect()->away($checkoutSession->url);
    }
    public function stripe_success(Request $request) {
        $after_success_url = route('payment-api.webview-success-payment', ['bearer_token' => request()->bearer_token]);

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

    public function pay_via_mollie() {
        $payment_setting = $this->get_payment_gateway_info();

        $mollie_credentials = (object) [
            'mollie_key' => $payment_setting->mollie_key,
        ];

        $after_success_url = route('payment-api.webview-success-payment', ['bearer_token' => request()->bearer_token]);
        $after_failed_url = route('payment-api.webview-failed-payment');

        session()->put('after_success_url', $after_success_url);
        session()->put('after_failed_url', $after_failed_url);

        $payable_currency = session()->get('payable_currency');
        $paid_amount = session()->get('paid_amount');

        try {
            Mollie::api()->setApiKey($mollie_credentials->mollie_key);
            $payment = Mollie::api()->payments()->create([
                'amount'      => [
                    'currency' => '' . strtoupper($payable_currency) . '',
                    'value'    => '' . $paid_amount . '',
                ],
                'description' => cache()->get('setting')->app_name,
                'redirectUrl' => route('payment-api.mollie-success', ['bearer_token' => request()->bearer_token]),
            ]);

            $payment = Mollie::api()->payments()->get($payment->id);

            session()->put('payment_id', $payment->id);
            session()->put('mollie_credentials', $mollie_credentials);

            return redirect($payment->getCheckoutUrl(), 303);

        } catch (Exception $ex) {
            info($ex->getMessage());
            $image = 'fail.png';
            $title = 'Your order has been fail';
            $sub_title = __('Please try again for more details connect with us');
            return view('basicpayment::app_order_notification', compact('image', 'title', 'sub_title'));
        }

    }
    public function mollie_success() {
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
    public function pay_via_razorpay(Request $request) {
        $payment_setting = $this->get_payment_gateway_info();

        $after_success_url = route('payment-api.webview-success-payment', ['bearer_token' => request()->bearer_token]);
        $after_failed_url = route('payment-api.webview-failed-payment');

        $razorpay_credentials = (object) [
            'razorpay_key'    => $payment_setting->razorpay_key,
            'razorpay_secret' => $payment_setting->razorpay_secret,
        ];

        return $this->pay_with_razorpay($request, $razorpay_credentials, $after_success_url, $after_failed_url);

    }
    public function pay_with_razorpay(Request $request, $razorpay_credentials, $after_success_url, $after_failed_url) {
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

            $image = 'success.png';
            $title = 'Your order has been placed';
            $sub_title = __('For check more details you can go to your dashboard');
            return view('basicpayment::app_order_notification', compact('image', 'title', 'sub_title'));

        } else {

            $image = 'fail.png';
            $title = 'Your order has been fail';
            $sub_title = __('Please try again for more details connect with us');
            return view('basicpayment::app_order_notification', compact('image', 'title', 'sub_title'));
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

            return response()->json(['message' => 'Payment Success.']);
        } else {
            info('here');
            $notification = 'Payment faild, please try again';
            return response()->json(['message' => $notification], 403);
        }
    }
    public function pay_via_instamojo() {
        $after_success_url = route('payment-api.webview-success-payment', ['bearer_token' => request()->bearer_token]);
        $after_failed_url = route('payment-api.webview-failed-payment');

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
                'buyer_name'              => auth()->user()?->name,
                'redirect_url'            => route('payment-api.instamojo-success', ['bearer_token' => request()->bearer_token]),
                'send_email'              => true,
                'webhook'                 => 'http://www.example.com/webhook/',
                'send_sms'                => true,
                'email'                   => auth()->user()->email,
                'allow_repeated_payments' => false,
            ];
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($payload));
            $response = curl_exec($ch);
            curl_close($ch);
            $response = json_decode($response);
            session()->put('instamojo_credentials', $instamojo_credentials);

            if (!empty($response?->payment_request?->longurl)) {
                return redirect($response?->payment_request?->longurl);
            } else {
                $image = 'fail.png';
                $title = 'Your order has been fail';
                $sub_title = __('Please try again for more details connect with us');
                return view('basicpayment::app_order_notification', compact('image', 'title', 'sub_title'));
            }

        } catch (Exception $ex) {
            $after_failed_url = Session::get('after_failed_url');
            return redirect($after_failed_url);
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
    public function handleMailSending(array $mailData) {
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
    public function sendingPaymentStatusMail(array $mailData) {
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

}
