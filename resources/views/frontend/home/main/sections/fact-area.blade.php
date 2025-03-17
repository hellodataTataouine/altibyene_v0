<section class="fact__area" >
  <div class="container">
      <div class="fact__inner-wrap">
          <div class="row">
              <div class="col-lg-3 col-sm-6" data-aos="fade-up" data-aos-delay="100">
                  <div class="fact__item">
                      <h2 class="count"><span class="odometer" data-count="{{ $counter?->global_content?->total_student_count }}"></span>+</h2>
                      <p>{{ __('Étudiants actifs') }}</p>
                  </div>
              </div>
              <div class="col-lg-3 col-sm-6"  data-aos="fade-up" data-aos-delay="200">
                  <div class="fact__item">
                      <h2 class="count"><span class="odometer" data-count="{{ $counter?->global_content?->total_courses_count }}"></span>+</h2>
                      <p>{{ __('Sessions d enseignement') }}</p>
                  </div>
              </div>
              <div class="col-lg-3 col-sm-6"  data-aos="fade-up" data-aos-delay="300">
                  <div class="fact__item">
                      <h2 class="count"><span class="odometer" data-count="{{ $counter?->global_content?->total_instructor_count }}"></span>+</h2>
                      <p>{{ __('Meilleurs instructeurs') }}</p>
                  </div>
              </div>
              <div class="col-lg-3 col-sm-6"  data-aos="fade-up" data-aos-delay="400">
                  <div class="fact__item">
                      <h2 class="count"><span class="odometer" data-count="{{ $counter?->global_content?->total_awards_count }}"></span>+</h2>
                      <p>{{ __('Prix ​​obtenu') }}</p>
                  </div>
              </div>
          </div>
      </div>
  </div>
</section>
