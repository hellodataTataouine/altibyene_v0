@extends('admin.master_layout')
@section('title')
    <title>{{ __('Demande de livre') }}</title>
@endsection
@section('admin-content')
     <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>{{ __('Demande de livre') }}</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a>
                    </div>
                    <div class="breadcrumb-item">{{ __('Demandes de livre') }}</div>
                </div>
            </div>
            <div class="section-body">
                <div class="invoice">
                    <div class="invoice-print">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="invoice-title">
                                    <h2>{{ __('Demande de livre') }}</h2>
                                    <div class="invoice-number">{{ __('Demande ') }} #{{ $book_request->id }}</div>
                                    <address>
                                        <strong>{{ __('Date du demande') }}:</strong><br>
                                        {{ $book_request->created_at->format('d-m-y H:s') }}<br><br>
                                    </address>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Nom et prénom :</strong> {{$book_request->name}}</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Téléphone :</strong> {{$book_request->phone}}</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>E-mail :</strong> {{$book_request->email}}</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Message :</strong> {{$book_request->message}}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </section>
     </div>
@endsection
