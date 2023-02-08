<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\CryptoController;
use App\Http\Controllers\PortfolioController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StatementController;
use App\Http\Controllers\TransferController;
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
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/accounts', [AccountController::class, 'index'])->name('accounts');
    Route::get('/accounts/{account}', [AccountController::class, 'show'])->name('accounts.show');
    Route::put('/accounts/{account}', [AccountController::class, 'update'])->name('accounts.update');
    Route::post('/accounts', [AccountController::class, 'store'])->name('accounts.add');
    Route::delete('/accounts/{account}/delete', [AccountController::class, 'delete'])->name('accounts.delete');
});

Route::middleware('auth')->group(function () {
    Route::get('/transfers', [TransferController::class, 'index'])->name('transfers');
    Route::post('/transfers/{selectedIndex}', [TransferController::class, 'makeTransfer'])
        ->name('transfers.make');
});

Route::middleware('auth')->group(function () {
    Route::get('/crypto', [CryptoController::class, 'index'])->name('crypto');
    Route::get('crypto/{account}/statements', [CryptoController::class, 'statements'])->name('crypto.statements');
    Route::post('crypto/{account}/statements', [CryptoController::class, 'filterStatements'])
        ->name('crypto.statements.filter');
    Route::get('/crypto/{cryptoCoin}', [CryptoController::class, 'show'])->name('crypto.show');
    Route::post('/crypto', [AccountController::class, 'storeCrypto'])->name('cryptoAccount.add');
    Route::post('/crypto/{cryptoCoin}/buy', [CryptoController::class, 'buy'])->name('crypto.buy');
    Route::post('/crypto/{cryptoCoin}/sell', [CryptoController::class, 'sell'])->name('crypto.sell');
    Route::get('/portfolio', [PortfolioController::class, 'index'])->name('crypto.portfolio');
});

Route::middleware('auth')->group(function () {
    Route::get('/statements', [StatementController::class, 'index'])->name('statements');
    Route::get('/statements/{account}', [StatementController::class, 'show'])->name('statements.show');
    Route::post('/statements/{account}', [StatementController::class, 'filter'])->name('statements.filter');
    Route::get('/statements/transaction/{transaction}', [StatementController::class, 'showSingle'])
        ->name('statements.transaction');
});

require __DIR__ . '/auth.php';
