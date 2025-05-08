<?php
namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RegisteredUserController extends Controller
{
    public function stepOne()
    {
        return view('auth.register.step1');
    }

    public function postStepOne(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'gender' => 'required|string|max:255',
            'birthdate' => 'required|date',
            'birthplace' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'postal_code' => 'required|string|max:10',
            'city' => 'required|string|max:255',
            'phone_fix' => 'required|string|max:20',
            'phone' => 'required|string|max:20',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:6|max:100|confirmed',
        ], [
            'password.confirmed' => __('Password confirmation does not match'),
        ]);

        // Stocker les données dans la session pour les étapes suivantes
        session([
            'StepOne' => array_merge(session('StepOne', []), $validated)
        ]);
        return redirect()->route('register.step2');
    }
    public function stepTow(){
        return view('auth.register.step2');
    }
    public function postStepTow(Request $request)
    {
        $validated = $request->validate([
            'honor_statement' => 'accepted',
            'rules_acknowledgment' => 'accepted',
            'data_processing_acknowledgment' => 'accepted',
        ], [
            'honor_statement.accepted' => 'Vous devez attester sur l\'honneur l\'exactitude des renseignements fournis.',
            'rules_acknowledgment.accepted' => 'Vous devez reconnaître avoir pris connaissance du règlement intérieur.',
            'data_processing_acknowledgment.accepted' => 'Vous devez reconnaître que les informations recueillies font l\'objet d\'un traitement informatique.',
        ]);

        // Tu peux éventuellement stocker qu'on a coché ces cases
        session(['StepTow' => array_merge(session('StepTow', []), $validated)]);

        return redirect()->route('register.step3');
    }

    public function postStepThree(Request $request)
    {
        $validated = $request->validate([
            // Ici normalement tu peux valider d'autres données si besoin.
            // Exemple : 'payment_method' => 'required|string',
        ]);

        // Récupère toutes les données enregistrées dans les sessions des étapes précédentes
        $registerData = array_merge(session('StepOne'),session('StepTow'));
       
        // Crée l'utilisateur (ou autre enregistrement)
        $user = User::create([
            'name' => $registerData['first_name'] ?? '',
            'last_name' => $registerData['last_name'] ?? '',
            'gender' => $registerData['gender']=="M" ? 'male' : 'female',
            'birth_date' => $registerData['birthdate'] ?? null,
            'birth_place' => $registerData['birthplace'] ?? '',
            'address' => $registerData['address'] ?? '',
            'zip_code' => $registerData['postal_code'] ?? '',
            'city' => $registerData['city'] ?? '',
            'phone_fix' => $registerData['phone_fix'] ?? '',
            'phone' => $registerData['phone'] ?? '',
            'email' => $registerData['email'],
            'password' => Hash::make($registerData['password']),
            'honor_statement' => $registerData['honor_statement']=="on" ? 1 :0,
            'rules_acknowledgment' => $registerData['rules_acknowledgment']=="on" ? 1 :0,
            'data_processing_acknowledgment' => $registerData['data_processing_acknowledgment']=="on" ? 1 :0,
        ]);

        // Nettoie la session
        session()->forget('StepOne','StepTow');

        // Connecte automatiquement l'utilisateur (optionnel)
        Auth::login($user);

        // Redirige où tu veux (par exemple vers la page d'accueil)
        return redirect()->route('home')->with('success', 'Inscription réussie !');
    }
}







