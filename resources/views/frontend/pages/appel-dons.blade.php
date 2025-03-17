@extends('frontend.layouts.master')
@section('meta_title', 'appel et dons ' . ' || ' . $setting->app_name)

@section('contents')
    <!-- breadcrumb-area -->
    <x-frontend.breadcrumb :title="__('Appel et Dons ')" :links="[['url' => route('home'), 'text' => __('Accueil')], ['url' => route('cart'), 'text' => __('Appel et Dons ')]]" />
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
        .carousel-inner img {
            object-fit: cover;
            height: 400px;
        }
        .donation-buttons button {
            padding: 15px 30px;
            font-size: 1.2rem;
            border-radius: 25px;
            margin-top: 30px;
            transition: background-color 0.3s ease;
        }
        .donation-buttons button:hover {

            color: white;
        }
        .carousel-item {
            animation: zoomIn 1s ease;
        }
        @keyframes fadeIn {
            0% { opacity: 0; }
            100% { opacity: 1; }
        }
        @keyframes zoomIn {
            0% { transform: scale(0.9); opacity: 0; }
            100% { transform: scale(1); opacity: 1; }
        }
    </style>
</head>
<body>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <h1 class="animate__animated animate__fadeIn">Soutenez Notre Cause</h1>
            <p class="lead animate__animated animate__fadeIn animate__delay-1s">Votre générosité peut faire une grande différence dans la vie de ceux qui en ont besoin.</p>
        </div>
    </section>

    <!-- Carousel Section -->
    <div id="donationCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="https://via.placeholder.com/800x400?text=Image+1" class="d-block w-100" alt="Image 1">
            </div>
            <div class="carousel-item">p
                <img src="https://via.placeholder.com/800x400?text=Image+2" class="d-block w-100" alt="Image 2">
            </div>
            <div class="carousel-item">
                <img src="https://via.placeholder.com/800x400?text=Image+3" class="d-block w-100" alt="Image 3">
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#donationCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#donationCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>

    <!-- Donation Section -->
    <section class="donation-buttons text-center py-5">
        <h3 class="animate__animated animate__fadeIn animate__delay-2s">Faites un Don Maintenant</h3>
        <button class="btn btn-primary btn-lg animate__animated animate__fadeIn animate__delay-3s" onclick="window.location.href='#'">Faire un Don</button>
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
