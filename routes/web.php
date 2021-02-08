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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/checkout.html', 'StripeController@checkout_page')->name('checkout.page');

Route::get('/order/{id}', function () {
    return view('confirm_checkout');
});

Auth::routes(['register' => false]);

Route::get('/home', 'HomeController@index')->name('home');


Route::get('orders/list', 'HomeController@datatable')->name('orders.list');
Route::get('orders/show', 'HomeController@orderShow')->name('orders.show');

Route::get('/stripe-payment', 'StripeController@handleGet');
Route::post('/stripe-payment', 'StripeController@handlePost')->name('stripe.payment');


Route::post('/checkout', 'StripeController@storePay')->name('pay.checkout');

Route::get('stripe', 'StripeController@stripe');
Route::post('payment', 'StripeController@payStripe');
