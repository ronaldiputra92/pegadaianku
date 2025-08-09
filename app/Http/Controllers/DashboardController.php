<?php

namespace App\Http\Controllers;

use App\Models\PawnTransaction;
use App\Models\Payment;
use App\Models\User;
use App\Models\Customer;
use App\Models\Notification;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        if ($user->isCustomer()) {
            return $this->customerDashboard();
        } elseif ($user->isOfficer()) {
            return $this->officerDashboard();
        } else {
            return $this->adminDashboard();
        }
    }

    private function customerDashboard()
    {
        $user = auth()->user();
        
        $activeTransactions = $user->pawnTransactions()
            ->whereIn('status', ['active', 'extended'])
            ->with('payments')
            ->get();

        $totalLoanAmount = $activeTransactions->sum('loan_amount');
        $totalInterest = $activeTransactions->sum(function($transaction) {
            return $transaction->calculateInterest();
        });

        $dueSoonTransactions = $activeTransactions->filter(function($transaction) {
            return $transaction->getDaysUntilDue() <= 7 && $transaction->getDaysUntilDue() > 0;
        });

        $overdueTransactions = $activeTransactions->filter(function($transaction) {
            return $transaction->isOverdue();
        });

        $recentPayments = Payment::whereHas('pawnTransaction', function($query) use ($user) {
            $query->where('customer_id', $user->id);
        })->with('pawnTransaction')->latest()->take(5)->get();

        return view('dashboard.customer', compact(
            'activeTransactions',
            'totalLoanAmount',
            'totalInterest',
            'dueSoonTransactions',
            'overdueTransactions',
            'recentPayments'
        ));
    }

    private function officerDashboard()
    {
        $todayTransactions = PawnTransaction::whereDate('created_at', today())->count();
        $todayPayments = Payment::whereDate('created_at', today())->count();
        $activeTransactions = PawnTransaction::whereIn('status', ['active', 'extended'])->count();
        $overdueTransactions = PawnTransaction::where('status', 'active')
            ->where('due_date', '<', now())
            ->count();

        $recentTransactions = PawnTransaction::with(['customer', 'officer'])
            ->latest()
            ->take(10)
            ->get();

        $dueSoonTransactions = PawnTransaction::where('status', 'active')
            ->whereBetween('due_date', [now(), now()->addDays(7)])
            ->with('customer')
            ->get();

        return view('dashboard.officer', compact(
            'todayTransactions',
            'todayPayments',
            'activeTransactions',
            'overdueTransactions',
            'recentTransactions',
            'dueSoonTransactions'
        ));
    }

    private function adminDashboard()
    {
        // Statistics
        $totalCustomers = Customer::where('status', 'active')->count();
        $totalOfficers = User::where('role', 'petugas')->count();
        $activeTransactions = PawnTransaction::whereIn('status', ['active', 'extended'])->count();
        $totalLoanAmount = PawnTransaction::whereIn('status', ['active', 'extended'])->sum('loan_amount');

        // Monthly statistics
        $monthlyTransactions = PawnTransaction::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereYear('created_at', now()->year)
            ->groupBy('month')
            ->pluck('count', 'month')
            ->toArray();

        $monthlyRevenue = Payment::selectRaw('MONTH(created_at) as month, SUM(interest_amount) as revenue')
            ->whereYear('created_at', now()->year)
            ->groupBy('month')
            ->pluck('revenue', 'month')
            ->toArray();

        // Recent activities
        $recentTransactions = PawnTransaction::with(['customer', 'officer'])
            ->latest()
            ->take(5)
            ->get();

        $recentPayments = Payment::with(['pawnTransaction.customer', 'officer'])
            ->latest()
            ->take(5)
            ->get();

        // Overdue transactions
        $overdueTransactions = PawnTransaction::where('status', 'active')
            ->where('due_date', '<', now())
            ->with('customer')
            ->count();

        return view('dashboard.admin', compact(
            'totalCustomers',
            'totalOfficers',
            'activeTransactions',
            'totalLoanAmount',
            'monthlyTransactions',
            'monthlyRevenue',
            'recentTransactions',
            'recentPayments',
            'overdueTransactions'
        ));
    }
}