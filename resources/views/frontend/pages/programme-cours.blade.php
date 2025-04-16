@extends('frontend.layouts.master')
@section('meta_title', 'programme et cours ' . ' || ' . $setting->app_name)

@section('contents')
    <!-- breadcrumb-area -->
    <x-frontend.breadcrumb :title="__('Programme et Cours')" :links="[['url' => route('home'), 'text' => __('Accueil')], ['url' => route('cart'), 'text' => __('Programme et Cours')]]" />
    <!-- breadcrumb-area-end -->


<!-- all-courses -->
<section class="all-courses-area section-py-120 top-baseline">
    <div class="container position-relative" data-aos="fade-down">
        <div class="preloader-two d-none">
            <div class="loader-icon-two"><img src="{{ asset(Cache::get('setting')->preloader) }}" alt="Preloader"></div>
        </div>
        <div class="row">
            <div class="col-xl-3 col-lg-4">
                <div class="courses__sidebar_area">
                    <div class="courses__sidebar_button d-lg-none">
                        <h4>{{ __('filter') }}</h4>
                    </div>
                    {{--<aside class="courses__sidebar">
                        <div class="courses-widget">
                            <h4 class="widget-title">{{ __('Categories') }}</h4>
                            <div class="courses-cat-list">
                                <ul class="list-wrap">
                                    @foreach ($categories->sortBy('translation.name') as $category)
                                        <li>
                                            <div class="form-check">
                                                <input class="form-check-input main-category-checkbox" type="radio"
                                                    name="main_category" value="{{ $category->slug }}"
                                                    id="cat_{{ $category->id }}">
                                                <label class="form-check-label"
                                                    for="cat_{{ $category->id }}">{{ $category->translation->name }}</label>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                                <div class="show-more">
                                </div>
                            </div>
                        </div>--}}

                      {{--  <div class="sub-category-holder "   data-aos="fade-down"></div>
                        <div class="courses-widget">
                            <h4 class="widget-title">{{ __('Langue') }}</h4>
                            <div class="courses-cat-list">
                                <ul class="list-wrap">

                                    <li>
                                        <div class="form-check">
                                            <input class="form-check-input language-checkbox" type="checkbox"
                                                value="" id="lang">
                                            <label class="form-check-label"
                                                for="lang">{{ __('Toutes les langues') }}</label>
                                        </div>
                                    </li>
                                    @foreach ($languages as $language)
                                        <li>
                                            <div class="form-check">
                                                <input class="form-check-input language-checkbox" type="checkbox"
                                                    value="{{ $language->id }}" id="lang_{{ $language->id }}">
                                                <label class="form-check-label"
                                                    for="lang_{{ $language->id }}">{{ $language->name }}</label>
                                            </div>
                                        </li>
                                    @endforeach

                                </ul>
                            </div>
                            <div class="show-more">
                            </div>
                        </div>--}}
                        {{--<div class="courses-widget"   data-aos="fade-down">
                            <h4 class="widget-title">{{ __('Prix') }}</h4>
                            <div class="courses-cat-list">
                                <ul class="list-wrap">
                                    <li>
                                        <div class="form-check">
                                            <input class="form-check-input price-checkbox" type="checkbox"
                                                value="" id="price_1">
                                            <label class="form-check-label"
                                                for="price_1">{{ __('Tous les prix') }}</label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="form-check">
                                            <input class="form-check-input price-checkbox" type="checkbox"
                                                value="free" id="price_2">
                                            <label class="form-check-label" for="price_2">{{ __('Gratuit') }}</label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="form-check">
                                            <input class="form-check-input price-checkbox" type="checkbox"
                                                value="paid" id="price_3">
                                            <label class="form-check-label" for="price_3">{{ __('Payé') }}</label>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>--}}

                        <div class="courses-widget" data-aos="fade-down">
                            <h4 class="widget-title">{{ __('Type de cours') }}</h4>
                            <div class="courses-cat-list">
                                <ul class="list-wrap">
                                    <li>
                                        <div class="form-check">
                                            <input class="form-check-input type-checkbox" type="checkbox"
                                                value="" id="type_all">
                                            <label class="form-check-label" for="type_all">{{ __('Tous les types') }}</label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="form-check">
                                            <input class="form-check-input type-checkbox" type="checkbox"
                                                value="presentiel" id="type_presentiel">
                                            <label class="form-check-label" for="type_presentiel">{{ __('Présentiel') }}</label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="form-check">
                                            <input class="form-check-input type-checkbox" type="checkbox"
                                                value="distanciel" id="type_distanciel">
                                            <label class="form-check-label" for="type_distanciel">{{ __('Distanciel') }}</label>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>


                        <div class="courses-widget"   data-aos="fade-down">
                            <h4 class="widget-title">{{ __('Niveau de compétence') }}</h4>
                            <div class="courses-cat-list">
                                <ul class="list-wrap">
                                    <li>
                                        <div class="form-check">
                                            <input class="form-check-input level-checkbox" type="checkbox"
                                                value="" id="difficulty_1">
                                            <label class="form-check-label"
                                                for="difficulty_1">{{ __('Tous les niveaux') }}</label>
                                        </div>
                                    </li>
                                    @foreach ($levels as $level)
                                        <li>
                                            <div class="form-check">
                                                <input class="form-check-input level-checkbox" type="checkbox"
                                                    value="{{ $level->id }}" id="difficulty_{{ $level->id }}">
                                                <label class="form-check-label"
                                                    for="difficulty_{{ $level->id }}">{{ $level->translation->name }}</label>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </aside>
                </div>
            </div>
            <div class="col-xl-9 col-lg-8"   data-aos="fade-down">
                <div class="courses-top-wrap courses-top-wrap">
                    <div class="row align-items-center">
                        <div class="col-md-5">
                            <div class="courses-top-left">
                                <p>{{ __('Total') }} <span class="course-count">0</span> {{ __('cours trouvés') }}
                                </p>
                            </div>
                        </div>
                        <div class="col-md-7"   data-aos="fade-down">
                            <div class="d-flex justify-content-center align-items-center flex-wrap">
                                <div class="courses-top-right m-0 ms-md-auto">
                                    <span class="sort-by">{{ __('Trier par') }}:</span>
                                    <div class="courses-top-right-select">
                                        <select name="orderby" class="orderby">
                                            <option value="desc">{{ __('Du plus récent au plus ancien') }}</option>
                                            <option value="asc">{{ __('Du plus ancien au plus récent') }}</option>
                                        </select>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>



                
                @forelse ($courses as $course)
                <div class="col-xxl-4 col-md-6 col-lg-6 col-xl-6">
                    <div class="courses__item shine__animate-item">
                        <div class="courses__item-thumb">
                            <a href="{{ route('course.show', $course->slug) }}" class="shine__animate-link">
                                <img src="{{ asset($course->thumbnail) }}" alt="img">
                            </a>
                            <a href="javascript:;" class="wsus-wishlist-btn common-white courses__wishlist-two"
                                data-slug="{{ $course?->slug }}" aria-label="WishList">
                                <i class="{{ $course?->favorite_by_client ? 'fas' : 'far' }} fa-heart"></i>
                            </a>
                        </div>
                        <div class="courses__item-content">
                            <ul class="courses__item-meta list-wrap">
                               {{--<li class="courses__item-tag">
                                    <a
                                        href="{{ route('courses', ['category' => $course->category->id]) }}">{{ $course->category->translation->name }}</a>
                                </li>--}}
                                <li class="avg-rating"><i class="fas fa-star"></i>
                                    {{ number_format($course->reviews()->avg('rating'), 1) ?? 0 }}</li>
                            </ul>
                            <h5 class="title"><a
                                    href="{{ route('course.show', $course->slug) }}">{{ truncate($course->title, 50) }}</a></h5>
                            <p class="author">{{ __('Par') }} <a
                                    href="{{ route('instructor-details', ['id' => $course->instructor->id, 'slug' => Str::slug($course->instructor->name)]) }}">{{ $course->instructor->name }}</a>
                            </p>
                            <div class="courses__item-bottom">
                                @if (in_array($course->id, session('enrollments') ?? []))
                                    <div class="button">
                                        <a href="{{ route('student.enrolled-courses') }}" class="already-enrolled-btn"
                                            data-id="">
                                            <span class="text">{{ __('Enrolled') }}</span>
                                            <i class="flaticon-arrow-right"></i>
                                        </a>
                                    </div>
                                @elseif ($course->enrollments->count() >= $course->capacity && $course->capacity != null)
                                    <div class="button">
                                        <a href="javascript:;" class="" data-id="{{ $course->id }}">
                                            <span class="text">{{ __('Booked') }}</span>
                                            <i class="flaticon-arrow-right"></i>
                                        </a>
                                    </div>
                                @else
                                  {{--  <div class="button">
                                        <a href="javascript:;" class="add-to-cart" data-id="{{ $course->id }}">
                                            <span class="text">{{ __('Ajouter au panier') }}</span>
                                            <i class="flaticon-arrow-right"></i>
                                        </a>
                                    </div>
                                @endif
                                @if ($course->price == 0)
                                    <h5 class="price">{{ __('Free') }}</h5>
                                @elseif ($course->price > 0 && $course->discount > 0)
                                    <h5 class="price">{{ currency($course->discount) }}</h5>
                                @else
                                    <h5 class="price">{{ currency($course->price) }}</h5>--}}
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="w-100">
                    <h6 class="text-center">{{ __('Aucun cours trouvé !') }}</h6>
                </div>
            @endforelse





                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="grid" role="tabpanel"
                        aria-labelledby="grid-tab">
                        <div
                            class="course-holder row courses__grid-wrap row-cols-1 row-cols-xl-3 row-cols-lg-2 row-cols-md-2 row-cols-sm-1">
                            {{-- dynamic content will go here via ajax --}}
                        </div>

                        <div class="pagination-wrap">
                            <div class="pagination">
                                {{-- dynamic content will go here via ajax --}}
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- all-courses-end -->
@endsection

@push('scripts')
<script src="{{ asset('frontend/js/default/course-page.js') }}"></script>
@endpush

