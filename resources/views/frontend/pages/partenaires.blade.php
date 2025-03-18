@extends('frontend.layouts.master')
@section('meta_title', 'partenaires' . ' || ' . $setting->app_name)

@section('contents')
    <!-- breadcrumb-area -->
    <x-frontend.breadcrumb :title="__('Partenaires')" :links="[['url' => route('home'), 'text' => __('Accueil')], ['url' => route('cart'), 'text' => __('Partenaires')]]" />
    <!-- breadcrumb-area-end -->

<style>

    .partner-card {
        transition: transform 0.3s ease-in-out;
        overflow: hidden;
        border-radius: 15px;
    }
    .partner-card:hover {
        transform: scale(1.1);
    }
    .partner-card img {
        transition: transform 0.3s ease-in-out;
    }
    .partner-card:hover img {
        transform: rotate(5deg) scale(1.05);
    }
</style>
</head>
<body>

<div class="container my-5" >
    <h1 class="text-center mb-4" >Nos Partenaires</h1>

    <div class="row">
        @foreach ($partenaires as $index => $partenaires)
            <div class="col-md-3 col-sm-6 mb-4" data-aos="fade-right">
                <div class="card shadow-lg text-center partner-card" data-aos="zoom-in" data-aos-delay="{{ $index * 100 }}">
                    <img src="{{ asset('images/' . $partenaires['logo']) }}" class="card-img-top p-3" alt="{{ $partenaires['name'] }}">
                    <div class="card-body">
                        <h5 class="card-title">{{ $partenaires['name'] }}</h5>
                        <a href="{{ $partenaires['website'] }}" class="btn btn-outline-primary" target="_blank">Visiter</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
<script>
    AOS.init({
        duration: 1000,
        easing: "ease-in-out",
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
