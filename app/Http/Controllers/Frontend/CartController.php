<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Traits\RedirectHelperTrait;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Modules\Coupon\app\Models\Coupon;

class CartController extends Controller {
    use RedirectHelperTrait;

    public function index() {
        if (auth()->check()) {
            $user = userAuth();
            $cart_count = $user->cart_count;
            if ($cart_count == 0) {
                $this->destroyCouponSession();
            }
            $products = $user->carts()->with('course:id,title,slug,price,discount,thumbnail')->get(['id', 'user_id', 'course_id']);
        }else{
            $cart_count = Cart::content()->count();
            if (Cart::content()->count() == 0) {
                $this->destroyCouponSession();
            }
            $products = Cart::content();
        }
        
        $cartTotal = $this->cartTotal();
        $discountPercent = Session::has('offer_percentage') ? Session::get('offer_percentage') : 0;
        $discountAmount = ($cartTotal * $discountPercent) / 100;
        $total = currency($cartTotal - $discountAmount);
        $coupon = Session::has('coupon_code') ? Session::get('coupon_code') : '';
        return view('frontend.pages.cart', compact('products', 'cart_count','total', 'discountAmount', 'discountPercent', 'coupon'));
    }

    public function addToCart(Request $request,string $id) {
        $course = Course::active()->find($id);

        if (!$course) {
            return response()->json(['status' => 'error', 'message' => 'Not Found!'], 404);
        }

        if (auth()->check()) {
            $user = userAuth();
            if (isOwnCourse($user, $course)) {
                return response()->json(['status' => 'error', 'message' => 'You can not add to cart your own course!'], 400);
            }
            if (hasCourseInCartOrPurchased($user, $course)) {
                return response()->json(['status' => 'error', 'message' => 'Already purchased'], 400);
            }
            $user->carts()->create(['course_id' => $course->id]);
            $response = ['status'     => 'success','message'    => 'Added to cart successfully!','cart_count' => $user->cartCount];
        } else {
            if ($this->checkItemExist($id)) {
                return response(['status' => 'error', 'message' => 'Already added to cart or cannot add your own course!']);
            }
            $cartData = $this->prepareCartData($course);
            Cart::add($cartData);
            $response = ['status' => 'success','message' => 'Added to cart successfully!','cart_count' => Cart::content()->count()];
        }
        $this->handleGoogleTagManager($course, $response);
        $this->updateCouponDiscountAmount();

        return response($response);
    }

    private function prepareCartData($course) {
        $price = $course->discount > 0 ? $course->discount : $course->price;
        return [
            'id'      => $course->id,
            'name'    => $course->title,
            'qty'     => 1,
            'price'   => $price,
            'weight'  => 0,
            'options' => [
                'image'          => $course->thumbnail,
                'slug'           => $course->slug,
                'real_price'     => $course->price,
                'discount_price' => $course->discount,
            ],
        ];
    }

    private function handleGoogleTagManager($course, &$response) {
        $settings = cache()->get('setting');
        $marketingSettings = cache()->get('marketing_setting');

        if ($settings->google_tagmanager_status == 'active' && $marketingSettings->add_to_cart) {
            $cartData = $this->prepareCartData($course);
            $cartData['price'] = currency($course->price);
            $cartData['options']['real_price'] = currency($course->price);
            $cartData['options']['image'] = asset($course->thumbnail);
            $cartData['options']['slug'] = route('course.show', $course->slug);
            unset($cartData['id']);
            $cartData['user'] = auth('web')->check() ? [
                'name'  => auth('web')->user()->name,
                'email' => auth('web')->user()->email,
            ] : 'guest';

            $response['dataLayer'] = $cartData;
        }
    }

    public function removeCartItem(string $rowId) {
        if (auth()->check()) {
            $user = userAuth();
            $course = Course::select('id')->whereSlug($rowId)->first();
            if (!$course || !$user->carts()->where('course_id', $course->id)->exists()) {
                $notification = [
                    'messege'    => __('Not Found!'),
                    'alert-type' => 'error',
                ];
                return redirect()->back()->with($notification);
            }
            $user->carts()->where('course_id', $course->id)->delete();
        }else{
            $cartItem = Cart::get($rowId)?->toArray();
            if ($cartItem) {
                unset($cartItem['rowId'], $cartItem['id']);
                $cartItem['price'] = currency($cartItem['price']);
                $cartItem['options']['real_price'] = currency($cartItem['options']['real_price']);
                $cartItem['options']['image'] = asset($cartItem['options']['image']);
                $cartItem['options']['slug'] = route('course.show', $cartItem['options']['slug']);
                $cartItem['user'] = auth('web')->check() ? [
                    'name'  => auth('web')->user()->name,
                    'email' => auth('web')->user()->email,
                ] : 'guest';
    
                $settings = cache()->get('setting');
                $marketingSettings = cache()->get('marketing_setting');
                if ($settings->google_tagmanager_status == 'active' && $marketingSettings->remove_from_cart) {
                    session()->put('removeFromCart', $cartItem);
                }
    
            }
            Cart::remove($rowId);
        }
        $notification = [
            'messege'    => __('Item removed from cart!'),
            'alert-type' => 'success',
        ];

        $this->updateCouponDiscountAmount();

        return redirect()->back()->with($notification);
    }

    public function cartTotal() {
        $cartTotal = 0;
        if (auth()->check()) {
            $cartTotal = userAuth()->cart_total;
        }else{
            $cartItems = Cart::content();
        foreach ($cartItems as $key => $cartItem) {
            $cartTotal += $cartItem->price;
        }
        }
        return $cartTotal;
    }

    public function checkItemExist(string $id) {
        $cartItems = Cart::content();
        foreach ($cartItems as $key => $cartItem) {
            if ($cartItem->id == $id) {
                return true;
            }
        }
        return false;
    }

    public function applyCoupon(Request $request) {
        $rules = [
            'coupon' => 'required',
        ];
        $customMessages = [
            'coupon.required' => __('Coupon is required'),
        ];

        $request->validate($rules, $customMessages);

        $coupon = Coupon::where(['coupon_code' => $request->coupon, 'status' => 'active'])->first();

        if (!$coupon) {
            $notification = __('Invalid coupon');

            return response()->json(['message' => $notification], 403);
        }

        if ($coupon->expired_date < date('Y-m-d')) {
            $notification = __('Coupon already expired');

            return response()->json(['message' => $notification], 403);
        }

        if ($this->cartTotal() < $coupon->min_price) {
            $notification = __('Minimum order amount should be :amount', ['amount' => currency($coupon->min_price)]);

            return response()->json(['message' => $notification], 403);
        }
        if ($this->cartTotal() <= 0) {
            $notification = __('Cart amount should be greater than 0');

            return response()->json(['message' => $notification], 403);
        }

        $discountAmount = currency(($this->cartTotal() * $coupon->offer_percentage) / 100);
        $total = currency($this->cartTotal() - ($this->cartTotal() * $coupon->offer_percentage) / 100);

        /** when coupon will be handle for particular seller or author , above condition will be used  */
        Session::put('coupon_code', $coupon->coupon_code);
        Session::put('offer_percentage', $coupon->offer_percentage);
        Session::put('coupon_discount_amount', ($this->cartTotal() * $coupon->offer_percentage) / 100);

        $notification = __('Coupon applied successful');

        return response()->json(['message' => $notification, 'coupon_code' => $coupon->coupon_code, 'offer_percentage' => $coupon->offer_percentage, 'discount_amount' => $discountAmount, 'total' => $total]);
    }

    public function updateCouponDiscountAmount() {
        if (!Session::has('coupon_code')) {
            return;
        }

        $coupon = Coupon::where(['coupon_code' => Session::get('coupon_code'), 'status' => 'active'])->first();
        // update discount amount
        Session::put('coupon_discount_amount', ($this->cartTotal() * $coupon->offer_percentage) / 100);
    }

    public function removeCoupon() {

        $this->destroyCouponSession();

        $notification = [
            'messege'    => __('Coupon removed successfully!'),
            'alert-type' => 'success',
        ];
        return redirect()->back()->with($notification);
    }

    public function destroyCouponSession() {
        Session::forget('coupon_code');
        Session::forget('offer_percentage');
        Session::forget('coupon_discount_amount');
    }
}
