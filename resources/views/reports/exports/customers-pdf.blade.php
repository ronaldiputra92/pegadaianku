<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Data Customer</title>
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
        .status-inactive { background-color: #6c757d; }
        .status-blocked { background-color: #dc3545; }
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
        <h1>LAPORAN DATA CUSTOMER</h1>
        <p>Sistem Informasi Pegadaian</p>
        <p>Dicetak pada: {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>

    @if(!empty($filters))
    <div class="filters">
        <h3>Filter yang Diterapkan:</h3>
        @if(isset($filters['status']) && $filters['status'])
            <p><strong>Status:</strong> {{ ucfirst($filters['status']) }}</p>
        @endif
    </div>
    @endif

    <table>
        <thead>
            <tr>
                <th width="15%">Nama</th>
                <th width="15%">Email</th>
                <th width="10%">Telepon</th>
                <th width="20%">Alamat</th>
                <th width="12%">No. Identitas</th>
                <th width="8%">Jenis ID</th>
                <th width="8%">Tgl Lahir</th>
                <th width="7%">Gender</th>
                <th width="5%">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($customers as $customer)
            <tr>
                <td>{{ $customer->name }}</td>
                <td>{{ $customer->email }}</td>
                <td>{{ $customer->phone }}</td>
                <td>{{ Str::limit($customer->address, 30) }}</td>
                <td>{{ $customer->id_number }}</td>
                <td class="text-center">{{ strtoupper($customer->id_type) }}</td>
                <td class="text-center">
                    {{ $customer->date_of_birth ? $customer->date_of_birth->format('d/m/Y') : '-' }}
                </td>
                <td class="text-center">
                    {{ $customer->gender === 'male' ? 'L' : 'P' }}
                </td>
                <td class="text-center">
                    <span class="status status-{{ $customer->status }}">
                        {{ ucfirst($customer->status) }}
                    </span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="text-center">Tidak ada data customer</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($customers->count() > 0)
    <div class="summary">
        <h3>Ringkasan:</h3>
        <div class="summary-item">
            <span>Total Customer:</span>
            <span>{{ $customers->count() }} orang</span>
        </div>
        <div class="summary-item">
            <span>Customer Aktif:</span>
            <span>{{ $customers->where('status', 'active')->count() }} orang</span>
        </div>
        <div class="summary-item">
            <span>Customer Tidak Aktif:</span>
            <span>{{ $customers->where('status', 'inactive')->count() }} orang</span>
        </div>
        <div class="summary-item">
            <span>Customer Diblokir:</span>
            <span>{{ $customers->where('status', 'blocked')->count() }} orang</span>
        </div>
    </div>
    @endif

    <div class="footer">
        <p>Laporan ini digenerate secara otomatis oleh sistem pada {{ now()->format('d/m/Y H:i:s') }}</p>
        <p>Sistem Informasi Pegadaian - {{ config('app.name') }}</p>
    </div>
</body>
</html>