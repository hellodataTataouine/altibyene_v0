<?php

namespace App\Http\Controllers;

use App\Models\CoursSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CoursSessionController extends Controller
{
    //
    public function show($id){
        $sessions = CoursSession::where('cours_id',$id)->get();
        return view('frontend.pages.cours-session-select',compact('sessions','id'));
    }
    public function redirectToCheckout($id){
        Session::put('session_id',$id);
        return redirect()->route('checkout.index');
    }
}
