<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\PawnTransaction;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Support\Facades\DB;

echo "=== FIXING CUSTOMER DATA INCONSISTENCY ===\n\n";

// 1. Check for transactions with invalid customer_id
echo "1. Checking for transactions with invalid customer_id...\n";
$invalidTransactions = PawnTransaction::whereNotIn('customer_id', function($query) {
    $query->select('id')->from('customers');
})->get();

echo "Found {$invalidTransactions->count()} transactions with invalid customer_id\n\n";

if ($invalidTransactions->count() > 0) {
    echo "Invalid transactions:\n";
    foreach ($invalidTransactions as $transaction) {
        echo "- Transaction ID: {$transaction->id}, Code: {$transaction->transaction_code}, Customer ID: {$transaction->customer_id}\n";
    }
    echo "\n";
}

// 2. Check if there are users with role 'nasabah' that should be migrated to customers table
echo "2. Checking for users with role 'nasabah'...\n";
$nasabahUsers = User::where('role', 'nasabah')->get();
echo "Found {$nasabahUsers->count()} users with role 'nasabah'\n\n";

if ($nasabahUsers->count() > 0) {
    echo "Users with role 'nasabah':\n";
    foreach ($nasabahUsers as $user) {
        echo "- User ID: {$user->id}, Name: {$user->name}, Email: {$user->email}\n";
    }
    echo "\n";
}

// 3. Check customers table
echo "3. Checking customers table...\n";
$customersCount = Customer::count();
echo "Total customers in customers table: {$customersCount}\n\n";

// 4. Suggest solutions
echo "=== SUGGESTED SOLUTIONS ===\n\n";

if ($invalidTransactions->count() > 0 && $nasabahUsers->count() > 0) {
    echo "OPTION 1: Migrate users with role 'nasabah' to customers table\n";
    echo "This will create customer records from existing nasabah users.\n\n";
    
    echo "OPTION 2: Update transaction customer_id to match existing customers\n";
    echo "This will update transactions to use valid customer IDs.\n\n";
    
    echo "OPTION 3: Create dummy customers for invalid transactions\n";
    echo "This will create placeholder customer records.\n\n";
} elseif ($invalidTransactions->count() > 0) {
    echo "OPTION: Create dummy customers for invalid transactions\n";
    echo "This will create placeholder customer records for orphaned transactions.\n\n";
} else {
    echo "✅ No data inconsistency found. All transactions have valid customer references.\n\n";
}

// 5. Show sample migration code
if ($nasabahUsers->count() > 0) {
    echo "=== SAMPLE MIGRATION CODE ===\n";
    echo "To migrate users to customers table, you can run:\n\n";
    echo "DB::transaction(function() {\n";
    echo "    \$nasabahUsers = User::where('role', 'nasabah')->get();\n";
    echo "    foreach (\$nasabahUsers as \$user) {\n";
    echo "        Customer::create([\n";
    echo "            'name' => \$user->name,\n";
    echo "            'email' => \$user->email,\n";
    echo "            'phone' => \$user->phone ?? 'N/A',\n";
    echo "            'address' => 'N/A',\n";
    echo "            'id_number' => 'N/A',\n";
    echo "            'id_type' => 'ktp',\n";
    echo "            'status' => 'active'\n";
    echo "        ]);\n";
    echo "    }\n";
    echo "});\n\n";
}

echo "=== COMPLETED ===\n";
echo "Please review the output and choose the appropriate solution.\n";
echo "Make sure to backup your database before making any changes.\n";