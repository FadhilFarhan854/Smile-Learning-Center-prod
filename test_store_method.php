<?php

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// Create a test request for store method
$request = Request::createFromGlobals();
$request->replace([
    'siswa_id' => 1,
    'year' => 2024,
    'month' => 1,
    'date' => 15,
    'status' => 'masuk',
    'pertemuan' => '5'
]);

// Set request method to POST
$request->setMethod('POST');

echo "Testing AbsenController store method...\n";
echo "Request data:\n";
print_r($request->all());

try {
    // Create controller instance
    $controller = new App\Http\Controllers\AbsenController();
    
    // Call store method
    $response = $controller->store($request);
    
    echo "\nStore method executed successfully!\n";
    echo "Response type: " . get_class($response) . "\n";
    
    if (method_exists($response, 'getContent')) {
        echo "Response content: " . $response->getContent() . "\n";
    }
    
} catch (Exception $e) {
    echo "\nError in store method: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
    echo "\nStack trace:\n" . $e->getTraceAsString() . "\n";
}
