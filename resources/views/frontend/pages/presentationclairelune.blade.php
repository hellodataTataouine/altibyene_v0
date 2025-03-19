@extends('frontend.layouts.master')
@section('meta_title', 'presentation claire de lune ' . ' || ' . $setting->app_name)

@section('contents')
    <!-- breadcrumb-area -->
    <x-frontend.breadcrumb :title="__('Presentation Claire de Lune')" :links="[['url' => route('home'), 'text' => __('Accueil')], ['url' => route('cart'), 'text' => __('Presentation Claire de Lune')]]" />
    <!-- breadcrumb-area-end -->
    <style>

       body {
            font-family: 'Arial', sans-serif;
            margin: 0;
        }

        .hero-section {

            color: white;
            text-align: center;
            padding: 120px 0;
        }

        .hero-section h1 {
            font-size: 3.5rem;
            font-weight: bold;
            animation: fadeInUp 1.5s ease-out;
        }

        .hero-section p {
            font-size: 1.5rem;
            margin-top: 15px;
            animation: fadeInUp 2s ease-out;
        }

        .mission-section, .team-section {
            padding: 60px 0;
            background-color: #ffffff;
            text-align: center;
        }

        .mission-section h2, .team-section h2 {
            font-size: 2.8rem;
            color: #000;
            margin-bottom: 20px;
        }

        .mission-text, .team-text {
            font-size: 1.2rem;
            color: #000;
            max-width: 600px;
            margin: 0 auto 40px;
        }

        .team-member {
            text-align: center;
            margin-bottom: 40px;
        }

        .team-member img {
            border-radius: 50%;
            border: 5px solid #F7C815;
            width: 160px;
            height: 160px;
            object-fit: cover;
            transition: transform 0.3s;
        }

        .team-member img:hover {
            transform: scale(1.1);
        }

        .team-member h5 {
            font-size: 1.6rem;
            color: #000;
            margin-top: 15px;
        }

        .team-member p {
            color: #000;
            font-size: 1rem;
        }

        .cta-section {

            color: white;
            padding: 50px 0;
            text-align: center;
        }

        .cta-section h3 {
            font-size: 2rem;
        }

        .cta-section .btn {
            background-color: #F7C815;
            color: #000;
            padding: 12px 30px;
            border-radius: 30px;
            transition: background-color 0.3s, color 0.3s;
        }



        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <h1>Bienvenue à l'Association Claire de Lune</h1>
            <p>Nous œuvrons pour le bien-être des communautés et la solidarité à travers diverses initiatives.</p>
        </div>
    </section>

    <!-- Mission Section -->
    <section class="mission-section">
        <div class="container">
            <h2>Notre Mission</h2>
            <p class="mission-text">
                L'Association Claire de Lune vise à promouvoir l'éducation, la solidarité et l'aide humanitaire par des projets locaux et internationaux.
            </p>
        </div>
    </section>

    <!-- Team Section -->
    <section class="team-section">
        <div class="container">
            <h2>Notre Équipe</h2>
            <p class="team-text">Une équipe de bénévoles passionnés qui changent des vies chaque jour.</p>

            <div class="row">
                <div class="col-md-4 team-member">
                    <img src="uploads/custom-images/instructor3.png" alt="Ahmed Ahmed">
                    <h5>Ahmed Ahmed</h5>
                    <p>Président de l'association</p>
                </div>
                <div class="col-md-4 team-member">
                    <img src="uploads/custom-images/pcl.webp" alt="Fatma Ahmed">
                    <h5>Fatma Ahmed</h5>
                    <p>Responsable des projets humanitaires</p>
                </div>
                <div class="col-md-4 team-member">
                    <img src="uploads/custom-images/instructor1.png" alt="Ahmed Ben Ali">
                    <h5>Ahmed Ben Ali</h5>
                    <p>Coordinateur des événements</p>
                </div>
            </div>
        </div>
        </div>

    </section>

    <!-- Call to Action Section -->
    <section class="cta-section">
        <h3>Rejoignez-nous pour faire la différence!</h3>
        <button class="btn btn-lg" onclick="window.location.href='#'">Faire un Don</button>
    </section>


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
