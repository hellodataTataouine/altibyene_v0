<section class="newsletter__area">
  <div class="container">
      <div class="row align-items-center">
          <div class="col-lg-4">
              <div class="newsletter__img-wrap"  data-aos="fade-up">
                  <img src="{{ asset($newsletterSection?->global_content?->image) }}" alt="img">
                  <img src="{{ asset('frontend/img/others/newsletter_shape01.png') }}" alt="img" data-aos="fade-up"
                      data-aos-delay="400">
                  <img src="{{ asset('frontend/img/others/newsletter_shape02.png') }}" alt="img" class="alltuchtopdown">
              </div>
          </div>
          <div class="col-lg-8">
              <div class="newsletter__content"  data-aos="fade-up">
                  <h1 class="title"><b>{{ __('Rejoignez notre programme de mémorisation du Coran sur 6 ans conçu pour un apprentissage ') }}</b> <br> <b>{{ __('progressif et structuré') }}?</b></h1>
                  <div class="newsletter__form">
                      {{-- <form action="{{route('newsletter-request')}}" method="post" class="newsletter">
                        @csrf
                         <input type="email" placeholder="{{ __('Tapez votre email') }} " name="email"  > </form>--}}
                          <a href="\memorisation">
                            <button type="submit" class="btn">{{ __('Abonnez-vous !') }}</button>
                          </a>


                  </div>
              </div>
          </div>
      </div>
  </div>
<div class="newsletter__shape">
      <img src="{{ asset('frontend/img/banner/avion.webp') }}" alt="img" data-aos="fade-right"
          data-aos-delay="400"  style="margin-top:50px;right:20px" >
  </div>


</section>
