<?php

use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController as AdminProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/balance', [DashboardController::class, 'refreshBalance'])->name('dashboard.balance');

    Route::get('/activity-log', [ActivityLogController::class, 'index'])->name('activity-log.index');

    Route::get('/transaksi', [TransactionController::class, 'index'])->name('transactions.index');
    Route::get('/transaksi/create', [TransactionController::class, 'create'])->name('transactions.create');
    Route::post('/transaksi', [TransactionController::class, 'store'])->name('transactions.store');
    Route::get('/transaksi/{id}', [TransactionController::class, 'show'])->name('transactions.show');
    Route::get('/transaksi/{id}/edit', [TransactionController::class, 'edit'])->name('transactions.edit');
    Route::put('/transaksi/{id}', [TransactionController::class, 'update'])->name('transactions.update');
    Route::delete('/transaksi/{id}', [TransactionController::class, 'destroy'])->name('transactions.destroy');

    Route::get('/laporan', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/laporan/export-pdf', [ReportController::class, 'exportPdf'])->name('reports.export-pdf');
    Route::get('/laporan/export-excel', [ReportController::class, 'exportExcel'])->name('reports.export-excel');

    Route::get('/pengguna', [UserController::class, 'index'])->name('users.index');
    Route::get('/pengguna/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/pengguna', [UserController::class, 'store'])->name('users.store');
    Route::get('/pengguna/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/pengguna/{id}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/pengguna/{id}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::get('/profil', [AdminProfileController::class, 'index'])->name('profile.index');
    Route::put('/profil', [AdminProfileController::class, 'update'])->name('profile.update');
    Route::put('/profil/password', [AdminProfileController::class, 'updatePassword'])->name('profile.update-password');
});

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/laporan', [ReportController::class, 'index'])->name('reports.public.index');
    Route::get('/laporan/export/{bulan}/{tahun}', [ReportController::class, 'exportPdf'])->name('reports.public.export-pdf');
});

require __DIR__ . '/auth.php';
