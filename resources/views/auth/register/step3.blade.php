
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

                        <img src="{{ asset('uploads/custom-images/logocd.png') }}" class="w-25 mb-5" alt="logo" style="margin-left:200px">
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

                        <img src="{{ asset('uploads/custom-images/rib.png') }}" class="w-30 mb-5" alt="logo" style="margin-left:15px;">

                        <form method="POST" action="{{ route('register.postStep3') }}">
                                 @csrf
                           <p>Paiement  par chèque : à l'ordre de l'association claire de Lune  (TIBYANE)
                            Chèque à envoyer à l'adresse de Mebtoul Mohamed au 14 Rue Gaston Carré, 93300 Aubervilliers</p>
                          <a href="{{ route('register.step2') }}" class="btn btn-secondary">Précédent</a>
                          <button type="submit" class="btn btn-success">Terminer</button>

                    </div>

                </form>
            </div>
        </div>
    </div>
</section>
<!-- singUp-area-end -->
@endsection
