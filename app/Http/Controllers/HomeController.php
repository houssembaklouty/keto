<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Commande;
use DataTables;
use Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $commandes = Commande::with(['user', 'user.subscriptions'])->paginate(2);

        // dd($commandes);

        return view('home', compact('commandes'));
    }

    public function datatable(Request $request) {
        

        if (Auth::user()->type == 'admin' && $request->ajax()) {

            $data = Commande::latest()->get();

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $actionBtn = '<a href="javascript:void(0)" onClick="ShowModal(this)" data-id="'.$row['ref'].'" class="edit btn btn-success btn-sm">MORE</a>';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true)
            ;
        }

        return 'error';

    }

    public function orderShow(Request $request) {

        $commande = Commande::where('ref', $request->ref)->with(['user.subscriptions'])->first();

        
        foreach ($commande->user->subscriptions as $subscription) {
            $subscription = '
                <br>  <hr/>
                <div class="card-header">Product: '.$subscription->name.' */* Stripe Id: '.$subscription->stripe_id.'
                */* Stripe Status: '.$subscription->stripe_status.'
                */* Stripe Plan: '.$subscription->stripe_plan.'
                */* Quantity: '.$subscription->quantity.' 
            </div>';
        }
        
        return '
        <div class="card">
            <div class="card-header">
                <h3>Order No: '.$commande->ref.'</h3>

                <h6 class="float-right">Date: '.$commande->created_at.' </h6>
            </div>
            <div class="card-body">
                <h4 class="card-title">Fullname: '.$commande->full_name.'

                <span class="float-right">Email: '.$commande->email_address.'</span>
                
                </h4>

                <p class="card-text">
                    <br>
                    <div class="table-responsive-sm">
                        <table class="table table-responsive">
                            <thead>
                                <tr>
                                    <th scope="col">Phone</th>
                                    <th scope="col">products</th>
                                    <th scope="col">address</th>
                                    <th scope="col">city</th>
                                    <th scope="col">country</th>
                                    <th scope="col">zip_codel</th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr>
                                    <td>'.$commande->phone_number.'</td>
                                    <td>'.$commande->products.'</td>
                                    <td>'.$commande->address.'</td>
                                    <td>'.$commande->city.'</td>
                                    <td>'.$commande->country.'</td>
                                    <td>'.$commande->zip_codel.'</td>
                                </tr>
                    
                            </tbody>
                        </table>
                    </div>
                </p>

                '.$subscription.'
            </div>
        </div>';

    }
}
