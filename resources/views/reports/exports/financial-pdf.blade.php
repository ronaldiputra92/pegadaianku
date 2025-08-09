<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Keuangan</title>
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
        .period {
            text-align: center;
            margin-bottom: 20px;
            font-size: 14px;
            font-weight: bold;
            color: #333;
        }
        .metrics {
            margin-bottom: 30px;
        }
        .metrics h3 {
            margin: 0 0 15px 0;
            font-size: 16px;
            color: #333;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }
        .metric-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 20px;
        }
        .metric-item {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            border-left: 4px solid #007bff;
        }
        .metric-item h4 {
            margin: 0 0 5px 0;
            font-size: 12px;
            color: #666;
        }
        .metric-item .value {
            font-size: 16px;
            font-weight: bold;
            color: #333;
        }
        .status-breakdown {
            margin-top: 30px;
        }
        .status-breakdown h3 {
            margin: 0 0 15px 0;
            font-size: 16px;
            color: #333;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
            font-size: 12px;
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
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        .revenue-chart {
            margin-top: 30px;
        }
        .revenue-chart h3 {
            margin: 0 0 15px 0;
            font-size: 16px;
            color: #333;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }
        .chart-note {
            font-style: italic;
            color: #666;
            font-size: 11px;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN KEUANGAN</h1>
        <p>Sistem Informasi Pegadaian</p>
        <p>Dicetak pada: {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>

    <div class="period">
        Periode: {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}
    </div>

    <div class="metrics">
        <h3>Ringkasan Keuangan</h3>
        <div class="metric-grid">
            <div class="metric-item">
                <h4>Total Pinjaman Disalurkan</h4>
                <div class="value">Rp {{ number_format($metrics['total_loans_disbursed'], 0, ',', '.') }}</div>
            </div>
            <div class="metric-item">
                <h4>Total Bunga Diterima</h4>
                <div class="value">Rp {{ number_format($metrics['total_interest_earned'], 0, ',', '.') }}</div>
            </div>
            <div class="metric-item">
                <h4>Total Pokok Terkumpul</h4>
                <div class="value">Rp {{ number_format($metrics['total_principal_collected'], 0, ',', '.') }}</div>
            </div>
            <div class="metric-item">
                <h4>Portfolio Pinjaman Aktif</h4>
                <div class="value">Rp {{ number_format($metrics['active_loan_portfolio'], 0, ',', '.') }}</div>
            </div>
        </div>
        
        <div class="metric-item" style="border-left-color: #dc3545;">
            <h4>Jumlah Tunggakan</h4>
            <div class="value" style="color: #dc3545;">Rp {{ number_format($metrics['overdue_amount'], 0, ',', '.') }}</div>
        </div>
    </div>

    <div class="status-breakdown">
        <h3>Breakdown Status Transaksi</h3>
        <table>
            <thead>
                <tr>
                    <th width="30%">Status</th>
                    <th width="20%" class="text-center">Jumlah Transaksi</th>
                    <th width="25%" class="text-right">Total Nilai (Rp)</th>
                    <th width="25%" class="text-right">Persentase</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $totalAmount = $statusBreakdown->sum('amount');
                    $totalCount = $statusBreakdown->sum('count');
                @endphp
                @forelse($statusBreakdown as $status)
                <tr>
                    <td>{{ ucfirst($status->status) }}</td>
                    <td class="text-center">{{ $status->count }}</td>
                    <td class="text-right">{{ number_format($status->amount, 0, ',', '.') }}</td>
                    <td class="text-right">
                        {{ $totalAmount > 0 ? number_format(($status->amount / $totalAmount) * 100, 1) : 0 }}%
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center">Tidak ada data</td>
                </tr>
                @endforelse
                @if($statusBreakdown->count() > 0)
                <tr style="background-color: #f8f9fa; font-weight: bold;">
                    <td>TOTAL</td>
                    <td class="text-center">{{ $totalCount }}</td>
                    <td class="text-right">{{ number_format($totalAmount, 0, ',', '.') }}</td>
                    <td class="text-right">100.0%</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>

    @if($dailyRevenue->count() > 0)
    <div class="revenue-chart">
        <h3>Pendapatan Harian (Bunga)</h3>
        <table>
            <thead>
                <tr>
                    <th width="30%">Tanggal</th>
                    <th width="70%" class="text-right">Pendapatan (Rp)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($dailyRevenue as $revenue)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($revenue->date)->format('d/m/Y') }}</td>
                    <td class="text-right">{{ number_format($revenue->revenue, 0, ',', '.') }}</td>
                </tr>
                @endforeach
                <tr style="background-color: #f8f9fa; font-weight: bold;">
                    <td>TOTAL</td>
                    <td class="text-right">{{ number_format($dailyRevenue->sum('revenue'), 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>
        <p class="chart-note">* Pendapatan harian berdasarkan bunga yang diterima dari pembayaran</p>
    </div>
    @endif

    <div class="footer">
        <p>Laporan ini digenerate secara otomatis oleh sistem pada {{ now()->format('d/m/Y H:i:s') }}</p>
        <p>Sistem Informasi Pegadaian - {{ config('app.name') }}</p>
    </div>
</body>
</html>