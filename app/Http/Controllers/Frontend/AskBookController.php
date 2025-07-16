<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\BookRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Modules\Frontend\app\Models\ContactSection;
use App\Traits\MailSenderTrait;

class AskBookController extends Controller
{
    //
    public function index()
    {
        $contact = ContactSection::first();
        return view('frontend.pages.ask-for-book',compact('contact'));
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:50',
            'message' => 'required|string|max:1000',
        ]);

        $bookRequest =BookRequest::create($validated);
        MailSenderTrait::setMailConfig();
        Mail::raw("
            Nouvelle demande de livre reçue :
            Nom : {$bookRequest->name}
            Email : {$bookRequest->email}
            Téléphone : {$bookRequest->phone}
            Message : {$bookRequest->message}
        ", function ($message) {
            $message->to(env('MAIL_FROM_ADDRESS'))
                    ->subject('Nouvelle demande de livre');
        });
        return response()->json([
            'status' => 'success',
            'message' => 'Votre demande a bien été envoyée.',
            'redirect' => route('home') // ou null si tu ne veux pas rediriger
        ]);
    }
}
