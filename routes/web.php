<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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
    if(auth()){
        return redirect('/home');
    } else{
        return redirect('/login');
    }
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/item', [App\Http\Controllers\ItemsController::class, 'index'])->name('item.home');
Route::get('/order', [App\Http\Controllers\OrdersController::class, 'index'])->name('order.home');
Route::get('/setting', [App\Http\Controllers\SettingsController::class, 'index'])->name('setting.home');
