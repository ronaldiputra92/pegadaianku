<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bukti Pembayaran - {{ $payment->payment_code }}</title>
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
        
        .payment-summary {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
        
        .payment-amount {
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            color: #2563eb;
            margin: 20px 0;
            padding: 15px;
            border: 2px solid #2563eb;
            border-radius: 5px;
        }
        
        .breakdown-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        
        .breakdown-table th,
        .breakdown-table td {
            padding: 8px;
            border: 1px solid #ddd;
            text-align: left;
        }
        
        .breakdown-table th {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        
        .breakdown-table .amount {
            text-align: right;
            font-weight: bold;
        }
        
        .signatures {
            display: flex;
            justify-content: space-between;
            margin-top: 40px;
        }
        
        .signature-box {
            width: 45%;
            text-align: center;
        }
        
        .signature-line {
            border-top: 1px solid #333;
            margin-top: 60px;
            padding-top: 5px;
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
            padding: 4px 8px;
            border-radius: 3px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .status-full {
            background-color: #dcfce7;
            color: #166534;
        }
        
        .status-partial {
            background-color: #dbeafe;
            color: #1d4ed8;
        }
        
        .status-interest {
            background-color: #fef3c7;
            color: #92400e;
        }
        
        .highlight-box {
            background-color: #f0f9ff;
            border: 2px solid #0ea5e9;
            border-radius: 5px;
            padding: 15px;
            margin: 15px 0;
            text-align: center;
        }
        
        .highlight-box.success {
            background-color: #f0fdf4;
            border-color: #22c55e;
            color: #166534;
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
    <div class="receipt-title">
        @if($payment->isFullPayment())
            Bukti Pelunasan
        @else
            Bukti Pembayaran
        @endif
    </div>

    <!-- Receipt Info -->
    <div class="receipt-info">
        <div>
            <strong>No. Pembayaran:</strong> {{ $payment->payment_code }}<br>
            @if($payment->receipt_number)
            <strong>No. Bukti:</strong> {{ $payment->receipt_number }}<br>
            @endif
            <strong>Tanggal:</strong> {{ $payment->payment_date->format('d/m/Y H:i') }}
        </div>
        <div style="text-align: right;">
            <strong>Jenis:</strong> 
            <span class="status-badge status-{{ $payment->payment_type }}">
                @if($payment->payment_type == 'interest')
                    Bunga
                @elseif($payment->payment_type == 'partial')
                    Sebagian
                @elseif($payment->payment_type == 'full')
                    Pelunasan
                @else
                    {{ ucfirst($payment->payment_type) }}
                @endif
            </span>
        </div>
    </div>

    <!-- Payment Amount Highlight -->
    <div class="payment-amount">
        JUMLAH PEMBAYARAN<br>
        Rp {{ number_format($payment->amount, 0, ',', '.') }}
    </div>

    <!-- Customer Information -->
    <div class="section">
        <div class="section-title">Informasi Nasabah</div>
        <table class="info-table">
            <tr>
                <td>Nama</td>
                <td>:</td>
                <td>{{ $payment->pawnTransaction->customer->name }}</td>
            </tr>
            <tr>
                <td>No. Telepon</td>
                <td>:</td>
                <td>{{ $payment->pawnTransaction->customer->phone ?? '-' }}</td>
            </tr>
            <tr>
                <td>Alamat</td>
                <td>:</td>
                <td>{{ $payment->pawnTransaction->customer->address ?? '-' }}</td>
            </tr>
        </table>
    </div>

    <!-- Transaction Information -->
    <div class="section">
        <div class="section-title">Informasi Transaksi</div>
        <table class="info-table">
            <tr>
                <td>Kode Transaksi</td>
                <td>:</td>
                <td>{{ $payment->pawnTransaction->transaction_code }}</td>
            </tr>
            <tr>
                <td>Barang Gadai</td>
                <td>:</td>
                <td>{{ $payment->pawnTransaction->item_name }}</td>
            </tr>
            <tr>
                <td>Pinjaman Pokok</td>
                <td>:</td>
                <td>Rp {{ number_format($payment->pawnTransaction->loan_amount, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Tanggal Mulai</td>
                <td>:</td>
                <td>{{ $payment->pawnTransaction->start_date->format('d/m/Y') }}</td>
            </tr>
            <tr>
                <td>Jatuh Tempo</td>
                <td>:</td>
                <td>{{ $payment->pawnTransaction->due_date->format('d/m/Y') }}</td>
            </tr>
        </table>
    </div>

    <!-- Payment Information -->
    <div class="section">
        <div class="section-title">Detail Pembayaran</div>
        <table class="info-table">
            <tr>
                <td>Metode Pembayaran</td>
                <td>:</td>
                <td>{{ $payment->payment_method_display }}</td>
            </tr>
            @if($payment->payment_method === 'transfer')
            <tr>
                <td>Bank</td>
                <td>:</td>
                <td>{{ $payment->bank_name ?? '-' }}</td>
            </tr>
            <tr>
                <td>No. Referensi</td>
                <td>:</td>
                <td>{{ $payment->reference_number ?? '-' }}</td>
            </tr>
            @endif
            <tr>
                <td>Tanggal Pembayaran</td>
                <td>:</td>
                <td>{{ $payment->payment_date->format('d/m/Y H:i') }}</td>
            </tr>
            <tr>
                <td>Petugas</td>
                <td>:</td>
                <td>{{ $payment->officer->name }}</td>
            </tr>
            @if($payment->notes)
            <tr>
                <td>Catatan</td>
                <td>:</td>
                <td>{{ $payment->notes }}</td>
            </tr>
            @endif
        </table>
    </div>

    <!-- Payment Breakdown -->
    <div class="section">
        <div class="section-title">Rincian Pembayaran</div>
        <table class="breakdown-table">
            <tr>
                <th>Keterangan</th>
                <th>Jumlah</th>
            </tr>
            <tr>
                <td>Pembayaran Bunga</td>
                <td class="amount">Rp {{ number_format($payment->interest_amount, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Pembayaran Pokok</td>
                <td class="amount">Rp {{ number_format($payment->principal_amount, 0, ',', '.') }}</td>
            </tr>
            <tr style="background-color: #f8f9fa; font-weight: bold;">
                <td>Total Pembayaran</td>
                <td class="amount">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Sisa Tagihan</td>
                <td class="amount">Rp {{ number_format($payment->remaining_balance, 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>

    <!-- Status Information -->
    @if($payment->isFullPayment())
    <div class="highlight-box success">
        <h3 style="margin: 0 0 10px 0;">🎉 TRANSAKSI LUNAS</h3>
        <p style="margin: 0;">Pembayaran ini melunasi seluruh kewajiban. Barang dapat diambil oleh nasabah dengan membawa bukti ini.</p>
    </div>
    @elseif($payment->remaining_balance > 0)
    <div class="highlight-box">
        <h4 style="margin: 0 0 10px 0;">Sisa Tagihan</h4>
        <p style="margin: 0; font-size: 18px; font-weight: bold;">Rp {{ number_format($payment->remaining_balance, 0, ',', '.') }}</p>
        <p style="margin: 5px 0 0 0; font-size: 10px;">Silakan lakukan pembayaran berikutnya sebelum jatuh tempo</p>
    </div>
    @endif

    <!-- Important Notes -->
    <div class="section">
        <div class="section-title">Catatan Penting</div>
        <div style="font-size: 10px; line-height: 1.4;">
            <ul style="margin: 0; padding-left: 15px;">
                @if($payment->isFullPayment())
                <li>Transaksi telah lunas. Barang dapat diambil dengan membawa bukti ini.</li>
                <li>Barang harus diambil dalam waktu 30 hari setelah pelunasan.</li>
                @else
                <li>Simpan bukti pembayaran ini dengan baik sebagai tanda bukti yang sah.</li>
                <li>Lakukan pembayaran berikutnya sebelum tanggal jatuh tempo untuk menghindari denda.</li>
                @endif
                <li>Untuk informasi lebih lanjut, hubungi customer service kami.</li>
                <li>Bukti pembayaran yang hilang harus segera dilaporkan untuk penerbitan surat keterangan.</li>
            </ul>
        </div>
    </div>

    <!-- Signatures -->
    <div class="signatures">
        <div class="signature-box">
            <div>Nasabah</div>
            <div class="signature-line">{{ $payment->pawnTransaction->customer->name }}</div>
        </div>
        <div class="signature-box">
            <div>Petugas</div>
            <div class="signature-line">{{ $payment->officer->name }}</div>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>Dokumen ini dicetak secara otomatis pada {{ now()->format('d/m/Y H:i') }}</p>
        <p>Terima kasih atas kepercayaan Anda menggunakan layanan kami</p>
    </div>
</body>
</html>