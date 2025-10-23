<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Property;

class SearchController extends Controller
{
    public function index(Request $request)
{
    $keyword = $request->input('search');

    $results = Property::where('name', 'like', "%{$keyword}%")->get();

    return view('search.results', compact('results', 'keyword'));
}
}
