
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
                    <form method="POST" action="{{ route('register.postStep2') }}">
                        @csrf
                        <div class="singUp-wrap">

                            <img src="{{ asset('uploads/custom-images/logocd.png') }}" class=" w-25 mb-5" alt="logo" style="margin-left:200px">
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

                            <img src="{{ asset('uploads/custom-images/Reglement 1.png') }}" class=" w-40 mb-6" alt="logo" style="margin-left:20px;">

                            <img src="{{ asset('uploads/custom-images/Reglement 2.png') }}" class="w-40 mb-6" alt="logo" style="margin-left:20px;">

                            <img src="{{ asset('uploads/custom-images/Reglement 3.png') }}" class="w-40 mb-5" alt="logo" style="margin-left:20px;">


                            <div class="mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="honor_statement" name="honor_statement" {{ old('honor_statement') ? 'checked' : '' }} required>
                                    <label class="form-check-label" for="honor_statement">
                                        Atteste sur l'honneur, l'exactitude des renseignements fournis et m'engage à prévenir l’Association de tout changement éventuel
                                    </label>
                                    @error('honor_statement')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-check mt-3">
                                    <input class="form-check-input" type="checkbox" id="rules_acknowledgment" name="rules_acknowledgment" {{ old('rules_acknowledgment') ? 'checked' : '' }} required>
                                    <label class="form-check-label" for="rules_acknowledgment">
                                        Reconnais également avoir pris connaissance du règlement intérieur, déclare approuver son contenu et m'engage à m'y conformer
                                    </label>
                                    @error('rules_acknowledgment')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-check mt-3">
                                    <input class="form-check-input" type="checkbox" id="data_processing_acknowledgment" name="data_processing_acknowledgment" {{ old('data_processing_acknowledgment') ? 'checked' : '' }} required>
                                    <label class="form-check-label" for="data_processing_acknowledgment">
                                        Reconnais avoir pris connaissance que les informations recueillies font l'objet d'un traitement informatique
                                    </label>
                                    @error('data_processing_acknowledgment')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>


                            <a href="{{ route('register.step1') }}" class="btn btn-secondary">Précédent</a>
                            <button type="submit" class="btn btn-primary">Suivant</button>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
<!-- singUp-area-end -->
@endsection
