<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/', function () { return view('welcome'); });

Route::get('/checkout.html', 'StripeController@checkout_page')->name('checkout.page');

Route::get('/order/{id}', function () { return view('confirm_checkout');});

Auth::routes(['register' => false]);

Route::redirect('/home', '/admin/order');
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/admin/order', 'HomeController@index')->name('orders.index');


Route::get('orders/list', 'HomeController@datatable')->name('orders.list');
Route::get('orders/show', 'HomeController@orderShow')->name('orders.show');

Route::post('/checkout', 'StripeController@storePay')->name('pay.checkout');


Route::delete('cancel', 'StripeController@cancelSubscription')->name('cancel.subscription');

// Route::get('/stripe-payment', 'StripeController@handleGet');
// Route::post('/stripe-payment', 'StripeController@handlePost')->name('stripe.payment');

// Route::get('stripe', 'StripeController@stripe');
// Route::post('payment', 'StripeController@payStripe');
