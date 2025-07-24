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
            <div class="col-md-8">
                <div class="card singUp-wrap">
                    <div class="card-header bg-transparent">
                        {!! nl2br(e($cheque_information)) !!}
                    </div>
                    <div class="card-body">
                        <form
                            action="{{ isset($token) ? route('payment-api.cheque-webview', ['bearer_token' => $token, 'order_id' => $order_id]) : route('pay-via-cheque') }}"
                            method="POST">
                            @csrf

                            <!-- Texte d'information -->
                            <div class="alert alert-info">
                                {{ __('Vous pouvez régler votre commande par chèque en maximum 4 fois. Veuillez respecter les dates de paiement que vous saisirez ci-dessous.') }}
                            </div>

                            <!-- Conditions d'acceptation -->
                            <div class="form-check my-3">
                                <input class="form-check-input" type="checkbox" id="accept_terms" required>
                                <label class="form-check-label fw-bold" for="accept_terms">
                                    {{ __('J’accepte les conditions générales de paiement par chèque.') }}
                                </label>
                            </div>

                            <!-- Titulaire du chèque -->
                            <div class="my-2 form-group">
                                <label for="holder_name">{{ __('Nom du titulaire du chèque') }} <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="holder_name" name="holder_name"
                                    placeholder="{{ __('Nom complet du titulaire') }}" required>
                            </div>

                            <!-- Nombre de paiements -->
                            <div class="my-2 form-group">
                                <label for="installments">{{ __('Nombre de paiements (maximum 4)') }} <span class="text-danger">*</span></label>
                                <select id="installments" name="installments" class="form-control" required>
                                    @for ($i = 1; $i <= 4; $i++)
                                        <option value="{{ $i }}">{{ $i }} {{ Str::plural('fois', $i) }}</option>
                                    @endfor
                                </select>
                            </div>

                            <!-- Détails des paiements dynamiques -->
                            <div id="payment-details" class="my-2"></div>

                            <!-- Nombre de chèques -->
                            <div class="my-2 form-group">
                                <label for="cheque_count">{{ __('Nombre de chèques') }} <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="cheque_count" name="cheque_count" min="1" required>
                            </div>

                            <!-- Commentaire -->
                            <div class="my-3 form-group">
                                <label for="comment">{{ __('Commentaire (facultatif)') }}</label>
                                <textarea id="comment" name="comment" class="form-control" rows="3"
                                    placeholder="{{ __('Votre commentaire ici...') }}"></textarea>
                            </div>

                            <button type="submit" class="btn btn-primary">{{ __('Envoyer') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Scripts JS -->
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
                case 'info': toastr.info(message); break;
                case 'success': toastr.success(message); break;
                case 'warning': toastr.warning(message); break;
                case 'error': toastr.error(message); break;
            }
        @endif

        // Génération dynamique des paiements
        function generatePaymentFields(count) {
            const container = document.getElementById('payment-details');
            container.innerHTML = '';
            for (let i = 1; i <= count; i++) {
                const div = document.createElement('div');
                div.classList.add('border', 'p-3', 'mb-2', 'rounded');
                div.innerHTML = `
                    <h6>Paiement ${i}</h6>
                    <div class="form-group my-1">
                        <label>Montant <span class="text-danger">*</span></label>
                        <input type="text" name="payment_amounts[]" class="form-control" placeholder="Montant du paiement" required>
                    </div>
                    <div class="form-group my-1">
                        <label>Date de paiement <span class="text-danger">*</span></label>
                        <input type="date" name="payment_dates[]" class="form-control" required>
                    </div>
                `;
                container.appendChild(div);
            }
        }

        // Sur changement du nombre de paiements
        document.getElementById('installments').addEventListener('change', function() {
            generatePaymentFields(parseInt(this.value));
        });

        // Chargement initial
        window.addEventListener('DOMContentLoaded', () => {
            generatePaymentFields(parseInt(document.getElementById('installments').value));
        });
    </script>
</body>

</html>
