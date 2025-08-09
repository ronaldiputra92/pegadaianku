<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pengingat Jatuh Tempo - PEGADAIANKU</title>
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
            background-color: #3b82f6;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .content {
            background-color: #f8fafc;
            padding: 30px;
            border: 1px solid #e2e8f0;
        }
        .footer {
            background-color: #1e293b;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 0 0 8px 8px;
        }
        .alert {
            background-color: #fef3c7;
            border: 1px solid #f59e0b;
            color: #92400e;
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
            background-color: #3b82f6;
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
    </style>
</head>
<body>
    <div class="header">
        <h1>🔔 PENGINGAT JATUH TEMPO</h1>
        <p>PEGADAIANKU - Sistem Informasi Pegadaian</p>
    </div>

    <div class="content">
        <h2>Halo, {{ $customer->name }}</h2>
        
        <div class="alert">
            <strong>⚠️ Perhatian!</strong> Transaksi gadai Anda akan jatuh tempo dalam <strong>{{ $days }} hari</strong>.
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
                    <td style="padding: 8px 0; border-bottom: 1px solid #e2e8f0;"><strong>Sisa Tagihan:</strong></td>
                    <td style="padding: 8px 0; border-bottom: 1px solid #e2e8f0;" class="highlight">Rp {{ number_format($transaction->remaining_balance, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0;"><strong>Barang Gadai:</strong></td>
                    <td style="padding: 8px 0;">{{ $transaction->item_description }}</td>
                </tr>
            </table>
        </div>

        <p><strong>Segera lakukan pembayaran</strong> untuk menghindari:</p>
        <ul>
            <li>Denda keterlambatan 1% per hari</li>
            <li>Status transaksi menjadi overdue</li>
            <li>Risiko barang dilelang jika terlambat lebih dari 120 hari</li>
        </ul>

        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ route('transactions.show', $transaction->id) }}" class="btn">Lihat Detail Transaksi</a>
        </div>

        <p>Jika Anda memiliki pertanyaan, silakan hubungi kami:</p>
        <ul>
            <li>📞 Telepon: (021) 1234-5678</li>
            <li>📧 Email: info@pegadaianku.com</li>
            <li>🏢 Alamat: Jl. Contoh No. 123, Kota Contoh</li>
        </ul>
    </div>

    <div class="footer">
        <p>&copy; {{ date('Y') }} PEGADAIANKU. Semua hak dilindungi.</p>
        <p>Email ini dikirim secara otomatis, mohon tidak membalas email ini.</p>
    </div>
</body>
</html>