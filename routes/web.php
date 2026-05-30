<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\OccupancyController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\TrendController;
use App\Http\Controllers\Admin\SchemaController;
use App\Http\Controllers\Admin\UserManageController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\BookingCrudController;
use App\Http\Controllers\Admin\HotelCrudController;
use App\Http\Controllers\Admin\TamuController;
use App\Http\Controllers\User\DashboardController as UserDashboard;
use App\Http\Controllers\User\OccupancyController as UserOccupancy;
use App\Http\Controllers\User\TrendController as UserTrend;
use App\Http\Controllers\Admin\ExportController;
use App\Http\Controllers\ProfileController;
// ── Landing ──
Route::get('/', fn() => view('landing.index'))->name('landing');

// ── Auth ──
Route::middleware('guest')->group(function () {
    Route::get('/login',     [LoginController::class,    'showForm'])->name('login');
    Route::post('/login',    [LoginController::class,    'login']);
    Route::get('/register',  [RegisterController::class, 'showForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('auth')->name('logout');

Route::get('/dashboard', function () {
    return Auth::user()?->role === 'admin'
        ? redirect()->route('admin.dashboard')
        : redirect()->route('user.dashboard');
})->middleware('auth')->name('dashboard');

// ── Profile ──
Route::middleware('auth')->prefix('profile')->name('profile.')->group(function () {
    Route::get('/',         [ProfileController::class, 'show'])          ->name('show');
    Route::get('/edit',     [ProfileController::class, 'edit'])          ->name('edit');
    Route::put('/update',   [ProfileController::class, 'update'])        ->name('update');
    Route::get('/password', [ProfileController::class, 'editPassword'])  ->name('password');
    Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password.update');
});

// ── Admin ──
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/hotels/{hotel}/rooms', function(\App\Models\Hotel $hotel) {
    return response()->json(
        \App\Models\Room::where('hotel_id', $hotel->id)
            ->where('is_available', true)
            ->get(['id','nomor_kamar','tipe','harga_dasar'])
    );
})->name('hotels.rooms');

        // Dashboard & Analitik
        Route::get('/dashboard', [AdminDashboard::class,    'index'])->name('dashboard');
        Route::get('/occupancy', [OccupancyController::class,'index'])->name('occupancy');
        Route::get('/customers', [CustomerController::class, 'index'])->name('customers');
        Route::get('/trends',    [TrendController::class,    'index'])->name('trends');
        Route::get('/schema',    [SchemaController::class,   'index'])->name('schema');
        Route::get('/reports',   [ReportController::class,   'index'])->name('reports');

        // User Management
        Route::get('/users',           [UserManageController::class,'index'])  ->name('users');
        Route::post('/users',          [UserManageController::class,'store'])  ->name('users.store');
        Route::delete('/users/{user}', [UserManageController::class,'destroy'])->name('users.destroy');

        // CRUD Bookings
        Route::resource('bookings', BookingCrudController::class);

        // CRUD Hotels
        Route::resource('hotels', HotelCrudController::class);

        // CRUD Tamu / Customers
        Route::resource('tamu', TamuController::class);
        Route::prefix('export')->name('export.')->group(function () {
    // Excel
    Route::get('/excel/bookings',  [ExportController::class, 'excelBookings']) ->name('excel.bookings');
    Route::get('/excel/customers', [ExportController::class, 'excelCustomers'])->name('excel.customers');
    Route::get('/excel/occupancy', [ExportController::class, 'excelOccupancy'])->name('excel.occupancy');

    // PDF
    Route::get('/pdf/bookings',   [ExportController::class, 'pdfBookings'])  ->name('pdf.bookings');
    Route::get('/pdf/occupancy',  [ExportController::class, 'pdfOccupancy']) ->name('pdf.occupancy');
    Route::get('/pdf/customers',  [ExportController::class, 'pdfCustomers']) ->name('pdf.customers');
});
    });

// ── User ──
Route::middleware(['auth', 'role:user,admin'])
    ->prefix('user')
    ->name('user.')
    ->group(function () {
        Route::get('/dashboard', [UserDashboard::class, 'index'])->name('dashboard');
        Route::get('/occupancy', [UserOccupancy::class, 'index'])->name('occupancy');
        Route::get('/hotels',    [\App\Http\Controllers\User\HotelController::class, 'index'])->name('hotels');
        Route::get('/demographics', [\App\Http\Controllers\User\DemographicController::class, 'index'])->name('demographics');
        Route::get('/trends',    [UserTrend::class,     'index'])->name('trends');
    });