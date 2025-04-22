@extends('frontend.layouts.master')
@section('meta_title', 'Galerie' . ' || ' . $setting->app_name)

@section('contents')
    <!-- breadcrumb-area -->
    <x-frontend.breadcrumb :title="__('Galerie')" :links="[['url' => route('home'), 'text' => __('Accueil')], ['url' => route('cart'), 'text' => __('Galerie')]]" />
    <!-- breadcrumb-area-end -->

    <style>
        body {
            background-color: #f8f9fa;
        }

        .page-header {

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
    <div class="container" style="margin-top:5%">
        <div class="page-header">
            <h1>Les Couleurs de notre Association</h1>
        </div>

        <div class="gallery-container" >
            <!-- Large Image -->
            <div class="gallery-item large animate__animated animate__zoomIn" data-aos="flip-left">
                <img src="https://app.assoconnect.com/services/storage?id=5163187&type=picture&secret=WDd14EPnUzQ3XH5BNDgaRgp8MNpomQA4ACMvxDUR&timestamp=1734723541&size=original" alt="Événement 1">
              {{--  <div class="gallery-caption">Événement 1</div>--}}
            </div>

            <!-- Regular Image -->
            <div class="gallery-item animate__animated animate__zoomIn" style="animation-delay: 0.2s;" data-aos="flip-left">
                <img src="https://app.assoconnect.com/services/storage?id=5163184&type=picture&secret=DP8LZaHAoVoHvDTBXBvz2sVN0slSIq6aTvFgCZ5n&timestamp=1734723494&size=original" alt="Événement 2">
                {{--<div class="gallery-caption">Événement 2</div>--}}
            </div>

            <!-- Regular Image -->
            <div class="gallery-item animate__animated animate__zoomIn" style="animation-delay: 0.4s;" data-aos="flip-left">
                <img src="https://app.assoconnect.com/services/storage?id=5163185&type=picture&secret=Jx28BkHlmeTbGQJPEnauppxG0yYREleLkvC51zkl&timestamp=1734723501&size=original" alt="Événement 3">
               {{-- <div class="gallery-caption">Événement 3</div>--}}
            </div>

            <!-- Regular Image -->
            <div class="gallery-item animate__animated animate__zoomIn" style="animation-delay: 0.6s;"   data-aos="flip-left">
                <img src="https://app.assoconnect.com/services/storage?id=5163205&type=picture&secret=EUeXRLE55hzd9WeDhPWkGRVswkcwHCGOOS3feEVL&timestamp=1734724109&size=original" alt="Événement 4">
             {{--   <div class="gallery-caption">Événement 4</div>--}}
            </div>

            <!-- Regular Image -->
            <div class="gallery-item animate__animated animate__zoomIn" style="animation-delay: 0.8s;"   data-aos="flip-left">
                <img src="uploads/custom-images/ga6.jpg" alt="Événement 5">
               {{-- <div class="gallery-caption">Événement 5</div>--}}
            </div>

            <!-- Large Image -->
            <div class="gallery-item large animate__animated animate__zoomIn" style="animation-delay: 1s;"  data-aos="flip-left">
                <img src="uploads/custom-images/claire.jpg" alt="Événement 6">
                {{-- <div class="gallery-caption">Événement 6</div>--}}
            </div>
        </div>



        <button id="voir plus-btn" class="btn btn-primary mt-6" onclick="toggleText()" style="margin-bottom:10% " data-aos="fade-up">Voir plus
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14" viewBox="0 0 16 14" fill="none" data-inject-url="http://127.0.0.1:8000/frontend/img/icons/right_arrow.svg" class="injectable">
              <path d="M1 7L15 7M15 7L9 1M15 7L9 13" stroke="currentcolor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
              <path d="M1 7L15 7M15 7L9 1M15 7L9 13" stroke="currentcolor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
              </svg></button>

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
