<?php

use App\Http\Controllers\Authentication\AuthController;
use App\Http\Controllers\Authentication\PasswordResetController;
use App\Http\Controllers\PJU\PJUController;
use App\Http\Controllers\Trafo\TrafoController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Wilayah\WilayahController;
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
    // User Management
    Route::resource('users', UserController::class);
    // Helper
    Route::get('/ajax/rayons/{areaId}', [WilayahController::class, 'getRayonsByArea'])->name('ajax.rayons');

    // PJU
    Route::prefix('pju')->name('pju.')->group(function () {
        Route::get('gallery', [PJUController::class, 'gallery'])->name('gallery');
        Route::get('meterisasi', [PJUController::class, 'meterisasiIndex'])->name('meterisasi');
        Route::get('visual', [PJUController::class, 'visualIndex'])->name('visual');
        Route::get('realisasi', [PJUController::class, 'realisasiIndex'])->name('realisasi');
        Route::get('rekap-jenis', [PJUController::class, 'rekapJenisIndex'])->name('rekap.jenis');
        Route::get('rekap-harian', [PJUController::class, 'dailyRecapIndex'])->name('rekap.harian');
        Route::get('rekap-total', [PJUController::class, 'rekapTotalIndex'])->name('rekap.total');
        Route::get('officers', [PJUController::class, 'officerPerformance'])->name('officers');
        Route::get('officers/{user}', [PJUController::class, 'officerDetail'])->name('officers.detail');
        Route::get('officers/{user}/export/excel', [PJUController::class, 'officerDetailExportExcel'])->name('officers.export.excel');
        Route::get('officers/{user}/export/pdf', [PJUController::class, 'officerDetailExportPdf'])->name('officers.export.pdf');
        Route::get('export/excel', [PJUController::class, 'exportExcel'])->name('export.excel');
        Route::get('export/pdf', [PJUController::class, 'exportPdf'])->name('export.pdf');
        Route::get('verification', [PJUController::class, 'verificationIndex'])->name('verification');
        Route::post('{pju}/verify', [PJUController::class, 'verify']);
    });

    Route::resource('pju', PJUController::class);

    // Trafo
    Route::get('trafo/gallery', [TrafoController::class, 'gallery'])->name('trafo.gallery');
    Route::resource('trafo', TrafoController::class);

    // User
    Route::get('/dashboard', function () {
        return view('pages.user.dashboard.index');
    })->name('dashboard.index');

    // Super Admin
    Route::middleware(['role:super_admin'])->prefix('admin')->group(function () {
        Route::get('/dashboard', function () {
            return view('pages.admin.dashboard.index');
        })->name('dashboard.admin');
    });
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






















