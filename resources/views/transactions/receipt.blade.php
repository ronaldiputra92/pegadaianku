<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bukti Transaksi Gadai - {{ $transaction->transaction_code }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        
        .company-name {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .company-info {
            font-size: 10px;
            color: #666;
        }
        
        .receipt-title {
            font-size: 16px;
            font-weight: bold;
            margin: 15px 0;
            text-align: center;
            text-transform: uppercase;
        }
        
        .receipt-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        
        .receipt-info div {
            flex: 1;
        }
        
        .section {
            margin-bottom: 20px;
        }
        
        .section-title {
            font-weight: bold;
            font-size: 13px;
            margin-bottom: 10px;
            padding-bottom: 3px;
            border-bottom: 1px solid #ccc;
        }
        
        .info-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .info-table td {
            padding: 4px 8px;
            vertical-align: top;
        }
        
        .info-table td:first-child {
            width: 30%;
            font-weight: bold;
        }
        
        .info-table td:nth-child(2) {
            width: 5%;
            text-align: center;
        }
        
        .calculation-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        
        .calculation-table th,
        .calculation-table td {
            padding: 6px 8px;
            border: 1px solid #ddd;
            text-align: left;
        }
        
        .calculation-table th {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        
        .calculation-table .amount {
            text-align: right;
        }
        
        .total-row {
            font-weight: bold;
            background-color: #f0f0f0;
        }
        
        .signatures-table {
            width: 100%;
            margin-top: 40px;
            border-collapse: collapse;
        }
        
        .signatures-table td {
            width: 50%;
            text-align: center;
            vertical-align: top;
            padding: 0 20px;
        }
        
        .signature-title {
            font-weight: bold;
            margin-bottom: 15px;
            font-size: 12px;
        }
        
        .signature-space {
            height: 60px;
            margin: 15px 0;
            border-bottom: 1px solid #333;
        }
        
        .signature-name {
            margin-top: 8px;
            font-size: 11px;
            font-weight: normal;
        }
        
        .signature-line {
            border-top: 1px solid #333;
            margin-top: 60px;
            padding-top: 5px;
        }
        
        .signature-image {
            max-width: 150px;
            max-height: 60px;
            margin: 10px 0;
        }
        
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #ccc;
            font-size: 10px;
            text-align: center;
            color: #666;
        }
        
        .status-badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .status-active {
            background-color: #e3f2fd;
            color: #1976d2;
        }
        
        .status-completed {
            background-color: #e8f5e8;
            color: #2e7d32;
        }
        
        .status-overdue {
            background-color: #fff3e0;
            color: #f57c00;
        }
        
        .item-photos {
            display: flex;
            gap: 10px;
            margin-top: 10px;
        }
        
        .item-photo {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border: 1px solid #ddd;
        }
        
        @media print {
            body {
                padding: 10px;
            }
            
            .signatures {
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="company-name">PEGADAIAN DIGITAL</div>
        <div class="company-info">
            Jl. Contoh No. 123, Kota Contoh<br>
            Telp: (021) 1234-5678 | Email: info@pegadaianku.com
        </div>
    </div>

    <!-- Receipt Title -->
    <div class="receipt-title">Bukti Transaksi Gadai</div>

    <!-- Receipt Info -->
    <div class="receipt-info">
        <div>
            <strong>No. Transaksi:</strong> {{ $transaction->transaction_code }}<br>
            <strong>No. Bukti:</strong> {{ $transaction->receipt_number }}<br>
            <strong>Tanggal:</strong> {{ $transaction->created_at->format('d/m/Y H:i') }}
        </div>
        <div style="text-align: right;">
            <strong>Status:</strong> 
            <span class="status-badge status-{{ $transaction->status }}">
                @if($transaction->status == 'active')
                    Aktif
                @elseif($transaction->status == 'paid')
                    Selesai
                @elseif($transaction->status == 'overdue')
                    Terlambat
                @else
                    {{ ucfirst($transaction->status) }}
                @endif
            </span>
        </div>
    </div>

    <!-- Customer Information -->
    <div class="section">
        <div class="section-title">Informasi Nasabah</div>
        <table class="info-table">
            <tr>
                <td>Nama</td>
                <td>:</td>
                <td>{{ $transaction->customer->name }}</td>
            </tr>
            <tr>
                <td>No. Identitas</td>
                <td>:</td>
                <td>{{ $transaction->customer->id_number ?? '-' }}</td>
            </tr>
            <tr>
                <td>No. Telepon</td>
                <td>:</td>
                <td>{{ $transaction->customer->phone ?? '-' }}</td>
            </tr>
            <tr>
                <td>Alamat</td>
                <td>:</td>
                <td>{{ $transaction->customer->address ?? '-' }}</td>
            </tr>
        </table>
    </div>

    <!-- Item Information -->
    <div class="section">
        <div class="section-title">Informasi Barang</div>
        <table class="info-table">
            <tr>
                <td>Nama Barang</td>
                <td>:</td>
                <td>{{ $transaction->item_name }}</td>
            </tr>
            <tr>
                <td>Kategori</td>
                <td>:</td>
                <td>{{ $transaction->item_category }}</td>
            </tr>
            <tr>
                <td>Kondisi</td>
                <td>:</td>
                <td>{{ $transaction->item_condition ?? '-' }}</td>
            </tr>
            @if($transaction->item_weight)
            <tr>
                <td>Berat</td>
                <td>:</td>
                <td>{{ $transaction->item_weight }} gram</td>
            </tr>
            @endif
            @if($transaction->item_description)
            <tr>
                <td>Deskripsi</td>
                <td>:</td>
                <td>{{ $transaction->item_description }}</td>
            </tr>
            @endif
        </table>

        @if($transaction->item_photos && count($transaction->item_photos) > 0)
        <div style="margin-top: 10px;">
            <strong>Foto Barang:</strong>
            <div class="item-photos">
                @foreach(array_slice($transaction->item_photos_urls, 0, 3) as $photo)
                <img src="{{ $photo }}" alt="Foto barang" class="item-photo">
                @endforeach
            </div>
        </div>
        @endif
    </div>

    <!-- Appraisal Information -->
    @if($transaction->isAppraised())
    <div class="section">
        <div class="section-title">Informasi Penilaian</div>
        <table class="info-table">
            @if($transaction->market_value)
            <tr>
                <td>Nilai Pasar</td>
                <td>:</td>
                <td>Rp {{ number_format($transaction->market_value, 0, ',', '.') }}</td>
            </tr>
            @endif
            <tr>
                <td>Nilai Taksir</td>
                <td>:</td>
                <td>Rp {{ number_format($transaction->appraisal_value, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>LTV Ratio</td>
                <td>:</td>
                <td>{{ $transaction->loan_to_value_ratio }}%</td>
            </tr>
            <tr>
                <td>Penilai</td>
                <td>:</td>
                <td>{{ $transaction->appraiser->name ?? '-' }}</td>
            </tr>
            <tr>
                <td>Tanggal Penilaian</td>
                <td>:</td>
                <td>{{ $transaction->appraised_at ? $transaction->appraised_at->format('d/m/Y H:i') : '-' }}</td>
            </tr>
            @if($transaction->appraisal_notes)
            <tr>
                <td>Catatan</td>
                <td>:</td>
                <td>{{ $transaction->appraisal_notes }}</td>
            </tr>
            @endif
        </table>
    </div>
    @endif

    <!-- Loan Information -->
    <div class="section">
        <div class="section-title">Informasi Pinjaman</div>
        <table class="calculation-table">
            <tr>
                <th>Keterangan</th>
                <th>Jumlah</th>
            </tr>
            <tr>
                <td>Jumlah Pinjaman</td>
                <td class="amount">Rp {{ number_format($transaction->loan_amount, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Suku Bunga (per bulan)</td>
                <td class="amount">{{ $transaction->interest_rate }}%</td>
            </tr>
            <tr>
                <td>Jangka Waktu</td>
                <td class="amount">{{ $transaction->loan_period_months }} Bulan</td>
            </tr>
            @if($transaction->admin_fee > 0)
            <tr>
                <td>Biaya Admin</td>
                <td class="amount">Rp {{ number_format($transaction->admin_fee, 0, ',', '.') }}</td>
            </tr>
            @endif
            @if($transaction->insurance_fee > 0)
            <tr>
                <td>Biaya Asuransi</td>
                <td class="amount">Rp {{ number_format($transaction->insurance_fee, 0, ',', '.') }}</td>
            </tr>
            @endif
            @if($transaction->calculateTotalFees() > 0)
            <tr>
                <td>Pinjaman Bersih</td>
                <td class="amount">Rp {{ number_format($transaction->calculateNetLoanAmount(), 0, ',', '.') }}</td>
            </tr>
            @endif
            <tr>
                <td>Tanggal Mulai</td>
                <td class="amount">{{ $transaction->start_date->format('d/m/Y') }}</td>
            </tr>
            <tr>
                <td>Tanggal Jatuh Tempo</td>
                <td class="amount">{{ $transaction->due_date->format('d/m/Y') }}</td>
            </tr>
            <tr class="total-row">
                <td>Estimasi Total Pembayaran</td>
                <td class="amount">Rp {{ number_format($transaction->calculateTotalAmount(), 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>

    <!-- Officer Information -->
    <div class="section">
        <div class="section-title">Petugas</div>
        <table class="info-table">
            <tr>
                <td>Nama Petugas</td>
                <td>:</td>
                <td>{{ $transaction->officer->name }}</td>
            </tr>
            <tr>
                <td>Tanggal Transaksi</td>
                <td>:</td>
                <td>{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
            </tr>
        </table>
    </div>

    <!-- Terms and Conditions -->
    <div class="section">
        <div class="section-title">Syarat dan Ketentuan</div>
        <div style="font-size: 10px; line-height: 1.3;">
            <ol>
                <li>Barang gadai harus ditebus sebelum tanggal jatuh tempo.</li>
                <li>Apabila melewati jatuh tempo, akan dikenakan denda sesuai ketentuan.</li>
                <li>Barang yang tidak ditebus dalam waktu yang ditentukan akan dilelang.</li>
                <li>Nasabah bertanggung jawab atas kebenaran data dan barang yang digadaikan.</li>
                <li>Bukti ini harus dibawa saat melakukan pembayaran atau penebusan.</li>
                <li>Kehilangan bukti ini harus segera dilaporkan untuk penerbitan surat keterangan.</li>
            </ol>
        </div>
    </div>

    <!-- Signatures -->
    <table class="signatures-table">
        <tr>
            <td>
                <div class="signature-title">Nasabah</div>
                <div class="signature-space"></div>
                <div class="signature-name">{{ $transaction->customer->name }}</div>
            </td>
            <td>
                <div class="signature-title">Petugas</div>
                <div class="signature-space"></div>
                <div class="signature-name">{{ $transaction->officer->name }}</div>
            </td>
        </tr>
    </table>

    <!-- Footer -->
    <div class="footer">
        <p>Dokumen ini dicetak secara otomatis pada {{ now()->format('d/m/Y H:i') }}</p>
        <p>Untuk informasi lebih lanjut, hubungi customer service kami</p>
    </div>
</body>
</html>