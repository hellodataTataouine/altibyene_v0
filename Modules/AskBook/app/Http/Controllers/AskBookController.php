<?php

namespace Modules\AskBook\app\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\BookRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AskBookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $requests = BookRequest::orderBy('created_at', 'desc')->get();
        return view('askbook::index',compact('requests'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('askbook::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        $book_request = BookRequest::findOrFail($id);
        if($book_request){
            $book_request->is_readed=true;
            $book_request->save();
        }
        return view('askbook::show',compact('book_request'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('askbook::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }
}
