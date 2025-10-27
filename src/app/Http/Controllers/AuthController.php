<?php

namespace App\Http\Controllers;

use app\Models\IsAdminUser;
use Illuminate\Http\Request;


class AuthController extends Controller
{
    public function attendance()
{  
    return view('attendance');
}
}
