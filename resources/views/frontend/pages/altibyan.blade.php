@extends('frontend.layouts.master')
@section('meta_title', 'altibyan' . ' || ' . $setting->app_name)

@section('contents')
    <!-- breadcrumb-area -->
    <x-frontend.breadcrumb :title="__('Altibyan')" :links="[['url' => route('home'), 'text' => __('Accueil')], ['url' => route('cart'), 'text' => __('Altibyan')]]" />
    <!-- breadcrumb-area-end -->
    @include('frontend.pages.adhkar')
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


      <!-- Header -->
      <div class="header" data-aos="fade-right">
        <h1>✨ Altibyan</h1>
      </div>

      <!-- Présentation -->
      <div class="container section" style="margin-top: -170px">
        <h2 class="mb-5 text-center" data-aos="fade-right" style="margin-top: -20px">Qui sommes-nous ?</h2>

             {{-- <p class="text-center" data-aos="fade-right">

                 Altibyan est une approche pédagogique spécifique dédiée à l'enseignement des études islamiques.
                Elle repose sur une méthode d'apprentissage progressive, intégrant à la fois la transmission du savoir religieux,
                la réflexion critique et la pratique des principes de l'islam dans la vie quotidienne.</p>


                <p class="text-center" data-aos="fade-right">
                L'objectif principal de l'attebyane est de former des individus non seulement savants en matière de théologie et de jurisprudence islamique,
                mais également capables de mettre en œuvre ces connaissances de manière éclairée et réfléchie.</p>



                <h2 class="mb-4 text-center"  style="margin-top: 20px" data-aos="fade-right">Pourquoi choisir la méthode Tibyane ?</h2>
                <h3  style="margin-top: 20px"  data-aos="fade-right">✅ Des résultats concrets pour vos élèves :</h2>
               <p data-aos="fade-right"> Lecture fluide et précise du Saint Coran.
                Mémorisation des textes fondamentaux (Tuhfat al-Atfal).
                Maîtrise des règles de récitation du tajwid.
                Compréhension approfondie de la langue arabe.
                Application pratique des enseignements (invocations du matin et
                du soir, comportement exemplaire)</p>
                <h3 style="margin-top: 20px" data-aos="fade-right">✅ Pour vous, un accompagnement sur-mesure :</h2>
               <p data-aos="fade-right"> Soutien pédagogique personnalisé
                Outils innovants pour simplifer et enrichir l’enseignement.
                Formations claires et adaptées.
                Gestion efficace des différences de niveau entre les élèves
                Gain de temps grâce à une méthode rapide
                Accès à un réseau dynamique de l’Association Clair de Lune
                (enseignants, formateurs)</p>
 --}}

 <p class="text-center" data-aos="fade-right">
    Altibyan est une approche pédagogique spécifique dédiée à l'enseignement de disciplines culturelles et linguistiques.
    Elle repose sur une méthode d'apprentissage progressive, intégrant à la fois la transmission de savoirs,
    la réflexion critique et l'application de valeurs éthiques dans le quotidien.
</p>

<p class="text-center" data-aos="fade-right">
    L'objectif principal d'Altibyan est de former des individus dotés de solides connaissances théoriques,
    tout en développant leur capacité à les mettre en œuvre de manière réfléchie, responsable et cohérente.
</p>

<h2 class="mb-4 text-center" style="margin-top: 20px" data-aos="fade-right">Pourquoi choisir la méthode Tibyane ?</h2>

<h3 style="margin-top: 20px" data-aos="fade-right">✅ Des résultats concrets pour vos élèves :</h3>
<p data-aos="fade-right">
    Lecture fluide et précise de textes en arabe.<br>
    Mémorisation de repères fondamentaux adaptés au niveau des apprenants.<br>
    Maîtrise des règles de prononciation et d’articulation spécifiques.<br>
    Compréhension approfondie de la langue arabe.<br>
    Mise en pratique des notions étudiées au quotidien (exercices, expressions utiles, comportements bienveillants).
</p>

<h3 style="margin-top: 20px" data-aos="fade-right">✅ Pour vous, un accompagnement sur-mesure :</h3>
<p data-aos="fade-right">
    Soutien pédagogique personnalisé.<br>
    Outils innovants pour simplifier et enrichir l’enseignement.<br>
    Formations claires et adaptées à votre progression.<br>
    Gestion efficace des différences de niveau entre les élèves.<br>
    Gain de temps grâce à une méthode structurée et progressive.<br>
    Accès à un réseau dynamique de l’Association Clair de Lune (enseignants, formateurs, coordinateurs).
</p>






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
