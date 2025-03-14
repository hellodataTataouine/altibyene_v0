<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function index()
    {
        return view('frontend.pages.actualite-evenement', compact('actualite-evenement'));
    }
}
