<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Mail\NewCommande;
use App\Transaction;
use App\Commande;
use App\User;

use Exception;
use Stripe;
use Session;

use Carbon\Carbon;

class StripeController extends Controller
{

    public function checkout_page() {
        
        $random = Str::uuid();

        $user = User::create([
            'name' => $random,
            'email' => $random.'@mail.com',
            'password' => bcrypt($random),
        ]);
        
        $data = [
            'access_token' => $user->name,
            'intent' => $user->createSetupIntent()
        ];

        return view('checkout')->with($data);
    }

    public function storePay(Request $request) {

        // return $request->all();
        
        $this->validate($request, [
            'token' => 'required',
            'user_shipping_fname' => 'required',
            'user_shipping_lname' => 'required',
            'country' => 'required',
            'access_token' => 'required',
        ]);

        $product = $request->products[0];

        $planStripe_id = '';

        if($product == '79') { $product = 'Pack 1';  $planStripe_id = 'price_1IJETqEzLd74QBPdhCf3cOxy'; }
        if($product == '81') { $product = 'Pack 2';  $planStripe_id = 'price_1IJHdFEzLd74QBPd531159Rs'; }
        if($product == '83') { $product = 'Pack 3';  $planStripe_id = 'price_1IJHdFEzLd74QBPd3NjyyJCL'; }
        if($product == '84') { $product = 'Pack 4';  $planStripe_id = 'price_1IJHdFEzLd74QBPdEbQuOgd7'; }
        if($product == '86') { $product = 'Pack 6';  $planStripe_id = 'price_1IJHdFEzLd74QBPdgu9N0bon'; }

        /*

        if($product == '79') { $product = 'Pack 1';  $planStripe_id = 'price_1IJETqEzLd74QBPdhCf3cOxy'; }
        if($product == '81') { $product = 'Pack 2';  $planStripe_id = 'price_1IJEUIEzLd74QBPdrjEVOIKo'; }
        if($product == '83') { $product = 'Pack 3';  $planStripe_id = 'price_1IJEUjEzLd74QBPdey38mhpu'; }
        if($product == '84') { $product = 'Pack 4';  $planStripe_id = 'price_1IJEVHEzLd74QBPdxBXrKVmU'; }
        if($product == '86') { $product = 'Pack 6';  $planStripe_id = 'price_1IJEVxEzLd74QBPdH7eN6izi'; }

        */

        
        $random = Str::uuid();
        
        $user = User::where('name', $request->access_token)->first();

        $commandeRes = $this->createCommande($request, $product, $user->id);

        $orderLink = config('app.url').'/order/'.$commandeRes->ref;
        
        $user->update([
            'name' => $request->user_shipping_fname,
            'lastname' => $request->user_shipping_lname,
            'country' => $request->country,
            'zip_code' => $request->zip_code,
            'email' => $request->email_address ?? $random.'@mail.com',
            'password' => bcrypt($random)
        ]);


        try {
            $user->newSubscription($product, $planStripe_id)->create($request->token, 
                [
                    'email'         => $user->email,
                    'description'   => 'TEQST'
                ] 
            );

        } catch (\Exception $e) {
            return back()->withErrors(['message' => 'Error creating subscription. ' . $e->getMessage()]);
        }
        
        // $user->ends_at = Carbon::now()->addMonths(1);

        return response()->json([
            'data' => 'Nous avons bien reçu votre commande.', 'redirect_link' => $orderLink, 200
        ]);
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
            'city' => $request->state,
            'user_id' => $userId,
            'zip_codel' => $request->zip_code,
            'paiement' => $request->paiement,
            'ref' => uniqid(),
        ]);

        return $commande;
    }

    public function cancelSubscription(Request $request) {

        $commande = Commande::where('ref', $request->ref)->with(['user.subscriptions'])->first();

        $subscription_id = $commande->user->subscriptions->first()->stripe_id;

        $stripe = Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        
        $sub = \Stripe\Subscription::retrieve($subscription_id);
        $sub->cancel();
        
        return back();
    }

}
