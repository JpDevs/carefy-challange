<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Patients\PatientsController;
use App\Http\Controllers\Api\Internments\InternmentsController;
use App\Http\Controllers\Api\Census\CensusController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::resource('/patients', PatientsController::class)->except(['create', 'edit']);
Route::resource('/internments', InternmentsController::class)->except(['create', 'edit']);;


Route::group(['prefix' => 'census'], function () {
    Route::post('/upload', [CensusController::class, 'uploadFile']);
});
