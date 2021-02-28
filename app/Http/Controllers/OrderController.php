<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function showOrderPageFr() {

        return view('confirm_checkout_fr');

    }

    public function showOrderPageEn() {

        return view('confirm_checkout_en');

    }
}
