<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bukti Perpanjangan Gadai - {{ $extension->extension_code }}</title>
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
            text-align: center;
            margin: 20px 0;
            text-transform: uppercase;
        }
        
        .receipt-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        
        .info-section {
            width: 48%;
        }
        
        .info-row {
            display: flex;
            margin-bottom: 5px;
        }
        
        .info-label {
            width: 120px;
            font-weight: bold;
        }
        
        .info-value {
            flex: 1;
        }
        
        .section {
            margin-bottom: 20px;
        }
        
        .section-title {
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 10px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 5px;
        }
        
        .fee-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        
        .fee-table th,
        .fee-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        
        .fee-table th {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        
        .fee-table .amount {
            text-align: right;
        }
        
        .total-row {
            background-color: #f0f8ff;
            font-weight: bold;
        }
        
        .signature-section {
            display: flex;
            justify-content: space-between;
            margin-top: 40px;
        }
        
        .signature-box {
            width: 200px;
            text-align: center;
        }
        
        .signature-line {
            border-top: 1px solid #333;
            margin-top: 60px;
            padding-top: 5px;
        }
        
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ccc;
            padding-top: 10px;
        }
        
        .important-note {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            padding: 10px;
            margin: 15px 0;
            border-radius: 4px;
        }
        
        @media print {
            body {
                padding: 0;
            }
            
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="company-name">{{ config('pawn.company_name', 'PEGADAIAN DIGITAL') }}</div>
        <div class="company-info">
            {{ config('pawn.company_address', 'Jl. Contoh No. 123, Kota Contoh') }}<br>
            Telp: {{ config('pawn.company_phone', '(021) 1234-5678') }} | Email: {{ config('pawn.company_email', 'info@pegadaianku.com') }}
        </div>
    </div>

    <!-- Receipt Title -->
    <div class="receipt-title">Bukti Perpanjangan Gadai</div>

    <!-- Receipt Information -->
    <div class="receipt-info">
        <div class="info-section">
            <div class="info-row">
                <span class="info-label">No. Bukti:</span>
                <span class="info-value">{{ $extension->receipt_number ?? $extension->extension_code }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Kode Perpanjangan:</span>
                <span class="info-value">{{ $extension->extension_code }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Tanggal:</span>
                <span class="info-value">{{ $extension->created_at->format('d/m/Y H:i') }}</span>
            </div>
        </div>
        <div class="info-section">
            <div class="info-row">
                <span class="info-label">Petugas:</span>
                <span class="info-value">{{ $extension->officer->name }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Kode Transaksi:</span>
                <span class="info-value">{{ $extension->transaction->transaction_code }}</span>
            </div>
        </div>
    </div>

    <!-- Customer Information -->
    <div class="section">
        <div class="section-title">Informasi Nasabah</div>
        <div class="info-row">
            <span class="info-label">Nama:</span>
            <span class="info-value">{{ $extension->transaction->customer->name }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">No. Telepon:</span>
            <span class="info-value">{{ $extension->transaction->customer->phone }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Alamat:</span>
            <span class="info-value">{{ $extension->transaction->customer->address }}</span>
        </div>
    </div>

    <!-- Transaction Information -->
    <div class="section">
        <div class="section-title">Informasi Transaksi</div>
        <div class="info-row">
            <span class="info-label">Nama Barang:</span>
            <span class="info-value">{{ $extension->transaction->item_name }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Jumlah Pinjaman:</span>
            <span class="info-value">Rp {{ number_format($extension->transaction->loan_amount, 0, ',', '.') }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Suku Bunga:</span>
            <span class="info-value">{{ $extension->transaction->interest_rate }}% per bulan</span>
        </div>
    </div>

    <!-- Extension Information -->
    <div class="section">
        <div class="section-title">Informasi Perpanjangan</div>
        <div class="info-row">
            <span class="info-label">Periode Perpanjangan:</span>
            <span class="info-value">{{ $extension->extension_months }} bulan</span>
        </div>
        <div class="info-row">
            <span class="info-label">Jatuh Tempo Lama:</span>
            <span class="info-value">{{ $extension->original_due_date->format('d/m/Y') }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Jatuh Tempo Baru:</span>
            <span class="info-value"><strong>{{ $extension->new_due_date->format('d/m/Y') }}</strong></span>
        </div>
    </div>

    <!-- Fee Breakdown -->
    <div class="section">
        <div class="section-title">Rincian Biaya Perpanjangan</div>
        <table class="fee-table">
            <thead>
                <tr>
                    <th>Keterangan</th>
                    <th class="amount">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Bunga Perpanjangan ({{ $extension->extension_months }} bulan)</td>
                    <td class="amount">Rp {{ number_format($extension->interest_amount, 0, ',', '.') }}</td>
                </tr>
                @if($extension->penalty_amount > 0)
                <tr>
                    <td>Denda Keterlambatan</td>
                    <td class="amount">Rp {{ number_format($extension->penalty_amount, 0, ',', '.') }}</td>
                </tr>
                @endif
                <tr>
                    <td>Biaya Administrasi</td>
                    <td class="amount">Rp {{ number_format($extension->admin_fee, 0, ',', '.') }}</td>
                </tr>
                <tr class="total-row">
                    <td><strong>Total Biaya Perpanjangan</strong></td>
                    <td class="amount"><strong>Rp {{ number_format($extension->total_amount, 0, ',', '.') }}</strong></td>
                </tr>
            </tbody>
        </table>
    </div>

    @if($extension->notes)
    <!-- Notes -->
    <div class="section">
        <div class="section-title">Catatan</div>
        <p>{{ $extension->notes }}</p>
    </div>
    @endif

    <!-- Important Note -->
    <div class="important-note">
        <strong>PENTING:</strong>
        <ul style="margin: 5px 0; padding-left: 20px;">
            <li>Simpan bukti ini sebagai tanda bukti perpanjangan gadai</li>
            <li>Jatuh tempo baru: <strong>{{ $extension->new_due_date->format('d/m/Y') }}</strong></li>
            <li>Pembayaran dapat dilakukan sebelum atau pada tanggal jatuh tempo</li>
            <li>Keterlambatan pembayaran akan dikenakan denda sesuai ketentuan</li>
        </ul>
    </div>

    <!-- Signature Section -->
    <div class="signature-section">
        <div class="signature-box">
            <div>Nasabah</div>
            <div class="signature-line">{{ $extension->transaction->customer->name }}</div>
        </div>
        <div class="signature-box">
            <div>Petugas</div>
            <div class="signature-line">{{ $extension->officer->name }}</div>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>Dokumen ini dicetak secara otomatis pada {{ now()->format('d/m/Y H:i') }}</p>
        <p>Untuk informasi lebih lanjut, hubungi customer service kami</p>
    </div>

    <script>
        // Auto print when page loads
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>