<?php
namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PartenairesController extends Controller
{
    public function index()
    {
    
        $partenaires = [
            ['name' => 'Partenaire 1', 'logo' => 'partner1.png', 'description' => 'Description courte du partenaire 1.', 'website' => 'https://partenaire1.com'],
            ['name' => 'Partenaire 2', 'logo' => 'partner2.png', 'description' => 'Description courte du partenaire 2.', 'website' => 'https://partenaire2.com'],
            ['name' => 'Partenaire 3', 'logo' => 'partner3.png', 'description' => 'Description courte du partenaire 3.', 'website' => 'https://partenaire3.com'],
            ['name' => 'Partenaire 4', 'logo' => 'partner4.png', 'description' => 'Description courte du partenaire 4.', 'website' => 'https://partenaire4.com'],
        ];

        return view('frontend.pages.partenaires', compact('partenaires'));
    }
}





