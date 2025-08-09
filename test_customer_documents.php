<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// Test if the route exists
$routes = app('router')->getRoutes();

echo "Testing Customer Documents Routes:\n";
echo "================================\n\n";

foreach ($routes as $route) {
    if (str_contains($route->uri(), 'customer-documents')) {
        echo "URI: " . $route->uri() . "\n";
        echo "Name: " . $route->getName() . "\n";
        echo "Methods: " . implode(', ', $route->methods()) . "\n";
        echo "Action: " . $route->getActionName() . "\n";
        echo "Middleware: " . implode(', ', $route->middleware()) . "\n";
        echo "---\n";
    }
}

// Test if controller exists and is accessible
echo "\nTesting Controller:\n";
echo "==================\n";

try {
    $controller = new App\Http\Controllers\CustomerDocumentController();
    echo "✓ CustomerDocumentController can be instantiated\n";
} catch (Exception $e) {
    echo "✗ Error instantiating CustomerDocumentController: " . $e->getMessage() . "\n";
}

// Test if model exists
echo "\nTesting Model:\n";
echo "=============\n";

try {
    $model = new App\Models\CustomerDocument();
    echo "✓ CustomerDocument model can be instantiated\n";
} catch (Exception $e) {
    echo "✗ Error instantiating CustomerDocument model: " . $e->getMessage() . "\n";
}

echo "\nDone!\n";