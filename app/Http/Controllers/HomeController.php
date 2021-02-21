<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Commande;
use App\Transaction;
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

            $deleteRoute = route('cancel.subscription');
            $deleteMethodField = method_field('DELETE');
            $csrfToken = csrf_field();

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row) use($deleteRoute, $deleteMethodField, $csrfToken){
                    $actionBtn = '<a href="javascript:void(0)" onClick="ShowModal(this)" data-id="'.$row['ref'].'" class="edit btn btn-sm btn-success"> <i class="fa fa-info-circle"></i> MORE</a>'.
                    '<form class="mt-1" method="POST" action="'.$deleteRoute.'?ref='.$row['ref'].'"> '.$deleteMethodField.' '.$csrfToken.' <div class="form-group"> <button type="submit" class="btn btn-sm btn-danger" onclick="return ConfirmDelete()""> <i class="fa fa-close"></i> Cancel </button> </div> </form>'
                    ;
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
                <h4 class="card-title">* Fullname: <b> '.$commande->full_name.' </b> 

                
                </h4>

                <h4 class="card-title float-right">* Email: <b> '.$commande->email_address.' </b> </h4>


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
                                    <th scope="col">termes</th>
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
                                    <td>'.$commande->termes.'</td>
                                </tr>
                    
                            </tbody>
                        </table>
                    </div>
                </p>

                '.$subscription.'
            </div>
        </div>';

    }

    public function orderShowPaypal(Request $request) {

        $commande = Commande::where('ref', $request->ref)->first();
        $transaction = Transaction::where('ref', $request->ref)->select('ref', 'amount', 'state')->get();

        return '
        <div class="card">
            <div class="card-header">
                <h3>Order No: '.$commande->ref.'</h3>

                <h6 class="float-right">Date: '.$commande->created_at.' </h6>
            </div>
            <div class="card-body">
                <h4 class="card-title">* Fullname: <b> '.$commande->full_name.' </b> 

                
                </h4>

                <h4 class="card-title float-right">* Email: <b> '.$commande->email_address.' </b> </h4>


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
                                    <th scope="col">termes</th>
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
                                    <td>'.$commande->termes.'</td>
                                </tr>
                    
                            </tbody>
                        </table>
                    </div>
                </p>

                <br>
                <hr>

                <b>Transaction : </b>'.$transaction.'
            </div>
        </div>';

    }
}
