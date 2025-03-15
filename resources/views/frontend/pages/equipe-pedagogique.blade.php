@extends('frontend.layouts.master')
@section('meta_title', 'equipe pédagogique ' . ' || ' . $setting->app_name)

@section('contents')
    <!-- breadcrumb-area -->
    <x-frontend.breadcrumb :title="__('Equipe Pédagogique')" :links="[['url' => route('home'), 'text' => __('Accueil')], ['url' => route('cart'), 'text' => __('Equipe Pédagogique')]]" />
    <!-- breadcrumb-area-end -->


    <style>
        body {
            background-color: #f8f9fa;
        }

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

        .team-section {
            padding: 50px 0;
            background-color: #fff;
        }

        .team-section h2 {
            text-align: center;
            font-size: 2.5rem;
            margin-bottom: 30px;
            animation: fadeIn 3s ease-in-out;
        }

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
<body>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <h1 class="animate__animated animate__fadeIn">Notre Équipe Pédagogique</h1>
            <p class="lead animate__animated animate__fadeIn animate__delay-1s">Des enseignants passionnés et dédiés à .......</p>
        </div>
    </section>

    <!-- Team Section -->
    <section class="team-section">
        <div class="container">
            <h2 class="animate__animated animate__fadeIn animate__delay-2s">Nos Formateurs et Educateurs</h2>
            <p class="team-text animate__animated animate__fadeIn animate__delay-3s">
                L'équipe pédagogique de l'Association Claire de Lune est composée de professionnels engagés et expérimentés, prêts à guider chaque étudiant vers l'excellence .....
            </p>

            <!-- Team Members -->
            <div class="row">
                <div class="col-md-4 team-member">
                    <img src="uploads/custom-images/teacher1.jpg" >
                    <h5>Ahmed Ahmed</h5>
                    <p>enseignant de Aqida</p>
                </div>
                <div class="col-md-4 team-member">
                    <img src="uploads/custom-images/teacher2.jpg" >
                    <h5>Marwa Ahmed</h5>
                    <p>enseignante de Sona</p>
                </div>
                <div class="col-md-4 team-member">
                    <img src="uploads/custom-images/teacher3.jpg" >
                    <h5>Ahmed Ben Ali</h5>
                    <p>enseignate de récitation de coran </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action Section -->
    <section class="text-center py-5">
        <h3 class="animate__animated animate__fadeIn animate__delay-4s">Rejoignez notre équipe pédagogique</h3>
        <button class="btn btn-primary btn-lg animate__animated animate__fadeIn animate__delay-5s" onclick="window.location.href='#'">Postuler Maintenant</button>
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
