<?php

// Simple test to check if the route exists
echo "Testing customer-documents route...\n";

// Check if we can access the route helper
try {
    $url = route('customer-documents.index');
    echo "✓ Route 'customer-documents.index' found: $url\n";
} catch (Exception $e) {
    echo "✗ Route 'customer-documents.index' not found: " . $e->getMessage() . "\n";
}

try {
    $url = route('customer-history.index');
    echo "✓ Route 'customer-history.index' found: $url\n";
} catch (Exception $e) {
    echo "✗ Route 'customer-history.index' not found: " . $e->getMessage() . "\n";
}

echo "\nDirect URL test:\n";
echo "customer-documents: " . url('/customer-documents') . "\n";
echo "customer-history: " . url('/customer-history') . "\n";