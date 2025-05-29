<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ödeme Sayfasına Yönlendiriliyorsunuz...</title>
</head>
<body onload="document.getElementById('paymentForm').submit();">
    <h2>Ödeme Sayfasına Yönlendiriliyorsunuz...</h2>
    <p>Lütfen bekleyin, ödeme işleminiz başlatılıyor.</p>

    <form id="paymentForm" method="POST" action="{{ env("ZIRAAT_3D_URL") }}">
        @foreach ($data as $key => $value)
            <input type="hidden" name="{{ $key }}" value="{{  $value }}">
        @endforeach
    </form>
</body>
</html>