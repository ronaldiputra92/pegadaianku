<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PawnTransactionController;
use App\Http\Controllers\PawnExtensionController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CustomerDocumentController;
use App\Http\Controllers\CustomerDocumentTestController;
use App\Http\Controllers\CustomerHistoryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReminderController;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;

// Redirect root to login
Route::get('/', function () {
    return redirect()->route('login');
});

// Test route (temporary) - accessible without auth for testing
Route::get('/test-routes', function () {
    return view('test-routes');
})->name('test.routes');

// Test customer documents route
Route::get('/test-customer-documents', function () {
    try {
        // Test 1: Controller instantiation
        $controller = new App\Http\Controllers\CustomerDocumentController();
        
        // Test 2: Model access
        $modelExists = class_exists('App\Models\CustomerDocument');
        
        // Test 3: Database table exists
        $tableExists = false;
        try {
            \DB::table('customer_documents')->count();
            $tableExists = true;
        } catch (Exception $e) {
            $tableExists = false;
        }
        
        // Test 4: View exists
        $viewExists = view()->exists('customer-documents.index');
        
        return response()->json([
            'status' => 'success',
            'tests' => [
                'controller_instantiation' => '✓ Pass',
                'model_exists' => $modelExists ? '✓ Pass' : '✗ Fail',
                'table_exists' => $tableExists ? '✓ Pass' : '✗ Fail',
                'view_exists' => $viewExists ? '✓ Pass' : '✗ Fail'
            ],
            'controller' => get_class($controller)
        ]);
    } catch (Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ]);
    }
})->name('test.customer.documents');

// Authentication Routes
require __DIR__.'/auth.php';

// Test routes for extension (temporary)
if (app()->environment(['local', 'testing'])) {
    require __DIR__.'/test-extension.php';
}

// Protected Routes
Route::middleware(['auth', 'verified'])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Profile Management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Pawn Transactions
    Route::resource('transactions', PawnTransactionController::class);
    Route::post('transactions/{transaction}/extend', [PawnTransactionController::class, 'extend'])->name('transactions.extend');
    
    // Pawn Extensions - API routes first to avoid conflicts
    Route::get('extensions/transaction-details', [PawnExtensionController::class, 'getTransactionDetails'])->name('extensions.transaction-details');
    Route::post('extensions/calculate-fees', [PawnExtensionController::class, 'calculateFees'])->name('extensions.calculate-fees');
    Route::get('extensions/{extension}/receipt', [PawnExtensionController::class, 'printReceipt'])->name('extensions.receipt');
    Route::resource('extensions', PawnExtensionController::class)->except(['edit', 'update', 'destroy']);
    
    // Appraisal Routes
    Route::get('transactions/{transaction}/appraise', [PawnTransactionController::class, 'appraise'])->name('transactions.appraise');
    Route::post('transactions/{transaction}/appraise', [PawnTransactionController::class, 'storeAppraisal'])->name('transactions.store-appraisal');
    
        
    // Receipt Printing
    Route::get('transactions/{transaction}/receipt', [PawnTransactionController::class, 'printReceipt'])->name('transactions.receipt');
    
    // Loan Calculation API
    Route::post('transactions/calculate-loan', [PawnTransactionController::class, 'calculateLoan'])->name('transactions.calculate-loan');
    
    // Payment API routes - place before resource routes to avoid conflicts
    Route::get('payments/transaction-details', [PaymentController::class, 'getTransactionDetails'])->name('payments.transaction-details');
    Route::get('payments/test-api', function() {
        return response()->json([
            'status' => 'success',
            'message' => 'Payment API is working',
            'timestamp' => now()
        ]);
    })->name('payments.test-api');
    
    // Payments
    Route::resource('payments', PaymentController::class)->except(['edit', 'update', 'destroy']);
    Route::get('payments/{payment}/receipt', [PaymentController::class, 'receipt'])->name('payments.receipt');

    // Notifications
    Route::get('notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('notifications/{notification}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-as-read');
    Route::post('notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-as-read');
    Route::get('notifications/unread-count', [NotificationController::class, 'getUnreadCount'])->name('notifications.unread-count');
    
    // Customer Documents - Test Route First
    Route::get('/customer-documents-test', function () {
        return response()->json([
            'status' => 'success',
            'message' => 'Route is accessible',
            'user' => auth()->user()->name ?? 'No user',
            'role' => auth()->user()->role ?? 'No role'
        ]);
    })->name('customer-documents.test');
    
    // Customer Documents - Test with simple controller
    Route::get('/customer-documents-simple', [CustomerDocumentTestController::class, 'index'])->name('customer-documents.simple');
    
    // Customer Documents - Explicit Routes
    Route::get('/customer-documents', [CustomerDocumentController::class, 'index'])->name('customer-documents.index');
    Route::get('/customer-documents/create', [CustomerDocumentController::class, 'create'])->name('customer-documents.create');
    Route::post('/customer-documents', [CustomerDocumentController::class, 'store'])->name('customer-documents.store');
    Route::get('/customer-documents/{customerDocument}', [CustomerDocumentController::class, 'show'])->name('customer-documents.show');
    Route::get('/customer-documents/{customerDocument}/edit', [CustomerDocumentController::class, 'edit'])->name('customer-documents.edit');
    Route::put('/customer-documents/{customerDocument}', [CustomerDocumentController::class, 'update'])->name('customer-documents.update');
    Route::patch('/customer-documents/{customerDocument}', [CustomerDocumentController::class, 'update'])->name('customer-documents.patch');
    Route::delete('/customer-documents/{customerDocument}', [CustomerDocumentController::class, 'destroy'])->name('customer-documents.destroy');
    Route::get('/customer-documents/{customerDocument}/download', [CustomerDocumentController::class, 'download'])->name('customer-documents.download');
    Route::get('/customer-documents/{customerDocument}/file', [CustomerDocumentController::class, 'serveFile'])->name('customer-documents.file');
    Route::post('/customer-documents/{customerDocument}/verify', [CustomerDocumentController::class, 'verify'])->name('customer-documents.verify');
    Route::get('/customers/{customer}/documents', [CustomerDocumentController::class, 'getByCustomer'])->name('customer-documents.by-customer');
    
    // Customer History - Explicit Routes
    Route::get('/customer-history', [CustomerHistoryController::class, 'index'])->name('customer-history.index');
    Route::get('/customer-history/{customer}', [CustomerHistoryController::class, 'show'])->name('customer-history.show');
    Route::get('/customer-history/{customer}/transactions', [CustomerHistoryController::class, 'transactions'])->name('customer-history.transactions');
    Route::get('/customer-history/{customer}/payments', [CustomerHistoryController::class, 'payments'])->name('customer-history.payments');
    Route::get('/customer-history/{customer}/export', [CustomerHistoryController::class, 'export'])->name('customer-history.export');
    
    // Customer Management
    Route::resource('customers', CustomerController::class);
    
    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/transactions', [ReportController::class, 'transactions'])->name('reports.transactions');
    Route::get('/reports/payments', [ReportController::class, 'payments'])->name('reports.payments');
    Route::get('/reports/financial', [ReportController::class, 'financial'])->name('reports.financial');
    Route::get('/reports/export', [ReportController::class, 'export'])->name('reports.export');
    
    // Reminder Management (Admin & Petugas)
    Route::middleware(['role:admin,petugas'])->group(function () {
        Route::get('/reminders', [ReminderController::class, 'index'])->name('reminders.index');
        Route::post('/reminders/{transaction}/send-manual', [ReminderController::class, 'sendManualReminder'])->name('reminders.send-manual');
        Route::post('/reminders/send-bulk', [ReminderController::class, 'sendBulkReminders'])->name('reminders.send-bulk');
    });
    
    // Admin Only Routes
    Route::middleware(['role:admin'])->group(function () {
        
        // User Management
        Route::resource('users', UserController::class);
        
    });
    
});