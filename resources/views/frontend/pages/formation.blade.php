@extends('frontend.layouts.master')
@section('meta_title', 'formation ' . ' || ' . $setting->app_name)

@section('contents')
    <!-- breadcrumb-area -->
    <x-frontend.breadcrumb :title="__('Formation')" :links="[['url' => route('home'), 'text' => __('Accueil')], ['url' => route('cart'), 'text' => __('Formation')]]" />
    <!-- breadcrumb-area-end -->
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f9f9f9;
        }

        .section-title {
            text-align: center;
            margin-bottom: 2rem;
            color:#000000;
        }

        .card {
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            border-radius: 15px;
            transition: transform 0.3s;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .section-icon {
            max-width: 100px;
        }

        .timeline {
            border-left: 4px solid #F7C815;
            padding-left: 20px;
        }

        .timeline h5 {
            color: #000000;
        }

        .img-fluid {
            border-radius: 10px;
        }
    </style>
</head>

<body>
    <div class="container py-5">
        <h1 class="section-title">FORMATION À LA MÉTHODE TIBYANE</h1>

        <div class="row align-items-center mb-5">
            <div class="col-md-6">
                <img src="https://via.placeholder.com/500x300" class="img-fluid" alt="Méthode Tibyane">
            </div>
            <div class="col-md-6">
                <h4>PROGRAMME DE 40 HEURES (EN PRÉSENTIEL OU EN LIGNE)</h4>
                <p>La méthode Tibyane vise à perfectionner la lecture et l’écriture du Coran, tout en introduisant des bases religieuses.
                    La formation est adaptée à tous niveaux, et se termine par un examen et une certification.</p>
                <ul>
                    <li>Certification conjointe Paris & Médine</li>
                    <li>Accompagnement personnalisé post-formation</li>
                    <li>Livres & ressources pédagogiques fournis</li>
                </ul>
            </div>
        </div>

        <div class="timeline">
            <h3 class="mb-4">DÉROULÉ DE LA FORMATION</h3>
            <h5>1. Teste de niveau pour la formation</h5>
            <h5>2. Adabe & comportements avant la formation</h5>
            <h5>3. Retour sur les notions de base</h5>
            <h5>4. Objectifs pédagogiques</h5>
            <h5>5. Premier & Deuxième Tomes</h5>
            <h5>6. Enseignant idéal & compétences</h5>
            <h5>7. Explication des livres</h5>
            <h5>8. Rencontre avec le Cheikh</h5>
            <h5>9. Examen final</h5>
            <h5>10. Distribution des livres & affiches</h5>
            <h5>11. Explication de l'application mobile</h5>
            <h5>12. Remise des attestations</h5>
        </div>

        <div class="row mt-5">
            <div class="col-md-6">
                <div class="card p-4">
                    <h5 class=>Formation Présentielle</h5>
                    <p>Du Lundi au Vendredi<br>De 8h à 17h</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card p-4">
                    <h5 class="text-success">Formation en ligne</h5>
                    <p>6h par week-end<br>Durée : 6 semaines</p>
                </div>
            </div>
        </div>

        <div class="text-center mt-5">
            <button class="btn btn-primary px-4 py-2">S'inscrire à la formation</button>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

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
