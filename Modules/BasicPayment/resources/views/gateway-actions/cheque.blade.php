@php
    $method = $paymentMethod;
    $cheque_information = $paymentService->getGatewayDetails($method)->cheque_information ?? '';
@endphp
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Paiement par chèque</title>
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset($setting->favicon) }}" />
    <link rel="stylesheet" href="{{ asset('frontend/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('frontend/css/main.min.css') }}?v={{ $setting?->version }}" />
    <link rel="stylesheet" href="{{ asset('global/toastr/toastr.min.css') }}" />
</head>

<body>
    <section class="about-area-three section-py-120 vh-100 d-flex align-items-center justify-content-center">
        <div class="container d-flex justify-content-center">
            <div class="col-md-7">
                <div class="card singUp-wrap">
                    <div class="card-header bg-transparent">
                        {!! nl2br(e($cheque_information)) !!}
                    </div>
                    <div class="card-body">
                        <form
                            action="{{ isset($token) ? route('payment-api.cheque-webview', ['bearer_token' => $token, 'order_id' => $order_id]) : route('pay-via-cheque') }}"
                            method="POST">
                            @csrf

                            <!-- Titulaire du chèque -->
                            <div class="my-1 form-group">
                                <label for="holder_name">{{ __('Nom du titulaire du chèque') }} <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="holder_name" name="holder_name"
                                    placeholder="{{ __('Nom complet du titulaire') }}" required>
                            </div>

                            <!-- Numéro de chèque -->
                            <div class="my-1 form-group">
                                <label for="cheque_number">{{ __('Numéro du chèque') }} <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="cheque_number" name="cheque_number"
                                    placeholder="{{ __('Numéro inscrit sur le chèque') }}" required>
                            </div>

                            <!-- Date du chèque -->
                            <div class="my-1 form-group">
                                <label for="cheque_date">{{ __('Date du chèque') }} <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="cheque_date" name="cheque_date" required>
                            </div>

                            <!-- Montant (optionnel, peut être pré-rempli côté serveur) -->
                            <div class="my-1 form-group">
                                <label for="amount">{{ __('Montant') }} <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="amount" name="amount"
                                    value="{{ $amount ?? '' }}">
                            </div>

                            <!-- Commentaire / Référence -->
                            <div class="my-1 form-group">
                                <label for="reference">{{ __('Référence (facultatif)') }}</label>
                                <input type="text" class="form-control" id="reference" name="reference"
                                    placeholder="{{ __('Numéro de commande ou autre référence') }}">
                            </div>

                            <button type="submit" class="mt-2 btn btn-primary">{{ __('Envoyer') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Toastr Notifications -->
    <script src="{{ asset('global/toastr/toastr.min.js') }}"></script>
    <script>
        "use strict";
        toastr.options.closeButton = true;
        toastr.options.progressBar = true;
        toastr.options.positionClass = 'toast-bottom-right';

        @if(session('messege'))
            var type = "{{ Session::get('alert-type', 'info') }}";
            var message = "{{ session('messege') }}";

            switch(type) {
                case 'info':
                    toastr.info(message);
                    break;
                case 'success':
                    toastr.success(message);
                    break;
                case 'warning':
                    toastr.warning(message);
                    break;
                case 'error':
                    toastr.error(message);
                    break;
            }
        @endif
    </script>
</body>

</html>
