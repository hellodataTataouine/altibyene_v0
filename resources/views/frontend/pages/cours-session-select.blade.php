@extends('frontend.layouts.master')
@section('meta_title', 'Sessions du cours' . ' || ' . $setting->app_name)
@section('contents')
    <!-- breadcrumb-area -->
    <x-frontend.breadcrumb :title="__('Choose cours session')" :links="[
        ['url' => route('home'), 'text' => __('Home')],
        ['url' => route('public.show-cours-sessions',$id), 'text' => __('Choose cours session')],
    ]" />
    <style>
        .disabled-session {
            opacity: 0.6;
            cursor: not-allowed;
        }
    </style>
    <!-- breadcrumb-area-end -->
    <div class="checkout__area section-py-120">
        <div class="preloader-two preloader-two-fixed d-none">
            <div class="loader-icon-two"><img src="{{ asset(Cache::get('setting')->preloader) }}" alt="Preloader"></div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="wsus__payment_area">
                        <div class="row">
                            @if (!$sessions->isEmpty())
                            @foreach ($sessions as $session)
                            @php
                                $availableSeats = $session->max_enrollments - $session->enrolled_students;
                                $isAvailable = $availableSeats > 0;
                            @endphp

                            <div class="col-lg-3 col-6 col-sm-4">
                                <a
                                    class="wsus__single_session place-order-btn p-2 d-flex flex-column align-items-center text-center {{ $isAvailable ? '' : 'disabled-session' }}"
                                    style="background-color: {{ $isAvailable ? 'white' : '#f5f5f5' }}; pointer-events: {{ $isAvailable ? 'auto' : 'none' }};"
                                    @if($isAvailable)
                                        href="{{ route('public.redirect-to-checkout', $session->id) }}"
                                    @endif
                                >
                                    <h5>{{ $session->title }}</h5>
                                    <p style="margin: 2px 0; color: {{ $isAvailable ? 'green' : 'red' }};">
                                        {{ $availableSeats }} {{ __('available_seats') }}
                                    </p>
                                    <p style="margin: 2px 0;">
                                        {{ __($session->type) }}
                                    </p>
                                </a>
                            </div>
                        @endforeach


                            @else
                                    <p>{{ __('no_session_found') }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
