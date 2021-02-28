<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function fr_welcome(Request $request) {

        $request->session()->put('locale', 'fr');

        return view('fr_welcome');
    }

    public function en_welcome(Request $request) {
        
        $request->session()->put('locale', 'en');

        return view('en_welcome');
    }

}
