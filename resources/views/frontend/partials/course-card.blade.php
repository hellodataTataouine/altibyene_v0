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
                    <li class="avg-rating"><i class="fas fa-euro-sign"></i>
                        {{ number_format($course->price, 2) ?? 0 }}</li>
                </ul>
                <h5 class="title"><a
                        href="{{ route('course.show', $course->slug) }}">{{ truncate($course->title, 50) }}</a></h5>

                {{--<p class="author">{{ __('Par') }} <a
                        href="{{ route('instructor-details', ['id' => $course->instructor->id, 'slug' => Str::slug($course->instructor->name)]) }}">{{ $course->instructor->name }}</a>
                </p>--}}

                <div class="courses__item-info mt-2">
                    <p><strong>{{ __('Date du cours') }} :</strong> {{ $course->date ?? 'À venir' }}</p>
                    {{-- <p><strong>{{ __('Public') }} :</strong> {{ ucfirst($course->public ?? 'Non spécifié') }}</p> --}}
                </div>

                <div class="courses__item-bottom">
                    @if (!is_null($course->date))

                        @if (in_array($course->id, session('enrollments') ?? []))
                            <div class="button">
                                <a href="{{ route('student.enrolled-courses') }}"
                                    class="already-enrolled-btn" data-id="">
                                    <span class="text">{{ __('Inscrit') }}</span>
                                    <i class="flaticon-arrow-right"></i>
                                </a>
                            </div>
                        @elseif ($course->enrollments_count >= $course->capacity && $course->capacity != null)
                            <div class="button">
                                <a href="javascript:;" class=""
                                    data-id="{{ $course->id }}">
                                    <span class="text">{{ __('Réservé') }}</span>
                                    <i class="flaticon-arrow-right"></i>
                                </a>
                            </div>
                        @else
                            <div class="button">

                                <a href="javascript:;" class="add-to-cart" data-id="{{ $course->id }}">
                                    <span class="text">{{ __('S\'abonner') }}</span>
                                    <i class="flaticon-arrow-right"></i>
                                </a>
                            </div>
                        @endif
                    @else
                            <a href="#"class="btn btn-two arrow-btn disabled">
                                <span class="text">{{ __('S\'abonner') }}</span>
                                <i class="flaticon-arrow-right"></i>
                            </a>
                    @endif
                </div>


             <div class="courses__item-bottom">
                    @if (in_array($course->id, session('inscriptions') ?? []))
                        <div class="button">
                            <a href="{{ route('student.enrolled-courses') }}" class="already-enrolled-btn"
                                data-id="">
                                <span class="text">{{ __('Inscrit') }}</span>
                                <i class="flaticon-arrow-right"></i>
                            </a>
                        </div>
                    @elseif ($course->enrollments->count() >= $course->capacity && $course->capacity != null)
                        <div class="button">
                            <a href="javascript:;" class="" data-id="{{ $course->id }}">
                                <span class="text">{{ __('Réservé') }}</span>
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
