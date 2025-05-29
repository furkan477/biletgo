@extends('site.layout.layout')
@section('content')

    <section class="payment-success">
        <div class="container">
            <h1 class="text-green-600 text-3xl font-bold mb-4">✅ Ödeme Başarılı!</h1>
            <p class="text-lg">Sipariş Numaranız: <strong>{{ $response->OrderId }}</strong></p>
            <p>İşlem Kimliği: <strong>{{ $response->trans_id }}</strong></p>
            <p class="mt-4">Sipariş bilgileriniz SMS ve e-posta ile gönderilecektir.</p>
            <a href="{{ route('tickets') }}" class="btn btn-primary mt-6">Biletlerim Sayfasına Git</a>
        </div>
    </section>

    <style>
        .payment-success {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 60vh;
            background-color: #f4fff4;
            text-align: center;
        }

        .btn {
            background-color: #10b981;
            color: white;
            padding: 12px 24px;
            border-radius: 8px;
            text-decoration: none;
        }

        .btn:hover {
            background-color: #059669;
        }
    </style>

@endsection