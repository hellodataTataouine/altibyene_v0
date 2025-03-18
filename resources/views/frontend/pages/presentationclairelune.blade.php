@extends('frontend.layouts.master')
@section('meta_title', 'presentation claire de lune ' . ' || ' . $setting->app_name)

@section('contents')
    <!-- breadcrumb-area -->
    <x-frontend.breadcrumb :title="__('Presentation Claire de Lune')" :links="[['url' => route('home'), 'text' => __('Accueil')], ['url' => route('cart'), 'text' => __('Presentation Claire de Lune')]]" />
    <!-- breadcrumb-area-end -->
<style>


    .hero-section {

        color: white;
        text-align: center;
        padding: 100px 0;
    }

    .hero-section h1 {
        font-size: 3rem;
        animation: fadeIn 2s ease-in-out;
    }

    .hero-section p {
        font-size: 1.2rem;
        animation: fadeIn 3s ease-in-out;
    }

    .mission-section,
    .team-section {
        padding: 50px 0;
        background-color: #fff;
    }

    .mission-section h2,
    .team-section h2 {
        text-align: center;
        font-size: 2.5rem;
        margin-bottom: 30px;
        animation: fadeIn 3s ease-in-out;
    }

    .mission-text,
    .team-text {
        text-align: center;
        font-size: 1.2rem;
        line-height: 1.6;
        margin-bottom: 30px;
        animation: fadeIn 4s ease-in-out;
    }

    .team-member {
        text-align: center;
        margin-bottom: 30px;
    }

    .team-member img {
        border-radius: 50%;
        width: 150px;
        height: 150px;
        object-fit: cover;
    }

    .team-member h5 {
        font-size: 1.5rem;
        color: #343a40;
        margin-top: 15px;
    }

    .team-member p {
        font-size: 1rem;
        color: #6c757d;
    }

    @keyframes fadeIn {
        0% { opacity: 0; }
        100% { opacity: 1; }
    }

</style>
</head>
<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <h1 class="wow animate__animated animate__fadeIn">Bienvenue à l'Association Claire de Lune</h1>
        <p class="lead wow animate__animated animate__fadeIn animate__delay-1s">Nous œuvrons pour le bien-être des communautés et la solidarité à travers diverses initiatives locales et internationales.</p>
    </div>
</section>

<!-- Mission Section -->
<section class="mission-section">
    <div class="container">
        <h2 class="wow animate__animated animate__fadeIn animate__delay-2s">Notre Mission</h2>
        <p class="mission-text wow animate__animated animate__fadeIn animate__delay-3s">
            L'Association Claire de Lune a pour mission de promouvoir l'éducation, la solidarité et l'aide humanitaire à travers divers projets locaux et internationaux. Nous croyons en un monde meilleur grâce à la collaboration et à l'implication de chacun.
        </p>
    </div>
</section>

<!-- Team Section -->
<section class="team-section">
    <div class="container">
        <h2 class="wow animate__animated animate__fadeIn animate__delay-4s">Notre Équipe</h2>
        <p class="team-text wow animate__animated animate__fadeIn animate__delay-5s">
            Notre équipe est composée de bénévoles passionnés et dévoués, qui travaillent ensemble pour faire une réelle différence dans la vie des personnes dans le besoin.
        </p>

        <!-- Team Members -->
        <div class="row">
            <div class="col-md-4 team-member wow animate__animated animate__fadeIn">
                <img src="uploads/custom-images/instructor3.png" alt="Membre de l'équipe 1">
                <h5>Ahmed Ahmed </h5>
                <p>Président de l'association</p>
            </div>
            <div class="col-md-4 team-member wow animate__animated animate__fadeIn animate__delay-1s">
                <img src="uploads/custom-images/pcl.webp" alt="Membre de l'équipe 2">
                <h5>Fatma Ahmed</h5>
                <p>Responsable des projets humanitaires</p>
            </div>
            <div class="col-md-4 team-member wow animate__animated animate__fadeIn animate__delay-2s">
                <img src="uploads/custom-images/instructor1.png" alt="Membre de l'équipe 3">
                <h5>Ahmed Ben Ali</h5>
                <p>Coordinateur des événements</p>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action Section -->
<section class="text-center py-5">
    <h3 class="wow animate__animated animate__fadeIn animate__delay-6s">Rejoignez-nous dans notre mission !</h3>
    <button class="btn btn-primary btn-lg wow animate__animated animate__fadeIn animate__delay-7s" onclick="window.location.href='#'">Faire un Don</button>
</section>

<!-- WOW.js Init -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/wow/1.1.2/wow.min.js"></script>
<script>
    new WOW().init();
</script>


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
