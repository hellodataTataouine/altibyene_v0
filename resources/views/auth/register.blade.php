@extends('frontend.layouts.master')
@section('meta_title', 'S\'inscrire'. ' || ' . $setting->app_name)

@section('contents')
    <!-- breadcrumb-area -->
    <x-frontend.breadcrumb :title="__('S\'inscrire')" :links="[['url' => route('home'), 'text' => __('Accueil')], ['url' => route('register.step1'), 'text' => __('S\'inscrire')]]" />
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
                        <form method="POST" action="{{ route('register.postStep1') }}" class="account__form" id="multiStepForm">
                            @csrf

                            <!-- Nom -->
                            <div class="form-grp">
                                <label for="last_name">Nom *</label>
                                <input type="text" id="last_name" name="last_name" placeholder="Nom" required>
                            </div>

                            <!-- Prénom -->
                            <div class="form-grp">
                                <label for="first_name">Prénom *</label>
                                <input type="text" id="first_name" name="first_name" placeholder="Prénom" required>
                            </div>

                            <!-- Genre -->
                            <div class="form-grp">
                                <label>Genre *</label><br>
                                <label><input type="radio" name="gender" value="M" required> Melle</label><br>
                                <label><input type="radio" name="gender" value="F" required> Femelle</label>
                            </div>

                            <!-- Né(e) à -->
                            <div class="form-grp">
                                <label for="birthplace">Né(e) à *</label>
                                <input type="text" id="birthplace" name="birthplace" placeholder="Lieu de naissance" required>
                            </div>

                            <!-- Date de naissance -->
                            <div class="form-grp">
                                <label for="birthdate">Date de naissance *</label>
                                <input type="date" id="birthdate" name="birthdate" required>
                            </div>

                            <!-- Adresse -->
                            <div class="form-grp">
                                <label for="address">Adresse *</label>
                                <input type="text" id="address" name="address" placeholder="Adresse" required>
                            </div>

                            <!-- Code postal -->
                            <div class="form-grp">
                                <label for="postal_code">Code Postal *</label>
                                <input type="text" id="postal_code" name="postal_code" required>
                            </div>

                            <!-- Ville -->
                            <div class="form-grp">
                                <label for="city">Ville *</label>
                                <input type="text" id="city" name="city" required>
                            </div>

                            <!-- Téléphone fixe -->
                            <div class="form-grp">
                                <label for="phone_fix">N° Fixe *</label>
                                <input type="text" id="phone_fix" name="phone_fix" placeholder="N° Fixe" required>
                            </div>

                            <!-- Téléphone portable -->
                            <div class="form-grp">
                                <label for="phone">N° Portable *</label>
                                <input type="text" id="phone" name="phone" placeholder="N° Portable" required>
                            </div>

                            <!-- Email -->
                            <div class="form-grp">
                                <label for="email">Adresse e-mail *</label>
                                <input type="email" id="email" name="email" placeholder="Adresse e-mail" required>
                            </div>
                            <div class="form-grp">
                                <label for="password">Mot de passe *</label>
                                <input type="password" id="password" name="password" placeholder="password" required>
                            </div>
                            <div class="form-grp">
                                <label for="password">Confirmer de passe *</label>
                                <input type="password" id="password" name="password" placeholder="password" required>
                            </div>
                            <!-- Informations supplémentaires -->
                            <button type="submit" class="btn btn-primary">Suivant</button>

                            </div>
                        </form>
                </div>
            </div>
        </div>
    </section>
    <!-- singUp-area-end -->
@endsection
