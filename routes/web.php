<?php

use App\Http\Controllers\ProfileController;
use App\Models\Admin;
use App\Models\Account;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    // Création automatique d'un admin si aucun user n'existe
    if (User::count() === 0) {
        $adminEmail = 'admin@note-app.com';
        $adminUsername = 'admin';
        $adminPassword = 'password';

        $user = User::create([
            'name' => 'admin',
            'username' => $adminUsername,
            'email' => $adminEmail,
            'password' => Hash::make($adminPassword),
        ]);

        $admin = Admin::create();
        Account::create([
            'accountable_type' => Admin::class,
            'accountable_id' => $admin->id,
            'user_id' => $user->id,
        ]);
    }
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

require __DIR__.'/auth.php';
