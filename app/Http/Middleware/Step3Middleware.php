<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Step3Middleware
{

public function handle($request, Closure $next)
{
    if (!session()->has('step2')) {
        return redirect()->route('register.step2')->with('error', 'Veuillez compléter l’étape 2.');
    }

    return $next($request);
}

}
