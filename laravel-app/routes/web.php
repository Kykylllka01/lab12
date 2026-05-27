<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CabinetController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Главная страница
Route::get('/', [HomeController::class, 'index'])->name('home');

// Маршруты аутентификации
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Выход
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Категория и мастер-классы (доступно всем)
Route::get('/category/{id}', [CategoryController::class, 'show'])->name('category.show');

// Запись на мастер-класс (только для авторизованных посетителей)
Route::middleware('auth')->group(function () {
    Route::get('/enroll/{masterClass}', [CategoryController::class, 'enroll'])->name('enroll.show');
    Route::post('/enroll/{masterClass}/confirm', [CategoryController::class, 'confirmEnroll'])->name('enroll.confirm');
    Route::delete('/enroll/{masterClass}/cancel', [CategoryController::class, 'cancelEnroll'])->name('enroll.cancel');
    
    // Личный кабинет пользователя (не инструктора)
    Route::prefix('user-cabinet')->name('cabinet.user.')->group(function () {
        Route::get('/', [CabinetController::class, 'userIndex'])->name('index');
        Route::post('/cancel/{masterClass}', [CabinetController::class, 'cancelEnrollment'])->name('cancel');
    });
});

// Личный кабинет ведущего (только для инструкторов)
Route::middleware(['auth'])->prefix('cabinet')->name('cabinet.')->group(function () {
    Route::get('/', [CabinetController::class, 'index'])->name('index');
    Route::get('/create', [CabinetController::class, 'create'])->name('create');
    Route::post('/store', [CabinetController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [CabinetController::class, 'edit'])->name('edit');
    Route::put('/{id}', [CabinetController::class, 'update'])->name('update');
    Route::delete('/{id}', [CabinetController::class, 'destroy'])->name('destroy');
    Route::get('/ajax/slots', [CabinetController::class, 'getOccupiedSlots'])->name('slots');
});
