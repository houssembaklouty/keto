<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use App\Mail\NewCommande;
use App\Commande;
use App\Transaction;
use Exception;
use Stripe;
use Session;

class APIController extends Controller
{
    
    public function kito_store_checkout(Request $request)
    {

        return $request->all();

        $this->validate($request, [
            'card_number' => 'required',
            'card_month' => 'required',
            'card_year' => 'required',
            'cvv' => 'required',
        ]);
        
        $product = $request->products[0];

        $price = 50;

        if($product == '79') { $product = 'BANNERS 1'; $price = 60; }
        if($product == '81') { $product = 'BANNERS 2'; }
        if($product == '83') { $product = 'BANNERS 3'; }
        if($product == '84') { $product = 'BANNERS 4'; }
        if($product == '86') { $product = 'BANNERS 6'; }

        $Commande = Commande::create([
            'address' => $request->address,
            'country' => $request->country,
            'email_address' => $request->email_address,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone_number' => $request->phone_number,
            'products' => $product,
            'city' => $request->state,
            'zip_codel' => $request->zip_codel,
            'paiement' => $request->paiement,
            'ref' => uniqid(),
        ]);

        $stripeResponse = $this->stripePay($request, $Commande->ref, $price);

        // dd($stripeResponse);
        
        if ($stripeResponse['code'] != '0000') {

            return response()->json(['data' => $stripeResponse['message'], 404]);
        }

        $getLocale = request()->session()->get('locale');
        $getLocale = $getLocale == null ? 'en' : $getLocale; 

        $orderLink = config('app.url').$getLocale.'/order/'.$Commande->ref;

        $address = $request->address;
        $country = $request->country;
        $email_address = $request->email_address;
        $first_name = $request->first_name;
        $last_name = $request->last_name;
        $phone_number = $request->phone_number;
        $product = $product;
        $state = $request->state;
        $zip_codel = $request->zip_codel;
        
        $mailable = new NewCommande(
            $address, 
            $country, 
            $email_address, 
            $first_name, 
            $last_name, 
            $phone_number, 
            $product, 
            $state,
            $zip_codel
        );

        try {
            Mail::to('houssem.baklouty@gmail.com')
                // ->cc($user->email)
                ->send($mailable); // send

        } catch (\Exception $e) {
            \Log::info($e);
            // return $e;
            
            return response()->json(['data' => "Une erreur s'est produite lors de l'envoi de la commande.", 404]);
        }

        return response()->json([
            'data' => 'Nous avons bien reçu votre commande.', 'redirect_link' => $orderLink, 200
        ]);
    }


    private function stripePay(Request $request, $commandeRef, $price) {

        $stripe = Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        try {

            $response = \Stripe\Token::create(array(
                "card" => array(
                    "number"    => $request->input('card_number'),
                    "exp_month" => $request->input('card_month'),
                    "exp_year"  => $request->input('card_year'),
                    "cvc"       => $request->input('cvv')
                )));

            if (!isset($response['id'])) {
                
                return [
                    'code' => '0001',
                    'message' => 'Un problème est survenu.',
                ];
            }

            $charge = \Stripe\Charge::create([
                'card' => $response['id'],
                'currency' => 'USD',
                'amount' =>  $price * 100,
                'description' => $commandeRef,
            ]);

            $this->saveTransaction($charge);
            
            if($charge['status'] == 'succeeded') {

                return [
                    'code' => '0000',
                    'message' => 'Paiement réussi!',
                ];

            } else {

                return [
                    'code' => '0001',
                    'message' => 'Un problème est survenu.',
                ];
            }
 
        }

        catch (Exception $e) {
            
            return [
                'code' => '0001',
                'message' => $e->getMessage(),
            ];
        }

    }

    private function saveTransaction($charge) {

        $Transaction = Transaction::create([
            'ref' => $charge['id'],
            'amount' => $charge['amount'],
            'state' => $charge['status'],
            'country' => $charge['payment_method_details']['card']['country'],
            'currency' => $charge['currency'],
            'details' => json_encode($charge),
        ]);

    }
}
