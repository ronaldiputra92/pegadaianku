<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\PawnTransaction;
use App\Models\Payment;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CustomerHistoryController extends Controller
{
    public function index(Request $request)
    {
        $customers = Customer::withCount(['pawnTransactions', 'payments'])
            ->orderBy('name')
            ->get();

        $selectedCustomer = null;
        $transactions = collect();
        $payments = collect();
        $statistics = null;

        if ($request->has('customer_id') && $request->customer_id) {
            $selectedCustomer = Customer::findOrFail($request->customer_id);
            
            // Ambil transaksi
            $transactions = PawnTransaction::where('customer_id', $selectedCustomer->id)
                ->with(['payments', 'officer'])
                ->latest()
                ->get();

            // Ambil pembayaran
            $payments = Payment::whereHas('pawnTransaction', function($query) use ($selectedCustomer) {
                $query->where('customer_id', $selectedCustomer->id);
            })
            ->with(['pawnTransaction', 'officer'])
            ->latest()
            ->get();

            // Statistik
            $statistics = $this->getCustomerStatistics($selectedCustomer);
        }

        return view('customer-history.index', compact(
            'customers', 
            'selectedCustomer', 
            'transactions', 
            'payments', 
            'statistics'
        ));
    }

    public function show(Customer $customer)
    {
        $transactions = PawnTransaction::where('customer_id', $customer->id)
            ->with(['payments', 'officer'])
            ->latest()
            ->paginate(10);

        $payments = Payment::whereHas('pawnTransaction', function($query) use ($customer) {
            $query->where('customer_id', $customer->id);
        })
        ->with(['pawnTransaction', 'officer'])
        ->latest()
        ->paginate(10);

        $statistics = $this->getCustomerStatistics($customer);

        return view('customer-history.show', compact('customer', 'transactions', 'payments', 'statistics'));
    }

    public function transactions(Customer $customer, Request $request)
    {
        $query = PawnTransaction::where('customer_id', $customer->id)
            ->with(['payments', 'officer']);

        // Filter berdasarkan status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan tanggal
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $transactions = $query->latest()->paginate(15);

        return view('customer-history.transactions', compact('customer', 'transactions'));
    }

    public function payments(Customer $customer, Request $request)
    {
        $query = Payment::whereHas('pawnTransaction', function($q) use ($customer) {
            $q->where('customer_id', $customer->id);
        })->with(['pawnTransaction', 'officer']);

        // Filter berdasarkan tipe pembayaran
        if ($request->has('payment_type') && $request->payment_type) {
            $query->where('payment_type', $request->payment_type);
        }

        // Filter berdasarkan tanggal
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('payment_date', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('payment_date', '<=', $request->date_to);
        }

        $payments = $query->latest('payment_date')->paginate(15);

        return view('customer-history.payments', compact('customer', 'payments'));
    }

    public function export(Customer $customer, Request $request)
    {
        $transactions = PawnTransaction::where('customer_id', $customer->id)
            ->with(['payments', 'officer'])
            ->get();

        $payments = Payment::whereHas('pawnTransaction', function($query) use ($customer) {
            $query->where('customer_id', $customer->id);
        })
        ->with(['pawnTransaction', 'officer'])
        ->get();

        // Generate PDF atau Excel export
        // Implementasi export bisa ditambahkan sesuai kebutuhan
        
        return response()->json([
            'message' => 'Export feature akan segera tersedia',
            'customer' => $customer->name,
            'total_transactions' => $transactions->count(),
            'total_payments' => $payments->count()
        ]);
    }

    private function getCustomerStatistics(Customer $customer)
    {
        $transactions = PawnTransaction::where('customer_id', $customer->id)->get();
        $payments = Payment::whereHas('pawnTransaction', function($query) use ($customer) {
            $query->where('customer_id', $customer->id);
        })->get();

        return [
            'total_transactions' => $transactions->count(),
            'active_transactions' => $transactions->where('status', 'active')->count(),
            'completed_transactions' => $transactions->where('status', 'paid')->count(),
            'overdue_transactions' => $transactions->where('status', 'overdue')->count(),
            'total_loan_amount' => $transactions->sum('loan_amount'),
            'total_payments' => $payments->sum('amount'),
            'total_interest_paid' => $payments->where('payment_type', 'interest')->sum('amount'),
            'total_principal_paid' => $payments->where('payment_type', 'principal')->sum('amount'),
            'first_transaction_date' => $transactions->min('created_at'),
            'last_transaction_date' => $transactions->max('created_at'),
            'last_payment_date' => $payments->max('payment_date'),
            'average_loan_amount' => $transactions->count() > 0 ? $transactions->avg('loan_amount') : 0,
        ];
    }
}