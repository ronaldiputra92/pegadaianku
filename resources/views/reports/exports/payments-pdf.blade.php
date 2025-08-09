<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Pembayaran</title>
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
        .payment-type {
            padding: 2px 6px;
            border-radius: 3px;
            color: white;
            font-size: 9px;
        }
        .payment-interest { background-color: #ffc107; color: #000; }
        .payment-partial { background-color: #17a2b8; }
        .payment-full { background-color: #28a745; }
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
        <h1>LAPORAN PEMBAYARAN</h1>
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
        @if(isset($filters['payment_type']) && $filters['payment_type'])
            <p><strong>Jenis Pembayaran:</strong> {{ ucfirst($filters['payment_type']) }}</p>
        @endif
    </div>
    @endif

    <table>
        <thead>
            <tr>
                <th width="10%">Kode Bayar</th>
                <th width="10%">Kode Transaksi</th>
                <th width="15%">Customer</th>
                <th width="12%">Petugas</th>
                <th width="8%">Jenis</th>
                <th width="8%">Metode</th>
                <th width="12%">Jumlah</th>
                <th width="10%">Bunga</th>
                <th width="10%">Pokok</th>
                <th width="5%">Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @forelse($payments as $payment)
            <tr>
                <td>{{ $payment->payment_code }}</td>
                <td>{{ $payment->pawnTransaction->transaction_code }}</td>
                <td>{{ $payment->pawnTransaction->customer->name }}</td>
                <td>{{ $payment->officer->name }}</td>
                <td class="text-center">
                    <span class="payment-type payment-{{ $payment->payment_type }}">
                        {{ ucfirst($payment->payment_type) }}
                    </span>
                </td>
                <td class="text-center">{{ ucfirst($payment->payment_method) }}</td>
                <td class="text-right">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($payment->interest_amount, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($payment->principal_amount, 0, ',', '.') }}</td>
                <td class="text-center">{{ $payment->payment_date->format('d/m/Y') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="10" class="text-center">Tidak ada data pembayaran</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($payments->count() > 0)
    <div class="summary">
        <h3>Ringkasan:</h3>
        <div class="summary-item">
            <span>Total Pembayaran:</span>
            <span>{{ $payments->count() }} transaksi</span>
        </div>
        <div class="summary-item">
            <span>Total Jumlah Bayar:</span>
            <span>Rp {{ number_format($payments->sum('amount'), 0, ',', '.') }}</span>
        </div>
        <div class="summary-item">
            <span>Total Bunga:</span>
            <span>Rp {{ number_format($payments->sum('interest_amount'), 0, ',', '.') }}</span>
        </div>
        <div class="summary-item">
            <span>Total Pokok:</span>
            <span>Rp {{ number_format($payments->sum('principal_amount'), 0, ',', '.') }}</span>
        </div>
    </div>
    @endif

    <div class="footer">
        <p>Laporan ini digenerate secara otomatis oleh sistem pada {{ now()->format('d/m/Y H:i:s') }}</p>
        <p>Sistem Informasi Pegadaian - {{ config('app.name') }}</p>
    </div>
</body>
</html>