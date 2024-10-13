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
Route::get('/patients/{id}/internments', [PatientsController::class, 'getInternments'])->name('patients.internments');
Route::get('/patients/getCode', [PatientsController::class, 'getCode'])->name('patients.getCode');


Route::get('/internments/trash', [InternmentsController::class, 'trash'])->name('internments.trash');
Route::post('/internments/trash/{id}/restore', [InternmentsController::class, 'restoreTrash'])->name('internments.trash.restore');
Route::delete('/internments/trash/clean', [InternmentsController::class, 'cleanTrash'])->name('internments.trash.clean');
Route::delete('/internments/trash/{id}', [InternmentsController::class, 'destroyTrash'])->name('internments.trash.destroy');
Route::resource('/internments', InternmentsController::class)->except(['create', 'edit'])->names(['index' => 'internmentsApi.index', 'store' => 'internmentsApi.store', 'show' => 'internmentsApi.show', 'update' => 'internmentsApi.update', 'destroy' => 'internmentsApi.destroy']);


Route::resource('/drafts', DraftsController::class)->names(['index' => 'drafts.index', 'store' => 'drafts.store', 'show' => 'drafts.show', 'update' => 'drafts.update', 'destroy' => 'drafts.destroy']);
Route::post('/drafts/{id}/publish', [DraftsController::class, 'publish'])->name('drafts.publish');
Route::post('/drafts/publish', [DraftsController::class, 'publishAll'])->name('drafts.publishAll');


Route::get('/statistics', [StatisticsController::class, 'getStatistics'])->name('statistics');
Route::post('/census/upload', [CensusController::class, 'uploadFile'])->name('census.upload');
Route::delete('/census/truncate', [CensusController::class, 'truncate'])->name('census.truncate');

