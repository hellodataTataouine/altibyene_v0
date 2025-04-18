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
          <!--presentataion attebyan --->
          <section class="presentation  offset-md-2 mt-6" style="margin-top:5%; margin-left:12%;">

            <div class="container-fluid">
              <div class="row align-items-center">

                <!-- Texte -->
                <div class="col-md-6" data-aos="fade-left" data-aos-delay="300">
                  <div class="presentation_title text-left mb-3">
                    <h2 class=sub-title>
                       A propos Altibyan </h2>
                  </div>


                  <img src="{{asset('frontend/img/blog/h4_blog_shape.svg')}}"alt="shape" class="rotateme"
                  style="position: absolute; ;left:1100px; top:-50px;">

                 <!-- Texte visible (limité à 3 lignes) -->
<p class="lead text-left" id="intro-text">
    Altibyan est une approche pédagogique spécifique dédiée à l'enseignement des études islamiques.
    Elle repose sur une méthode d'apprentissage progressive, intégrant à la fois la transmission du savoir religieux,
    la réflexion critique et la pratique des principes de l'islam dans la vie quotidienne.
</p>

<!-- Texte caché -->
<p class="lead text-left overflow-hidden" id="full-text" style="display: none;">
    L'objectif principal de l'attebyane est de former des individus non seulement savants en matière de théologie et de jurisprudence islamique,
    mais également capables de mettre en œuvre ces connaissances de manière éclairée et réfléchie.
</p>



<!-- CSS pour limiter le texte visible à 3 lignes -->
<style>
    #intro-text {
        display: -webkit-box;
        -webkit-line-clamp:3; /* Limite le texte à 3 lignes */
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis; /* Ajoute "..." si le texte dépasse */
    }
</style>

               <!-- button lire plus -->
               <button id="read-more-btn" class="btn btn-primary mt-3" onclick="toggleText()">Lire plus
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14" viewBox="0 0 16 14" fill="none" data-inject-url="http://127.0.0.1:8000/frontend/img/icons/right_arrow.svg" class="injectable">
                  <path d="M1 7L15 7M15 7L9 1M15 7L9 13" stroke="currentcolor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                  <path d="M1 7L15 7M15 7L9 1M15 7L9 13" stroke="currentcolor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                  </svg></button>

                </div>
              </div>
            </div>
          </section>

          <!-- Script JavaScript -->
          <script>
            function toggleText() {
              var fullText = document.getElementById("full-text");
              var button = document.getElementById("read-more-btn");

              // Si le texte est caché, on l'affiche et change le texte du bouton
              if (fullText.style.display === "none") {
                fullText.style.display = "block";
                button.textContent = "Lire moins";  // Change le texte du bouton
              } else {
                fullText.style.display = "none";
                button.textContent = "Lire plus";  // Change le texte du bouton
              }
            }

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
