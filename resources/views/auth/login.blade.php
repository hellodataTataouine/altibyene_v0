@extends('frontend.layouts.master')
@section('meta_title', 'Se connecter'. ' || ' . $setting->app_name)
@section('contents')
    <!-- breadcrumb-area -->
    <x-frontend.breadcrumb
        :title="__('Login')"
        :links="[
            ['url' => route('home'), 'text' => __('Accueil')],
            ['url' => route('login'), 'text' => __('Se connecter')],
        ]"
    />
    <!-- breadcrumb-area-end -->

    <!-- singUp-area -->
    <section class="singUp-area section-py-120">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-6 col-lg-8">
                    <div class="singUp-wrap">
                        <h2 class="title">{{ __('Salut!') }}</h2>
                        <p>{{ __(' Prêt à vous connecter ? Saisissez simplement votre adresse e-mail et votre mot de passe ci-dessous et vous serez de nouveau opérationnel en un rien de temps. C\'est parti !') }}
                        </p>
                        @if($setting->google_login_status == 'active')
                        <div class="account__social">
                            <a href="{{ route('auth.social', 'google') }}" class="account__social-btn">
                                <img src="{{ asset('frontend/img/icons/google.svg') }}" alt="img">
                                {{ __('Continuer avec Google') }}
                            </a>
                        </div>
                        <div class="account__divider">
                            <span>{{ __('ou') }}</span>
                        </div>
                        @endif
                        <form method="POST" action="{{ route('user-login') }}" class="account__form">
                            @csrf
                            <div class="form-grp">
                                <label for="email">{{ __('Email') }} <code>*</code></label>
                                <input id="email" type="text" placeholder="email" value="{{ old('email') }}" name="email">
                                <x-frontend.validation-error name="email" />
                            </div>
                            <div class="form-grp">
                                <label for="password">{{ __('Mot de passe') }} <code>*</code></label>
                                <input id="password" type="password" placeholder="password" name="password">
                            </div>
                            <div class="account__check">
                                <div class="account__check-remember">
                                    <input type="checkbox" class="form-check-input" name="remember" value=""
                                        id="terms-check">
                                    <label for="terms-check" class="form-check-label">{{ __('Se souvenir de moi') }}</label>
                                </div>
                                <div class="account__check-forgot">
                                    <a href="{{ route('password.request') }}">{{ __('Mot de passe oublié?') }}</a>
                                </div>
                            </div>
                            <!-- g-recaptcha -->
                            @if (Cache::get('setting')->recaptcha_status === 'active')
                            <div class="form-grp mt-3">
                                <div class="g-recaptcha" data-sitekey="{{ Cache::get('setting')->recaptcha_site_key }}"></div>
                                <x-frontend.validation-error name="g-recaptcha-response" />
                            </div>
                            @endif
                            <button type="submit" class="btn btn-two arrow-btn">{{ __('Se connecter') }}<img
                                    src="{{ asset('frontend/img/icons/right_arrow.svg') }}" alt="img"
                                    class="injectable"></button>
                        </form>
                        <div class="account__switch">
                            <p>{{ __('Je n\'ai pas de compte?') }}<a href="{{ route('register') }}">{{ __('S\'inscrire') }}</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- singUp-area-end -->
@endsection
