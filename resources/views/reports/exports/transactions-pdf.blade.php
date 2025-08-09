<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Transaksi Gadai</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
            color: #333;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        .filters {
            margin-bottom: 20px;
            background-color: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
        }
        .filters h3 {
            margin: 0 0 10px 0;
            font-size: 14px;
        }
        .filters p {
            margin: 2px 0;
            font-size: 11px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            font-size: 10px;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .status {
            padding: 2px 6px;
            border-radius: 3px;
            color: white;
            font-size: 9px;
        }
        .status-active { background-color: #28a745; }
        .status-extended { background-color: #ffc107; color: #000; }
        .status-paid { background-color: #17a2b8; }
        .status-overdue { background-color: #dc3545; }
        .status-auction { background-color: #6c757d; }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        .summary {
            margin-top: 20px;
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
        }
        .summary h3 {
            margin: 0 0 10px 0;
            font-size: 14px;
        }
        .summary-item {
            display: flex;
            justify-content: space-between;
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN TRANSAKSI GADAI</h1>
        <p>Sistem Informasi Pegadaian</p>
        <p>Dicetak pada: {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>

    @if(!empty($filters))
    <div class="filters">
        <h3>Filter yang Diterapkan:</h3>
        @if(isset($filters['start_date']) && $filters['start_date'])
            <p><strong>Tanggal Mulai:</strong> {{ \Carbon\Carbon::parse($filters['start_date'])->format('d/m/Y') }}</p>
        @endif
        @if(isset($filters['end_date']) && $filters['end_date'])
            <p><strong>Tanggal Akhir:</strong> {{ \Carbon\Carbon::parse($filters['end_date'])->format('d/m/Y') }}</p>
        @endif
        @if(isset($filters['status']) && $filters['status'])
            <p><strong>Status:</strong> {{ ucfirst($filters['status']) }}</p>
        @endif
    </div>
    @endif

    <table>
        <thead>
            <tr>
                <th width="8%">Kode</th>
                <th width="12%">Customer</th>
                <th width="10%">Petugas</th>
                <th width="15%">Barang</th>
                <th width="8%">Kategori</th>
                <th width="8%">Kondisi</th>
                <th width="10%">Nilai Taksir</th>
                <th width="10%">Pinjaman</th>
                <th width="5%">Bunga</th>
                <th width="8%">Jatuh Tempo</th>
                <th width="6%">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $transaction)
            <tr>
                <td>{{ $transaction->transaction_code }}</td>
                <td>{{ $transaction->customer->name }}</td>
                <td>{{ $transaction->officer->name }}</td>
                <td>{{ $transaction->item_name }}</td>
                <td>{{ $transaction->item_category }}</td>
                <td>{{ $transaction->item_condition }}</td>
                <td class="text-right">Rp {{ number_format($transaction->estimated_value, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($transaction->loan_amount, 0, ',', '.') }}</td>
                <td class="text-center">{{ $transaction->interest_rate }}%</td>
                <td class="text-center">{{ $transaction->due_date->format('d/m/Y') }}</td>
                <td class="text-center">
                    <span class="status status-{{ $transaction->status }}">
                        {{ ucfirst($transaction->status) }}
                    </span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="11" class="text-center">Tidak ada data transaksi</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($transactions->count() > 0)
    <div class="summary">
        <h3>Ringkasan:</h3>
        <div class="summary-item">
            <span>Total Transaksi:</span>
            <span>{{ $transactions->count() }} transaksi</span>
        </div>
        <div class="summary-item">
            <span>Total Nilai Taksir:</span>
            <span>Rp {{ number_format($transactions->sum('estimated_value'), 0, ',', '.') }}</span>
        </div>
        <div class="summary-item">
            <span>Total Pinjaman:</span>
            <span>Rp {{ number_format($transactions->sum('loan_amount'), 0, ',', '.') }}</span>
        </div>
    </div>
    @endif

    <div class="footer">
        <p>Laporan ini digenerate secara otomatis oleh sistem pada {{ now()->format('d/m/Y H:i:s') }}</p>
        <p>Sistem Informasi Pegadaian - {{ config('app.name') }}</p>
    </div>
</body>
</html>