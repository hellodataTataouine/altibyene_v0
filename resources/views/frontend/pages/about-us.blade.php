@extends('frontend.layouts.master')

@section('meta_title', $seo_setting['about_page']['seo_title'])
@section('meta_description', $seo_setting['about_page']['seo_description'])

@section('contents')
    <!-- breadcrumb-area -->
    <x-frontend.breadcrumb :title="__(' A Propos de l\'Altibyane')" :links="[['url' => route('home'), 'text' => __('Accueil')], ['url' => '', 'text' => __(' A Propos de l\'Altiyane')]]" />
    <!-- breadcrumb-area-end -->
    @include('frontend.pages.adhkar')
    <style>
   .about-section {
    padding: 80px 0;
    background-color: #000000;
    color: #333;
    text-align: center;
}

.about-section .row {
    justify-content: center;
}


.about-content {
    max-width: 800px;
    margin: 0 auto;
    font-size: 1.1rem;
    line-height: 1.8;
    text-align: left;
}

.about-content h2 {
    font-size: 1.5rem;
    margin-top: 30px;
    color: #00000;
    text-align: center;
-
}

.about-content p {
    margin-bottom: 20px;
}
.about-image {
    text-align: center;
    margin-top: 40px;

}

.about-image img {
    max-width: 100%;
    height: auto;
    border-radius: 10px;

    margin-right:50px;
}
        </style>

    <div class="col-md-12" data-aos="fade-right"  style="margin-top:40px">
    <div class="about-content">
        <h2 >Une méthode efficace</h2>
        <p>
            Nous offrons aux enseignants une approche pédagogique structurée et efficace pour l’enseignement du Coran et de la langue arabe. Elle est reconnue pour ses résultats exceptionnels.
        </p>

        <h2>Un responsable agréé auprès du fondateur</h2>
        <p>
            Son responsable, Walid REFIS, est reconnu au niveau européen comme formateur de la méthode Tybiane par le fondateur Abderrahmane Bakr à Médina, un gage de la qualité et de la pertinence de cette méthode.
        </p>

        <h2>Une méthode déjà éprouvée et appliquée</h2>
        <p>
            L'Institut et la Faculté de la mosquée du Prophète (que la paix soit sur lui), ainsi que certains bureaux de prédication, de guidance et centres de bienfaisance...
        </p>

        <p>
            À l’international, cette méthode est mise en œuvre dans de nombreux pays : France, Indonésie, Malaisie, Thaïlande, Sri Lanka, Inde, Pakistan, Turquie, Somalie, Guinée, Djibouti, Nigeria, ainsi que certaines régions en Mauritanie selon le récit de Warsh.
        </p>

    <div class="about-image mt-4"  data-aos="fade-up-right">
        <img src="{{ asset('uploads/custom-images/map.png') }}" alt="Image de la méthode" >
    </div>

          {{-- <div class="image mt-4">
   <img src="{{ asset('uploads/custom-images/lo.png') }}" alt="Image de la méthode" style="width: -50px" >--}}


</div>
</div>
    </div>
  </div>
</section>

  {{--  <!-- about-area -->
    <section class="about-area tg-motion-effects section-py-120">
        <div class="container">
            <div class="row align-items-center justify-content-center">
                <div class="col-lg-6 col-md-9">
                    <div class="about__images">
                        <img src="{{ asset($aboutSection?->global_content?->image) }}" alt="img" class="main-img">
                        <img src="{{ asset('frontend/img/others/about_shape.svg') }}" alt="img"
                            class="shape alltuchtopdown">
                        <a href="{{ $aboutSection?->global_content?->video_url }}" class="popup-video" aria-label="Watch introductory video">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="28" viewBox="0 0 22 28"
                                fill="none">
                                <path
                                    d="M0.19043 26.3132V1.69421C0.190288 1.40603 0.245303 1.12259 0.350273 0.870694C0.455242 0.6188 0.606687 0.406797 0.79027 0.254768C0.973854 0.10274 1.1835 0.0157243 1.39936 0.00193865C1.61521 -0.011847 1.83014 0.0480663 2.02378 0.176003L20.4856 12.3292C20.6973 12.4694 20.8754 12.6856 20.9999 12.9535C21.1245 13.2214 21.1904 13.5304 21.1904 13.8456C21.1904 14.1608 21.1245 14.4697 20.9999 14.7376C20.8754 15.0055 20.6973 15.2217 20.4856 15.3619L2.02378 27.824C1.83056 27.9517 1.61615 28.0116 1.40076 27.9981C1.18536 27.9847 0.97607 27.8983 0.792638 27.7472C0.609205 27.596 0.457661 27.385 0.352299 27.1342C0.246938 26.8833 0.191236 26.6008 0.19043 26.3132Z"
                                    fill="currentcolor" />
                            </svg>
                        </a>
                        @use(App\Enums\ThemeList)
                        @php
                            $theme = session()->has('demo_theme') ? session()->get('demo_theme') : DEFAULT_HOMEPAGE;
                        @endphp
                        @if (!in_array($theme, [ThemeList::BUSINESS->value, ThemeList::KINDERGARTEN->value]))
                            <div class="about__enrolled" data-aos="fade-right" data-aos-delay="200">
                                <p class="title"><span>{{ $hero?->content?->total_student }}</span>
                                    {{ __('') }}</p>
                                <img src="{{ asset($hero?->global_content?->enroll_students_image) }}" alt="img">
                            </div>
                        @endif
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="about__content">
                        <div class="section__title">
                            <span class="sub-title">{{ __('Savoir plus') }}</span>
                            <h2 class="title">
                                {!! clean(processText($aboutSection?->content?->title)) !!}
                            </h2>
                        </div>

                        {!! clean(processText($aboutSection?->content?->description)) !!}
                        @if ($aboutSection?->global_content?->button_url != null)
                            <div class="tg-button-wrap">
                                <a href="{{ $aboutSection?->global_content?->button_url }}"
                                    class="btn arrow-btn">{{ $aboutSection?->content?->button_text }} <img
                                        src="{{ asset('frontend/img/icons/right_arrow.svg') }}" alt="img"
                                        class="injectable"></a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- about-area-end -->

    <!-- brand-area -->
    <div class="brand-area">
        <div class="container-fluid">
            <div class="marquee_mode">
                @foreach ($brands as $brand)
                    <div class="brand__item">
                        <a href="{{ $brand?->url }}"><img src="{{ asset($brand?->image) }}" alt="brand"></a>
                        <img src="{{ asset('frontend/img/icons/brand_star.svg') }}" alt="star">
                    </div>
                @endforeach

            </div>
        </div>
    </div>
    <!-- brand-area-end -->


    <section class="faq__area about">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="faq__img-wrap tg-svg">
                        <div class="faq__round-text">
                            <div class="curved-circle {{ getSessionLanguage() == 'en' ? '' : 'd-none' }}">
                                * {{ __('Education') }} * {{ __('System ') }} * {{ __('can') }} * {{ __('Make ') }} * {{ __('Change ') }} *
                            </div>
                        </div>
                        <div class="faq__img">
                            <img src="{{ asset($faqSection?->global_content?->image) }}" alt="img">
                            <div class="shape-one">
                                <img src="{{ asset('frontend/img/others/faq_shape01.svg') }}"
                                    class="injectable tg-motion-effects4" alt="img">
                            </div>
                            <div class="shape-two">
                                <span class="svg-icon" id="faq-svg"
                                    data-svg-icon="{{ asset('frontend/img/others/faq_shape02.svg') }}"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="faq__content">
                        <div class="section__title pb-10">
                            <span class="sub-title">{{ $faqSection?->content?->short_title }}</span>
                            <h2 class="title">{!! clean(processText($faqSection?->content?->title)) !!}</h2>
                        </div>
                        <p>{!! clean(processText($faqSection?->content?->description)) !!}</p>
                        <div class="faq__wrap">
                            <div class="accordion" id="accordionExample">
                                @foreach ($faqs as $faq)
                                    <div class="accordion-item">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button {{ $loop?->first ? '' : 'collapsed' }}"
                                                type="button" data-bs-toggle="collapse"
                                                data-bs-target="#collapse{{ $faq?->id }}" aria-expanded="true"
                                                aria-controls="collapse{{ $faq?->id }}">
                                                {{ $faq?->translation?->question }}
                                            </button>
                                        </h2>
                                        <div id="collapse{{ $faq?->id }}"
                                            class="accordion-collapse collapse {{ $loop?->first ? 'show' : '' }}"
                                            data-bs-parent="#accordionExample">
                                            <div class="accordion-body">
                                                <p>
                                                    {{ $faq?->translation?->answer }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @php
        $theme_name = session()->has('demo_theme') ? session()->get('demo_theme') : DEFAULT_HOMEPAGE;
    @endphp



  {{--  <!-- features-area -->
    <section class="features__area {{ isRoute('about-us', "feature_{$theme_name}") }}">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-6">
                    <div class="section__title white-title text-center mb-50">
                        <span class="sub-title">{{ __('Comment nous commençons') }}</span>
                        <h2 class="title">{{ __('Commencez votre parcours d’apprentissage dès aujourd’hui !') }}</h2>
                        <p>{{ __('Découvrez un monde de connaissances et de compétences à portée de main – Libérez votre potentiel et réalisez vos rêves grâce à nos ressources d’apprentissage complètes !') }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-xl-3 col-lg-4 col-md-6">
                    <div class="features__item">
                        <div class="features__icon">
                            <img src="{{ asset($ourFeatures?->global_content?->image_one) }}" class="injectable"
                                alt="img">
                        </div>
                        <div class="features__content">
                            <h4 class="title">{{ $ourFeatures?->content?->title_one }}</h4>
                            <p>{{ $ourFeatures?->content?->sub_title_one }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6">
                    <div class="features__item">
                        <div class="features__icon">
                            <img src="{{ asset($ourFeatures?->global_content?->image_two) }}" class="injectable"
                                alt="img">
                        </div>
                        <div class="features__content">
                            <h4 class="title">{{ $ourFeatures?->content?->title_two }}</h4>
                            <p>{{ $ourFeatures?->content?->sub_title_two }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6">
                    <div class="features__item">
                        <div class="features__icon">
                            <img src="{{ asset($ourFeatures?->global_content?->image_three) }}" class="injectable"
                                alt="img">
                        </div>
                        <div class="features__content">
                            <h4 class="title">{{ $ourFeatures?->content?->title_three }}</h4>
                            <p>{{ $ourFeatures?->content?->sub_title_three }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6">
                    <div class="features__item">
                        <div class="features__icon">
                            <img src="{{ asset($ourFeatures?->global_content?->image_four) }}" class="injectable"
                                alt="img">
                        </div>
                        <div class="features__content">
                            <h4 class="title">{{ $ourFeatures?->content?->title_four }}</h4>
                            <p>{{ $ourFeatures?->content?->sub_title_four }}</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
    <!-- features-area-end -->---}}

    <!-- testimonial-area -->
    <section class="testimonial__area about_testimonial section-py-120" >
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-5">
                    <div class="section__title text-center mb-50" data-aos="fade-up-right">
                        <span class="sub-title">{{ __('Nos témoignages') }}</span>
                        <h2 class="title">{{ __('Ce qu’ils disent de nous :') }}</h2>
                        <p>{{ __('Grâce à Allah Taala : ') }}</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="testimonial__item-wrap" data-aos="fade-up-right">
                        <div class="swiper-container testimonial-swiper-active">
                            <div class="swiper-wrapper">
                                @foreach ($reviews as $testimonial)
                                    <div class="swiper-slide">
                                        <div class="testimonial__item">
                                            <div class="testimonial__item-top">
                                                <div class="testimonial__author">
                                                 {{--   <div class="testimonial__author-thumb" data-aos="fade-up-right">
                                                        <img src="{{ asset($testimonial?->image) }}" alt="img">
                                                    </div>--}}
                                                    <div class="testimonial__author-content">
                                                        <div class="rating">
                                                            @for ($i = 0; $i < $testimonial?->rating; $i++)
                                                                <i class="fas fa-star"></i>
                                                            @endfor
                                                        </div>
                                                        <h2 class="title">{{ $testimonial?->translation?->name }}</h2>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="testimonial__content">
                                                <p>“ {{ $testimonial?->translation?->comment }} ”</p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="testimonial__nav">
                            <button class="testimonial-button-prev"><i class="flaticon-arrow-right"></i></button>
                            <button class="testimonial-button-next"><i class="flaticon-arrow-right"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- testimonial-area-end -->


    <!-- newsletter-area -->
    <section class="newsletter__area" >
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-4">
                    <div class="newsletter__img-wrap" data-aos="fade-up">
                        <img src="{{ asset($newsletterSection?->global_content?->image) }}" alt="img">
                        <img src="{{ asset('frontend/img/others/newsletter_shape01.png') }}" alt="img"
                            data-aos="fade-up" data-aos-delay="400">
                        <img src="{{ asset('frontend/img/others/newsletter_shape02.png') }}" alt="img"
                            class="alltuchtopdown">
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="newsletter__content" data-aos="fade-up">
                        <h1 class="title"><b>{{ __('Rejoignez notre programme de mémorisation du Coran sur 6 ans conçu pour un apprentissage ') }}</b> <br> <b>{{ __('progressif et structuré') }}?</b></h1>
                        </h2>
                              {{--<div class="newsletter__form">
                            <form action="" method="post" class="newsletter">
                                @csrf
                                {{--<input type="email" placeholder="{{ __('Tapez votre email') }}" name="email">       </form>--}}
                                <a href="\memorisation">
                                <button type="submit" class="btn">{{ __('Abonnez-vous !') }}</button>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="newsletter__shape">
            <img src="{{ asset('frontend/img/banner/avion.webp') }}" alt="img" data-aos="fade-right"
                data-aos-delay="400"  style="margin-top:50px;right:20px" >
        </div>
    </section>
    <!-- newsletter-area-end -->
@endsection
 <section>


 </section>
