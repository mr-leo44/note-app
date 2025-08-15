<?php

use App\Models\User;
use App\Models\Admin;
use App\Models\Account;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JuryController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\PeriodController;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\FacultyController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SemesterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\CourseCategoryController;

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
    if (Auth::check()) {
        return redirect()->back();
    } else {
        return redirect()->route('login');
    }
})->name('home');

Route::get('/dashboard', [DashboardController::class, 'dashboard'])->middleware(['auth', 'verified', 'admin_only'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('publications/publish-results', [ResultController::class, 'onlinePublishResults'])->name('publications.onlinePublishResults');
    Route::resource('publications', ResultController::class)->only(['index', 'store', 'show', 'update', 'destroy']);
    Route::resource('promotions', PromotionController::class)->only(['index', 'store', 'show', 'update', 'destroy']);
    Route::post('students/{student}/sessions/{session}/assign-results', [StudentController::class, 'assignResults'])->name('students.assignResults');
    Route::post('students/{student}/results/{result}/publish', [StudentController::class, 'publishResult'])->name('students.publishResult');
});

Route::middleware(['auth', 'admin_only'])->group(function () {
    Route::resource('sections', FacultyController::class)->only(['index', 'store', 'show', 'update', 'destroy']);
    Route::resource('departments', DepartmentController::class)->only(['index','store', 'show', 'update', 'destroy']);
    Route::post('juries/{jury}/reset-password', [JuryController::class, 'resetPassword'])->name('juries.resetPassword');
    Route::resource('juries', JuryController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::resource('periods', PeriodController::class)->only(['index', 'show', 'store', 'update', 'destroy']);
    Route::resource('semesters', SemesterController::class)->only(['index','show', 'store', 'update', 'destroy']);
    Route::resource('sessions', SessionController::class)->only(['store', 'update', 'destroy']);
    Route::resource('course-categories', CourseCategoryController::class)->only(['index', 'show', 'store', 'update', 'destroy']);
    Route::post('courses/{course}/assign-promotion', [CourseController::class, 'assignPromotion'])->name('courses.assignPromotion');
    Route::put('courses/{course}/promotions/{promotion}/maxima', [CourseController::class, 'updateMaxima'])->name('courses.updateMaxima');
    Route::delete('courses/{course}/promotions/{promotion}', [CourseController::class, 'detachPromotion'])->name('courses.detachPromotion');
    Route::resource('courses', CourseController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::resource('students', StudentController::class)->only(['store', 'update', 'destroy']);
});

require __DIR__ . '/auth.php';
