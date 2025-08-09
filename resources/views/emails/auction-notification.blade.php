<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pemberitahuan Lelang - PEGADAIANKU</title>
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
            background-color: #7c2d12;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .content {
            background-color: #fef7ff;
            padding: 30px;
            border: 1px solid #e9d5ff;
        }
        .footer {
            background-color: #1e293b;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 0 0 8px 8px;
        }
        .auction-alert {
            background-color: #7c2d12;
            color: white;
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
            background-color: #7c2d12;
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
        .timeline {
            background-color: #f3f4f6;
            padding: 20px;
            border-radius: 6px;
            margin: 20px 0;
        }
        .final-notice {
            background-color: #dc2626;
            color: white;
            padding: 20px;
            border-radius: 6px;
            text-align: center;
            margin: 20px 0;
            border: 3px solid #991b1b;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🔨 PEMBERITAHUAN LELANG</h1>
        <p>PEGADAIANKU - Sistem Informasi Pegadaian</p>
    </div>

    <div class="content">
        <h2>Halo, {{ $customer->name }}</h2>
        
        <div class="final-notice">
            <h3>🚨 PEMBERITAHUAN TERAKHIR</h3>
            <h2>BARANG GADAI AKAN DILELANG</h2>
            <p>Transaksi telah melewati batas waktu maksimal (120 hari)</p>
        </div>

        <div class="transaction-details">
            <h3>Detail Transaksi</h3>
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="padding: 8px 0; border-bottom: 1px solid #e2e8f0;"><strong>Kode Transaksi:</strong></td>
                    <td style="padding: 8px 0; border-bottom: 1px solid #e2e8f0;">{{ $transaction->transaction_code }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; border-bottom: 1px solid #e2e8f0;"><strong>Tanggal Transaksi:</strong></td>
                    <td style="padding: 8px 0; border-bottom: 1px solid #e2e8f0;">{{ $transaction->created_at->format('d/m/Y') }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; border-bottom: 1px solid #e2e8f0;"><strong>Tanggal Jatuh Tempo:</strong></td>
                    <td style="padding: 8px 0; border-bottom: 1px solid #e2e8f0;" class="highlight">{{ $transaction->due_date->format('d/m/Y') }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; border-bottom: 1px solid #e2e8f0;"><strong>Barang Gadai:</strong></td>
                    <td style="padding: 8px 0; border-bottom: 1px solid #e2e8f0;"><strong>{{ $transaction->item_description }}</strong></td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; border-bottom: 1px solid #e2e8f0;"><strong>Nilai Taksiran:</strong></td>
                    <td style="padding: 8px 0; border-bottom: 1px solid #e2e8f0;">Rp {{ number_format($transaction->appraised_value, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0;"><strong>Total Tagihan:</strong></td>
                    <td style="padding: 8px 0;" class="highlight">Rp {{ number_format($transaction->remaining_balance + ($transaction->penalty_amount ?? 0), 0, ',', '.') }}</td>
                </tr>
            </table>
        </div>

        <div class="timeline">
            <h4>📅 Timeline Proses Lelang:</h4>
            <ol>
                <li><strong>Hari ini:</strong> Pemberitahuan lelang dikirim</li>
                <li><strong>7 hari ke depan:</strong> Periode terakhir untuk penyelesaian</li>
                <li><strong>Setelah 7 hari:</strong> Barang akan diproses untuk lelang</li>
                <li><strong>Hasil lelang:</strong> Akan dikurangi biaya administrasi dan sisa tagihan</li>
            </ol>
        </div>

        <div style="background-color: #fef3c7; padding: 20px; border-radius: 6px; margin: 20px 0; border: 1px solid #f59e0b;">
            <h4>💡 Masih Ada Kesempatan Terakhir!</h4>
            <p>Anda masih memiliki <strong>7 hari</strong> untuk menyelesaikan pembayaran dan mengambil barang gadai Anda.</p>
            <p><strong>Setelah 7 hari, barang akan diproses untuk lelang dan tidak dapat dibatalkan.</strong></p>
        </div>

        <div style="background-color: #fee2e2; padding: 20px; border-radius: 6px; margin: 20px 0;">
            <h4>⚠️ Konsekuensi Lelang:</h4>
            <ul>
                <li>Barang gadai akan dijual melalui lelang</li>
                <li>Hasil lelang akan dipotong untuk biaya administrasi</li>
                <li>Sisa hasil lelang (jika ada) akan dikembalikan kepada Anda</li>
                <li>Jika hasil lelang tidak mencukupi, Anda tetap bertanggung jawab atas sisa tagihan</li>
            </ul>
        </div>

        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ route('transactions.show', $transaction->id) }}" class="btn">BAYAR SEKARANG - SELAMATKAN BARANG ANDA</a>
        </div>

        <div style="background-color: #dc2626; color: white; padding: 20px; border-radius: 6px; text-align: center;">
            <h3>📞 HUBUNGI KAMI SEGERA!</h3>
            <p><strong>Telepon: (021) 1234-5678</strong></p>
            <p><strong>Email: info@pegadaianku.com</strong></p>
            <p><strong>Alamat: Jl. Contoh No. 123, Kota Contoh</strong></p>
            <p><strong>Jam Operasional: Senin-Jumat 08:00-17:00, Sabtu 08:00-12:00</strong></p>
        </div>

        <p style="text-align: center; margin-top: 30px; font-style: italic;">
            <strong>Ini adalah kesempatan terakhir Anda untuk menyelamatkan barang gadai.</strong><br>
            Jangan biarkan barang berharga Anda hilang karena keterlambatan pembayaran.
        </p>
    </div>

    <div class="footer">
        <p>&copy; {{ date('Y') }} PEGADAIANKU. Semua hak dilindungi.</p>
        <p>Email ini dikirim secara otomatis, mohon tidak membalas email ini.</p>
    </div>
</body>
</html>