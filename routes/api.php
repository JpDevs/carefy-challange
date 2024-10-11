<?php

use App\Http\Controllers\Api\Census\CensusController;
use App\Http\Controllers\Api\Internments\InternmentsController;
use App\Http\Controllers\Api\Patients\PatientsController;
use App\Http\Controllers\Api\Drafts\DraftsController;
use App\Http\Controllers\Api\Statistics\StatisticsController;
use Illuminate\Support\Facades\Route;

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


Route::resource('/patients', PatientsController::class)->except(['create', 'edit'])->names(['index' => 'patientsApi.index', 'store' => 'patientsApi.store', 'show' => 'patientsApi.show', 'update' => 'patientsApi.update', 'destroy' => 'patientsApi.destroy']);
Route::resource('/internments', InternmentsController::class)->except(['create', 'edit'])->names(['index' => 'internments.index', 'store' => 'internments.store', 'show' => 'internments.show', 'update' => 'internments.update', 'destroy' => 'internments.destroy']);
Route::resource('/drafts', DraftsController::class)->names(['index' => 'drafts', 'store' => 'drafts.store', 'show' => 'drafts.show', 'update' => 'drafts.update', 'destroy' => 'drafts.destroy']);

Route::get('/patients/{id}/internments', [PatientsController::class, 'getInternments'])->name('patients.internments');
Route::get('/statistics', [StatisticsController::class, 'getStatistics'])->name('statistics');


Route::post('/census/upload', [CensusController::class, 'uploadFile'])->name('census.upload');
Route::post('/drafts/{id}/publish', [DraftsController::class, 'publish'])->name('drafts.publish');
Route::post('/drafts/publish', [DraftsController::class, 'publishAll'])->name('drafts.publishAll');

