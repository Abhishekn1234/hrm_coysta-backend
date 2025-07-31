<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\HrmController;
use App\Http\Controllers\CustomerController;
Route::prefix('admin/api/v1')->group(function () {
    Route::get('/staff/counts', [HrmController::class, 'getCounts']);
    Route::get('/staff/monthly', [HrmController::class, 'getMonthlyStaffCounts']);
});


Route::prefix('v1')->group(function () {
    Route::apiResource('customer', CustomerController::class);
    Route::get('customer-count', [CustomerController::class, 'count']);
    Route::post('customers', [CustomerController::class, 'store']);
    Route::get('customer-count-business', [CustomerController::class, 'countBusinessCustomers']);
    Route::get('customer-count-individual', [CustomerController::class, 'countIndividualCustomers']);
    Route::get('customer-count-per-month', [CustomerController::class, 'countPerMonth']);
});
