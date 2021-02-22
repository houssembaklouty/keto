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


Route::get('/test', function () { return view('paytest'); });

Route::get('/checkout.html', 'PaypalController@index')->name('checkout.page');
// Route::get('/checkout.html', 'StripeController@checkout_page')->name('checkout.page');

Route::get('/order/{id}', function () { return view('confirm_checkout');});

Auth::routes(['register' => false]);

Route::redirect('/home', '/admin/order');
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/admin/order', 'HomeController@index')->name('orders.index');


Route::get('orders/list', 'HomeController@datatable')->name('orders.list');
Route::get('orders/show', 'HomeController@orderShowPaypal')->name('orders.show');

Route::post('/checkout', 'StripeController@storePay')->name('pay.checkout');


Route::delete('cancel', 'StripeController@cancelSubscription')->name('cancel.subscription');


Route::get('/fr/Return-Refund-Policy', function () { return view('pages/Return-Refund-Policy'); });
Route::get('/fr/privacy-policy', function () { return view('pages/privacy-policy'); });
Route::get('/fr/Terms-Conditions', function () { return view('pages/terms'); });
Route::get('/pay-failed', function () { return view('pages/pay-failed'); });