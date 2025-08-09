<?php

require_once 'vendor/autoload.php';

// Load Laravel application
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

try {
    echo "Removing signature fields from pawn_transactions table...\n";
    
    // Check if columns exist before dropping them
    $columns = [];
    if (Schema::hasColumn('pawn_transactions', 'customer_signature')) {
        $columns[] = 'customer_signature';
    }
    if (Schema::hasColumn('pawn_transactions', 'officer_signature')) {
        $columns[] = 'officer_signature';
    }
    if (Schema::hasColumn('pawn_transactions', 'signed_at')) {
        $columns[] = 'signed_at';
    }
    
    if (!empty($columns)) {
        Schema::table('pawn_transactions', function ($table) use ($columns) {
            $table->dropColumn($columns);
        });
        echo "Successfully removed columns: " . implode(', ', $columns) . "\n";
    } else {
        echo "No signature columns found to remove.\n";
    }
    
    echo "Signature fields removal completed successfully!\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}