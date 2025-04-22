@extends('frontend.layouts.master')
@section('meta_title', 'histoire ' . ' || ' . $setting->app_name)

@section('contents')
    <!-- breadcrumb-area -->
    <x-frontend.breadcrumb :title="__('Histoire')" :links="[['url' => route('home'), 'text' => __('Accueil')], ['url' => route('cart'), 'text' => __('Histoire')]]" />
    <!-- breadcrumb-area-end -->

    <style>
        .history-section {
            padding: 50px 0;

        }
        .history-section .container p{
           text-align: center;
           margin-top: 2%;

        }
        .history-container {
            display: flex;
            justify-content: space-around;
            align-items: flex-start;
        }
        .timeline-item {
            position: relative;
            text-align: center;
            margin: 20px;
        }
        .timeline-frame {
            width: 192px;
            height: 390px;
            border: 4px solid #f1f0ef;
            border-radius: 10px;
            position: relative;
            overflow: hidden;
            padding: 20px;
            background: rgba(255, 255, 255, 0.8);
            display: flex;
            flex-direction: column;
            justify-content: center;
            text-align: center;
            transition: transform 0.3s ease-in-out;
        }
        .timeline-frame:hover {
            transform: scale(1.1);
        }
        .timeline-frame h5 {
            font-size: 1.5rem;
            color: #343a40;
            margin-bottom: 10px;
        }
        .timeline-frame p {
            font-size: 1rem;
            color: #6c757d;
        }
        .timeline-frame img {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: auto;
            opacity: 0.8;
            z-index: -1;
        }
        @media (max-width: 768px) {
            .history-container {
                flex-direction: column;
                align-items: center;
            }
        }
    </style>

    <section class="history-section" style="margin-top: 5%"  data-aos="fade-up-left" >
        <div class="container">
            <h2 class="text-center" >Notre Parcours</h2>
            <p>Découvrez comment notre association a vu le jour et l'impact qu'elle a eu au fil des années.</p>
            <div class="history-container"   data-aos="fade-up-left">

                <!-- Item 1 -->
                <div class="timeline-item"  style="margin-top: 8%">
                    <div class="timeline-frame" style="width: 192px; height: 390px;">
                        <h5  style="margin-top: -60%">2020 - Fondation</h5>
                        <p>Création de l'association par des bénévoles passionnés.</p>
                        <img src="uploads/custom-images/formation.jpg" alt="Fondation" >
                    </div>
                </div>

                <!-- Item 2 -->
                <div class="timeline-item" style="margin-top: 8%">
                    <div class="timeline-frame" style="width: 272px; height: 338px;">
                        <h5  style="margin-top: -60%">2022 - Extension</h5>
                        <p>Lancement des programmes éducatifs pour les jeunes.</p>
                        <img src="uploads/custom-images/formation.jpg" alt="Extension">
                    </div>
                </div>

                <!-- Item 3 -->
                <div class="timeline-item"  style="margin-top: 8%">
                    <div class="timeline-frame" style="width: 272px; height: 434px;">
                        <h5  style="margin-top: -60%">2023 - Reconnaissance</h5>
                        <p>Prix national pour améliorer les conditions sociales.</p>
                        <img src="uploads/custom-images/formation.jpg" alt="Reconnaissance">
                    </div>
                </div>

                <!-- Item 4 -->
                <div class="timeline-item"   style="margin-top: 8%">
                    <div class="timeline-frame" style="width: 272px; height: 338px;">
                        <h5    style="margin-top: -70%">2024 - Expansion Internationale</h5>
                        <p>Aide humanitaire et soutien éducatif à l'international.</p>
                        <img src="uploads/custom-images/formation.jpg" alt="Expansion">
                    </div>
                </div>

            </div>
        </div>
    </section>


    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true
        });
    </script>

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
