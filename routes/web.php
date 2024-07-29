<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\Staffcontroller;

Route::get('/', [HomeController::class, 'index'])->name('main.home');
Route::get('staff/data', [HomeController::class, 'staffdataTable'])->name('staff.data');
Route::get('staff/{id}', [HomeController::class, 'edit'])->name('get-staff-response');
Route::put('staff/{id}', [HomeController::class, 'update'])->name('update-staff-response');
Route::delete('staff/{id}', [HomeController::class, 'destroy'])->name('delete-staff-response');
Route::prefix('staff')->group(function () {

    Route::post('/store', [StaffController::class, 'store'])->name('staff.store');

});
