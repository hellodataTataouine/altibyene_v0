<section class="blog__post-area">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="section__title text-center mb-40"  data-aos="fade-up-right">
                    <span class="sub-title">{{ __('Actualité et evénement') }}</span>
                    <h2 class="title">{{ __('Restez informé des dernières nouveautés') }}</h2>
                    <p>{{ __('Nous sommes heureux de vous inviter à rejoindre nos evenement et activités') }}</p>
                </div>
            </div>
        </div>
        <div class="row gutter-20"  >
            @foreach ($featuredBlogs as $blog)
                <div class="col-xxl-3 col-md-6 col-lg-4"    data-aos="fade-up-left">
                    <div class="blog__post-item shine__animate-item">
                        <div class="blog__post-thumb">
                            <a href="{{ route('blog.show', $blog->slug) }}" class="shine__animate-link blog"><img src="{{ asset($blog->image) }}"
                                    alt="img"></a>
                            <a href="{{ route('blogs', ['category' => $blog->category->slug]) }}"
                                class="post-tag">{{ $blog->category?->title }}</a>
                        </div>
                        <div class="blog__post-content">
                            <div class="blog__post-meta">
                                <ul class="list-wrap">
                                    <li><i class="flaticon-calendar"></i>{{ formatDate($blog->created_at) }}</li>
                                    <li><i class="flaticon-user-1"></i>{{ __('by') }} <a
                                            href="javascript:;">{{ truncate($blog->author->name, 14) }}</a>
                                    </li>
                                </ul>
                            </div>
                            <h3 class="title"><a
                                    href="{{ route('blog.show', $blog->slug) }}">{{ truncate($blog?->title, 50) }}</a>
                            </h3>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
