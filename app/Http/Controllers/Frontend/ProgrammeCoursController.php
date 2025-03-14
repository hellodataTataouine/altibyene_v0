<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProgrammeCoursController extends Controller
{
    public function index()
    {
        return view('programme-cours');
    }
}
