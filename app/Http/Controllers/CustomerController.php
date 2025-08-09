<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = Customer::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('id_number', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $customers = $query->withCount('pawnTransactions')->latest()->paginate(15);

        return view('customers.index', compact('customers'));
    }

    public function create()
    {
        return view('customers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:customers',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'id_number' => 'required|string|max:50|unique:customers',
            'id_type' => 'nullable|in:ktp,sim,passport',
            'date_of_birth' => 'nullable|date',
            'place_of_birth' => 'nullable|string|max:255',
            'gender' => 'required|in:male,female',
            'occupation' => 'nullable|string|max:255',
            'monthly_income' => 'nullable|numeric|min:0',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:20',
            'notes' => 'nullable|string',
        ]);

        Customer::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'id_number' => $request->id_number,
            'id_type' => $request->id_type ?? 'ktp',
            'date_of_birth' => $request->date_of_birth,
            'place_of_birth' => $request->place_of_birth,
            'gender' => $request->gender,
            'occupation' => $request->occupation,
            'monthly_income' => $request->monthly_income,
            'emergency_contact_name' => $request->emergency_contact_name,
            'emergency_contact_phone' => $request->emergency_contact_phone,
            'notes' => $request->notes,
            'status' => 'active',
        ]);

        return redirect()->route('customers.index')
            ->with('success', 'Nasabah berhasil ditambahkan.');
    }

    public function show(Customer $customer)
    {
        $customer->load(['pawnTransactions.payments', 'documents']);
        
        return view('customers.show', compact('customer'));
    }

    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:customers,email,' . $customer->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'id_number' => 'required|string|max:50|unique:customers,id_number,' . $customer->id,
            'id_type' => 'nullable|in:ktp,sim,passport',
            'date_of_birth' => 'nullable|date',
            'place_of_birth' => 'nullable|string|max:255',
            'gender' => 'required|in:male,female',
            'occupation' => 'nullable|string|max:255',
            'monthly_income' => 'nullable|numeric|min:0',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:20',
            'notes' => 'nullable|string',
            'status' => 'required|in:active,inactive,blocked',
        ]);

        $customer->update($request->only([
            'name', 'email', 'phone', 'address', 'id_number', 'id_type',
            'date_of_birth', 'place_of_birth', 'gender', 'occupation',
            'monthly_income', 'emergency_contact_name', 'emergency_contact_phone',
            'notes', 'status'
        ]));

        return redirect()->route('customers.show', $customer)
            ->with('success', 'Data nasabah berhasil diperbarui.');
    }

    public function destroy(Customer $customer)
    {
        if ($customer->pawnTransactions()->count() > 0) {
            return back()->with('error', 'Nasabah yang memiliki transaksi tidak dapat dihapus.');
        }

        $customer->delete();

        return redirect()->route('customers.index')
            ->with('success', 'Nasabah berhasil dihapus.');
    }
}