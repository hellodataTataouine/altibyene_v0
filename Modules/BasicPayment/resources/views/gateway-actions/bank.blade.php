@php
    $method = $paymentMethod;
    $bank_information = $paymentService->getGatewayDetails($method)->bank_information ?? '';
@endphp

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paiement par Virement Bancaire</title>
    <link rel="shortcut icon" href="{{ asset($setting->favicon) }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/main.min.css') }}?v={{ $setting?->version }}">
    <link rel="stylesheet" href="{{ asset('global/toastr/toastr.min.css') }}">
</head>

<body>
<section class="about-area-three section-py-120 vh-100 d-flex align-items-center justify-content-center">
    <div class="container d-flex justify-content-center">
        <div class="col-md-8">
            <div class="card singUp-wrap">
                <div class="card-header bg-transparent">
                    {!! nl2br($bank_information) !!}
                </div>
                <div class="card-body">


                    <form action="{{ isset($token) ? route('payment-api.bank-webview', ['bearer_token' => $token, 'order_id' => $order_id]) : route('pay-via-bank') }}" method="POST">
                        @csrf
                        <!-- Texte d'information -->
                        <div class="alert alert-info">
                            {{ __('Vous pouvez régler votre commande par virement bancaire en maximum 4 fois. Veuillez respecter les dates de paiement que vous saisirez ci-dessous.') }}
                        </div>

                        <!-- Conditions d'acceptation -->
                        <div class="form-check my-3">
                            <input class="form-check-input" type="checkbox" id="accept_terms" name="accept_terms" required>
                            <label class="form-check-label fw-bold" for="accept_terms">
                                {{ __('J’accepte les conditions générales de paiement par virement.') }}
                            </label>
                        </div>
                        <!-- Informations bancaires de la société -->
                        <div class="alert alert-secondary my-4">
                            {{-- Informations bancaires de la société à remplir ici --}}
                            <strong>Informations bancaires de la société :</strong>
                            <div id="company-bank-details">
                                <!-- Exemple : Nom de la banque, IBAN, BIC, etc. -->
                                <!-- À remplir par vos soins -->
                                <p>Titulaire du compte: Clair de lune</p>
                                <p>IBAN: IE14SUMU99036511043727</p>
                                <p>BIC: SUMUIE22XXX</p>
                                <p>Institution: SumUp Limited</p>
                            </div>
                        </div>

                        <!-- Nombre d'échéances -->
                        <div class="form-group mb-3">
                            <label for="installments">{{ __('Nombre de paiements') }} <span class="text-danger">*</span></label>
                            <select class="form-control" id="installments" name="installments" required>
                                @for($i = 1; $i <= 4; $i++)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                        </div>

                        <div id="installment-fields"></div>

                        <!-- Champ de commentaire facultatif -->
                        <div class="form-group mt-3">
                            <label for="comment">{{ __('Commentaire (facultatif)') }}</label>
                            <textarea class="form-control" id="comment" name="comment" rows="2"></textarea>
                        </div>

                        <button class="mt-3 btn btn-primary">{{ __('Soumettre le paiement') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="{{ asset('frontend/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('global/toastr/toastr.min.js') }}"></script>
<script>
    "use strict";

    // Dynamically show amount and date inputs
    const installmentSelect = document.getElementById('installments');
    const installmentFields = document.getElementById('installment-fields');

    function renderInstallmentInputs(count) {
        installmentFields.innerHTML = '';
        for (let i = 0; i < count; i++) {
            installmentFields.innerHTML += `
                <div class="row mb-2">
                    <div class="col-md-6">
                        <label>{{ __('Montant du paiement') }} ${i + 1}</label>
                        <input type="number" name="payment_amounts[]" step="0.01" min="0.01" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label>{{ __('Date de paiement') }} ${i + 1}</label>
                        <input type="date" name="payment_dates[]" class="form-control" required>
                    </div>
                </div>
            `;
        }
    }

    // Initial render
    renderInstallmentInputs(installmentSelect.value);

    installmentSelect.addEventListener('change', function () {
        renderInstallmentInputs(this.value);
    });

    // Toastr feedback
    toastr.options = {
        closeButton: true,
        progressBar: true,
        positionClass: 'toast-bottom-right',
    };

    @if(Session::has('messege'))
        let type = "{{ Session::get('alert-type', 'info') }}";
        let message = "{{ Session::get('messege') }}";
        switch (type) {
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
