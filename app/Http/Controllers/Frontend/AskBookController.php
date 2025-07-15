<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\BookRequest;
use Illuminate\Http\Request;

class AskBookController extends Controller
{
    //
    public function index()
    {
        return view('frontend.pages.ask-for-book');
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:50',
            'message' => 'required|string|max:1000',
        ]);

        BookRequest::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Votre demande a bien été envoyée.',
            'redirect' => route('home') // ou null si tu ne veux pas rediriger
        ]);
    }
}
