@extends('frontend.layouts.master')
@section('meta_title', 'S\'inscrire'. ' || ' . $setting->app_name)

@section('contents')
    <!-- breadcrumb-area -->
    <x-frontend.breadcrumb :title="__('S\'inscrire')" :links="[['url' => route('home'), 'text' => __('Accueil')], ['url' => route('register'), 'text' => __('S\'inscrire')]]" />
    <!-- breadcrumb-area-end -->

    <!-- singUp-area -->
    <section class="singUp-area section-py-120">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-6 col-lg-8">
                    <div class="singUp-wrap">
                        <h2 class="title">{{ __('Créez votre compte') }}</h2>
                        <p>{{ __('Salut ! Prêt à nous rejoindre ? Nous avons juste besoin de quelques informations :') }}<br>{{ __('') }}
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
                        <form method="POST" action="{{ route('register') }}" class="account__form">
                            @csrf

                            <div class="row gutter-20">
                                <div class="col-md-12">
                                    <div class="form-grp">
                                        <label for="fast-name">{{ __('Nom et Prénom') }}</label>
                                        <input type="text" id="fast-name" placeholder="{{ __('Nom et Prénom') }}"
                                            name="name">
                                        <x-frontend.validation-error name="name" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-grp">
                                <label for="email">{{ __('Email') }}</label>
                                <input type="email" id="email" placeholder="{{ __('email') }}" name="email">
                                <x-frontend.validation-error name="email" />
                            </div>
                            <div class="form-grp">
                                <label for="password">{{ __('Mot de passe') }}</label>
                                <input type="password" id="password" placeholder="{{ __('Mot de passe') }}" name="password">
                                <x-frontend.validation-error name="password" />
                            </div>
                            <div class="form-grp">
                                <label for="confirm-password">{{ __('Confirmez le mot de passe') }}</label>
                                <input type="password" id="confirm-password" placeholder="{{ __('Confirmez le mot de passe') }}"
                                    name="password_confirmation">
                                <x-frontend.validation-error name="password_confirmation" />
                            </div>

                            <!-- g-recaptcha -->
                            @if (Cache::get('setting')->recaptcha_status === 'active')
                                <div class="form-grp mt-3">
                                    <div class="g-recaptcha"
                                        data-sitekey="{{ Cache::get('setting')->recaptcha_site_key }}"></div>
                                    <x-frontend.validation-error name="g-recaptcha-response" />
                                </div>
                            @endif

                            <button type="submit" class="btn btn-two arrow-btn">{{ __('S\'inscrire') }}<img
                                    src="{{ asset('frontend/img/icons/right_arrow.svg') }}" alt="img"
                                    class="injectable"></button>
                        </form>
                        <div class="account__switch">
                            <p>{{ __('Vous avez déjà un compte ?') }}<a href="{{ route('login') }}">{{ __('se connecter') }}</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- singUp-area-end -->
@endsection
