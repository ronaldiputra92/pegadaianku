
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CustomerDocumentTestController extends Controller
{
    public function index()
    {
        return response()->json([
            'status' => 'success',
            'message' => 'CustomerDocumentTestController index method works',
            'user' => auth()->user()->name ?? 'No user',
            'role' => auth()->user()->role ?? 'No role'
        ]);
    }
}
