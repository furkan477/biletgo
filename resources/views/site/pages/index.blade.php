@extends('site.layout.layout')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/smoothness/jquery-ui.css">
<script>
    $(function () {
        $(".autocomplete-input").autocomplete({
            // source: "{{ route('flight.ticket') }}",
            source: async function (request, response) {
                $.ajax({
                    url: "{{ route('flight.ticket') }}",
                    type: "GET",
                    dataType: "json",
                    data: {
                        term: request.term // Kullanıcının girdiği kelimeyi gönderiyoruz
                    },
                    success: function (autocompleteResponse) {
                        response($.map(autocompleteResponse.data, function (item) {
                            return {
                                label: item.city_name + ", " + item.name + " (" + item.code + "), " + item.country_name, // Görünen metin
                                value: item.code// Seçildiğinde inputa gelecek değer
                            };
                        }));
                    }
                });
            },
            minLength: 2,
        });
    });
</script>
@section('content')
    <header class="w3-display-container w3-content" style="max-width:1500px;">
        <img class="w3-image" src="{{asset('images/anaresim.jpg')}}" alt="The Hotel" style="min-width:1000px" width="1500"
            height="800">
        <div class="w3-display-left w3-padding w3-col l10 m8">
            <div class="w3-container w3-red">
                <h2><i class="fa-solid fa-plane-departure w3-margin-right"></i>Uçak Bileti</h2>
            </div>
            <div class="w3-container w3-white w3-padding-16">
                <div style="margin-bottom: 20px;" class="mb-5">
                    <label>
                        <input type="radio" name="trip_type" value="departure" checked> Gidiş
                    </label>
                    <label>
                        <input type="radio" name="trip_type" value="return"> Dönüş Ekle
                    </label>
                </div>
                <form action="{{ route('flight.search') }}" method="get">
                    <div class="w3-row-padding" style="margin:0 -16px;">
                        <div class="w3-half w3-margin-bottom">
                            <label><i class="fa-solid fa-plane"></i> Nereden (Kalkış Havalimanı)</label>
                            <input class="autocomplete-input w3-input w3-border" type="text" name="origin"
                                value="{{ old('origin') }}">
                        </div>
                        <div class="w3-half w3-margin-bottom">
                            <label><i class="fa-solid fa-plane"></i> Nereye (İniş Havalimanı)</label>
                            <input class="autocomplete-input w3-input w3-border" type="text"
                                value="{{ old('destination') }}" name="destination">
                        </div>
                    </div>
                    <div class="w3-row-padding" style="margin:0 -16px;">
                        <div id="departure-date-container" class="w3-half w3-margin-bottom">
                            <label><i class="fa-solid fa-plane"></i> Gidiş Tarihi</label>
                            <input class="w3-input w3-border" type="date" min="{{ now()->toDateString() }}"
                                value="{{ old('departure_date') }}" name="departure_date">
                        </div>
                        <div id="return-date-container" class="w3-half w3-margin-bottom">
                            <label><i class="fa-solid fa-plane"></i> Dönüş Tarihi</label>
                            <input id="return-date" class="w3-input w3-border" type="date" value="{{ old('return_date') }}"
                                name="return_date">
                        </div>
                    </div>
                    <div class="w3-row-padding" style="margin:8px -16px; display: flex;">
                        <div class="w3-half w3-margin-bottom">
                            <label><i class="fa fa-male"></i> Yetişkin</label>
                            <div class="select">
                                <select name="passengers[ADT]">
                                    <option value="0">0</option>
                                    <option selected value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="3">4</option>
                                    <option value="3">5</option>
                                </select>
                            </div>
                        </div>
                        <div class="w3-half w3-margin-bottom">
                            <label><i class="fa-solid fa-child"></i> Çocuk</label>
                            <div class="select">
                                <select name="passengers[CHD]">
                                    <option value="0">0</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="3">4</option>
                                    <option value="3">5</option>
                                </select>
                            </div>
                        </div>
                        <div class="w3-half w3-margin-bottom">
                            <label><i class="fa-solid fa-baby"></i> Bebek</label>
                            <div class="select">
                                <select name="passengers[INF]">
                                    <option value="0">0</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="3">4</option>
                                    <option value="3">5</option>
                                </select>
                            </div>
                        </div>
                        <div class="w3-half w3-margin-bottom">
                            <label><i class="fa-solid fa-person-walking-with-cane"></i> 65 Yaş Üstü</label>
                            <div class="select">
                                <select name="passengers[YCD]">
                                    <option value="0">0</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="3">4</option>
                                    <option value="3">5</option>
                                </select>
                            </div>
                        </div>
                        <div class="w3-half w3-margin-bottom">
                            <label><i class="fa-solid fa-graduation-cap"></i> Öğrenci</label>
                            <div class="select">
                                <select name="passengers[STU]">
                                    <option value="0">0</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="3">4</option>
                                    <option value="3">5</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <button style="margin-top: 30px;" style="background-color: green;" class="w3-button w3-red"
                        type="submit"><i class="fa fa-search w3-margin-right"></i>
                        Bilet Go</button>
                </form>
            </div>
        </div>
    </header>

    <!-- Page content -->
    <div class="w3-content" style="max-width:1532px;">

        <div class="w3-container">
            <h3>En Popüler Şehirler</h3>
            <h6>(Arama motorundan session ile en çok aranan şehirleri alıp pushluyacağız )</h6>
        </div>

        <div class="w3-row-padding w3-padding-16 w3-text-white w3-large">
            <div class="w3-half w3-margin-bottom">
                <div class="w3-display-container">
                    <img src="https://www.w3schools.com/w3images/cinqueterre.jpg" alt="Cinque Terre" style="width:100%">
                    <span class="w3-display-bottomleft w3-padding">Cinque Terre</span>
                </div>
            </div>
            <div class="w3-half">
                <div class="w3-row-padding" style="margin:0 -16px">
                    <div class="w3-half w3-margin-bottom">
                        <div class="w3-display-container">
                            <img src="https://www.w3schools.com/w3images/newyork2.jpg" alt="New York" style="width:100%">
                            <span class="w3-display-bottomleft w3-padding">New York</span>
                        </div>
                    </div>
                    <div class="w3-half w3-margin-bottom">
                        <div class="w3-display-container">
                            <img src="https://www.w3schools.com/w3images/pisa.jpg" alt="San Francisco" style="width:100%">
                            <span class="w3-display-bottomleft w3-padding">San Francisco</span>
                        </div>
                    </div>
                </div>
                <div class="w3-row-padding" style="margin:0 -16px">
                    <div class="w3-half w3-margin-bottom">
                        <div class="w3-display-container">
                            <img src="https://www.w3schools.com/w3images/sanfran.jpg" alt="Pisa" style="width:100%">
                            <span class="w3-display-bottomleft w3-padding">Pisa</span>
                        </div>
                    </div>
                    <div class="w3-half w3-margin-bottom">
                        <div class="w3-display-container">
                            <img src="https://www.w3schools.com/w3images/paris.jpg" alt="Paris" style="width:100%">
                            <span class="w3-display-bottomleft w3-padding">Paris</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

<script>
    document.addEventListener("DOMContentLoaded", function () {
        // İlk başta sadece gidiş tarihini göster
        const departureRadio = document.querySelector('input[value="departure"]');
        const roundTripRadio = document.querySelector('input[value="return"]');
        const departureDateContainer = document.getElementById("departure-date-container");
        const returnDateContainer = document.getElementById("return-date-container");
        const returnDateInput = document.getElementById("return-date");

        // Gidiş veya dönüş radio butonları değiştiğinde çalışacak fonksiyon
        function updateForm() {
            if (roundTripRadio.checked) {
                returnDateContainer.style.display = "block"; // Dönüş tarihi alanını göster
                returnDateInput.setAttribute("required", "required"); // Dönüş tarihi zorunlu
            } else {
                returnDateContainer.style.display = "none"; // Dönüş tarihi alanını gizle
                returnDateInput.removeAttribute("required"); // Dönüş tarihi zorunlu değil
            }
        }

        // Sayfa yüklendiğinde, varsayılan olarak sadece gidiş tarihi alanını gösterecek şekilde ayarla
        updateForm();

        // Radio butonları değiştirildiğinde formu güncelle
        departureRadio.addEventListener("change", updateForm);
        roundTripRadio.addEventListener("change", updateForm);
    });
</script>