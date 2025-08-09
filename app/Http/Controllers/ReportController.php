<?php

namespace App\Http\Controllers;

use App\Models\PawnTransaction;
use App\Models\Payment;
use App\Models\User;
use App\Models\Customer;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index()
    {
        // Summary statistics
        $totalTransactions = PawnTransaction::count();
        $totalPayments = Payment::count();
        $totalCustomers = Customer::where('status', 'active')->count();
        $totalLoanAmount = PawnTransaction::whereIn('status', ['active', 'extended'])->sum('loan_amount');
        $totalRevenue = Payment::sum('interest_amount');

        // Monthly data for charts
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

        return view('reports.index', compact(
            'totalTransactions',
            'totalPayments', 
            'totalCustomers',
            'totalLoanAmount',
            'totalRevenue',
            'monthlyTransactions',
            'monthlyRevenue'
        ));
    }

    public function transactions(Request $request)
    {
        $query = PawnTransaction::with(['customer', 'officer']);

        // Date filters
        if ($request->filled('start_date')) {
            $query->whereDate('start_date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('start_date', '<=', $request->end_date);
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $transactions = $query->latest()->paginate(20);

        // Summary for the filtered data
        $summary = [
            'total_count' => $query->count(),
            'total_loan_amount' => $query->sum('loan_amount'),
            'total_estimated_value' => $query->sum('estimated_value'),
        ];

        return view('reports.transactions', compact('transactions', 'summary'));
    }

    public function payments(Request $request)
    {
        $query = Payment::with(['pawnTransaction.customer', 'officer']);

        // Date filters
        if ($request->filled('start_date')) {
            $query->whereDate('payment_date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('payment_date', '<=', $request->end_date);
        }

        // Payment type filter
        if ($request->filled('payment_type')) {
            $query->where('payment_type', $request->payment_type);
        }

        $payments = $query->latest()->paginate(20);

        // Summary for the filtered data
        $summary = [
            'total_count' => $query->count(),
            'total_amount' => $query->sum('amount'),
            'total_interest' => $query->sum('interest_amount'),
            'total_principal' => $query->sum('principal_amount'),
        ];

        return view('reports.payments', compact('payments', 'summary'));
    }

    public function financial(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->endOfMonth()->format('Y-m-d'));

        // Financial metrics
        $metrics = [
            'total_loans_disbursed' => PawnTransaction::whereBetween('start_date', [$startDate, $endDate])
                ->sum('loan_amount'),
            'total_interest_earned' => Payment::whereBetween('payment_date', [$startDate, $endDate])
                ->sum('interest_amount'),
            'total_principal_collected' => Payment::whereBetween('payment_date', [$startDate, $endDate])
                ->sum('principal_amount'),
            'active_loan_portfolio' => PawnTransaction::whereIn('status', ['active', 'extended'])
                ->sum('loan_amount'),
            'overdue_amount' => PawnTransaction::where('status', 'active')
                ->where('due_date', '<', now())
                ->sum('loan_amount'),
        ];

        // Daily revenue for chart
        $dailyRevenue = Payment::selectRaw('DATE(payment_date) as date, SUM(interest_amount) as revenue')
            ->whereBetween('payment_date', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Transaction status breakdown
        $statusBreakdown = PawnTransaction::selectRaw('status, COUNT(*) as count, SUM(loan_amount) as amount')
            ->groupBy('status')
            ->get();

        return view('reports.financial', compact(
            'metrics',
            'dailyRevenue',
            'statusBreakdown',
            'startDate',
            'endDate'
        ));
    }

    public function export(Request $request)
    {
        $type = $request->get('type', 'transactions');
        $format = $request->get('format', 'pdf');
        
        // If no type specified, show export page
        if (!$request->has('type')) {
            return view('reports.export');
        }

        try {
            switch ($type) {
                case 'transactions':
                    return $this->exportTransactions($request, $format);
                case 'payments':
                    return $this->exportPayments($request, $format);
                case 'customers':
                    return $this->exportCustomers($request, $format);
                case 'financial':
                    return $this->exportFinancial($request, $format);
                default:
                    return back()->with('error', 'Tipe export tidak valid.');
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat export: ' . $e->getMessage());
        }
    }

    private function exportTransactions(Request $request, string $format)
    {
        $query = PawnTransaction::with(['customer', 'officer']);

        // Apply filters
        if ($request->filled('start_date')) {
            $query->whereDate('start_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('start_date', '<=', $request->end_date);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $transactions = $query->latest()->get();

        if ($format === 'pdf') {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('reports.exports.transactions-pdf', [
                'transactions' => $transactions,
                'filters' => $request->all()
            ]);
            
            return $pdf->download('laporan-transaksi-' . now()->format('Y-m-d') . '.pdf');
        } else {
            return $this->exportTransactionsCSV($transactions);
        }
    }

    private function exportPayments(Request $request, string $format)
    {
        $query = Payment::with(['pawnTransaction.customer', 'officer']);

        // Apply filters
        if ($request->filled('start_date')) {
            $query->whereDate('payment_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('payment_date', '<=', $request->end_date);
        }
        if ($request->filled('payment_type')) {
            $query->where('payment_type', $request->payment_type);
        }

        $payments = $query->latest()->get();

        if ($format === 'pdf') {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('reports.exports.payments-pdf', [
                'payments' => $payments,
                'filters' => $request->all()
            ]);
            
            return $pdf->download('laporan-pembayaran-' . now()->format('Y-m-d') . '.pdf');
        } else {
            return $this->exportPaymentsCSV($payments);
        }
    }

    private function exportCustomers(Request $request, string $format)
    {
        $query = Customer::query();

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $customers = $query->orderBy('name')->get();

        if ($format === 'pdf') {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('reports.exports.customers-pdf', [
                'customers' => $customers,
                'filters' => $request->all()
            ]);
            
            return $pdf->download('laporan-customer-' . now()->format('Y-m-d') . '.pdf');
        } else {
            return $this->exportCustomersCSV($customers);
        }
    }

    private function exportFinancial(Request $request, string $format)
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->endOfMonth()->format('Y-m-d'));

        // Financial metrics
        $metrics = [
            'total_loans_disbursed' => PawnTransaction::whereBetween('start_date', [$startDate, $endDate])
                ->sum('loan_amount'),
            'total_interest_earned' => Payment::whereBetween('payment_date', [$startDate, $endDate])
                ->sum('interest_amount'),
            'total_principal_collected' => Payment::whereBetween('payment_date', [$startDate, $endDate])
                ->sum('principal_amount'),
            'active_loan_portfolio' => PawnTransaction::whereIn('status', ['active', 'extended'])
                ->sum('loan_amount'),
            'overdue_amount' => PawnTransaction::where('status', 'active')
                ->where('due_date', '<', now())
                ->sum('loan_amount'),
        ];

        // Daily revenue
        $dailyRevenue = Payment::selectRaw('DATE(payment_date) as date, SUM(interest_amount) as revenue')
            ->whereBetween('payment_date', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Status breakdown
        $statusBreakdown = PawnTransaction::selectRaw('status, COUNT(*) as count, SUM(loan_amount) as amount')
            ->groupBy('status')
            ->get();

        if ($format === 'pdf') {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('reports.exports.financial-pdf', [
                'metrics' => $metrics,
                'dailyRevenue' => $dailyRevenue,
                'statusBreakdown' => $statusBreakdown,
                'startDate' => $startDate,
                'endDate' => $endDate
            ]);
            
            return $pdf->download('laporan-keuangan-' . now()->format('Y-m-d') . '.pdf');
        } else {
            return $this->exportFinancialCSV($metrics, $dailyRevenue, $statusBreakdown, $startDate, $endDate);
        }
    }

    private function exportTransactionsCSV($transactions)
    {
        $filename = 'laporan-transaksi-' . now()->format('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control' => 'max-age=0',
        ];

        $callback = function() use ($transactions) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8 to ensure proper encoding in Excel
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Title and metadata
            fputcsv($file, ['LAPORAN TRANSAKSI GADAI'], ';');
            fputcsv($file, ['Tanggal Export: ' . now()->format('d/m/Y H:i:s')], ';');
            fputcsv($file, ['Total Data: ' . $transactions->count() . ' transaksi'], ';');
            fputcsv($file, [], ';'); // Empty row
            
            // Header with styling hint for Excel
            fputcsv($file, [
                'Kode Transaksi',
                'Customer',
                'Petugas',
                'Nama Barang',
                'Kategori',
                'Kondisi',
                'Nilai Taksir (Rp)',
                'Jumlah Pinjaman (Rp)',
                'Bunga (%)',
                'Periode (Bulan)',
                'Tanggal Mulai',
                'Tanggal Jatuh Tempo',
                'Status'
            ], ';');

            // Data rows
            foreach ($transactions as $transaction) {
                fputcsv($file, [
                    $transaction->transaction_code,
                    $transaction->customer->name,
                    $transaction->officer->name,
                    $transaction->item_name,
                    $transaction->item_category,
                    $transaction->item_condition,
                    $transaction->estimated_value, // Keep as number for Excel formatting
                    $transaction->loan_amount, // Keep as number for Excel formatting
                    $transaction->interest_rate,
                    $transaction->loan_period_months,
                    $transaction->start_date->format('d/m/Y'),
                    $transaction->due_date->format('d/m/Y'),
                    ucfirst($transaction->status)
                ], ';');
            }
            
            // Summary section
            fputcsv($file, [], ';'); // Empty row
            fputcsv($file, ['RINGKASAN'], ';');
            fputcsv($file, ['Total Transaksi', $transactions->count()], ';');
            fputcsv($file, ['Total Nilai Taksir', $transactions->sum('estimated_value')], ';');
            fputcsv($file, ['Total Pinjaman', $transactions->sum('loan_amount')], ';');

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function exportPaymentsCSV($payments)
    {
        $filename = 'laporan-pembayaran-' . now()->format('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control' => 'max-age=0',
        ];

        $callback = function() use ($payments) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8 to ensure proper encoding in Excel
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Title and metadata
            fputcsv($file, ['LAPORAN PEMBAYARAN'], ';');
            fputcsv($file, ['Tanggal Export: ' . now()->format('d/m/Y H:i:s')], ';');
            fputcsv($file, ['Total Data: ' . $payments->count() . ' pembayaran'], ';');
            fputcsv($file, [], ';'); // Empty row
            
            // Header
            fputcsv($file, [
                'Kode Pembayaran',
                'Kode Transaksi',
                'Customer',
                'Petugas',
                'Jenis Pembayaran',
                'Metode Pembayaran',
                'Jumlah Bayar (Rp)',
                'Bunga (Rp)',
                'Pokok (Rp)',
                'Sisa Saldo (Rp)',
                'Tanggal Bayar'
            ], ';');

            // Data
            foreach ($payments as $payment) {
                fputcsv($file, [
                    $payment->payment_code,
                    $payment->pawnTransaction->transaction_code,
                    $payment->pawnTransaction->customer->name,
                    $payment->officer->name,
                    ucfirst($payment->payment_type),
                    ucfirst($payment->payment_method),
                    $payment->amount, // Keep as number for Excel formatting
                    $payment->interest_amount, // Keep as number for Excel formatting
                    $payment->principal_amount, // Keep as number for Excel formatting
                    $payment->remaining_balance, // Keep as number for Excel formatting
                    $payment->payment_date->format('d/m/Y')
                ], ';');
            }
            
            // Summary section
            fputcsv($file, [], ';'); // Empty row
            fputcsv($file, ['RINGKASAN'], ';');
            fputcsv($file, ['Total Pembayaran', $payments->count()], ';');
            fputcsv($file, ['Total Jumlah Bayar', $payments->sum('amount')], ';');
            fputcsv($file, ['Total Bunga', $payments->sum('interest_amount')], ';');
            fputcsv($file, ['Total Pokok', $payments->sum('principal_amount')], ';');

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function exportCustomersCSV($customers)
    {
        $filename = 'laporan-customer-' . now()->format('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control' => 'max-age=0',
        ];

        $callback = function() use ($customers) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8 to ensure proper encoding in Excel
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Title and metadata
            fputcsv($file, ['LAPORAN DATA CUSTOMER'], ';');
            fputcsv($file, ['Tanggal Export: ' . now()->format('d/m/Y H:i:s')], ';');
            fputcsv($file, ['Total Data: ' . $customers->count() . ' customer'], ';');
            fputcsv($file, [], ';'); // Empty row
            
            // Header
            fputcsv($file, [
                'Nama',
                'Email',
                'Telepon',
                'Alamat',
                'No. Identitas',
                'Jenis Identitas',
                'Tanggal Lahir',
                'Tempat Lahir',
                'Jenis Kelamin',
                'Pekerjaan',
                'Pendapatan Bulanan (Rp)',
                'Status'
            ], ';');

            // Data
            foreach ($customers as $customer) {
                fputcsv($file, [
                    $customer->name,
                    $customer->email,
                    $customer->phone,
                    $customer->address,
                    $customer->id_number,
                    strtoupper($customer->id_type),
                    $customer->date_of_birth ? $customer->date_of_birth->format('d/m/Y') : '',
                    $customer->place_of_birth,
                    $customer->gender === 'male' ? 'Laki-laki' : 'Perempuan',
                    $customer->occupation,
                    $customer->monthly_income ?: 0, // Keep as number for Excel formatting
                    ucfirst($customer->status)
                ], ';');
            }
            
            // Summary section
            fputcsv($file, [], ';'); // Empty row
            fputcsv($file, ['RINGKASAN'], ';');
            fputcsv($file, ['Total Customer', $customers->count()], ';');
            fputcsv($file, ['Customer Aktif', $customers->where('status', 'active')->count()], ';');
            fputcsv($file, ['Customer Tidak Aktif', $customers->where('status', 'inactive')->count()], ';');
            fputcsv($file, ['Customer Diblokir', $customers->where('status', 'blocked')->count()], ';');

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function exportFinancialCSV($metrics, $dailyRevenue, $statusBreakdown, $startDate, $endDate)
    {
        $filename = 'laporan-keuangan-' . now()->format('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control' => 'max-age=0',
        ];

        $callback = function() use ($metrics, $dailyRevenue, $statusBreakdown, $startDate, $endDate) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8 to ensure proper encoding in Excel
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Title and metadata
            fputcsv($file, ['LAPORAN KEUANGAN'], ';');
            fputcsv($file, ['Periode: ' . Carbon::parse($startDate)->format('d/m/Y') . ' - ' . Carbon::parse($endDate)->format('d/m/Y')], ';');
            fputcsv($file, ['Tanggal Export: ' . now()->format('d/m/Y H:i:s')], ';');
            fputcsv($file, [], ';'); // Empty row
            
            // Metrics
            fputcsv($file, ['RINGKASAN KEUANGAN'], ';');
            fputcsv($file, ['Keterangan', 'Nilai (Rp)'], ';');
            fputcsv($file, ['Total Pinjaman Disalurkan', $metrics['total_loans_disbursed']], ';');
            fputcsv($file, ['Total Bunga Diterima', $metrics['total_interest_earned']], ';');
            fputcsv($file, ['Total Pokok Terkumpul', $metrics['total_principal_collected']], ';');
            fputcsv($file, ['Portfolio Pinjaman Aktif', $metrics['active_loan_portfolio']], ';');
            fputcsv($file, ['Jumlah Tunggakan', $metrics['overdue_amount']], ';');
            fputcsv($file, [], ';'); // Empty row
            
            // Status breakdown
            fputcsv($file, ['BREAKDOWN STATUS TRANSAKSI'], ';');
            fputcsv($file, ['Status', 'Jumlah', 'Total Nilai (Rp)'], ';');
            foreach ($statusBreakdown as $status) {
                fputcsv($file, [
                    ucfirst($status->status),
                    $status->count,
                    $status->amount // Keep as number for Excel formatting
                ], ';');
            }
            
            // Daily revenue if available
            if ($dailyRevenue->count() > 0) {
                fputcsv($file, [], ';'); // Empty row
                fputcsv($file, ['PENDAPATAN HARIAN'], ';');
                fputcsv($file, ['Tanggal', 'Pendapatan (Rp)'], ';');
                foreach ($dailyRevenue as $revenue) {
                    fputcsv($file, [
                        Carbon::parse($revenue->date)->format('d/m/Y'),
                        $revenue->revenue // Keep as number for Excel formatting
                    ], ';');
                }
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}