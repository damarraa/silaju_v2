<?php

use App\Http\Controllers\Admin\WilayahController;
use App\Http\Controllers\Authentication\AuthController;
use App\Http\Controllers\Authentication\PasswordResetController;
use App\Http\Controllers\PJU\PJUController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

Route::middleware('guest')->group(function () {
    // Login
    Route::get('login', [AuthController::class, 'create'])->name('login');
    Route::post('login', [AuthController::class, 'store']);

    // Reset Password
    Route::get('forgot-password', [PasswordResetController::class, 'create'])
        ->name('password.request');
    Route::post('forgot-password', [PasswordResetController::class, 'store'])
        ->name('password.email');
    Route::get('reset-password/{token}', [PasswordResetController::class, 'edit'])
        ->name('password.reset');
    Route::post('reset-password', [PasswordResetController::class, 'update'])
        ->name('password.update');
});

Route::middleware('auth')->group(function () {
    // Logout
    Route::post('logout', [AuthController::class, 'destroy'])->name('logout');

    // User
    Route::get('/dashboard', function () {
        return view('pages.user.dashboard.index');
    })->name('dashboard.index');

    Route::resource('pju', PJUController::class);

    // Super Admin
    Route::middleware(['role:super_admin'])->prefix('admin')->group(function () {
        Route::get('/dashboard', function () {
            return view('pages.admin.dashboard.index');
        })->name('dashboard.admin');

        Route::resource('wilayah', WilayahController::class);
    });

    // Route::get('/admin/dashboard', function () {
    //     return "Welcome Super Admin!";
    // })->name('dashboard.admin');
});

// ---=== TEMPLATE ===---
// dashboard pages
Route::get('/', function () {
    return view('pages.dashboard.ecommerce', ['title' => 'E-commerce Dashboard']);
})->name('dashboard');

// calender pages
Route::get('/calendar', function () {
    return view('pages.calender', ['title' => 'Calendar']);
})->name('calendar');

// profile pages
Route::get('/profile', function () {
    return view('pages.profile', ['title' => 'Profile']);
})->name('profile');

// form pages
Route::get('/form-elements', function () {
    return view('pages.form.form-elements', ['title' => 'Form Elements']);
})->name('form-elements');

// tables pages
Route::get('/basic-tables', function () {
    return view('pages.tables.basic-tables', ['title' => 'Basic Tables']);
})->name('basic-tables');

// pages

Route::get('/blank', function () {
    return view('pages.blank', ['title' => 'Blank']);
})->name('blank');

// error pages
Route::get('/error-404', function () {
    return view('pages.errors.error-404', ['title' => 'Error 404']);
})->name('error-404');

// chart pages
Route::get('/line-chart', function () {
    return view('pages.chart.line-chart', ['title' => 'Line Chart']);
})->name('line-chart');

Route::get('/bar-chart', function () {
    return view('pages.chart.bar-chart', ['title' => 'Bar Chart']);
})->name('bar-chart');


// authentication pages
Route::get('/signin', function () {
    return view('pages.auth.signin', ['title' => 'Sign In']);
})->name('signin');

Route::get('/signup', function () {
    return view('pages.auth.signup', ['title' => 'Sign Up']);
})->name('signup');

// ui elements pages
Route::get('/alerts', function () {
    return view('pages.ui-elements.alerts', ['title' => 'Alerts']);
})->name('alerts');

Route::get('/avatars', function () {
    return view('pages.ui-elements.avatars', ['title' => 'Avatars']);
})->name('avatars');

Route::get('/badge', function () {
    return view('pages.ui-elements.badges', ['title' => 'Badges']);
})->name('badges');

Route::get('/buttons', function () {
    return view('pages.ui-elements.buttons', ['title' => 'Buttons']);
})->name('buttons');

Route::get('/image', function () {
    return view('pages.ui-elements.images', ['title' => 'Images']);
})->name('images');

Route::get('/videos', function () {
    return view('pages.ui-elements.videos', ['title' => 'Videos']);
})->name('videos');






















