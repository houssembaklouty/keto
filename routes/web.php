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

Route::redirect('/', '/en');
Route::redirect('/home', '/admin/order');

// Route::get('/fr', function () { return view('fr_welcome'); });
// Route::get('/en', function () { return view('en_welcome'); });
Route::get('/fr', 'WelcomeController@fr_welcome')->name('fr.welcome.page');
Route::get('/en', 'WelcomeController@en_welcome')->name('en.welcome.page');
Route::get('/fr/checkout', 'PaypalController@index_fr')->name('fr.checkout.page');
Route::get('/en/checkout', 'PaypalController@index_en')->name('en.checkout.page');
Route::get('/fr/Return-Refund-Policy', function () { return view('pages/Return-Refund-Policy'); });
Route::get('/fr/privacy-policy', function () { return view('pages/privacy-policy'); });
Route::get('/fr/Terms-Conditions', function () { return view('pages/terms'); });
Route::get('/pay-failed', function () { return view('pages/pay-failed'); });

Auth::routes(['register' => false]);

Route::get('/fr/order/{id}', 'OrderController@showOrderPageFr')->name('fr.order.page');
Route::get('/en/order/{id}', 'OrderController@showOrderPageEn')->name('en.order.page');
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/admin/order', 'HomeController@index')->name('orders.index');

Route::get('orders/list', 'HomeController@datatable')->name('orders.list');
Route::get('orders/show', 'HomeController@orderShowPaypal')->name('orders.show');

Route::post('/checkout', 'StripeController@storePay')->name('pay.checkout');
// Route::get('/test', function () { return view('paytest'); });
// Route::get('/checkout.html', 'StripeController@checkout_page')->name('checkout.page');
// Route::delete('cancel', 'StripeController@cancelSubscription')->name('cancel.subscription');