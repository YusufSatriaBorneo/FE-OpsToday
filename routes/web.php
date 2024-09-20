<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EngineerController;
use App\Http\Controllers\EngineerLeaveController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

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
    return redirect()->route('dashboard.index');
});

Route::get('/ids', [TaskController::class, 'showIds']);
Route::get('/duration', [TaskController::class, 'taskDuration']);
Route::get('/api/absensi', [AbsensiController::class, 'index']);
Route::get('/absensi/data', [AbsensiController::class, 'fetchData'])->name('absensi.data');
Route::get('/absensi/data', [AbsensiController::class, 'fetchDataDashboard'])->name('absensi.dashboard');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
Route::get('/attendance/timeliness', [AbsensiController::class, 'calculateTimeliness']);
Route::get('/dashboard/content', [DashboardController::class, 'getDashboardContent'])->name('dashboard.content');

//Auth
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('admin.logout');
Route::get('users/sendLoginInfo/{id}', [LoginController::class, 'sendLoginInfo'])->name('users.sendLoginInfo');
Route::post('users', [UserController::class, 'store'])->name('users.store');

Route::prefix('admin')->middleware(['auth', 'Admin'])->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.index')->middleware('auth');
    Route::get('/engineers', [AdminController::class, 'manageEngineers'])->name('admin.engineers');
    Route::get('/users', [AdminController::class, 'manageUsers'])->name('admin.users');
    Route::get('/tickets', [AdminController::class, 'manageTickets'])->name('admin.tickets');
    Route::get('/engineer/{engineer_id}/activities', [AdminController::class, 'engineerActivities'])->name('admin.engineer.activities');
    // Rute untuk halaman edit user
    Route::get('/admin/users/{id}/edit', [AdminController::class, 'edit'])->name('users.edit');
    // Rute untuk menghapus user
    Route::delete('/admin/users/{id}', [AdminController::class, 'destroy'])->name('users.destroy');
    // Route untuk halaman engineer leaves
    Route::get('/engineer-leaves', [AdminController::class, 'engineerLeavesView'])->name('admin.engineer.leaves');
    Route::post('/engineer-leaves', [AdminController::class, 'engineerLeavesStore'])->name('admin.engineer_leaves.store');
    Route::get('/engineer-onprogress', [AdminController::class, 'engineerOnProgressView'])->name('admin.engineer.onprogress');
    Route::delete('/admin/engineer-onprogress/{id}', [AdminController::class, 'destroyEngineerOnProgress'])->name('engineerOnProgress.destroy');
    Route::get('/engineer-extra-miles', [AdminController::class, 'engineerExtraMilesView'])->name('admin.engineer.extra-miles');
    Route::post('/engineer-extra-miles/store', [AdminController::class, 'engineerExtraMilesStore'])->name('admin.engineer_extra_miles.store');
    Route::delete('/engineer-extra-miles/{id}', [AdminController::class, 'destroyEngineerExtraMiles'])->name('admin.engineer_extra_miles.destroy');
});
Route::get('/unauthorized', function () {
    return view('unauthorize');
})->name('unauthorized');
// Reset Password
// Menampilkan form reset password
Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');

// Mengirim permintaan reset password
Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');

// Untuk Engineer
Route::get('engineer/dashboard', [EngineerController::class, 'index'])->name('engineer.dashboard')->middleware('auth');
Route::post('engineer/activities', [EngineerController::class, 'store'])->name('engineer.activities.store')->middleware('auth');
Route::get('/api/engineer-leaves', [EngineerLeaveController::class, 'index']);

//Chart Js
Route::get('/api/engineer-tasks', [TaskController::class, 'chartJs']);

// Status Count
Route::get('/api/status-count', [DashboardController::class, 'statusCount']);