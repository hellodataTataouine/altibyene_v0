@extends('frontend.layouts.master')
@section('meta_title', 'Panier' . ' || ' . $setting->app_name)

@section('contents')
    <!-- breadcrumb-area -->
    <x-frontend.breadcrumb :title="__('Panier')" :links="[['url' => route('home'), 'text' => __('Accueil')], ['url' => route('cart'), 'text' => __('Panier')]]" />
    <!-- breadcrumb-area-end -->

    <!-- cart-area -->
    <div class="cart__area section-py-120">
        <div class="container">
            @auth('web')
                @foreach ($products as $item)
                    @if (in_array($item?->course?->id, session()->get('enrollments')))
                        <div class="alert alert-danger d-flex align-items-center" role="alert">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                                class="bi bi-exclamation-triangle-fill flex-shrink-0 me-2" viewBox="0 0 16 16" role="img"
                                aria-label="Warning:">
                                <path
                                    d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
                            </svg>
                            <div>
                                {{ __('Vous avez des articles dans votre panier que vous avez déjà achetés. Avant de continuer, veuillez les supprimer du panier. ') }}
                            </div>
                        </div>
                    @elseif (in_array($item?->course?->id, session()->get('instructor_courses')))
                        <div class="alert alert-danger d-flex align-items-center" role="alert">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                                class="bi bi-exclamation-triangle-fill flex-shrink-0 me-2" viewBox="0 0 16 16" role="img"
                                aria-label="Warning:">
                                <path
                                    d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
                            </svg>
                            <div>
                                {{ __('Vous avez vos propres cours dans votre panier. Avant de continuer, veuillez les supprimer du panier. ') }}
                            </div>
                        </div>
                    @endif
                @endforeach

                @if ($cart_count > 0)
                    <div class="row">
                        <div class="col-lg-8">
                            <table class="table cart__table">
                                <thead>
                                    <tr>
                                        <th class="product__thumb">&nbsp;</th>
                                        <th class="product__name">{{ __('Cours') }}</th>
                                        <th class="product__price">{{ __('Prix') }}</th>
                                        <th class="product__remove">&nbsp;</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($products as $product)
                                        <tr>
                                            <td class="product__thumb pe-2">
                                                <a href="{{ route('course.show', $product?->course?->slug) }}"><img
                                                        src="{{ asset($product?->course?->thumbnail) }}" alt=""></a>
                                            </td>
                                            <td class="product__name">
                                                <a
                                                    href="{{ route('course.show', $product?->course?->slug) }}">{{ $product?->course?->title }}</a>
                                                <br>
                                                @if (in_array($product?->course?->id, session()->get('enrollments')))
                                                    <span class="badge bg-warning mt-2">{{ __('Already purchased') }}</span>
                                                @elseif (in_array($product?->course?->id, session()->get('instructor_courses')))
                                                    <span class="badge bg-warning mt-2">{{ __('Own course') }}</span>
                                                @else
                                                @endif
                                            </td>
                                            <td class="product__price">{{ $product?->course?->discount > 0 ? currency($product?->course?->discount) : currency($product?->course?->price) }}</td>
                                            <td class="product__remove">
                                                <a href="{{ route('remove-cart-item', $product?->course?->slug) }}">×</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td colspan="6" class="cart__actions">
                                            <form action="{{ route('apply-coupon') }}" class="cart__actions-form coupon-form"
                                                method="POST">
                                                @csrf
                                                <input type="text" name="coupon" placeholder="Code promo">
                                                <button type="submit" class="btn">{{ __('Appliquer le promo') }}</button>
                                            </form>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-lg-4">
                            <div class="cart__collaterals-wrap">
                                <h2 class="title">{{ __('Totaux du panier') }}</h2>
                                <ul class="list-wrap">
                                    <li>{{ __('Total des articles') }}<span>{{ $cart_count }}</span></li>
                                    <li>
                                        @if (Session::has('coupon_code'))
                                            <p class="coupon-discount m-0">
                                                <span>{{ __('Discount') }}</span>
                                                <br>
                                                <small>{{ $coupon }} ({{ $discountPercent }} %)<a
                                                        class="ms-2 text-danger" href="/remove-coupon">×</a></small>
                                            </p>
                                            <span class="discount-amount">{{ currency($discountAmount) }}</span>
                                        @else
                                            <p class="coupon-discount m-0">
                                                <span>{{ __('Discount') }}</span>
                                            </p>
                                            <span class="discount-amount">{{ currency(0) }}</span>
                                        @endif
                                    </li>
                                    <li>{{ __('Total') }} <span class="amount">{{ $total }}</span></li>
                                </ul>
                                <a href="{{ route('checkout.index') }}" class="btn">{{ __('Passer à la caisse') }}</a>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="w-100 text-center">
                        <img class="mb-4" src="{{ asset('uploads/website-images/empty-cart.png') }}" alt="">
                        <h4 class="text-center">{{ __('Le panier est vide!') }}</h4>
                        <p class="text-center">
                            {{ __('Veuillez ajouter des cours dans votre panier.') }}
                        </p>
                    </div>
                @endif
            @else
                @foreach ($products as $item)
                    @if (in_array($item->id, session()->get('enrollments')))
                        <div class="alert alert-danger d-flex align-items-center" role="alert">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                                class="bi bi-exclamation-triangle-fill flex-shrink-0 me-2" viewBox="0 0 16 16" role="img"
                                aria-label="Warning:">
                                <path
                                    d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
                            </svg>
                            <div>
                                {{ __('You have items in your cart that you already purchased. before proceed please remove those from cart ') }}
                            </div>
                        </div>
                    @elseif (in_array($item->id, session()->get('instructor_courses')))
                        <div class="alert alert-danger d-flex align-items-center" role="alert">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                                class="bi bi-exclamation-triangle-fill flex-shrink-0 me-2" viewBox="0 0 16 16" role="img"
                                aria-label="Warning:">
                                <path
                                    d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
                            </svg>
                            <div>
                                {{ __('Vous avez vos propres cours dans votre panier. Avant de continuer, veuillez les supprimer du panier. ') }}
                            </div>
                        </div>
                    @endif
                @endforeach

                @if ($cart_count > 0)
                    <div class="row">
                        <div class="col-lg-8">
                            <table class="table cart__table">
                                <thead>
                                    <tr>
                                        <th class="product__thumb">&nbsp;</th>
                                        <th class="product__name">{{ __('Cours') }}</th>
                                        <th class="product__price">{{ __('Prix') }}</th>
                                        <th class="product__remove">&nbsp;</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($products as $product)
                                        <tr>
                                            <td class="product__thumb pe-2">
                                                <a href="{{ route('course.show', $product->options['slug']) }}"><img
                                                        src="{{ asset($product->options['image']) }}" alt=""></a>
                                            </td>
                                            <td class="product__name">
                                                <a
                                                    href="{{ route('course.show', $product->options['slug']) }}">{{ $product->name }}</a>
                                                <br>
                                                @if (in_array($product->id, session()->get('enrollments')))
                                                    <span class="badge bg-warning mt-2">{{ __('Déjà acheté') }}</span>
                                                @elseif (in_array($product->id, session()->get('instructor_courses')))
                                                    <span class="badge bg-warning mt-2">{{ __('Propre cours') }}</span>
                                                @else
                                                @endif
                                            </td>
                                            <td class="product__price">{{ currency($product->price) }}</td>
                                            <td class="product__remove">
                                                <a href="{{ route('remove-cart-item', $product->rowId) }}">×</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td colspan="6" class="cart__actions">
                                            <form action="{{ route('apply-coupon') }}" class="cart__actions-form coupon-form"
                                                method="POST">
                                                @csrf
                                                <input type="text" name="coupon" placeholder="Code promo">
                                                <button type="submit" class="btn">{{ __('Appliquer promo') }}</button>
                                            </form>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-lg-4">
                            <div class="cart__collaterals-wrap">
                                <h2 class="title">{{ __('Totaux du panier') }}</h2>
                                <ul class="list-wrap">
                                    <li>{{ __('Total des articles') }}<span>{{ $cart_count }}</span></li>
                                    <li>
                                        @if (Session::has('coupon_code'))
                                            <p class="coupon-discount m-0">
                                                <span>{{ __('Discount') }}</span>
                                                <br>
                                                <small>{{ $coupon }} ({{ $discountPercent }} %)<a
                                                        class="ms-2 text-danger" href="/remove-coupon">×</a></small>
                                            </p>
                                            <span class="discount-amount">{{ currency($discountAmount) }}</span>
                                        @else
                                            <p class="coupon-discount m-0">
                                                <span>{{ __('Discount') }}</span>
                                            </p>
                                            <span class="discount-amount">{{ currency(0) }}</span>
                                        @endif
                                    </li>
                                    <li>{{ __('Total') }} <span class="amount">{{ $total }}</span></li>
                                </ul>
                                <a href="{{ route('checkout.index') }}" class="btn">{{ __('Passer à la caisse') }}</a>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="w-100 text-center">
                        <img class="mb-4" src="{{ asset('uploads/website-images/empty-cart.png') }}" alt="">
                        <h4 class="text-center">{{ __('Le panier est vide!') }}</h4>
                        <p class="text-center">
                            {{ __('Veuillez ajouter des cours dans votre panier.') }}
                        </p>
                    </div>
                @endif
            @endauth
        </div>
    </div>
    <!-- cart-area-end -->
@endsection
@if (session('removeFromCart') &&
        $setting->google_tagmanager_status == 'active' &&
        $marketing_setting?->remove_from_cart)
    @php
        $removeFromCart = session('removeFromCart');
        session()->forget('removeFromCart');
    @endphp
    @push('scripts')
        <script>
            $(function() {
                dataLayer.push({
                    'event': 'removeFromCart',
                    'cart_details': @json($removeFromCart)
                });
            });
        </script>
    @endpush
@endif
