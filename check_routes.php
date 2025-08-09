<?php

// Simple script to check if routes are registered
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';

try {
    $routes = $app->make('router')->getRoutes();
    
    echo "Checking for customer-documents routes:\n";
    echo "=====================================\n";
    
    foreach ($routes as $route) {
        $name = $route->getName();
        if ($name && strpos($name, 'customer-documents') !== false) {
            echo "✓ Route found: " . $name . " -> " . $route->uri() . "\n";
        }
    }
    
    echo "\nChecking for customer-history routes:\n";
    echo "====================================\n";
    
    foreach ($routes as $route) {
        $name = $route->getName();
        if ($name && strpos($name, 'customer-history') !== false) {
            echo "✓ Route found: " . $name . " -> " . $route->uri() . "\n";
        }
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}