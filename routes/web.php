<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/', function () {
    return view('system.index');
});

Route::group(['prefix' => 'patients'], function () {
    Route::get('/', function () {
        return view('system.patients.index');
    })->name('patients.index');

    Route::get('/{id}', function ($id) {
        return view('system.patients.show', compact('id'));
    })->name('patients.show');

    Route::get('/{id}/edit', function ($id) {
        return view('system.patients.edit', compact('id'));
    })->name('patients.edit');
})->name('patients');
