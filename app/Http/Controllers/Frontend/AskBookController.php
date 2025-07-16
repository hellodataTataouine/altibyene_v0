<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\BookRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Modules\Frontend\app\Models\ContactSection;
use App\Traits\MailSenderTrait;
use Illuminate\Support\Facades\Cache;
use Modules\GlobalSetting\app\Models\Setting;

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
        if (Cache::has('setting')) {
            $email_setting = Cache::get('setting');
        } else {
            $setting_info = Setting::get();
            $setting = [];
            foreach ($setting_info as $setting_item) {
                $setting[$setting_item->key] = $setting_item->value;
            }
            $email_setting = (object) $setting;
        }
        Mail::raw("
            Nouvelle demande de livre reçue :
            Nom : {$bookRequest->name}
            Email : {$bookRequest->email}
            Téléphone : {$bookRequest->phone}
            Message : {$bookRequest->message}
        ", function ($message) use($email_setting) {
            $message->to($email_setting->mail_username)
                    ->subject('Nouvelle demande de livre');
        });
        return response()->json([
            'status' => 'success',
            'message' => 'Votre demande a bien été envoyée.',
            'redirect' => route('home') // ou null si tu ne veux pas rediriger
        ]);
    }
}
