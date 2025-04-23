
@extends('frontend.layouts.master')

@section('meta_title', $seo_setting['about_page']['seo_title'])
@section('meta_description', $seo_setting['about_page']['seo_description'])

@section('contents')
    <!-- breadcrumb-area -->
    <x-frontend.breadcrumb :title="__(' Mémorisation du coran en 6 ans')" :links="[['url' => route('home'), 'text' => __('Accueil')], ['url' => '', 'text' => __(' Mémorisation du coran en 6 ans')]]" />
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

    .section {
      padding: 2rem;
    }

    .section h2 {
      color: #000000;
    }

    .card {
      border-radius: 20px;
      background-color: #ffffff;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
      margin-bottom: 1rem;
      transition: transform 0.3s;
    }

    .card:hover {
      transform: translateY(-5px);
    }

    .footer {
      text-align: center;
      padding: 20px;
    }

    .fade-in {
      opacity: 0;
      transform: translateY(20px);
      animation: fadeInUp 1s ease forwards;
    }

    .fade-in-delay {
      animation-delay: 0.5s;
    }

    @keyframes fadeInUp {
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .btn-animated {
      transition: all 0.3s ease;
      font-size: 1.2rem;
      border-radius: 30px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.2);
    }

    .btn-animated:hover {
      background-color: #198754 !important;
      transform: translateY(-3px);
      box-shadow: 0 6px 15px rgba(0,0,0,0.25);
    }
  </style>
</head>
<body>

  <div class="header fade-in" >
    <h1>
        <img src="{{ asset('uploads/custom-images/coran (2).png') }}" alt="Quran Emoji"
         style="height: 40px; vertical-align: middle; margin-right: 10px;">
        MÉMORISATION DU CORAN EN 6 ANS
      </h1>
   {{-- <h1> 📖MÉMORISATION DU CORAN EN 6 ANS</h1>--}}
    <p class="lead">PROGRAMME EN PRÉSENTIEL ET EN LIGNE</p>

  </div>

  <div class="container section fade-in fade-in-delay">
    <h2>Présentation du Programme</h2>
    <div class="card p-4">
      <p>Découvrez notre programme unique pour mémoriser le Coran en entier en 6 ans. Ce cursus repose sur une méthode structurée, intégrant une compréhension globale du texte sacré, une révision approfondie des règles de lecture issues du programme Tybiane, ainsi que l'étude des vers de <strong>Touhfatoul Atfal</strong>.</p>
      <p>À l'issue du programme de Tybiane, l’étudiant passe à l’étape de mémorisation.</p>
    </div>

    <h2>Le Déroulé du Programme</h2>
    <div class="card p-4">
      <ul>
        <li><strong>Jouz Amma</strong> + certains vers de <strong>Touhfatoul Atfal</strong></li>
        <li>Programme de 6 ans avec une méthode spécifique de préparation, mémorisation et révision avec <strong>Ijaza</strong> (attestation/autorisation d’enseigner ce qui a été appris)</li>
        <li>1/4 de hizb (2,5 pages) par semaine avec explication + apprentissage des synonymes (Gharib Quran)</li>
        <p> <strong>* Durant ces 6 ans l’élève devra apprendre :</strong></p>
        <li>1 hizb par mois (10 pages)</li>
        <li>10 hizb par an</li>
        <strong> * Possibilité de faire une page par semaine avec le même programme</strong>
      </ul>
    </div>

    <h2>Déroulé des Cours</h2>
    <div class="card p-4">
      <h5>Pour les Hommes :</h5>
      <ul>
        <li><strong>🕗 Samedi de 8h à 10h</strong><br>8h-9h30 : Apprentissage du Coran<br>9h30-10h : Révision avec le livre <strong>Al-Jazariyah</strong></li>
        <li><strong>🕗 Dimanche de 8h à 9h :</strong> Explication et interprétation des Roubo à apprendre</li>
      </ul>
      <h5>Pour les Femmes :</h5>
      <ul>
        <li><strong>🕗 Dimanche de 10h à 11h</strong> : Mémorisation du Coran</li>
      </ul>
    </div>
  </div>


  <div class="text-center mt-5  fade-in fade-in-delay">
    <p class="mb-3 fs-5 fw-semibold">Rejoignez-nous pour une aventure spirituelle enrichissante et structurée.</p>
    <button class="btn btn-primary btn-animated px-4 py-2">Rejoindre le programme</button>

</div>
</div>



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
