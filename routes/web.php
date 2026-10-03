<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\PortfolioController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PortfolioController::class, 'home'])->name('home');
Route::get('/proyectos', [PortfolioController::class, 'projects'])->name('projects');
Route::get('/proyectos/{project}', [PortfolioController::class, 'show'])->name('projects.show');

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->middleware('throttle:5,1');
});

Route::post('/logout', [LoginController::class, 'destroy'])->middleware('auth')->name('logout');

Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', Admin\DashboardController::class)->name('dashboard');
    Route::get('/perfil', [Admin\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/perfil', [Admin\ProfileController::class, 'update'])->name('profile.update');
    Route::resource('proyectos', Admin\ProjectController::class)
        ->except('show')
        ->parameters(['proyectos' => 'project'])
        ->names('projects');
    Route::resource('experiencia', Admin\ExperienceController::class)
        ->except('show')
        ->parameters(['experiencia' => 'experience'])
        ->names('experiences');
});
