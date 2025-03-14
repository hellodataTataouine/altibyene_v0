@extends('frontend.layouts.master')
@section('meta_title', $seo_setting['cours_page']['seo_title'])
@section('meta_description', $seo_setting['cours_page']['seo_description'])

@section('contents')
    <!-- breadcrumb-area -->
    <x-frontend.breadcrumb :title="__('Cours')" :links="[['url' => route('home'), 'text' => __('Accueil')], ['url' => '', 'text' => __('Cours')]]" />
    <!-- breadcrumb-area-end -->

    @endsection
@if (session('removeFromCart') &&
        $setting->google_tagmanager_status == 'active' &&
        $marketing_setting?->remove_from_cart)
    @php
        $removeFromCart = session('removeFromCart');
        session()->forget('removeFromCart');
    @endphp
    @push('scripts')
        <script>
            $(function() {
                dataLayer.push({
                    'event': 'removeFromCart',
                    'cart_details': @json($removeFromCart)
                });
            });
        </script>
    @endpush
@endif
