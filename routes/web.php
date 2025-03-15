<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ResumeController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\EnsureUserHasResume;
use App\Http\Controllers\Auth\SocialLoginController;

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

// Homepage (Login Page)
Route::get('/', function () {
    return view('auth.login'); 
})->name('login');

// Dashboard
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Logout Route
Route::post('/logout', function () {
    Auth::logout();
    return redirect('/login'); 
})->middleware('auth')->name('logout');

// Profile routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Apply the middleware to the "Create Resume" and "View Resumes" routes
Route::middleware('resume.access')->group(function () {
    // Create Resume (GET)
    Route::get('/resumes/create', [ResumeController::class, 'create'])->name('resumes.create');

    // View Resumes (GET)
    Route::get('/resumes/view', [ResumeController::class, 'show'])->name('resumes.view');

    // Display the edit form (GET)
    Route::get('/resumes/edit', [ResumeController::class, 'edit'])->name('resumes.edit');

    // Handle the form submission (PUT)
    Route::put('/resumes/update', [ResumeController::class, 'update'])->name('resumes.update');
    
});



// Store Resume (POST) - No middleware needed since it's handled by the controller
Route::post('/store', [ResumeController::class, 'store'])->name('store');



// Social Login Routes
// Google Login
Route::get('/login/google', [SocialLoginController::class, 'redirectToGoogle'])->name('login.google');
Route::get('/login/google/callback', [SocialLoginController::class, 'handleGoogleCallback']);

// GitHub Login
Route::get('/login/github', [SocialLoginController::class, 'redirectToGithub'])->name('login.github');
Route::get('/login/github/callback', [SocialLoginController::class, 'handleGithubCallback']);
// Authentication routes (e.g., login, register, password reset)
require __DIR__.'/auth.php';
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
