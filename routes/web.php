<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\KasirController;
use App\Http\Controllers\MemberController;

// Public Routes
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Authentication Routes - Dual Login System
Route::middleware('guest')->group(function () {
    // Member Login
    Route::get('/login', [AuthController::class, 'showMemberLogin'])->name('login');
    Route::get('/member/login', [AuthController::class, 'showMemberLogin'])->name('member.login');
    Route::post('/member/login', [AuthController::class, 'memberLogin'])->name('member.login.post');
    
    // Staff Login (Admin & Kasir)
    Route::get('/staff/login', [AuthController::class, 'showStaffLogin'])->name('staff.login');
    Route::post('/staff/login', [AuthController::class, 'staffLogin'])->name('staff.login.post');
    
    // Register
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // Members Management
    Route::get('/members', [AdminController::class, 'membersIndex'])->name('members.index');
    Route::post('/members/{id}/approve', [AdminController::class, 'approveMember'])->name('members.approve');
    Route::post('/members/{id}/suspend', [AdminController::class, 'suspendMember'])->name('members.suspend');
    
    // Products Management
    Route::get('/products', [AdminController::class, 'productsIndex'])->name('products.index');
    Route::get('/products/create', [AdminController::class, 'productsCreate'])->name('products.create');
    Route::post('/products', [AdminController::class, 'productsStore'])->name('products.store');
    Route::get('/products/{id}/edit', [AdminController::class, 'productsEdit'])->name('products.edit');
    Route::put('/products/{id}', [AdminController::class, 'productsUpdate'])->name('products.update');
    Route::delete('/products/{id}', [AdminController::class, 'productsDestroy'])->name('products.destroy');
    Route::get('/products/{id}/qr-download', [AdminController::class, 'downloadProductQR'])->name('products.qr-download');
    
    // Categories Management
    Route::get('/categories', [AdminController::class, 'categoriesIndex'])->name('categories.index');
    
    // Transactions Management
    Route::get('/transactions', [AdminController::class, 'transactionsIndex'])->name('transactions.index');
    Route::get('/transactions/{id}', [AdminController::class, 'transactionsShow'])->name('transactions.show');
    
    // Users Management
    Route::get('/users', [AdminController::class, 'usersIndex'])->name('users.index');
    Route::get('/users/create', [AdminController::class, 'usersCreate'])->name('users.create');
    Route::post('/users', [AdminController::class, 'usersStore'])->name('users.store');
    Route::get('/users/{id}/edit', [AdminController::class, 'usersEdit'])->name('users.edit');
    Route::put('/users/{id}', [AdminController::class, 'usersUpdate'])->name('users.update');
    Route::delete('/users/{id}', [AdminController::class, 'usersDestroy'])->name('users.destroy');
    Route::post('/users/{id}/reset-password', [AdminController::class, 'usersResetPassword'])->name('users.reset-password');
    
    // Kasir Management
    Route::get('/kasir', [AdminController::class, 'kasirIndex'])->name('kasir.index');
    Route::get('/kasir/create', [AdminController::class, 'kasirCreate'])->name('kasir.create');
    Route::post('/kasir', [AdminController::class, 'kasirStore'])->name('kasir.store');
    
    // Promotions Management
    Route::get('/promotions', [AdminController::class, 'promotionsIndex'])->name('promotions.index');
    
    // Reports
    Route::get('/reports', [AdminController::class, 'reportsIndex'])->name('reports.index');
    Route::get('/reports/sales', [AdminController::class, 'reportsSales'])->name('reports.sales');
    
    // Settings
    Route::get('/settings', [AdminController::class, 'settingsIndex'])->name('settings.index');
    Route::put('/settings', [AdminController::class, 'settingsUpdate'])->name('settings.update');
    
    // Vouchers Management
    Route::resource('vouchers', \App\Http\Controllers\Admin\VoucherController::class);
});

// Kasir Routes
Route::middleware(['auth', 'role:kasir'])->prefix('kasir')->name('kasir.')->group(function () {
    Route::get('/dashboard', [KasirController::class, 'dashboard'])->name('dashboard');
    Route::get('/pos', [KasirController::class, 'pos'])->name('pos');
    Route::get('/transactions', [KasirController::class, 'transactionHistory'])->name('transactions');
    
    // API Routes for POS
    Route::get('/scan-qr', [KasirController::class, 'scanQR'])->name('scan-qr');
    Route::get('/search-product', [KasirController::class, 'searchProduct'])->name('search-product');
    Route::get('/search-member', [KasirController::class, 'searchMember'])->name('search-member');
    Route::get('/products/{id}', [KasirController::class, 'getProduct'])->name('get-product');
    Route::post('/checkout', [KasirController::class, 'processTransaction'])->name('checkout');
    Route::post('/process-transaction', [KasirController::class, 'processTransaction'])->name('process-transaction');
    Route::get('/transactions/{id}', [KasirController::class, 'getTransaction'])->name('get-transaction');
    Route::get('/transactions/{id}/print', [KasirController::class, 'printReceipt'])->name('print-receipt');
    
    // Hold Transaction
    Route::post('/hold-transaction', [KasirController::class, 'holdTransaction'])->name('hold-transaction');
    Route::get('/held-transactions', [KasirController::class, 'getHeldTransactions'])->name('held-transactions');
    Route::get('/held-transactions/{id}', [KasirController::class, 'retrieveHeldTransaction'])->name('retrieve-held');
    Route::delete('/held-transactions/{id}', [KasirController::class, 'deleteHeldTransaction'])->name('delete-held');
    
    // Split Payment
    Route::post('/process-split-payment', [KasirController::class, 'processTransactionWithSplitPayment'])->name('process-split-payment');
    
    // Void Transaction
    Route::post('/transactions/{id}/void', [KasirController::class, 'voidTransaction'])->name('void-transaction');
    
    // Refund
    Route::get('/refund/search/{transactionCode}', [KasirController::class, 'getTransactionForRefund'])->name('refund-search');
    Route::post('/transactions/{id}/refund', [KasirController::class, 'createRefund'])->name('create-refund');
    
    // Quick Member Registration
    Route::post('/register-member', [KasirController::class, 'registerMember'])->name('register-member');
    
    // Members Management (Kasir can view and approve)
    Route::get('/members', [KasirController::class, 'membersIndex'])->name('members.index');
    Route::post('/members/{id}/approve', [KasirController::class, 'approveMember'])->name('members.approve');
    
    // Reports (Read-only for Kasir)
    Route::get('/reports', [KasirController::class, 'reportsIndex'])->name('reports.index');
    Route::get('/reports/sales', [KasirController::class, 'reportsSales'])->name('reports.sales');
});

// Member Routes
Route::middleware(['auth', 'role:member', 'member.status'])->prefix('member')->name('member.')->group(function () {
    Route::get('/dashboard', [MemberController::class, 'dashboard'])->name('dashboard');
    Route::get('/qr-code', [MemberController::class, 'qrCode'])->name('qr-code');
    Route::post('/qr-code/generate', [MemberController::class, 'generateQR'])->name('generate-qr');
    Route::get('/transactions', [MemberController::class, 'transactions'])->name('transactions');
    Route::get('/transactions/{id}', [MemberController::class, 'transactionDetail'])->name('transaction-detail');
    Route::get('/points-history', [MemberController::class, 'pointsHistory'])->name('points-history');
    Route::get('/products', [MemberController::class, 'products'])->name('products');
    Route::get('/promotions', [MemberController::class, 'promotions'])->name('promotions');
    Route::get('/profile', [MemberController::class, 'profile'])->name('profile');
    Route::put('/profile', [MemberController::class, 'updateProfile'])->name('profile.update');
    Route::put('/password', [MemberController::class, 'changePassword'])->name('password.change');
    
    // Vouchers (Member)
    Route::get('/vouchers', [MemberController::class, 'vouchers'])->name('vouchers');
    Route::post('/vouchers/{id}/redeem', [MemberController::class, 'redeemVoucher'])->name('vouchers.redeem');
    Route::get('/my-vouchers', [MemberController::class, 'myVouchers'])->name('my-vouchers');
});
