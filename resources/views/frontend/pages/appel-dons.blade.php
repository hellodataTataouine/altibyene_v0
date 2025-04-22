@extends('frontend.layouts.master')
@section('meta_title', 'appel et dons ' . ' || ' . $setting->app_name)

@section('contents')
    <!-- breadcrumb-area -->
    <x-frontend.breadcrumb :title="__('Appel et Dons ')" :links="[['url' => route('home'), 'text' => __('Accueil')], ['url' => route('cart'), 'text' => __('Appel et Dons ')]]" />
    <!-- breadcrumb-area-end -->
<style>
    .cta-section {

        color: white;
        padding: 100px 0;
        text-align: center;
    }
    .cta-section h1 {
        font-size: 3rem;
        font-weight: bold;
    }
    .cta-section p {
        font-size: 1.2rem;
        margin-bottom: 30px;
    }
    .btn-cta {
        font-size: 1.2rem;
        padding: 12px 30px;
        margin-top: 10%;
        justify-content:space-between;
    }
    .cta-image img {
        max-width: 100%;
        height: auto;
        border-radius: 15px;
    }
    .features {
        padding: 60px 0;
    }
    .feature-item {
        text-align: center;
        padding: 20px;
    }
</style>
</head>
<body>

<!-- Call to Action Section -->
<section class="cta-section">
<div class="container">
    <h1 class="wow animate__animated animate__fadeInDown">Agissez pour un Monde Meilleur</h1>
    <p class="wow animate__animated animate__fadeInUp animate__delay-1s">
        Rejoignez-nous dans notre mission de transformation sociale. Ensemble, nous pouvons faire la différence.
    </p>

    <!-- Center Image -->
    <div class="cta-image wow animate__animated animate__zoomIn animate__delay-1s" >
        <img src="uploads/custom-images/dons2.jpg" alt="Impact Social">
    </div>

    <button class="btn btn-light btn-cta wow animate__animated animate__pulse animate__infinite animate__delay-2s" >
        Faites un Don
    </button>
    <button class="btn btn-outline-light btn-cta wow animate__animated animate__pulse animate__infinite animate__delay-2s">
        Rejoignez-nous
    </button>
</div>
</section>

<!-- Features Section -->
<section class="features text-center py-5">
    <div class="container">
        <div class="row">
            <!-- Card 1 -->
            <div class="col-md-4 mb-4">
                <div class="card h-100 wow animate__animated animate__fadeInLeft">
                    <div class="card-body">
                        <i class="fas fa-hands-helping fa-3x mb-3"></i>
                        <h4 class="card-title">Fraternité</h4>
                        <p class="card-text">Renforcez les liens entre étudiants et enseignants dans un esprit de bienveillance islamique.</p>
                    </div>
                </div>
            </div>

            <!-- Card 2 -->
            <div class="col-md-4 mb-4">
                <div class="card h-100 wow animate__animated animate__fadeInUp">
                    <div class="card-body">
                        <i class="fas fa-school fa-3x mb-3"></i>
                        <h4 class="card-title">Savoir</h4>
                        <p class="card-text">Approfondissez vos connaissances en sciences islamiques à travers une formation authentique et structurée.</p>
                    </div>
                </div>
            </div>

            <!-- Card 3 -->
            <div class="col-md-4 mb-4">
                <div class="card h-100 wow animate__animated animate__fadeInRight">
                    <div class="card-body">
                        <i class="fas fa-globe fa-3x mb-3"></i>
                        <h4 class="card-title">Transmission</h4>
                        <p class="card-text">Participez à la diffusion du savoir islamique auprès des générations futures.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- WOW.js & Bootstrap JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/wow/1.1.2/wow.min.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://kit.fontawesome.com/a076d05399.js"></script>
<script>
new WOW().init();
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
