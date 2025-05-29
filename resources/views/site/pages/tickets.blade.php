@extends('site.layout.layout')
@section('content')

    <link href="https://fonts.googleapis.com/css2?family=Inter&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #f2f4f7;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 40px auto;
            background: white;
            border-radius: 12px;
            padding: 30px 40px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.05);
        }

        .success {
            display: flex;
            align-items: center;
            background: linear-gradient(90deg, #0ac775, #39c3ad);
            padding: 20px;
            border-radius: 10px;
            color: white;
            margin-bottom: 30px;
        }

        .success h2 {
            margin: 0;
            font-size: 22px;
        }

        .pnr {
            margin-left: auto;
            background: white;
            color: #0ac775;
            font-weight: bold;
            padding: 8px 15px;
            border-radius: 8px;
        }

        .section-title {
            font-size: 18px;
            margin: 20px 0 10px;
            font-weight: bold;
            border-bottom: 1px solid #ddd;
            padding-bottom: 6px;
        }

        .flight-details,
        .passenger-info {
            font-size: 15px;
            line-height: 1.6;
        }

        .flight-details span,
        .passenger-info span {
            font-weight: bold;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        .table th,
        .table td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: left;
        }

        .table th {
            background: #f5f5f5;
        }

        .ticket-link a {
            color: #007bff;
            text-decoration: underline;
        }
    </style>

    @foreach ($tickets as $ticket)
        @php $reservation = json_decode($ticket->reservation_data, true); @endphp
        <div class="container">
            <div class="success">
                <h2>Tebrikler, seyahatinizi başarıyla satın aldınız.</h2>
                <div class="pnr">PNR No: {{$reservation['id']}}</div>
            </div>

            <div class="section-title">Uçuşunuzun Detayları
                {{ count($reservation['flights'][0]['segments']['departure']) > 1 ? '(Aktarmalı Uçuş)' : '' }}</div>
            <div class="flight-details">
                <h2>Gidiş Uçuşu</h2>
                @foreach($reservation['flights'][0]['segments']['departure'] as $index => $info)
                    <p><span>Havayolu:</span> {{ $info['airline']['name'] . ' (' . $info['airline']['code'] . ')' }}</p>
                    <p><span>Uçuş No:</span> {{$info['flightNumber']}}</p>
                    <p><span>Sınıf:</span> {{ $info['class'] }}</p>
                    <p><span>Uçuş Süresi:</span>
                        {{ $info['duration']['day'] . ' gün ' . $info['duration']['hours'] . ' saat ' . $info['duration']['minutes'] . ' dakika' }}
                    </p>
                    <p><span>Kalkış:</span> {{ \Carbon\Carbon::parse($info['departureAt'])->translatedFormat('d F Y H:i') }} -
                        {{$info['departureAirport']['name']}}</p>
                    <p><span>Varış:</span> {{ \Carbon\Carbon::parse($info['arrivalAt'])->translatedFormat('d F Y H:i') }} -
                        {{$info['arrivalAirport']['name']}}</p>
                    <hr>
                @endforeach
                @if(!empty($reservation['flights'][0]['segments']['return']))
                    <h2>Dönüş Uçuşu</h2>
                    @foreach($reservation['flights'][0]['segments']['return'] as $index => $info)
                        <p><span>Havayolu:</span> {{ $info['airline']['name'] . ' (' . $info['airline']['code'] . ')' }}</p>
                        <p><span>Uçuş No:</span> {{$info['flightNumber']}}</p>
                        <p><span>Sınıf:</span> {{ $info['class'] }}</p>
                        <p><span>Uçuş Süresi:</span>
                            {{ $info['duration']['day'] . ' gün ' . $info['duration']['hours'] . ' saat ' . $info['duration']['minutes'] . ' dakika' }}
                        </p>
                        <p><span>Kalkış:</span> {{ \Carbon\Carbon::parse($info['departureAt'])->translatedFormat('d F Y H:i') }} -
                            {{$info['departureAirport']['name']}}</p>
                        <p><span>Varış:</span> {{ \Carbon\Carbon::parse($info['arrivalAt'])->translatedFormat('d F Y H:i') }} -
                            {{$info['arrivalAirport']['name']}}</p>
                        <hr>
                    @endforeach
                @endif

                <p><span>Biletleme Tarihi:</span> {{$reservation['created_at']}}</p>
            </div>

            <div class="section-title">Yolcu Bilgileri</div>
            <table class="table">
                <thead>
                    <tr>
                        <th>Yolcu</th>
                        <th>Adı Soyadı</th>
                        <th>Bagaj Hakkı</th>
                        <th>Fiyat</th>
                        <th>E-bilet</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($reservation['passengers'] as $index => $fares)
                        <tr>
                            <td>{{$fares['type']}}</td>
                            <td>{{$fares['name'] . ' ' . $fares['surname']}}</td>
                            <td>{{$fares['flight_baggages'][0]['quantity'] . ' ' . $fares['flight_baggages'][0]['unit']}}</td>
                            <td>123 TL</td>
                            <td class="ticket-link"><a href="#">Görüntüle</a></td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" style="text-align:right; font-weight:bold;">Toplam Fiyat</td>
                        <td colspan="2">123 TL</td>
                    </tr>
                </tfoot>
            </table>
            <!-- kişisel fiyat , bagaj hakkı ve ayrıyeten toplam fiyat gelicek. daha sonra ise E-bilet Görüntüle alanı  -->
        </div>
    @endforeach

@endsection