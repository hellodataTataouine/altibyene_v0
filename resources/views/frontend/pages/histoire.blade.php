@extends('frontend.layouts.master')
@section('meta_title', 'histoire ' . ' || ' . $setting->app_name)

@section('contents')
    <!-- breadcrumb-area -->
    <x-frontend.breadcrumb :title="__('Histoire')" :links="[['url' => route('home'), 'text' => __('Accueil')], ['url' => route('cart'), 'text' => __('Histoire')]]" />
    <!-- breadcrumb-area-end -->


    <style>

        .hero-section {

            color: white;
            text-align: center;
            padding: 80px 0;
        }
        .hero-section h1 {
            font-size: 3rem;
            animation: fadeIn 2s ease-in-out;
        }
        .hero-section p {
            font-size: 1.2rem;
            animation: fadeIn 3s ease-in-out;
        }
        .history-section {

            padding: 50px 0;
        }
        .history-section h2 {
            text-align: center;
            font-size: 2.5rem;
            margin-bottom: 30px;
            animation: fadeIn 3s ease-in-out;
        }
        .history-text {
            text-align: center;
            font-size: 1.2rem;
            line-height: 1.6;
            margin-bottom: 30px;
            animation: fadeIn 4s ease-in-out;
        }
        .history-timeline {
            list-style-type: none;
            padding: 0;
            animation: fadeIn 5s ease-in-out;
        }
        .timeline-item {
            margin-bottom: 20px;

            padding: 20px;
            border-radius: 10px;
        }
        .timeline-item h5 {
            font-size: 1.5rem;
            color: #343a40;
        }
        .timeline-item p {
            font-size: 1rem;
            color: #6c757d;
        }
        @keyframes fadeIn {
            0% { opacity: 0; }
            100% { opacity: 1; }
        }
    </style>
</head>
<body>

   <!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <h1 class="wow animate__animated animate__zoomIn">L'Histoire de Notre Association</h1>
        <p class="lead wow animate__animated animate__fadeInUp animate__delay-1s">
            Découvrez comment notre association a vu le jour et l'impact qu'elle a eu au fil des années.
        </p>
    </div>
</section>

<!-- History Section -->
<section class="history-section">
    <div class="container">
        <h2 data-aos="fade-up" data-aos-delay="200">Notre Parcours</h2>
        <p class="history-text" data-aos="fade-up" data-aos-delay="300">
            Notre association a été fondée avec la mission de soutenir les communautés en difficulté. Depuis notre création, nous avons travaillé sans relâche pour améliorer la vie de ceux qui en ont besoin. Voici notre histoire en quelques étapes clés :
        </p>

        <!-- History Timeline -->
        <ul class="history-timeline">
            <li class="timeline-item" data-aos="fade-up" data-aos-delay="400">
                <h5>2020 - Fondation de l'Association</h5>
                <p>2020, l'association a été fondée par un groupe de bénévoles passionnés, avec pour mission de venir en aide aux personnes en situation de précarité.</p>
            </li>
            <li class="timeline-item" data-aos="fade-up" data-aos-delay="500">
                <h5>2022 - Extension des Activités</h5>
                <p>En 2022, l'association a élargi ses activités en lançant des programmes d'éducation et de formation pour les jeunes défavorisés.</p>
            </li>
            <li class="timeline-item" data-aos="fade-up" data-aos-delay="600">
                <h5>2023 - Reconnaissance Nationale</h5>
                <p>En 2023, l'association a reçu un prix national pour ses efforts exceptionnels dans l'amélioration des conditions de vie des communautés vulnérables.</p>
            </li>
            <li class="timeline-item" data-aos="fade-up" data-aos-delay="700">
                <h5>2024 - Projet International</h5>
                <p>En 2024, nous avons étendu nos actions à l'international, en apportant de l'aide humanitaire et en soutenant des projets éducatifs dans plusieurs pays en développement.</p>
            </li>
        </ul>
    </div>
</section>

<!-- WOW.js & Animate.css -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/wow/1.1.2/wow.min.js"></script>
<script>
    new WOW().init();
</script>

    <!-- Call to Action Section -->
    <section class="text-center py-5">
        <h3 class="animate__animated animate__fadeIn animate__delay-8s">Rejoignez-nous pour faire une différence !</h3>
        <button class="btn btn-primary btn-lg animate__animated animate__fadeIn animate__delay-9s" onclick="window.location.href='#'">Devenir Bénévole</button>
    </section>

    <!-- Bootstrap JS and Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>






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
