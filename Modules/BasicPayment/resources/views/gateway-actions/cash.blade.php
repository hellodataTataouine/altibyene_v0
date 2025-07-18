<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Paiement Comptant</title>
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset($setting->favicon) }}" />
    <link rel="stylesheet" href="{{ asset('frontend/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('frontend/css/main.min.css') }}?v={{ $setting?->version }}" />
</head>

<body>
    <form id="cash-payment-form"
        action="{{ isset($token) ? route('payment-api.cash-webview', ['bearer_token' => $token, 'order_id' => $order_id]) : route('pay-via-cash') }}"
        method="POST" class="d-none">
        @csrf
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.getElementById('cash-payment-form').submit();
        });
    </script>
</body>

</html>
