<div class="brand-area">
  <div class="container-fluid">
      <div class="marquee_mode">
        @foreach ($brands as $brand)
        <div class="brand__item">
            <a href="{{ $brand?->url }}"><img src="{{ asset($brand?->image) }}" alt="{{ $brand?->name }}"></a>
                   <!--   <img src="{{ asset('frontend/img/icons/.png') }}" alt="{{ $brand?->name }}"> --->
             <img src="{{ asset('frontend/img/icons/brand_star.svg') }}" alt="star">
             
          </div>
        @endforeach

      </div>
  </div>
</div>
