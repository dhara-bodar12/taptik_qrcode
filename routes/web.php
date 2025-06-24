<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminLoginController;
use App\Http\Controllers\Admin\OfferController;
use App\Http\Controllers\Admin\OfferFieldController;
use App\Http\Controllers\Admin\OfferClaimController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| These routes return Blade views for traditional web-based admin pages.
|--------------------------------------------------------------------------
*/

// ✅ Public Welcome Page
Route::get('/', function () {
    return view('auth.login');
});

// ✅ Admin Login Form (Blade View)
Route::get('/admin/login', [AdminLoginController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminLoginController::class, 'login'])->name('admin.login');
Route::post('/admin/logout', [AdminLoginController::class, 'logout'])->name('admin.logout');

// ✅ Admin Dashboard - Protected Route
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    Route::resource('offers', OfferController::class)->names('offers');
    Route::get('offer-fields', [OfferFieldController::class, 'index'])->name('offer.fields.index');
    Route::get('offer-claims', [OfferClaimController::class, 'index'])->name('offer.claims.index');
});



// ✅ User Profile Routes (Optional)
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/offers/redeem/{id}', [OfferController::class, 'redeem'])->name('offers.redeem');
Route::get('/offers/{offer}/{user}', [App\Http\Controllers\OfferFormController::class, 'show'])->name('offers.public');
Route::post('/offers/{offer}', [App\Http\Controllers\OfferFormController::class, 'submit'])->name('offers.public.submit');


// ✅ Optional Breeze or Jetstream Auth Routes (Login/Register)
require __DIR__.'/auth.php';
