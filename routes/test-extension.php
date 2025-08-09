<?php

use App\Models\PawnTransaction;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Support\Facades\Route;

// Test route untuk memeriksa data transaksi
Route::get('/test-extension-data', function () {
    try {
        // Check if tables exist and have data
        $transactionCount = PawnTransaction::count();
        $customerCount = Customer::count();
        $userCount = User::count();
        
        // Get sample transactions
        $transactions = PawnTransaction::with('customer')
            ->select('id', 'transaction_code', 'status', 'customer_id', 'due_date')
            ->take(5)
            ->get();
        
        // Get transactions that can be extended
        $extendableTransactions = PawnTransaction::with('customer')
            ->whereIn('status', ['active', 'extended', 'overdue'])
            ->select('id', 'transaction_code', 'status', 'customer_id', 'due_date')
            ->take(5)
            ->get();
        
        return response()->json([
            'status' => 'success',
            'counts' => [
                'transactions' => $transactionCount,
                'customers' => $customerCount,
                'users' => $userCount,
            ],
            'sample_transactions' => $transactions->map(function($t) {
                return [
                    'id' => $t->id,
                    'code' => $t->transaction_code,
                    'status' => $t->status,
                    'customer' => $t->customer ? $t->customer->name : 'No customer',
                    'due_date' => $t->due_date->format('Y-m-d'),
                ];
            }),
            'extendable_transactions' => $extendableTransactions->map(function($t) {
                return [
                    'id' => $t->id,
                    'code' => $t->transaction_code,
                    'status' => $t->status,
                    'customer' => $t->customer ? $t->customer->name : 'No customer',
                    'due_date' => $t->due_date->format('Y-m-d'),
                ];
            }),
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);
    }
})->name('test.extension.data');

// Test route untuk membuat transaksi sample jika tidak ada
Route::get('/create-sample-transaction', function () {
    try {
        // Check if we have customers and users
        $customer = Customer::first();
        $officer = User::where('role', 'admin')->orWhere('role', 'petugas')->first();
        
        if (!$customer) {
            // Create a sample customer
            $customer = Customer::create([
                'name' => 'John Doe',
                'phone' => '081234567890',
                'email' => 'john@example.com',
                'id_number' => '1234567890123456',
                'address' => 'Jl. Contoh No. 123',
                'status' => 'active',
            ]);
        }
        
        if (!$officer) {
            return response()->json([
                'status' => 'error',
                'message' => 'No officer found. Please create a user with admin or petugas role first.',
            ]);
        }
        
        // Create a sample transaction
        $transaction = PawnTransaction::create([
            'customer_id' => $customer->id,
            'officer_id' => $officer->id,
            'item_name' => 'Emas Kalung',
            'item_description' => 'Kalung emas 24 karat',
            'item_category' => 'Emas',
            'item_condition' => 'Baik',
            'item_weight' => 10.5,
            'estimated_value' => 5000000,
            'loan_amount' => 4000000,
            'interest_rate' => 2.5,
            'loan_to_value_ratio' => 80,
            'admin_fee' => 25000,
            'insurance_fee' => 15000,
            'loan_period_months' => 4,
            'start_date' => now(),
            'status' => 'active',
        ]);
        
        return response()->json([
            'status' => 'success',
            'message' => 'Sample transaction created successfully',
            'transaction' => [
                'id' => $transaction->id,
                'code' => $transaction->transaction_code,
                'status' => $transaction->status,
                'customer' => $customer->name,
                'due_date' => $transaction->due_date->format('Y-m-d'),
            ],
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);
    }
})->name('create.sample.transaction');

// Debug view for extension testing
Route::get('/debug-extension', function () {
    return view('debug.extension-test');
})->name('debug.extension');

// Test route untuk memastikan extension route berfungsi
Route::get('/test-extension-route', function () {
    try {
        $url = route('extensions.transaction-details', ['transaction_code' => 'TEST123']);
        return response()->json([
            'status' => 'success',
            'message' => 'Extension route is accessible',
            'route_url' => $url,
            'auth_check' => auth()->check(),
            'user' => auth()->user() ? auth()->user()->name : 'Not authenticated'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
    }
})->name('test.extension.route');

// Test direct controller access (without auth for testing)
Route::get('/test-extension-controller', function () {
    try {
        $controller = new \App\Http\Controllers\PawnExtensionController();
        
        // Create a mock request
        $request = new \Illuminate\Http\Request();
        $request->merge(['transaction_code' => 'PG202501150001']); // Sample transaction code
        
        $response = $controller->getTransactionDetails($request);
        
        return response()->json([
            'status' => 'success',
            'message' => 'Controller is accessible',
            'controller_response' => $response->getData()
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
    }
})->name('test.extension.controller');

// Temporary route without auth for testing (REMOVE IN PRODUCTION)
Route::get('/temp-extension-search', [\App\Http\Controllers\PawnExtensionController::class, 'getTransactionDetails'])
    ->name('temp.extension.search');