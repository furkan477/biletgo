<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\TicketOrder;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function index(){
        return view('site.pages.index');
    }

    public function tickets(){
        $tickets = TicketOrder::where('user_id','1')->orderBy('created_at','desc')->get();
        return view('site.pages.tickets',compact('tickets'));
    }
}