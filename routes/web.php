<?php

use App\Http\Controllers\BankAccountController;
use App\Http\Controllers\ProfileController;
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
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified', 'bankAccounts'])->name('dashboard');

Route::get('/transfers', function () {
    return view('transfers');
})->middleware(['auth', 'verified'])->name('transfers');

Route::get('/crypto', function () {
    return view('crypto');
})->middleware(['auth', 'verified'])->name('crypto');

Route::get('/statements', function () {
    return view('statements');
})->middleware(['auth', 'verified'])->name('statements');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('bankAccounts')->group(function () {
    Route::get('/accounts', [BankAccountController::class, 'show'])->name('accounts.show');
    Route::post('/accounts', [BankAccountController::class, 'add'])->name('accounts.add');
    Route::delete('/accounts', [BankAccountController::class, 'delete'])->name('accounts.delete');
});

require __DIR__.'/auth.php';
