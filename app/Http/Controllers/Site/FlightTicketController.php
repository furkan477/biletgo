<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Http\Requests\FlightSearchRequest;
use App\Http\Requests\SelectFlightRequest;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

class FlightTicketController extends Controller
{
    public function flightSearch(FlightSearchRequest $request){
        $data = $request->validated();
        $info = [
            'origin' => $data['origin'],
            'destination' => $data['destination'],
            'departure_date' => $data['departure_date'],
            'return_date' => $data['return_date'] ?? null,
            'passengers' => [
                'ADT' => $data['passengers']['ADT'],
                'CHD' => $data['passengers']['CHD'],
                'INF' => $data['passengers']['INF'],
                'STU' => $data['passengers']['STU'],
                'YCD' => $data['passengers']['YCD'],
            ],
        ];
        $response = Http::accept('application/json')->withUserAgent('Test/Test')->post("https://staging.sorgulamax.com/api/flight-ticket/get-flights",$info);
        $data = json_decode($response->getBody(), true);

        return view('site.pages.flightstickets',compact('data'));
    }

    public function flightTicketSelect(SelectFlightRequest $request){
        $req = $request->validated();

        $info = [
            "flights" => $req['flights'],
            "id" => $req['id'],
        ];

        $response = Http::accept('application/json')->withUserAgent('Test/Test')->post("https://staging.sorgulamax.com/api/flight-ticket/select-flight", $info);
        $data = json_decode($response->getBody(), true);
        
        return view('site.pages.flightdetail',compact('data'));
    }
}
