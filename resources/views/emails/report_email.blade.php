<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Analitik Venue</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f6f9ff;
            color: #1b2b5a;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 30px auto;
            background-color: #ffffff;
            border-radius: 16px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            border: 1px solid #e1e8ff;
        }
        .header {
            background: linear-gradient(135deg, #1b2b5a 0%, #3f5efb 100%);
            padding: 30px;
            text-align: center;
            color: #ffffff;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 700;
            letter-spacing: 0.5px;
        }
        .header p {
            margin: 5px 0 0 0;
            font-size: 14px;
            opacity: 0.9;
        }
        .content {
            padding: 30px;
        }
        .greeting {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 15px;
        }
        .intro-text {
            font-size: 15px;
            line-height: 1.6;
            color: #53628c;
            margin-bottom: 25px;
        }
        .filter-info {
            background-color: #f7f9ff;
            border-left: 4px solid #3f5efb;
            padding: 15px 20px;
            border-radius: 0 8px 8px 0;
            margin-bottom: 30px;
        }
        .filter-info table {
            width: 100%;
            border-collapse: collapse;
        }
        .filter-info td {
            padding: 4px 0;
            font-size: 14px;
        }
        .filter-info td.label {
            font-weight: 600;
            color: #1b2b5a;
            width: 130px;
        }
        .filter-info td.value {
            color: #53628c;
        }
        .metrics-grid {
            margin-bottom: 30px;
        }
        .metric-card {
            background: #ffffff;
            border: 1px solid #dce5ff;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .metric-details {
            display: inline-block;
        }
        .metric-title {
            font-size: 13px;
            text-transform: uppercase;
            font-weight: 600;
            letter-spacing: 0.5px;
            color: #8a96c2;
            margin-bottom: 5px;
        }
        .metric-value {
            font-size: 22px;
            font-weight: 700;
            color: #1b2b5a;
        }
        .footer {
            background-color: #f7f9ff;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #8a96c2;
            border-top: 1px solid #eef2ff;
        }
        .footer a {
            color: #3f5efb;
            text-decoration: none;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>OLGA SEHAT</h1>
            <p>Laporan Ringkasan Analitik Performa Venue</p>
        </div>
        <div class="content">
            <div class="greeting">Halo, {{ $dataLaporan['owner_name'] }}!</div>
            <div class="intro-text">
                Berikut adalah rangkuman data performa aktivitas venue Anda berdasarkan rentang waktu penyaringan filter yang telah ditentukan pada sistem:
            </div>
            
            <div class="filter-info">
                <table>
                    <tr>
                        <td class="label">Nama Venue</td>
                        <td class="value">: {{ $dataLaporan['venue'] }}</td>
                    </tr>
                    <tr>
                        <td class="label">Lapangan</td>
                        <td class="value">: {{ $dataLaporan['lapangan'] }}</td>
                    </tr>
                    <tr>
                        <td class="label">Periode Laporan</td>
                        <td class="value">: {{ $dataLaporan['mulai_dari'] }} s.d. {{ $dataLaporan['sampai_dengan'] }}</td>
                    </tr>
                </table>
            </div>

            <div class="metrics-grid">
                <!-- Total Pendapatan -->
                <div class="metric-card" style="border-left: 5px solid #28c778;">
                    <div class="metric-details">
                        <div class="metric-title" style="color: #28c778;">Total Pendapatan</div>
                        <div class="metric-value">Rp {{ number_format($dataLaporan['total_revenue'], 0, ',', '.') }}</div>
                    </div>
                </div>

                <!-- Total Transaksi -->
                <div class="metric-card" style="border-left: 5px solid #4f8bff;">
                    <div class="metric-details">
                        <div class="metric-title" style="color: #4f8bff;">Jumlah Transaksi</div>
                        <div class="metric-value">{{ number_format($dataLaporan['total_transactions'], 0, ',', '.') }} Kali Booking</div>
                    </div>
                </div>

                <!-- Pertumbuhan Pelanggan -->
                <div class="metric-card" style="border-left: 5px solid #a56bff;">
                    <div class="metric-details">
                        <div class="metric-title" style="color: #a56bff;">Pelanggan Baru Terdaftar</div>
                        <div class="metric-value">{{ number_format($dataLaporan['total_users'], 0, ',', '.') }} User Baru</div>
                    </div>
                </div>
            </div>
            
            <div class="intro-text" style="font-size: 13px; margin-bottom: 0;">
                * Data pendapatan di atas dihitung berdasarkan seluruh jadwal slot lapangan yang dipesan (status <strong>booked</strong>) pada platform kami selama periode waktu tersebut.
            </div>
        </div>
        <div class="footer">
            &copy; 2026 Olga Sehat. All rights reserved.<br>
            Dikirim secara otomatis kepada pemilik terdaftar mitra lapangan.<br>
            Kunjungi website kami di <a href="http://localhost">olgasehat.id</a>
        </div>
    </div>
</body>
</html>
