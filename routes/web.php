<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\UnitController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LandingController::class, 'index'])->name('home');
Route::get('/catalog', [UnitController::class, 'catalog'])->name('catalog');
Route::get('/privacy-policy', fn () => view('legal.privacy'))->name('privacy');
Route::get('/terms-of-service', fn () => view('legal.terms'))->name('terms');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Password Reset
Route::get('/forgot-password', [\App\Http\Controllers\ForgotPasswordController::class, 'showLinkRequestForm'])->middleware('guest')->name('password.request');
Route::post('/forgot-password', [\App\Http\Controllers\ForgotPasswordController::class, 'sendResetLinkEmail'])->middleware('guest')->name('password.email');
Route::get('/reset-password/{token}', [\App\Http\Controllers\ResetPasswordController::class, 'showResetForm'])->middleware('guest')->name('password.reset');
Route::post('/reset-password', [\App\Http\Controllers\ResetPasswordController::class, 'reset'])->middleware('guest')->name('password.update');

Route::get('/units/{unit}/reviews', [ReviewController::class, 'show'])->name('units.reviews');

Route::match(['GET', 'POST'], '/payments/notification', [PaymentController::class, 'notification'])->name('payments.notification');

Route::get('/units/{unit}', [UnitController::class, 'show'])->name('units.show');
Route::get('/units/{unit}/booked-dates', [BookingController::class, 'getBookedDates'])->name('units.booked-dates');

Route::get('/sitemap.xml', function () {
    $units = \App\Models\Unit::where('is_active', true)->get();
    return response()->view('sitemap', ['units' => $units])->header('Content-Type', 'application/xml');
})->name('sitemap');

Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount'])->name('notifications.unread-count');
Route::get('/notifications/latest', [NotificationController::class, 'latest'])->name('notifications.latest');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Cart
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
    Route::post('/cart/{unit}', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/{unit}/remove', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/calculate-price', [BookingController::class, 'calculatePrice'])->name('calculate.price');

    Route::middleware('customer')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'customer'])->name('dashboard');
        Route::get('/units/{unit}/book', [BookingController::class, 'create'])->name('bookings.create');
        Route::get('/bookings/create-multi', [BookingController::class, 'createMulti'])->name('bookings.create.multi');
        Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
        Route::get('/bookings', [BookingController::class, 'myBookings'])->name('bookings.index');
        Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
        Route::get('/bookings/{booking}/payment', [PaymentController::class, 'show'])->name('payments.show');
        Route::post('/bookings/{booking}/payment', [PaymentController::class, 'process'])->name('payments.process');
        Route::get('/payments/result/{status}/{booking}', [PaymentController::class, 'result'])->name('payments.result');
        Route::post('/bookings/{booking}/payment/confirm', [PaymentController::class, 'confirmPayment'])->name('payments.confirm');
        Route::get('/bookings/{booking}/payment/status', [PaymentController::class, 'checkStatus'])->name('payments.status');
        Route::get('/invoices', [InvoiceController::class, 'index'])->name('invoices.index');
        Route::get('/invoices/{invoice}', [InvoiceController::class, 'show'])->name('invoices.show');
        Route::get('/invoices/{invoice}/download', [InvoiceController::class, 'download'])->name('invoices.download');
        Route::post('/bookings/{booking}/cancel', [BookingController::class, 'customerCancel'])->name('bookings.cancel');
        Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
        Route::put('/reviews/{review}', [ReviewController::class, 'update'])->name('reviews.update');
        Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
    });

    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
        Route::post('/notifications/read-all', [NotificationController::class, 'readAll'])->name('notifications.read-all');
        Route::post('/notifications/{notification}/read', [NotificationController::class, 'read'])->name('notifications.read');
        Route::get('/customers', [DashboardController::class, 'customers'])->name('customers');
        Route::get('/customers/{user}', [DashboardController::class, 'customerShow'])->name('customers.show');
        Route::put('/customers/{user}', [DashboardController::class, 'customerUpdate'])->name('customers.update');
        Route::patch('/customers/{user}/toggle', [DashboardController::class, 'customerToggleStatus'])->name('customers.toggle');
        Route::resource('units', UnitController::class)->except(['show']);
        Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
        Route::get('/bookings/create', [BookingController::class, 'adminCreate'])->name('bookings.create');
        Route::post('/bookings', [BookingController::class, 'adminStore'])->name('bookings.store');
        Route::get('/bookings/{booking}', [BookingController::class, 'adminShow'])->name('bookings.show');
        Route::patch('/bookings/{booking}/status', [BookingController::class, 'updateStatus'])->name('bookings.status');
        Route::get('/calendar', [BookingController::class, 'calendar'])->name('calendar');
        Route::get('/calendar-data', [BookingController::class, 'calendarData'])->name('calendar.data');
        Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
        Route::get('/payments/{payment}', [PaymentController::class, 'adminShow'])->name('payments.show');
        Route::get('/invoices', [InvoiceController::class, 'adminIndex'])->name('invoices.index');
        Route::get('/invoices/{invoice}', [InvoiceController::class, 'adminShow'])->name('invoices.show');
        Route::get('/invoices/{invoice}/download', [InvoiceController::class, 'download'])->name('invoices.download');
        Route::resource('categories', CategoryController::class)->except(['show']);
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/export', [ReportController::class, 'export'])->name('reports.export');
        Route::get('/reviews', [ReviewController::class, 'adminIndex'])->name('reviews.index');
        Route::delete('/reviews/{review}', [ReviewController::class, 'adminDestroy'])->name('reviews.destroy');
    });
});
