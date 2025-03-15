<?php
namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HistoireController extends Controller
{
    public function index()
    {
        $images = ['image1.jpg', 'image2.jpg', 'image3.jpg'];
        return view('frontend.pages.histoire', compact('images'));
    }
}

