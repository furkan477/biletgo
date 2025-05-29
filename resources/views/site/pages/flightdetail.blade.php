@extends('site.layout.layout')
@section('content')

    <style>
        .submit-section {
            margin-top: 30px;
            padding: 20px;
            background-color: #f3f3f3;
            text-align: center;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .submit-section button {
            background-color: green;
            color: white;
            font-size: 18px;
            padding: 12px 30px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .submit-section button:hover {
            background-color: #0056b3;
        }

        #faturaBilgileri {
            display: none;
        }

        .toggle-button {
            color: blue;
            cursor: pointer;
            text-decoration: underline;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 90%;
            max-width: 900px;
            margin: 20px auto;
        }

        .box {
            background: #fff;
            border: 1px solid #ddd;
            padding: 20px;
            margin-bottom: 15px;
            border-left: 5px solid #4CAF50;
            border-radius: 6px;
        }

        h3 {
            margin-top: 0;
            color: #333;
        }

        .flight {
            border-top: 1px dashed #ccc;
            padding-top: 10px;
            margin-top: 10px;
        }

        .sub-info {
            font-size: 0.9em;
            color: #555;
        }

        .price {
            font-size: 1.2em;
            color: #d32f2f;
        }

        .form-row {
            margin: 10px 0;
        }

        input[type="text"],
        input[type="email"],
        input[type="tel"] {
            padding: 8px;
            width: 100%;
            max-width: 300px;
            margin-right: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .phone-input {
            display: flex;
            align-items: center;
        }

        .phone-input span {
            padding: 8px;
            background: #eee;
            border: 1px solid #ccc;
            border-right: none;
            border-radius: 4px 0 0 4px;
        }

        .phone-input input {
            border-radius: 0 4px 4px 0;
            flex: 1;
        }

        label {
            margin-right: 15px;
        }

        .fatura-link {
            color: #007BFF;
            text-decoration: none;
        }
    </style>

    <div class="container">

        @php $passenger = ['ADT' => 'Yetişkin', 'YCD' => '65 + Yaş', 'STU' => 'Öğrenci', 'INF' => 'Bebek', 'CHD' => 'Çocuk'] @endphp

        <!-- Gidiş -->
        <section class="box">
            <h3>🛫 Gidiş</h3>
            @foreach($data['data']['flightList']['departure']['segments'] as $index => $departureSegment)
                @php
                    $airline = $data['data']['airlineList'][$departureSegment['airline']];
                    $departureAirport = $data['data']['airportList'][$departureSegment['departureAirport']];
                    $arrivalAirport = $data['data']['airportList'][$departureSegment['arrivalAirport']];
                    $departure = $data['data']['flightList']['departure'];
                @endphp
                <div class="flight">
                    <p><strong>{{ $airline['name'] }} Uçuşu:</strong> {{$departureSegment['flightNumber']}} | Sınıf:
                        {{$departureSegment['class']}}
                    </p>
                    <p><strong>Kalkış:</strong>
                        {{ \Carbon\Carbon::parse($departureSegment['departureDatetime'])->translatedFormat('d F Y H:i') }} -
                        {{$departureAirport['name'] . ', ' . $departureAirport['city_name'] }}
                    </p>
                    <p><strong>Varış:</strong>
                        {{ \Carbon\Carbon::parse($departureSegment['arrivalDatetime'])->translatedFormat('d F Y H:i') }} -
                        {{$arrivalAirport['name'] . ', ' . $arrivalAirport['city_name'] }}
                    </p>
                    @if($index < count($departure['segments']) - 1)
                        @php
                            $atime = \Carbon\Carbon::parse($departureSegment['arrivalDatetime']);
                            $dtime = \Carbon\Carbon::parse($departure['segments'][$index + 1]['departureDatetime']);

                            $diff = $dtime->diff($atime);
                        @endphp
                        <p class="sub-info">Aktarma: {{$diff}} Bekleme Süresi</p>
                    @endif
                </div>
            @endforeach
            <p class="mt-5"><i class="fas fa-clock"></i> Toplam Uçuş Süresi:
                {{ $departure['duration']['day'] . ' gün ' . $departure['duration']['hours'] . ' saat ' . $departure['duration']['minutes'] . ' dakika.' }}
                <br><i class="fa-solid fa-suitcase-rolling"></i> Bagaj Haklarınız;
            <div class="pl-5">
                @foreach ($departure['baggageInfo'] as $fares => $baggage)
                    &nbsp;&nbsp;&nbsp;&nbsp;-r&nbsp;&nbsp;{{ $passenger[$fares] . ' için: ' . $baggage['quantity'] . ' ' . $baggage['unit'] }},<br>
                @endforeach
            </div>
            </p>
        </section>

        <!-- Dönüş -->
        @if (!empty($data['data']['flightList']['return']))
            <section class="box">
                <h3>🛬 Dönüş</h3>
                @foreach($data['data']['flightList']['return']['segments'] as $index => $returnSegment)
                    @php
                        $airline = $data['data']['airlineList'][$returnSegment['airline']];
                        $departureAirport = $data['data']['airportList'][$returnSegment['departureAirport']];
                        $arrivalAirport = $data['data']['airportList'][$returnSegment['arrivalAirport']];
                        $return = $data['data']['flightList']['return'];
                    @endphp
                    <div class="flight">
                        <p><strong>{{ $airline['name'] }} Uçuşu:</strong> {{$returnSegment['flightNumber']}} | Sınıf:
                            {{$returnSegment['class']}}
                        </p>
                        <p><strong>Kalkış:</strong>
                            {{ \Carbon\Carbon::parse($returnSegment['departureDatetime'])->translatedFormat('d F Y H:i') }} -
                            {{$departureAirport['name'] . ', ' . $departureAirport['city_name'] }}
                        </p>
                        <p><strong>Varış:</strong>
                            {{ \Carbon\Carbon::parse($returnSegment['arrivalDatetime'])->translatedFormat('d F Y H:i') }} -
                            {{$arrivalAirport['name'] . ', ' . $arrivalAirport['city_name'] }}
                        </p>
                        @if($index < count($return['segments']) - 1)
                            @php
                                $atime = \Carbon\Carbon::parse($returnSegment['arrivalDatetime']);
                                $dtime = \Carbon\Carbon::parse($return['segments'][$index + 1]['departureDatetime']);

                                $diff = $dtime->diff($atime);
                            @endphp
                            <p class="sub-info">Aktarma: {{$diff}} Bekleme Süresi</p>
                        @endif
                    </div>
                @endforeach
                <p class="mt-5"><i class="fas fa-clock"></i> Toplam Uçuş Süresi:
                    {{ $return['duration']['day'] . ' gün ' . $return['duration']['hours'] . ' saat ' . $return['duration']['minutes'] . ' dakika.' }}
                    <br><i class="fa-solid fa-suitcase-rolling"></i> Bagaj Haklarınız;
                <div class="pl-5">
                    @foreach ($return['baggageInfo'] as $fares => $baggage)
                        &nbsp;&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;{{ $passenger[$fares] . ' için: ' . $baggage['quantity'] . ' ' . $baggage['unit'] }},<br>
                    @endforeach
                </div>
                </p>
            </section>
        @endif

        <!-- Fiyat Bilgileri -->
        <section class="box">
            <h3>💰 Fiyat Bilgileri</h3>
            @php $total = 0; @endphp
            <ul>
                @foreach($data['data']['fares'] as $fares => $info)
                    @php $price = ($info['baseFare'] + $info['tax'] + $info['serviceFee']) * $info['quantity'];@endphp
                    @php $total += $price @endphp
                    <li class="pl-5">
                        {{ $passenger[$fares] . ' Yolcu ' . $info['quantity'] . ' Kişi Seçilmiştir ve Adet Ücreti: ' . $info['baseFare'] + $info['tax'] + $info['serviceFee'] . ' ₺' . ', Toplam:' . $price . ' ₺'}}
                    </li>
                @endforeach
            </ul>
            <p><strong>Toplam Ücret:</strong> <span class="price">{{ number_format($total, 2, ',', '.') }} TL</span></p>
        </section>

        <form action="{{ route('payment.process') }}" method="POST">
            @csrf
            <!-- İletişim Bilgileri -->
            <section class="box">
                <h3>📞 İletişim Bilgileri</h3>
                <div class="form-row">
                    <label for="phone">Telefon:</label>
                    <div class="phone-input">
                        <span>+90</span>
                        <input type="tel" id="phone" name="phone_number" placeholder="5XXXXXXXXX">
                    </div>
                    <small>(Bilgileriniz bu numaraya sms olarak gönderilecektir.)</small>
                    <label for="email">E-mail (Zorunlu Değildir):</label>
                    <input type="email" id="email" name="email" placeholder="ornek@mail.com">
                </div>
                <div class="form-row">

                </div>
            </section>

            <!-- Yolcu Bilgileri -->
            <section class="box">
                <h3>👤 Yolcu Bilgileri</h3>
                @php $yolcu = 0; @endphp
                @foreach($data['data']['fares'] as $fares => $info)
                    @php $number = 1 @endphp
                    @for($x = 0; $x < $info['quantity']; $x++)
                        <p><strong>{{$number . '. ' . $passenger[$fares]}} Yolcu</strong></p>
                        <div class="form-row">
                            <input type="hidden" name="passengers[{{ $yolcu }}][type]" value="{{ $fares }}">
                            <input type="text" name="passengers[{{ $yolcu }}][name]" placeholder="İsim">
                            <input type="text" name="passengers[{{ $yolcu }}][surname]" placeholder="Soyisim">

                            <input type="text" id="tcNo" name="passengers[{{ $yolcu }}][citizen_id]" placeholder="TC Kimlik No">

                            <label>
                                <input type="checkbox" id="foreignCheckbox" onclick="toggleTcInput()"> T.C. Vatandaşı Değil
                            </label>
                        </div>
                        <div class="form-row">
                            <label>Cinsiyet:</label>
                            <label><input type="radio" value="male" name="passengers[{{ $yolcu }}][gender]"> Bay</label>
                            <label><input type="radio" value="female" name="passengers[{{ $yolcu }}][gender]"> Bayan</label>
                        </div>
                        @if($fares == 'CHD' || $fares == 'INF' || $fares == 'YCD')
                            <div class="form-row">
                                <label>Doğum Tarihi D/M/Y</label>
                                <select name="passengers[{{ $yolcu }}][birthday_day]">
                                    <option value="01">01</option>
                                    <option value="02">02</option>
                                    <option value="03">03</option>
                                    <option value="04">04</option>
                                    <option value="05">05</option>
                                    <option value="06">06</option>
                                    <option value="07">07</option>
                                    <option value="08">08</option>
                                    <option value="09">09</option>
                                    <option value="10">10</option>
                                    <option value="11">11</option>
                                    <option value="12">12</option>
                                </select>
                                <select name="passengers[{{ $yolcu }}][birthday_month]">
                                    <option value="01">Ocak</option>
                                    <option value="02">Şubat</option>
                                    <option value="03">Mart</option>
                                    <option value="04">Nisan</option>
                                    <option value="05">Mayıs</option>
                                    <option value="06">Haziran</option>
                                    <option value="07">Temmuz</option>
                                    <option value="08">Ağustos</option>
                                    <option value="09">Eylül</option>
                                    <option value="10">Ekim</option>
                                    <option value="11">Kasım</option>
                                    <option value="12">Aralık</option>
                                </select>
                                @if($fares == 'INF')
                                    <select name="passengers[{{ $yolcu }}][birthday_year]">
                                        <option value="2025">2025</option>
                                        <option value="2024">2024</option>
                                        <option value="2023">2023</option>
                                    </select>
                                @elseif($fares == 'CHD')
                                    <select name="passengers[{{ $yolcu }}][birthday_year]">        
                                        <option value="2023">2023</option>
                                        <option value="2022">2022</option>
                                        <option value="2021">2021</option>
                                        <option value="2020">2020</option>
                                        <option value="2019">2019</option>
                                        <option value="2018">2018</option>
                                        <option value="2017">2017</option>
                                        <option value="2016">2016</option>
                                        <option value="2015">2015</option>
                                        <option value="2014">2014</option>
                                        <option value="2013">2013</option>
                                    </select>
                                @elseif($fares == 'YCD')
                                    <input type="text" name="passengers[{{ $yolcu }}][birthday_year]" placeholder="Doğum Yılı 1960 veya altı olmak zorunda">
                                @endif
                            </div>
                        @endif
                        <hr>
                        @php $yolcu++; @endphp
                        @php $number++; @endphp
                    @endfor
                @endforeach
            </section>

            <!-- Fatura -->
            <section class="box">
                <p id="toggleText" class="toggle-button mt-2">Fatura Bilgilerimi Girmek İstiyorum</p>
                <div id="faturaBilgileri" class="border p-4 mt-4 rounded-lg">
                    <h2 class="text-lg font-semibold mb-2">🧾 Fatura Bilgileri</h2>
                    <label>
                        <input type="radio" name="fatura_tipi" value="bireysel" checked> Bireysel
                    </label>
                    <label>
                        <input type="radio" name="fatura_tipi" value="kurumsal"> Kurumsal
                    </label><br><br>

                    <div id="bireyselForm">
                        <label class="block">Adınız Soyadınız</label>
                        <input type="text" class="w-full p-2 border rounded" name="invoice[name]"
                            placeholder="İsim Soyisim"><br>
                        <label class="block mt-2">TC Kimlik No</label>
                        <input type="text" class="w-full p-2 border rounded" name="invoice[citizen_id]" placeholder="TC NO">
                    </div>

                    <div id="kurumsalForm">
                        <label class="block">Firma Adı</label>
                        <input type="text" class="w-full p-2 border rounded" name="invoice[corporate_name]"
                            placeholder="Firma Adı"><br>
                        <label class="block">Vergi Numarası</label>
                        <input type="text" class="w-full p-2 border rounded" name="invoice[tax_number]"
                            placeholder="Vergi NO"><br>
                        <label class="block">Vergi Dairesi</label>
                        <input type="text" class="w-full p-2 border rounded" name="invoice[tax_office]"
                            placeholder="Vergi Dairesi">
                    </div>
                </div>
            </section>

            <!-- Ödeme Bilgileri -->
            <section class="box">
                <h3>💳 Ödeme Bilgileri</h3>
                <p><strong>Kart Bilgileri</strong></p>
                <div class="form-row">
                    <input type="text" name="pan" placeholder="0000-1111-2222-3333">
                    <input type="text" name="Ecom_Payment_Card_ExpDate_Month" placeholder="Son Kullanım Ayı">
                    <input type="text" name="Ecom_Payment_Card_ExpDate_Year" placeholder="Son Kullanım Yılı">
                    <input type="text" name="cvv" placeholder="CVV">
                    <select name="cardType">
                        <option value="1">Visa</option>
                        <option value="2">MasterCard</option>
                    </select>
                </div>
            </section>

            <section class="submit-section">
                <input type="hidden" name="data_id" value="{{ $data['data']['id'] }}">
                <input type="hidden" name="flights[]" value="{{ $data['data']['flightList']['departure']['id'] }}">
                @if(!empty($data['data']['flightList']['return']))
                    <input type="hidden" name="flights[]" value="{{ $data['data']['flightList']['return']['id'] }}">
                @endif
                <button type="submit">Rezervasyon Ödemesini Yap</button>
            </section>
        </form>
    </div>


    <script>
        function toggleTcInput() {
            const checkbox = document.getElementById('foreignCheckbox');
            const tcInput = document.getElementById('tcNo');

            if (checkbox.checked) {
                tcInput.value = ''; // varsa içeriği temizle
                tcInput.disabled = true;
                tcInput.style.backgroundColor = '#eee'; // gri arka plan opsiyonel
            } else {
                tcInput.disabled = false;
                tcInput.style.backgroundColor = ''; // eski haline döndür
            }
        }

        document.addEventListener("DOMContentLoaded", function () {
            let toggleText = document.getElementById("toggleText");
            let faturaBilgileri = document.getElementById("faturaBilgileri");
            let bireyselForm = document.getElementById("bireyselForm");
            let kurumsalForm = document.getElementById("kurumsalForm");
            let radioButtons = document.getElementsByName("fatura_tipi");

            // Sayfa açıldığında sadece bireysel form açık olsun
            bireyselForm.style.display = "block";
            kurumsalForm.style.display = "none";

            toggleText.addEventListener("click", function () {
                let isHidden = getComputedStyle(faturaBilgileri).display === "none";

                if (isHidden) {
                    faturaBilgileri.style.display = "block";
                    toggleText.textContent = "Fatura Bilgilerimi Girmek İstemiyorum";
                } else {
                    faturaBilgileri.style.display = "none";
                    toggleText.textContent = "Fatura Bilgilerimi Girmek İstiyorum";
                }
            });

            // Radio butonlarına tıklanınca formu değiştir
            radioButtons.forEach(radio => {
                radio.addEventListener("change", function () {
                    if (this.value === "bireysel") {
                        bireyselForm.style.display = "block";
                        kurumsalForm.style.display = "none";
                    } else {
                        bireyselForm.style.display = "none";
                        kurumsalForm.style.display = "block";
                    }
                });
            });
        });
    </script>
@endsection