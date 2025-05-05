@extends('frontend.layouts.master')
@section('meta_title', 'Offre et Tarif ' . ' || ' . $setting->app_name)

@section('contents')
    <!-- breadcrumb-area -->
    <x-frontend.breadcrumb :title="__('Offre et Tarif')" :links="[['url' => route('home'), 'text' => __('Accueil')], ['url' => route('cart'), 'text' => __('Offre et Tarif')]]" />
    <!-- breadcrumb-area-end -->
    @include('frontend.pages.adhkar')


    <style>
        body {
            background: #f8f9fa;
            font-family: 'Segoe UI', sans-serif;
        }
        .pricing-card {
            border-radius: 20px;
            padding: 30px;
            background: white;
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }
        .pricing-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.15);
        }
        .price {
            font-size: 2rem;
            font-weight: bold;
            color: #007bff;
        }
        .plan-title {
            font-size: 1.4rem;
            font-weight: 600;
        }
    </style>
</head>
<body>

<div class="container py-5">
    <h1 class="text-center mb-4">Nos Offres & Tarifs</h1>
    <p class="text-center text-muted mb-5">Choisissez le plan qui vous convient le mieux</p>

    <div class="row g-4">
        <!-- Card 1 -->
        <div class="col-md-6 col-lg-3">
            <div class="pricing-card text-center">
                <h5 class="plan-title">Formation à la methode Altibyane</h5>
                <p class="price">💰670 €</p>
                <p>Nombre de places limitées (30)</p>
                <p>Support limité</p>
                <button onclick="window.location.href='/register/step1'" class="btn btn-outline-primary rounded-pill">
                    Choisir
                </button>

            </div>
        </div>
        <!-- Card 2 -->
        <div class="col-md-6 col-lg-3">
            <div class="pricing-card text-center">
                <h5 class="plan-title">Programme de science en 3 ans </h5>
                <p class="price">💰250 €</p>
                <p>Nombre de places limitées (30)</p>
                <p>Support Email</p>
                <button onclick="window.location.href='/register/step1'" class="btn btn-outline-primary rounded-pill">
                    Choisir
                </button>
            </div>
        </div>
        <!-- Card 3 -->
        <div class="col-md-6 col-lg-3">
            <div class="pricing-card text-center">
                <h5 class="plan-title">Programme de science en 6 ans </h5>
                <p class="price">💰350 €</p>
                <p>Nombre de places limitées (30)</p>
                <p>Support prioritaire</p>
                <button onclick="window.location.href='/register/step1'" class="btn btn-outline-primary rounded-pill">
                    Choisir
                </button>
            </div>
        </div>
        <!-- Card 4 -->
        <div class="col-md-6 col-lg-3">
            <div class="pricing-card text-center">
                <h5 class="plan-title">Programme de mémorisation pour femme</h5>
                <p class="price">💰200 €</p>
                <p>Nombre de places limitées (30)</p>
                <p>Gestion de compte dédiée</p>
                <button onclick="window.location.href='/register/step1'" class="btn btn-outline-primary rounded-pill">
                    Choisir
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

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
