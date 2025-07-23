@extends('frontend.layouts.master')
@section('meta_title', 'Demande de livre ' . ' || ' . $setting->app_name)
@section('contents')
    <!-- breadcrumb-area -->
    <x-frontend.breadcrumb :title="__('Demande de livre')" :links="[['url' => route('home'), 'text' => __('Accueil')], ['url' => route('askbook.index'), 'text' => __('Demande de livre')]]" />
    <!-- breadcrumb-area-end -->
    @include('frontend.pages.adhkar')
    <!-- contact-area -->
    <section class="contact-area section-py-120">
        <div class="container">
            <div class="row">
                <div class="col-lg-4">
                    <div class="contact-info-wrap">
                        <ul class="list-wrap">
                            @if($contact?->address)
                            <li>
                                <div class="icon">
                                    <img src="{{ asset('frontend/img/icons/map.svg') }}" alt="img" class="injectable">
                                </div>
                                <div class="content">
                                    <h4 class="title">{{ __('Addresse') }}</h4>
                                    <p>{{ $contact?->address }}</p>
                                </div>
                            </li>
                            @endif
                            @if ($contact?->phone_one || $contact?->phone_two)
                            <li>
                                <div class="icon">
                                    <img src="{{ asset('frontend/img/icons/contact_phone.svg') }}" alt="img"
                                        class="injectable">
                                </div>
                                <div class="content">
                                    <h4 class="title">{{ __('Téléphone') }}</h4>
                                    <a href="tel:{{ $contact?->phone_one }}">{{ $contact?->phone_one }}</a>
                                    <a href="tel:{{ $contact?->phone_two }}">{{ $contact?->phone_two }}</a>
                                </div>
                            </li>
                            @endif
                            @if($contact?->email_one || $contact?->email_two)
                            <li>
                                <div class="icon">
                                    <img src="{{ asset('frontend/img/icons/emial.svg') }}" alt="img"
                                        class="injectable">
                                </div>
                                <div class="content">
                                    <h4 class="title">{{ __('Adresse email') }}</h4>
                                    <a href="mailto:{{ $contact?->email_one }}">{{ $contact?->email_one }}</a>
                                    <a href="mailto:{{ $contact?->email_one }}">{{ $contact?->email_two }}</a>
                                </div>
                            </li>
                            @endif
                        </ul>
                    </div>
                </div>
                <div class="{{ ($contact?->address || $contact?->phone_one || $contact?->phone_two || $contact?->email_one || $contact?->email_two) ? 'col-lg-8' : 'col-lg-12' }}">
                    <div class="contact-form-wrap">
                        <h4 class="title">{{ __('Les livres à demander') }}</h4>
                        <p>{{ __('Votre adresse email ne sera pas publiée. Les champs obligatoires sont marqués') }} *</p>
                        <form id="askbook-form" class="askbook-form" action="{{ route('askbook.store')}}" method="POST">
                            @csrf
                            @method('POST')
                            <div class="row">

                                <div class="col-md-6">
                                    <div class="form-grp">
                                        <input
                                            name="name"
                                            type="text"
                                            placeholder="{{ __('Nom et prénom') }} *"
                                            required
                                            oninvalid="this.setCustomValidity('Veuillez remplir ce champ')"
                                            oninput="setCustomValidity('')"
                                        >
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-grp">
                                        <input
                                            name="school"
                                            type="text"
                                            placeholder="{{ __('Nom de l\'école') }}"

                                        >
                                    </div>
                                </div>

                                <div class="form-grp">
                                    <textarea
                                        name="address"
                                        placeholder="{{ __('Adresse postale') }} *"
                                        required
                                        oninvalid="this.setCustomValidity('Veuillez remplir ce champ')"
                                        oninput="setCustomValidity('')"
                                        rows="3"
                                    ></textarea>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-grp">
                                        <input
                                            name="email"
                                            type="email"
                                            placeholder="{{ __('E-mail') }} *"
                                            required
                                            oninvalid="this.setCustomValidity('Veuillez remplir ce champ avec une adresse email valide')"
                                            oninput="setCustomValidity('')"
                                        >
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-grp">
                                        <input
                                            name="phone"
                                            type="text"
                                            placeholder="{{ __('Téléphone') }} *"
                                            required
                                            oninvalid="this.setCustomValidity('Veuillez remplir ce champ')"
                                            oninput="setCustomValidity('')"
                                        >
                                    </div>
                                </div>

                                {{--
                                @if (Cache::get('setting')->recaptcha_status === 'active')
                                    <div class="form-grp mt-3">
                                        <div class="g-recaptcha"
                                            data-sitekey="{{ Cache::get('setting')->recaptcha_site_key }}">
                                        </div>
                                    </div>
                                @endif
                                --}}

                                <div class="form-grp">
                                    <textarea
                                        name="message"
                                        placeholder="{{ __('Commantaire') }} *"
                                        required
                                        oninvalid="this.setCustomValidity('Veuillez remplir ce champ')"
                                        oninput="setCustomValidity('')"
                                    ></textarea>
                                </div>
                            </div>
                                <button type="submit" class="btn btn-two arrow-btn">{{ __('Envoyer') }}<img
                                        src="{{ asset('frontend/img/icons/right_arrow.svg') }}" alt="img"
                                        class="injectable"></button>
                        </form>
                        <p class="ajax-response mb-0"></p>
                    </div>
                </div>
            </div>
            <!-- contact-map -->
            {{-- @if($contact?->map)
            <div class="contact-map">
                <iframe src="{{ $contact?->map }}" style="border:0;" allowfullscreen="" loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
            @endif --}}
            <!-- contact-map-end -->
        </div>
    </section>
    <!-- contact-area-end -->
@endsection
@push('scripts')
<script>
    $(".askbook-form").on("submit", function (e) {
        e.preventDefault();
        let url = $(this).attr("action");
        let formData = new FormData(this);

        $.ajax({
            method: "POST",
            url: url,
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: function () {
                $(".preloader").removeClass("d-none");
            },
            success: function (response) {
                $(".preloader").addClass("d-none");

                if (response.status === "success") {
                    toastr.success(response.message);

                    // Rediriger si `redirect` est présent
                    if (response.redirect) {
                        window.location.href = response.redirect;
                    }
                } else {
                    toastr.info(response.message || "Demande envoyée.");
                }
            },
            error: function (xhr, status, error) {
                $(".preloader").addClass("d-none");

                if (xhr.responseJSON?.errors) {
                    let errors = xhr.responseJSON.errors;
                    Object.entries(errors).forEach(([key, value]) => {
                        toastr.error(value);
                    });
                } else {
                    toastr.error("Une erreur est survenue. Veuillez réessayer.");
                }
            },
        });
    });
</script>
@endpush
