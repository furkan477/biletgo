<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Http;
use Illuminate\Http\Request;

class AutoCompleteController extends Controller
{
    public function flightTicket(Request $request){
        $data = Http::get('https://biletbayisi.com/autocomplete?term='.$request->term);
        return $data->json();
    }
}
