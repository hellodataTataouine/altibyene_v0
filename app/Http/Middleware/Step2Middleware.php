<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Step2Middleware
{

public function handle($request, Closure $next)
{
    if (!session()->has('step1')) {
        return redirect()->route('register.step1')->with('error', 'Veuillez compléter l’étape 1.');
    }

    return $next($request);
}
}
