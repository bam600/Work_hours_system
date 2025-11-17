<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Staff;

class StaffListController extends Controller
{
    public function show(Request $request)
    {
        $stafflist = Staff::select('id', 'user_name', 'email')->get();

        return view('stafflist', compact('stafflist'));
    }
}