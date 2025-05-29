@extends('site.layout.layout')
@section('content')
<style>
button{
	width: 300px;
	height: 50px;
	border: 0;
	border-radius: 15px;
	background-color: #1bbf33;
	font-family: raleway;
	font-weight: 700;
	font-size: 1em;
	color: white;
	box-shadow: 0 5px lightblue;
	cursor: pointer;
    color: black;
}
button:active{
	box-shadow: 0 0px green, inset 0px 0px 30px rgba(0,0,0,.1);
	transform: translate(0px, 5px);
}
</style>
    <div class="container mt-5">
        <form action="{{ route('flight.ticket.select') }}" method="get">
            <div class="row">
                @if(!empty($data['data']['flightList']['return']))
                    <div class="col-md-6">
                @else
                    <div class="col-md-12">
                @endif
                        <h2 class="mb-3 text-center">Gidiş Uçuşları </h2>
                        <p>{{ count($data['data']['flightList']['departure']) }} Uçuş bulunmaktadır.</p>
                        <div class="row">
                            @foreach ($data['data']['flightList']['departure'] as $departure)
                                <div class="col-md-12">
                                    <div class="flight-card">
                                        <div class="d-flex justify-content-between align-items-center">                                  
                                            <div>
                                                @if(count($departure['segments']) > 0)
                                                @foreach($departure['segments'] as $segment)
                                                    @php
                                                        $departureDate = \Carbon\Carbon::parse($segment['departureDatetime'])->translatedFormat('d F Y H:i');
                                                        $arrivalDate = \Carbon\Carbon::parse($segment['arrivalDatetime'])->translatedFormat('d F Y H:i')
                                                    @endphp

                                                    <div style="display: flex; justify-content: space-between;">
                                                        <h5>{{ $data['data']['airlineList'][$segment['airline']]['name'] }} </h5>
                                                        <h5><strong>{{ count($departure['segments']) > 1 ? '(Aktarmalı Uçuş)' : '' }}</strong></h5>
                                                    </div>
                                                    <p><strong>{{ $data['data']['airportList'][$segment['departureAirport']]['city_name'].' ('.$data['data']['airportList'][$segment['departureAirport']]['code'].')' }}</strong>
                                                    → <strong>{{ $data['data']['airportList'][$segment['arrivalAirport']]['city_name'].' ('.$data['data']['airportList'][$segment['arrivalAirport']]['code'].')' }}</strong></p>
                                                    <p><i class="fas fa-clock"></i> Kalkış: {{ \Carbon\Carbon::parse($segment['departureDatetime'])->translatedFormat('H:i') }} | <i class="fas fa-plane-arrival"></i>
                                                    Varış:  {{ \Carbon\Carbon::parse($segment['arrivalDatetime'])->translatedFormat('H:i') }}</p>

                                                    <p>{{ $data['data']['airlineList'][$segment['airline']]['name'] }} Uçuşu: {{ $segment['flightNumber'] }}<br>
                                                    Sınıf: {{ $segment['class'] }}</p>
                                                    <p><strong>{{ $departure['viewBaggage']['quantity'] . ' ' . $departure['viewBaggage']['unit'].' Bagaj Hakkı' }}</strong></p>
                                                    <p><strong>Kalkış: </strong>{{$departureDate.', '.$data['data']['airportList'][$segment['departureAirport']]['name'].', '.$data['data']['airportList'][$segment['departureAirport']]['city_name']}}</p>
                                                    <p><strong>Varış: </strong>{{$arrivalDate.', '.$data['data']['airportList'][$segment['arrivalAirport']]['name'].', '.$data['data']['airportList'][$segment['arrivalAirport']]['city_name']}}</p>
                                                    <p> <strong>NOT : </strong>{{ \Carbon\Carbon::parse($segment['departureDatetime'])->translatedFormat('H:i').' saatinde kalkıcak olan uçuşunuz '.$departure['duration']['day'].' gün '.$departure['duration']['hours'].' saat '.$departure['duration']['minutes'].' dakika sürücektir ve '.\Carbon\Carbon::parse($segment['arrivalDatetime'])->translatedFormat('H:i').' saatinde iniş yapıcaktır.' }}</p>
                                                @endforeach
                                                @endif
                                            </div>                                        
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center mt-3">
                                            <span class="text-success fw-bold fs-5">{{ $departure['viewPrice'] }} ₺</span>
                                            <div style="background-color: green; width: 140px; height: 40px; display: flex; justify-content: center; align-items: center;">
                                                <span style="color: white;" >Uçuş Seçiniz <input type="radio" name="flights[0]" value="{{ $departure['id'] }}"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @if(!empty($data['data']['flightList']['return']))
                        <div class="col-md-6">
                            <h2 class="mb-3 text-center">Dönüş Uçuşları</h2>
                            <p>{{ count($data['data']['flightList']['return']) }} Uçuş bulunmaktadır.</p>
                            <div class="row">
                            @foreach ($data['data']['flightList']['return'] as $return)
                                <div class="col-md-12">
                                    <div class="flight-card">
                                        <div class="d-flex justify-content-between align-items-center">                                  
                                            <div>
                                                @if(count($return['segments']) > 0)
                                                @foreach($return['segments'] as $segment)
                                                    @php
                                                        $departureDate = \Carbon\Carbon::parse($segment['departureDatetime'])->translatedFormat('d F Y H:i');
                                                        $arrivalDate = \Carbon\Carbon::parse($segment['arrivalDatetime'])->translatedFormat('d F Y H:i')
                                                    @endphp

                                                    <div style="display: flex; justify-content: space-between;">
                                                        <h5>{{ $data['data']['airlineList'][$segment['airline']]['name'] }} </h5>
                                                        <h5><strong>{{ count($return['segments']) > 1 ? '(Aktarmalı Uçuş)' : '' }}</strong></h5>
                                                    </div>
                                                    <p><strong>{{ $data['data']['airportList'][$segment['departureAirport']]['city_name'].' ('.$data['data']['airportList'][$segment['departureAirport']]['code'].')' }}</strong>
                                                    → <strong>{{ $data['data']['airportList'][$segment['arrivalAirport']]['city_name'].' ('.$data['data']['airportList'][$segment['arrivalAirport']]['code'].')' }}</strong></p>
                                                    <p><i class="fas fa-clock"></i> Kalkış: {{ \Carbon\Carbon::parse($segment['departureDatetime'])->translatedFormat('H:i') }} | <i class="fas fa-plane-arrival"></i>
                                                    Varış:  {{ \Carbon\Carbon::parse($segment['arrivalDatetime'])->translatedFormat('H:i') }}</p>

                                                    <p>{{ $data['data']['airlineList'][$segment['airline']]['name'] }} Uçuşu: {{ $segment['flightNumber'] }}<br>
                                                    Sınıf: {{ $segment['class'] }}</p>
                                                    <p><strong>{{ $return['viewBaggage']['quantity'] . ' ' . $return['viewBaggage']['unit'].' Bagaj Hakkı' }}</strong></p>
                                                    <p><strong>Kalkış: </strong>{{$departureDate.', '.$data['data']['airportList'][$segment['departureAirport']]['name'].', '.$data['data']['airportList'][$segment['departureAirport']]['city_name']}}</p>
                                                    <p><strong>Varış: </strong>{{$arrivalDate.', '.$data['data']['airportList'][$segment['arrivalAirport']]['name'].', '.$data['data']['airportList'][$segment['arrivalAirport']]['city_name']}}</p>
                                                    <p> <strong>NOT : </strong>{{ \Carbon\Carbon::parse($segment['departureDatetime'])->translatedFormat('H:i').' saatinde kalkıcak olan uçuşunuz '.$return['duration']['day'].' gün '.$return['duration']['hours'].' saat '.$return['duration']['minutes'].' dakika sürücektir ve '.\Carbon\Carbon::parse($segment['arrivalDatetime'])->translatedFormat('H:i').' saatinde iniş yapıcaktır.' }}</p>
                                                @endforeach
                                                @endif
                                            </div>                                        
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center mt-3">
                                            <span class="text-success fw-bold fs-5">{{ $return['viewPrice'] }} ₺</span>
                                            <div style="background-color: green; width: 140px; height: 40px; display: flex; justify-content: center; align-items: center;">
                                                <span style="color: white;" >Uçuş Seçiniz <input type="radio" name="flights[1]" value="{{ $return['id'] }}"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            </div>
                        </div>
                    @endif
                </div>
                <div class="col-md-12 mt-5 mb-5" style="display: flex; justify-content: center; align-items: center;">
                    <input type="hidden" name="id" value="{{ $data['data']['id'] }}">
                    <button type="submit" >Rezervasyon Oluştur</button>
                </div>
            </div>
        </form>
    </div>
@endsection