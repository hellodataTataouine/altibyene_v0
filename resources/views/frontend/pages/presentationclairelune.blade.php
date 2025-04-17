@extends('frontend.layouts.master')
@section('meta_title', 'presentation claire de lune ' . ' || ' . $setting->app_name)

@section('contents')
    <!-- breadcrumb-area -->
    <x-frontend.breadcrumb :title="__('Presentation Claire de Lune')" :links="[['url' => route('home'), 'text' => __('Accueil')], ['url' => route('cart'), 'text' => __('Presentation Claire de Lune')]]" />
    <!-- breadcrumb-area-end -->
    <style>

        .header {

          height: 400px;
          display: flex;
          align-items: center;
          justify-content: center;
          color: white;
          text-shadow: 2px 2px 4px #000;
          position: relative;
        }

        .header::after {
          content: "";
          position: absolute;
          top: 0; left: 0;
          width: 100%; height: 100%;

        }

        .header h1 {
          z-index: 1;
          font-size: 3rem;
          animation: fadeInDown 2s;
        }

        @keyframes fadeInDown {
          0% { opacity: 0; transform: translateY(-30px); }
          100% { opacity: 1; transform: translateY(0); }
        }

        .section {
          padding: 4rem 2rem;
        }


        .quote {
          font-style: italic;
          color: #198754;
          text-align: center;
          margin-top: 3rem;
        }
      </style>
    </head>
    <body>

      <!-- Header -->
      <div class="header">
        <h1>✨ Association Clair de Lune</h1>
      </div>

      <!-- Présentation -->
      <div class="container section">
        <h2 class="mb-4 text-center">Qui sommes-nous ?</h2>
        <p class="lead text-center">
          Clair de lune vous accompagne dans l'apprentissage des fondements de votre religion. En effet, la pratique correcte de la religion trouve son essence dans la bonne méthode d'apprentissage.
        </p>
        <p class="text-center">
          Ainsi, un musulman bien formé trouve de l'aisance dans sa pratique religieuse car il comprend mieux ce que <strong>SON SEIGNEUR</strong> attend de lui à travers l'explication correcte des textes et de leurs applications dans le bon contexte tout en respectant les valeurs du pays dans lequel il vit.
        </p>
        <p class="text-center">
          Il devient donc un modèle parfait car il réussit à concilier sa pratique religieuse et les relations humaines. Cette conciliation le conduit sans doute à une réussite dans ce monde et dans l'au-delà.
        </p>
      </div>

      <!-- Nos Valeurs -->
<div class="container section objectifs">
    <h2 class="mb-4 text-center">Nos Valeurs</h2>
    <p class="text-center text-muted mb-5">Le Prophète ﷺ a dit : Le meilleur d’entre vous est celui qui apprend le Coran et l’enseigne.</p>
    <div class="row g-4">
      <div class="col-md-4">
        <div class="card p-4 text-center border-0 shadow-sm" style="background-color: white;">
          <img src="{{ asset('uploads/custom-images/coran (1).png') }}" class="w-25 mb-3" alt="Coran" />
          <h5 class="mb-2">La récitation correcte du Saint Coran</h5>
          <p>Permettre à chacun d’apprendre à réciter le Coran selon les règles du Tajwid.</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card p-4 text-center border-0 shadow-sm" style="background-color:white;">
          <img src="{{ asset('uploads/custom-images/education.png') }}" class="w-25 mb-3" alt="Éducation" />
          <h5 class="mb-2">La purification et l'éducation</h5>
          <p>Éduquer les cœurs, purifier les âmes et favoriser un bon comportement au quotidien.</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card p-4 text-center border-0 shadow-sm" style="background-color: white;">
          <img src="{{ asset('uploads/custom-images/apprentissage.png') }}" class="w-25 mb-3" alt="Apprentissage" />
          <h5 class="mb-2">L'apprentissage inclusif</h5>
          <p>Offrir un cadre d’apprentissage ouvert à tous : hommes, femmes, jeunes et moins jeunes.</p>
        </div>
      </div>
    </div>
  </div>

      <!-- Citation -->
      <div class="quote container">
        <p>"Une bonne compréhension mène à une bonne application. Et la bonne application, c'est la lumière sur le chemin."</p>
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
