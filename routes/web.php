<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BreakdownReportController;
use App\Http\Controllers\BusController;
use App\Http\Controllers\HomeController;

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

// public
Route::view('/', 'landing')->name('landing');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

// all
Route::middleware('auth')->group(function () {
    Route::get('/home', [HomeController::class, 'index']);    
    Route::view('/profile', 'profile');

    Route::middleware('role:Admin & Operation')->group(function () {
        // admin & operation 
        Route::get('/admin-maintenance', [BreakdownReportController::class, 'adminMaintenance']);      

        Route::get('/admin-generate', function () {return view('admin-generate');});
        Route::get('/admin-generate/result', [BreakdownReportController::class, 'generateReport']);

        Route::get('/admin-records', [BreakdownReportController::class, 'adminRecords']);

        Route::get('/admin-request', [BreakdownReportController::class, 'adminRequests']);
        Route::post('/admin-request/{id}/approve', [BreakdownReportController::class, 'approve']);
        Route::post('/admin-request/{id}/reject', [BreakdownReportController::class, 'reject']);

        Route::get('/admin-busstatus', [BusController::class, 'adminBusStatus']);
        Route::put('/admin-busstatus/{id}/update', [BusController::class, 'updateBusStatus'])->name('admin-busstatus.update');
        Route::post('/admin-busstatus/add', [BusController::class, 'storeBus'])->name('admin-busstatus.store');
        Route::put('/admin-busstatus/{id}/edit-route', [BusController::class, 'updateBusRoute'])->name('admin-busstatus.update-route');
        Route::delete('/admin-busstatus/{id}/delete', [BusController::class, 'deleteBus'])->name('admin-busstatus.delete');

        Route::get('/admin-users', [UserController::class, 'index']);
        Route::post('/admin-users', [UserController::class, 'store'])->name('admin-users.store');
        Route::post('/admin-users/{id}/deactivate', [UserController::class, 'deactivate'])->name('admin-users.deactivate');
        Route::post('/admin-users/{id}/activate', [UserController::class, 'activate'])->name('admin-users.activate');
        Route::put('/admin-users/{id}', [UserController::class, 'update'])->name('admin-users.update');

    });

    Route::middleware('role:Bus Driver')->group(function () {
        // driver
        Route::view('/driver-maintenance', 'driver-maintenance');
        Route::view('/driver-reportID', 'driver-reportID');
        Route::get('/driver-busstatus', [BusController::class, 'driverBusStatus']);        
        Route::view('/breakdown-report', 'breakdown-report');
        Route::post('/breakdown-report', [BreakdownReportController::class, 'store'])->name('breakdown-report.store');
        Route::get('/maintenance-status', [BreakdownReportController::class, 'showStatus']);
        Route::get('/driver-inbox', [BreakdownReportController::class, 'inbox']);

    });

    Route::middleware('role:Mechanic')->group(function () {
        //mechanic
        Route::get('/mechanic-maintenance', [BreakdownReportController::class, 'mechanicMaintenance']);     
        Route::put('/mechanic-maintenance/{id}/update', [BreakdownReportController::class, 'updateMaintenanceStatus'])->name('mechanic-maintenance.update');

        Route::get('/mechanic-tostart', [BreakdownReportController::class, 'mechanicToStart']);
        Route::get('/mechanic-inprogress', [BreakdownReportController::class, 'mechanicInProgress']);
        Route::get('/mechanic-history', [BreakdownReportController::class, 'mechanicHistory']);;
    });
});

// logout 
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');