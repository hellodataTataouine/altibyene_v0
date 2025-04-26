@extends('frontend.layouts.master')
@section('meta_title', 'Formation à la méthode Altibyan ' . ' || ' . $setting->app_name)

@section('contents')
    <!-- breadcrumb-area -->
    <x-frontend.breadcrumb :title="__('Formation à la méthode Altibyan')" :links="[['url' => route('home'), 'text' => __('Accueil')], ['url' => route('cart'), 'text' => __('Formation à la méthode Altibyan')]]" />
    <!-- breadcrumb-area-end -->
    @include('frontend.pages.adhkar')
    <style>

.header {
      padding: 2rem;
      text-align: center;
    }

    .header h1 {
      font-size: 2.5rem;
      animation: bounce 1.5s infinite;
      color: #000000;
    }

    @keyframes bounce {
      0%, 100% { transform: translateY(0); }
      50% { transform: translateY(-8px); }
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

-   .timeline {
            border-left: 4px solid #F7C815;
            padding-left: 20px;
        }

    .timeline h5 {
            color: #000000;
        }

   .img-fluid {
            border-radius: 10px;
        }

    .timeline-container {
    padding: 2rem;
    background: #f9f9f9;
    border-radius: 10px;
    max-width: 800px;
    margin: auto;
}

.timeline-title {
    text-align: center;
    font-size: 2rem;
    margin-bottom: 2rem;
    color: #333;
}

.timeline {
    list-style: none;
    padding: 0;
    position: relative;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 30px;
    top: 0;
    bottom: 0;
    width: 4px;
    background: #3498db;
}

.timeline li {
    position: relative;
    margin-bottom: 2rem;
    padding-left: 70px;
}

.timeline .step {
    width: 40px;
    height: 40px;
    background: #3498db;
    color: white;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    position: absolute;
    left: 10px;
    top: 0;
}

.timeline .content h5 {
    font-size: 1.2rem;
    margin: 0;
    color: #2c3e50;
}

.timeline .content p {
    margin: 0.5rem 0 0 0;
    color: #7f8c8d;
}

    </style>
</head>

<body>
    <div class="container py-5">

        <h1 class="section-title" style="font-size: 2.5rem;
      animation: bounce 2.5s infinite;
      color: #000000;">FORMATION À LA MÉTHODE TIBYANE</h1>

        <div class="row align-items-center mb-5">
            <div class="col-md-6">
                <img src="{{ asset('uploads/custom-images/formation.jpg') }}" class="img-fluid" alt="Méthode Tibyane">

            </div>
            <div class="col-md-6">
                <h4>PROGRAMME DE 40 HEURES (EN PRÉSENTIEL OU EN LIGNE)</h4>
                <p>La méthode Tibyane vise à perfectionner la lecture et l’écriture du Coran, tout en introduisant les bases de l’éducation religieuse.
                    L’association Clair de Lune propose une formation destinée en premier lieu à des adultes sachant lire et écrire en arabe,
                    souhaitant mettre en place cette méthode dans leur foyer,école ou institut.
                    La formation s’adapte à votre niveau et se termine par un examen ainsi qu’une certification.</p>
                    <p class="fw-bold text-primary">💰 Tarif : 670 € TTC</p>
                <ul>

                    <li>Certification conjointe Paris & Médine</li>
                    <li>Accompagnement personnalisé post-formation</li>
                    <li>Livres & ressources pédagogiques fournis</li>
                </ul>
            </div>
        </div>

        <div class="timeline-container"  style="margin-left:-10px" >
            <h3 class="timeline-title">DÉROULÉ DE LA FORMATION</h3>
            <ul class="timeline">
                <li>
                    <span class="step">1</span>
                    <div class="content">
                        <h5>Évaluation initiale</h5>
                        <p>Évaluer les connaissances préalables des participants sur les lettres et les versets.</p>
                    </div>
                </li>
                <li>
                    <span class="step">2</span>
                    <div class="content">
                        <h5>Éthique et concepts fondamentaux</h5>
                        <p>Introduction aux bonnes pratiques pédagogiques et révision des notions de base.</p>
                    </div>
                </li>
                <li>
                    <span class="step">3</span>
                    <div class="content">
                        <h5>Objectifs pédagogiques</h5>
                        <p>Identifier les attentes et les objectifs éducatifs à atteindre.</p>
                    </div>
                </li>
                <li>
                    <span class="step">4</span>
                    <div class="content">
                        <h5>Compétences clés</h5>
                        <p>Développer des compétences professionnelles en enseignement et mémorisation.</p>
                    </div>
                </li>
                <li>
                    <span class="step">5</span>
                    <div class="content">
                        <h5>Analyse des supports pédagogiques</h5>
                        <p>Présentation et explication succincte des ouvrages et du programme.</p>
                    </div>
                </li>
                <li>
                    <span class="step">6</span>
                    <div class="content">
                        <h5>Rencontre avec le fondateur</h5>
                        <p>Échange avec le cheikh concepteur de la méthode pour des conseils personnalisés.</p>
                    </div>
                </li>
                <li>
                    <span class="step">7</span>
                    <div class="content">
                        <h5>Examen final</h5>
                        <p>Évaluer les compétences des participants et valider leurs acquis.</p>
                    </div>
                </li>
                <li>
                    <span class="step">8</span>
                    <div class="content">
                        <h5>Distribution des supports pédagogiques</h5>
                        <p>Ouvrages, outils éducatifs et supports interactifs nécessaires.</p>
                    </div>
                </li>
                <li>
                    <span class="step">9</span>
                    <div class="content">
                        <h5>Formation sur l’application Tibyane</h5>
                        <p>Explication détaillée et démonstration de l’utilisation de l’application éducative.</p>
                    </div>
                </li>
                <li>
                    <span class="step">10</span>
                    <div class="content">
                        <h5>Délivrance des certificats</h5>
                        <p>Remise d’attestations de réussite pour les participants ayant terminé le programme avec succès.</p>
                    </div>
                </li>
            </ul>
        </div>

        <div class="row mt-5">
            <h5 class="mb-4"> ✦ Les créneaux des séances seront choisis, selon vos disponibilités et nos différents créneaux, au moment de l’inscription.</h5>


            <div class="col-md-6">
                <div class="card p-4">
                    <h5 class>Formation en ligne</h5>
                    <p>6h par week-end<br>Durée : 6 semaines<br>( sur une période de 6 semaines )</p>
                </div>
            </div>



        <div class="col-md-6">
            <div class="card p-4">
                <h5 class="text-success">Formation présentielle </h5>
                <p>Du Lundi au Vendredi<br>De 8h à 17h<br>( sur une période )</p>
            </div>
        </div>
    </div>


        <div class="text-center mt-5">
            <button class="btn btn-primary px-4 py-2">S'inscrire à la formation</button>
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
