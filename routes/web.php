<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PandawaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerAuthController;
use App\Http\Controllers\AdminDashboardController;

// ================= HALAMAN UTAMA TOKO (FRONTEND) =================
Route::get('/', [PandawaController::class, 'index'])->name('home');

// API Endpoints Database Frontend
Route::prefix('api')->group(function () {
    Route::get('/products', [PandawaController::class, 'getProducts']);
    Route::post('/order/catalog', [PandawaController::class, 'createCatalogOrder']);
    Route::post('/order/delivery', [PandawaController::class, 'createDeliveryOrder']);
    Route::post('/order/jastip', [PandawaController::class, 'createJastipOrder']);
    Route::get('/tracking/{orderCode}', [PandawaController::class, 'getOrderTracking']);
    Route::post('/tracking/{orderCode}/step', [PandawaController::class, 'updateTrackingStep']);
    Route::post('/voucher/check', [PandawaController::class, 'checkVoucher']);
    Route::get('/orders/history', [PandawaController::class, 'getOrderHistory']);
});

// ================= AUTHENTICATION CUSTOMER =================
Route::get('/login', [CustomerAuthController::class, 'showLoginForm'])->name('customer.login');
Route::post('/login', [CustomerAuthController::class, 'login'])->name('customer.login.submit');
Route::get('/register', [CustomerAuthController::class, 'showRegisterForm'])->name('customer.register');
Route::post('/register', [CustomerAuthController::class, 'register'])->name('customer.register.submit');
Route::post('/logout', [CustomerAuthController::class, 'logout'])->name('customer.logout');

// ================= AUTHENTICATION ADMIN =================
Route::get('/admin/login', [AuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'login'])->name('admin.login.submit');
Route::match(['get', 'post'], '/admin/logout', [AuthController::class, 'logout'])->name('admin.logout');

// ================= DASHBOARD ADMIN (PROTECTED) =================
Route::middleware('auth')->prefix('admin')->group(function () {
    // 1. Dashboard Utama
    Route::get('/dashboard', [AdminDashboardController::class, 'dashboard'])->name('admin.dashboard');

    // 2. Manajemen Kurir
    Route::get('/couriers', [AdminDashboardController::class, 'couriers'])->name('admin.couriers');
    Route::post('/couriers', [AdminDashboardController::class, 'storeCourier'])->name('admin.couriers.store');
    Route::post('/couriers/{id}', [AdminDashboardController::class, 'updateCourier'])->name('admin.couriers.update');
    Route::post('/couriers/{id}/delete', [AdminDashboardController::class, 'deleteCourier'])->name('admin.couriers.delete');

    // 3. Manajemen Member
    Route::get('/members', [AdminDashboardController::class, 'members'])->name('admin.members');
    Route::post('/members', [AdminDashboardController::class, 'storeMember'])->name('admin.members.store');
    Route::post('/members/{id}', [AdminDashboardController::class, 'updateMember'])->name('admin.members.update');
    Route::post('/members/{id}/delete', [AdminDashboardController::class, 'deleteMember'])->name('admin.members.delete');

    // 4. Manajemen Pesanan
    Route::get('/orders', [AdminDashboardController::class, 'orders'])->name('admin.orders');
    Route::post('/orders/{id}/status', [AdminDashboardController::class, 'updateOrderStatus'])->name('admin.orders.status');
    Route::get('/orders/{id}/invoice', [AdminDashboardController::class, 'printInvoice'])->name('admin.orders.invoice');

    // 5. Laporan
    Route::get('/reports', [AdminDashboardController::class, 'reports'])->name('admin.reports');

    // 6. Pengaturan
    Route::get('/settings', [AdminDashboardController::class, 'settings'])->name('admin.settings');
    Route::post('/settings', [AdminDashboardController::class, 'updateSettings'])->name('admin.settings.update');
    Route::post('/settings/profile', [AdminDashboardController::class, 'updateAdminProfile'])->name('admin.settings.profile');
});