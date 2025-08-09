<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Denda Keterlambatan - PEGADAIANKU</title>
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
            background-color: #f59e0b;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .content {
            background-color: #fffbeb;
            padding: 30px;
            border: 1px solid #fde68a;
        }
        .footer {
            background-color: #1e293b;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 0 0 8px 8px;
        }
        .penalty-box {
            background-color: #fee2e2;
            border: 2px solid #dc2626;
            color: #991b1b;
            padding: 20px;
            border-radius: 6px;
            margin: 20px 0;
            text-align: center;
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
        .calculation {
            background-color: #f3f4f6;
            padding: 15px;
            border-radius: 6px;
            margin: 15px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>💸 DENDA KETERLAMBATAN</h1>
        <p>PEGADAIANKU - Sistem Informasi Pegadaian</p>
    </div>

    <div class="content">
        <h2>Halo, {{ $customer->name }}</h2>
        
        <div class="penalty-box">
            <h3>⚠️ DENDA TELAH DITERAPKAN</h3>
            <h2 style="margin: 10px 0; font-size: 2em;">Rp {{ number_format($penaltyAmount, 0, ',', '.') }}</h2>
            <p>Denda keterlambatan untuk {{ $penaltyDays }} hari</p>
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
                    <td style="padding: 8px 0; border-bottom: 1px solid #e2e8f0;">{{ $transaction->due_date->format('d/m/Y') }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; border-bottom: 1px solid #e2e8f0;"><strong>Hari Terlambat:</strong></td>
                    <td style="padding: 8px 0; border-bottom: 1px solid #e2e8f0;" class="highlight">{{ $penaltyDays }} hari</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; border-bottom: 1px solid #e2e8f0;"><strong>Sisa Tagihan Pokok:</strong></td>
                    <td style="padding: 8px 0; border-bottom: 1px solid #e2e8f0;">Rp {{ number_format($transaction->remaining_balance, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; border-bottom: 1px solid #e2e8f0;"><strong>Denda Keterlambatan:</strong></td>
                    <td style="padding: 8px 0; border-bottom: 1px solid #e2e8f0;" class="highlight">Rp {{ number_format($penaltyAmount, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; font-weight: bold; font-size: 1.1em;"><strong>TOTAL TAGIHAN:</strong></td>
                    <td style="padding: 8px 0; font-weight: bold; font-size: 1.1em;" class="highlight">Rp {{ number_format($transaction->remaining_balance + $penaltyAmount, 0, ',', '.') }}</td>
                </tr>
            </table>
        </div>

        <div class="calculation">
            <h4>📊 Perhitungan Denda:</h4>
            <p>Denda = 1% × Sisa Tagihan × Hari Terlambat</p>
            <p>Denda = 1% × Rp {{ number_format($transaction->remaining_balance, 0, ',', '.') }} × {{ $penaltyDays }} hari</p>
            <p><strong>Denda = Rp {{ number_format($penaltyAmount, 0, ',', '.') }}</strong></p>
            <small><em>*Maksimal denda untuk 30 hari keterlambatan</em></small>
        </div>

        <div style="background-color: #fee2e2; padding: 15px; border-radius: 6px; margin: 20px 0;">
            <h4>⚠️ Peringatan Penting:</h4>
            <ul>
                <li>Denda akan terus bertambah setiap hari hingga maksimal 30 hari</li>
                <li>Jika terlambat lebih dari <strong>120 hari</strong>, barang akan <strong>DILELANG</strong></li>
                <li>Segera lakukan pembayaran untuk menghentikan penambahan denda</li>
            </ul>
        </div>

        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ route('transactions.show', $transaction->id) }}" class="btn">BAYAR SEKARANG</a>
        </div>

        <p><strong>Hubungi kami untuk penyelesaian pembayaran:</strong></p>
        <ul>
            <li>📞 Telepon: (021) 1234-5678</li>
            <li>📧 Email: info@pegadaianku.com</li>
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