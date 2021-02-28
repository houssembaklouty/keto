<?php

namespace App\Http\Controllers;

use Stevebauman\Location\Facades\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Mail;
use App\Mail\NewCommande;
use App\Mail\NewCommandeForClient;
use App\Transaction;
use App\Commande;
use App\User;

use Exception;

class PaypalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index_fr()
    {
        $location = Location::get( \Request::ip() );

        if (!$location) { $location = 'FR'; } 
        else { $location = $location->countryCode; }

        return view('checkout_paypal_fr', compact('location'));
    }

    public function index_en()
    {
        $location = Location::get( \Request::ip() );

        if (!$location) { $location = 'FR'; } 
        else { $location = $location->countryCode; }

        return view('checkout_paypal_en', compact('location'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    public function paypalCapturePayment(Request $request) {

        $createTransactionRes = $this->createTransaction($request);

        return $createTransactionRes;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function saveCommande(Request $request)
    {
        // return $request->all();

        $this->validate($request, [
            'user_shipping_fname' => 'required',
            'user_shipping_lname' => 'required',
            'country' => 'required',
        ]);

        $product = $request->products[0];
        
        if($product == '79') { $product = 'Pack 1'; $amountValue = 49; }
        if($product == '81') { $product = 'Pack 2'; $amountValue = 79; }
        if($product == '83') { $product = 'Pack 3'; $amountValue = 110; }
        if($product == '84') { $product = 'Pack 4'; $amountValue = 130; }
        if($product == '86') { $product = 'Pack 6'; $amountValue = 180; }


        $commandeRes = $this->createCommande($request, $product, 1);

        $orderRef = $commandeRes->ref;

        return response()->json([
            'data' => 'Nous avons bien reçu votre commande.',
            'amountValue' => $amountValue,
            'orderRef' => $orderRef,
        ], 200);

    }

    
    private function createCommande($request, $product, $userId) {

        $commande = Commande::create([
            'address' => $request->address,
            'country' => $request->country,
            'email_address' => $request->email_address,
            'first_name' => $request->user_shipping_fname,
            'last_name' => $request->user_shipping_lname,
            'termes_conditions' => $request->termes_conditions == 'on' ? true : false ,
            'phone_number' => $request->phone_number,
            'products' => $product,
            'city' => $request->state ? $request->state : ' ' ,
            'user_id' => $userId,
            'zip_codel' => $request->zip_code,
            'paiement' => $request->paiement,
            'ref' => uniqid(),
        ]);

        return $commande;
    }
    
    private function createTransaction($request) {

        // return $request->all();

        $transaction = Transaction::create([
            'ref' => $request->orderRef,
            'amount' => $request->amountValue,
            'state' => $request->status,
            'country' => $request->country ? $request->country : ' ',
            'currency' => $request->currency ? $request->currency : ' ',
            'details' => json_encode($request->details, true),
        ]);

        return $transaction;
    }

    private function sendEmailToAdmin($commandeRes) {

        $mailable = new NewCommande(
            $commandeRes->address, 
            $commandeRes->country, 
            $commandeRes->email_address, 
            $commandeRes->first_name, 
            $commandeRes->last_name, 
            $commandeRes->phone_number, 
            $commandeRes->products, 
            $commandeRes->state,
            $commandeRes->zip_codel
        );

        try {
            $email = config('app.admin_email');
            Mail::to($email)
                // ->cc($user->email)
                ->send($mailable); // send

        } catch (\Exception $e) {
            \Log::info($e);
            // return $e;
                
            return response()->json([
                'data' => "Une erreur s'est produite lors de l'envoi de la commande.", 404
            ]);

            // return response()->json(['data' => "Une erreur s'est produite lors de l'envoi de la commande.", 404]);
        }

    }

    private function sendEmailClient($commandeRes) {

        $mailable = new NewCommandeForClient($commandeRes->ref);

        try {
            Mail::to($commandeRes->email_address)
                ->send($mailable); // send

        } catch (\Exception $e) {
            \Log::info($e);
                
            return response()->json([
                'data' => "Une erreur s'est produite lors de l'envoi de la commande.", 404
            ]);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
