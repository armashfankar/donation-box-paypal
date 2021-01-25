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

Route::get("/", "DonationController@index")->name("home");
Route::post("/", "DonationController@checkout");
Route::get("/callback", "DonationController@callback")->name("paypal.callback");
Route::get("/thankyou/{id}", "DonationController@thankyou")->name("thankyou");
