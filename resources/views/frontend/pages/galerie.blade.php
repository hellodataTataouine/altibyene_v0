@extends('frontend.layouts.master')
@section('meta_title', 'Galerie' . ' || ' . $setting->app_name)

@section('contents')
    <!-- breadcrumb-area -->
    <x-frontend.breadcrumb :title="__('Galerie')" :links="[['url' => route('home'), 'text' => __('Accueil')], ['url' => route('cart'), 'text' => __('Galerie')]]" />
    <!-- breadcrumb-area-end -->



    <style>
        .gallery-img {
            width: 100%;
            height: 250px;
            object-fit: cover;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .gallery-item:hover .gallery-img {
            transform: scale(1.1);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.5);
        }

        .gallery-item {
            margin-bottom: 30px;
        }

        .page-header {
            background-color: #343a40;
            color: white;
            padding: 50px 0;
        }

        .page-header h1 {
            font-size: 3rem;
            text-transform: uppercase;
        }
    </style>



<style>
    body {
        background-color: #f8f9fa;
    }

    .page-header {
        background-color: #343a40;
        color: white;
        padding: 50px 0;
        text-align: center;
    }

    .page-header h1 {
        font-size: 3rem;
    }

    .gallery-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        grid-auto-rows: 200px;
        gap: 15px;
        padding: 30px;
    }

    .gallery-item {
        position: relative;
        overflow: hidden;
    }

    .gallery-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease, filter 0.3s ease;
    }

    .gallery-item:hover img {
        transform: scale(1.1);
        filter: brightness(80%);
    }

    .gallery-item.large {
        grid-column: span 2;
        grid-row: span 2;
    }

    .gallery-caption {
        position: absolute;
        bottom: 10px;
        left: 10px;
        background-color: rgba(0, 0, 0, 0.7);
        color: white;
        padding: 5px 10px;
        font-size: 1rem;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .gallery-item:hover .gallery-caption {
        opacity: 1;
    }
</style>

<!-- Gallery Section -->
<div class="container"   style="margin-top:10%">
    <div class="gallery-container">
        <!-- Large Image -->
        <div class="gallery-item large animate__animated animate__zoomIn">
            <img src="https://via.placeholder.com/600x600" alt="Événement 1">
            <div class="gallery-caption">Événement 1</div>
        </div>

        <!-- Regular Image -->
        <div class="gallery-item animate__animated animate__zoomIn" style="animation-delay: 0.2s;">
            <img src="https://via.placeholder.com/400x300" alt="Événement 2">
            <div class="gallery-caption">Événement 2</div>
        </div>

        <!-- Regular Image -->
        <div class="gallery-item animate__animated animate__zoomIn" style="animation-delay: 0.4s;">
            <img src="https://via.placeholder.com/400x300" alt="Événement 3">
            <div class="gallery-caption">Événement 3</div>
        </div>

        <!-- Regular Image -->
        <div class="gallery-item animate__animated animate__zoomIn" style="animation-delay: 0.6s;">
            <img src="https://via.placeholder.com/400x300" alt="Événement 4">
            <div class="gallery-caption">Événement 4</div>
        </div>

        <!-- Regular Image -->
        <div class="gallery-item animate__animated animate__zoomIn" style="animation-delay: 0.8s;">
            <img src="https://via.placeholder.com/400x300" alt="Événement 5">
            <div class="gallery-caption">Événement 5</div>
        </div>

        <!-- Large Image -->
        <div class="gallery-item large animate__animated animate__zoomIn" style="animation-delay: 1s;">
            <img src="https://via.placeholder.com/600x600" alt="Événement 6">
            <div class="gallery-caption">Événement 6</div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>


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
