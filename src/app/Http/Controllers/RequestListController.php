<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RequestListController extends Controller
{
    public function create()
    {
        return view('requestlist'); 
    }

}
