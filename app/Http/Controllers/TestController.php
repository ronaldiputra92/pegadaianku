<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{
    public function test()
    {
        return response()->json([
            'message' => 'Test route is working!',
            'routes' => [
                'customer-documents.index' => route('customer-documents.index'),
                'customer-history.index' => route('customer-history.index'),
            ]
        ]);
    }
}