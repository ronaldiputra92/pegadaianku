<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Transaksi Jatuh Tempo - PEGADAIANKU</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #dc2626;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .content {
            background-color: #fef2f2;
            padding: 30px;
            border: 1px solid #fecaca;
        }
        .footer {
            background-color: #1e293b;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 0 0 8px 8px;
        }
        .alert {
            background-color: #fee2e2;
            border: 1px solid #dc2626;
            color: #991b1b;
            padding: 15px;
            border-radius: 6px;
            margin: 20px 0;
        }
        .transaction-details {
            background-color: white;
            padding: 20px;
            border-radius: 6px;
            margin: 20px 0;
            border: 1px solid #e2e8f0;
        }
        .btn {
            display: inline-block;
            background-color: #dc2626;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 6px;
            margin: 10px 0;
        }
        .highlight {
            color: #dc2626;
            font-weight: bold;
        }
        .urgent {
            background-color: #dc2626;
            color: white;
            padding: 20px;
            border-radius: 6px;
            text-align: center;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🚨 TRANSAKSI JATUH TEMPO</h1>
        <p>PEGADAIANKU - Sistem Informasi Pegadaian</p>
    </div>

    <div class="content">
        <h2>Halo, {{ $customer->name }}</h2>
        
        <div class="urgent">
            <h3>⚠️ URGENT - SEGERA LAKUKAN PEMBAYARAN!</h3>
            <p>Transaksi Anda telah jatuh tempo <strong>{{ $daysOverdue }} hari</strong> yang lalu</p>
        </div>

        <div class="transaction-details">
            <h3>Detail Transaksi</h3>
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="padding: 8px 0; border-bottom: 1px solid #e2e8f0;"><strong>Kode Transaksi:</strong></td>
                    <td style="padding: 8px 0; border-bottom: 1px solid #e2e8f0;">{{ $transaction->transaction_code }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; border-bottom: 1px solid #e2e8f0;"><strong>Tanggal Jatuh Tempo:</strong></td>
                    <td style="padding: 8px 0; border-bottom: 1px solid #e2e8f0;" class="highlight">{{ $transaction->due_date->format('d/m/Y') }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; border-bottom: 1px solid #e2e8f0;"><strong>Terlambat:</strong></td>
                    <td style="padding: 8px 0; border-bottom: 1px solid #e2e8f0;" class="highlight">{{ $daysOverdue }} hari</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; border-bottom: 1px solid #e2e8f0;"><strong>Sisa Tagihan:</strong></td>
                    <td style="padding: 8px 0; border-bottom: 1px solid #e2e8f0;" class="highlight">Rp {{ number_format($transaction->remaining_balance, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0;"><strong>Barang Gadai:</strong></td>
                    <td style="padding: 8px 0;">{{ $transaction->item_description }}</td>
                </tr>
            </table>
        </div>

        <div class="alert">
            <h4>⚠️ Konsekuensi Keterlambatan:</h4>
            <ul>
                <li><strong>Denda 1% per hari</strong> dari sisa tagihan</li>
                <li>Status transaksi menjadi <strong>OVERDUE</strong></li>
                <li>Jika terlambat lebih dari <strong>120 hari</strong>, barang akan <strong>DILELANG</strong></li>
            </ul>
        </div>

        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ route('transactions.show', $transaction->id) }}" class="btn">BAYAR SEKARANG</a>
        </div>

        <p><strong>Hubungi kami segera untuk penyelesaian:</strong></p>
        <ul>
            <li>📞 Telepon: (021) 1234-5678</li>
            <li>��� Email: info@pegadaianku.com</li>
            <li>🏢 Alamat: Jl. Contoh No. 123, Kota Contoh</li>
            <li>⏰ Jam Operasional: Senin-Jumat 08:00-17:00, Sabtu 08:00-12:00</li>
        </ul>
    </div>

    <div class="footer">
        <p>&copy; {{ date('Y') }} PEGADAIANKU. Semua hak dilindungi.</p>
        <p>Email ini dikirim secara otomatis, mohon tidak membalas email ini.</p>
    </div>
</body>
</html>