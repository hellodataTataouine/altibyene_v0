<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureRegistrationStep 
{
    public function handle(Request $request, Closure $next, $step)
    {
        $registerSession = session('register', []);

        // Protection selon l'étape demandée
        if ($step == 'step2') {
            if (empty($registerSession['first_name']) || empty($registerSession['last_name']) || empty($registerSession['email'])) {
                return redirect()->route('register.step1')->withErrors('Veuillez compléter la première étape.');
            }
        }

        if ($step == 'step3') {
            if (
                empty($registerSession['honor_statement']) ||
                empty($registerSession['rules_acknowledgment']) ||
                empty($registerSession['data_processing_acknowledgment'])
            ) {
                return redirect()->route('register.step2')->withErrors('Veuillez accepter les conditions avant de continuer.');
            }
        }

        return $next($request);
    }
}
