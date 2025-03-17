<section class="features__area">
  <div class="container">
      <div class="row justify-content-center"  data-aos="fade-up" >
          <div class="col-xl-6"  data-aos="fade-up" data-aos-delay="100">
              <div class="section__title white-title text-center mb-50"    >
                  <span class="sub-title">{{ __('Ce n\'est que le début.') }}</span>
                  <h2 class="title">{{ __('Les médailles du comportement ') }}</h2>
                  <p>{{ __('Favoriser l’épanouissement moral et spirituel des élèves en valorisant les comportements exemplaires selon les principes du Coran et de la Sunnah') }}</p>
              </div>
          </div>
      </div>
      <div class="row justify-content-center" >
        <div class="col-xl-3 col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
            <div class="features__item">
                <div class="features__icon">
                    <img src="{{ asset($ourFeatures?->global_content?->image_one) }}" alt="img">
                </div>
                <div class="features__content">
                    <p class="title lh-base">{{ $ourFeatures?->content?->title_one }}</p>
                    <p>{{ $ourFeatures?->content?->sub_title_one }}</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-4 col-md-6"  data-aos="fade-up" data-aos-delay="200">
            <div class="features__item">
                <div class="features__icon">
                    <img src="{{ asset($ourFeatures?->global_content?->image_two) }}" alt="img">
                </div>
                <div class="features__content">
                    <p class="title lh-base">{{ $ourFeatures?->content?->title_two }}</p>
                    <p>{{ $ourFeatures?->content?->sub_title_two }}</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-4 col-md-6"  data-aos="fade-up" data-aos-delay="300">
            <div class="features__item">
                <div class="features__icon">
                    <img src="{{ asset($ourFeatures?->global_content?->image_three) }}" alt="img">
                </div>
                <div class="features__content">
                    <p class="title lh-base">{{ $ourFeatures?->content?->title_three }}</p>
                    <p>{{ $ourFeatures?->content?->sub_title_three }}</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-4 col-md-6"  data-aos="fade-up" data-aos-delay="400">
            <div class="features__item">
                <div class="features__icon">
                    <img src="{{ asset($ourFeatures?->global_content?->image_four) }}" alt="img">
                </div>
                <div class="features__content">
                    <p class="title lh-base">{{ $ourFeatures?->content?->title_four }}</p>
                    <p>{{ $ourFeatures?->content?->sub_title_four }}</p>
                </div>
            </div>
        </div>
    </div>

  </div>
</section>
