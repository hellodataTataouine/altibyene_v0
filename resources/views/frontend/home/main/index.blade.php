@extends('frontend.layouts.master')

@section('meta_title', $seo_setting['home_page']['seo_title'])
@section('meta_description', $seo_setting['home_page']['seo_description'])
@section('meta_keywords', '')

@section('contents')
    @if ($sectionSetting?->hero_section)
        <!-- banner-area -->
        @include('frontend.home.main.sections.banner-area')
        <!-- banner-area-end -->
    @endif

    <style>


        @import url('https://fonts.googleapis.com/css2?family=Cairo:wght@500;700&display=swap');

        .adhkar-container {
          position: fixed;
          bottom: 580px;
          left: 50%;
          transform: translateX(-50%);
          background: rgba(255, 255, 255, 0.85);
          backdrop-filter: blur(10px);
          border-radius: 25px;
          padding: 18px 30px;
          box-shadow: 0 0 25px  #ffffff;
          border: 2px solid  #F7C815;
          text-align: center;
          transition: all 0.5s ease;
          opacity: 0;
          transform: translate(-50%, 30px);
          min-width: 260px;
          max-width: 400px;
          z-index: 999;
          margin-left:-43%;

        }

        .adhkar-container.show {
          opacity: 1;
          transform: translate(-50%, 0);
        }

        .adhkar-text {
          font-size: 22px;
          color: #333;
          font-weight: 600;
          letter-spacing: 1px;
          text-shadow: 0 0 4px #000000;
        }

        .adhkar-decor {
          position: absolute;
          top: -10px;
          right: -10px;
          width: 20px;
          height: 20px;
          background:#F7C815;
          border-radius: 50%;
          animation: pulse 2s infinite;
        }

        @keyframes pulse {
          0% {
            transform: scale(0.9);
            opacity: 0.7;
          }
          50% {
            transform: scale(1.2);
            opacity: 1;
          }
          100% {
            transform: scale(0.9);
            opacity: 0.7;
          }
        }
      </style>
    </head>
    <body>

      <div class="adhkar-container" id="adhkarBox"  >
        <div class="adhkar-decor"></div>
        <div class="adhkar-text" id="adhkarText"></div>
      </div>

      <script>
        const adhkarList = [
          " ٱللَّهِ  ﷽",
          "سُبْحَانَ ٱللَّٰه",
          "ٱلْـحَمْدُ لِلَّٰه",
          "اللّٰهُ أَكْبَر",
          "لَا إِلَٰهَ إِلَّا ٱللَّٰه",
          "ٱسْتَغْفِرُ ٱللَّٰه",
          "اللَّهُمَّ صَلِّ عَلَىٰ مُحَمَّد",
          "سُبْحَانَ ٱللَّهِ وَبِحَمْدِهِ",
          "سُبْحَانَ ٱللَّهِ ٱلْعَظِيم"
        ];

        const box = document.getElementById('adhkarBox');
        const text = document.getElementById('adhkarText');
        let index = 0;

        function showAdhkar() {
          box.classList.remove('show');
          setTimeout(() => {
            text.innerText = adhkarList[index];
            box.classList.add('show');
            index = (index + 1) % adhkarList.length;
          }, 300);
        }

        showAdhkar();
        setInterval(showAdhkar, 5000); // toutes les 5 secondes
      </script>

    </body>

          <!--presentataion attebyan --->
          <section class="presentation  offset-md-2 mt-6" style="margin-top:5%; margin-left:12%;">

            <div class="container-fluid">
              <div class="row align-items-center">

                   <div class="col-md-6" data-aos="fade-left" data-aos-delay="300">
                  <div class="presentation_title text-left mb-3">
                    <h2 class=sub-title>
                       A propos Altibyan </h2>
             <p >
                    Altibyan est une approche pédagogique spécifique dédiée à l'enseignement des études islamiques.
                    Elle repose sur une méthode d'apprentissage progressive, intégrant à la fois la transmission du savoir religieux,
                    la réflexion critique et la pratique des principes de l'islam dans la vie quotidienne.
                </p>
                <p style="display: none;">
                    L'objectif principal de l'attebyane est de former des individus non seulement savants en matière de théologie et de jurisprudence islamique,
                    mais également capables de mettre en œuvre ces connaissances de manière éclairée et réfléchie.
                </p>


                  <img src="{{asset('frontend/img/blog/h4_blog_shape.svg')}}"alt="shape" class="rotateme"
                  style="position: absolute; ;left:1100px; top:-50px;">

<!-- Bouton  vers la page Altibyan -->
<a href="/altibyan" class="btn btn-primary mt-3">
    En savoir plus
    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14" viewBox="0 0 16 14" fill="none">
      <path d="M1 7L15 7M15 7L9 1M15 7L9 13" stroke="currentcolor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
    </svg>
  </a>
</div>
<style>
    #intro-text {
        display: -webkit-box;
        -webkit-line-clamp:3; /* Limite le texte à 3 lignes */
        -webkit-box-orient: vertical;
        overflow: hidden;

    }
</style>

              </div>
            </div>
          </section>

          <!-- Script JavaScript -->
          <script>


            AOS.init({
              duration: 1000,  // Durée de l'animation
              easing: 'ease-in-out', // Type d'animation
              once: true, // L'animation se déclenche une seule fois
            });
          </script>
<!-- Presentatio Attebyan-end  -->

    @if ($sectionSetting?->top_category_section)
        <!-- categories-area -->
        @include('frontend.home.main.sections.category-area')
        <!-- categories-area-end -->
    @endif

    @if ($sectionSetting?->brands_section)
        <!-- brand-area -->
        @include('frontend.home.main.sections.brand-area')
        <!-- brand-area-end -->
    @endif

    @if ($sectionSetting?->about_section)
        <!-- about-area -->
        @include('frontend.home.main.sections.about-area')
        <!-- about-area-end -->
    @endif
    @if ($sectionSetting?->featured_course_section)
        <!-- course-area -->
        @include('frontend.home.main.sections.course-area')
        <!-- course-area-end -->
    @endif

    @if ($sectionSetting?->news_letter_section)
        <!-- newsletter-area -->
        @include('frontend.home.main.sections.newsletter-area')
        <!-- newsletter-area-end -->
    @endif


    <!--  @if ($sectionSetting?->featured_instructor_section)
  instructor-area
        @include('frontend.home.main.sections.instructor-area')
    @endif instructor-area-end -->

     <!--@if ($sectionSetting?->counter_section)
        fact-area
        @include('frontend.home.main.sections.fact-area')
   fact-area-end -->
    @endif

    @if ($sectionSetting?->faq_section)
        <!-- faq-area -->
        @include('frontend.home.main.sections.faq-area')
        <!-- faq-area-end -->
    @endif
    <section class="cta__area home_3_cta" >
       <div class="cta__bg" data-background="uploads/custom-images/formation.jpg"
        style="background-image: url('uploads/custom-images/formation.jpg');">
   </div>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="cta__content">
                        <h2 class="title"> FORMATION À LA MÉTHODE TIBYANE</h2>
                        <p>Rejoignez notre formation et avancez à votre rythme, dans un cadre bienveillant et structuré..</p>
                        <a href="/formation"  class="btn arrow-btn"> Inscrivez-vous ! </a>
    <path d="M1 7L15 7M15 7L9 1M15 7L9 13" stroke="currentcolor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
    <path d="M1 7L15 7M15 7L9 1M15 7L9 13" stroke="currentcolor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
    </svg></a>
                    </div>
                </div>
            </div>
        </div>
    </section>
<style>
    .cta__bg::after {
    content: '';
    position: absolute;
    top: 0; left: 0;
    width: 100%; height: 100%;
    background-color: rgba(0, 0, 0, 0.4); /* atténue l’image en ajoutant un voile */
    z-index: 1;
}
</style>

    {{--@if ($sectionSetting?->our_features_section)
        <!-- features-area -->
        @include('frontend.home.main.sections.features-area')
        <!-- features-area-end -->
    @endif--}}

    {{--@if ($sectionSetting?->banner_section)
        <!-- instructor-area-two -->
        @include('frontend.home.main.sections.instructor-area-two')
        <!-- instructor-area-two-end -->
    @endif--}}

    @if ($sectionSetting?->latest_blog_section)
        <!-- blog-area -->
        @include('frontend.home.main.sections.blog-area')
        <!-- blog-area-end -->
    @endif
@endsection
