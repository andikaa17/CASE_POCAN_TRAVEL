<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $booking->booking_code ?? 'N/A' }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            color: #333;
            font-size: 24px;
        }
        .company-info {
            margin-top: 10px;
            color: #666;
        }
        .invoice-info {
            margin-bottom: 30px;
        }
        .invoice-info table {
            width: 100%;
        }
        .invoice-info td {
            padding: 5px;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .items-table th {
            background-color: #f2f2f2;
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }
        .items-table td {
            padding: 8px;
            border: 1px solid #ddd;
        }
        .total {
            text-align: right;
            font-size: 14px;
            font-weight: bold;
            margin-top: 20px;
            padding-top: 10px;
            border-top: 2px solid #333;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            color: #666;
            font-size: 10px;
            border-top: 1px solid #ccc;
            padding-top: 20px;
        }
        .status-paid {
            color: green;
            font-weight: bold;
        }
        .status-pending {
            color: orange;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $company['name'] ?? 'CAN TRAVEL' }}</h1>
        <div class="company-info">
            <p>{{ $company['address'] ?? 'Jl. Raya No. 123, Jakarta' }}</p>
            <p>Telp: {{ $company['phone'] ?? '021-1234567' }} | Email: {{ $company['email'] ?? 'info@cantravel.com' }}</p>
        </div>
    </div>

    <div class="invoice-info">
        <table>
            <tr>
                <td><strong>Invoice Number:</strong></td>
                <td>INV-{{ $booking->booking_code ?? 'N/A' }}</td>
                <td><strong>Tanggal:</strong></td>
                <td>{{ $date ?? now()->format('d/m/Y H:i:s') }}</td>
            </tr>
            <tr>
                <td><strong>Customer:</strong></td>
                <td>{{ $booking->user->name ?? '-' }}</td>
                <td><strong>Email:</strong></td>
                <td>{{ $booking->user->email ?? '-' }}</td>
            </tr>
            <tr>
                <td><strong>Phone:</strong></td>
                <td>{{ $booking->user->phone ?? '-' }}</td>
                <td><strong>Status:</strong></td>
                <td class="{{ ($booking->status ?? 'pending') === 'paid' ? 'status-paid' : 'status-pending' }}">
                    {{ strtoupper($booking->status ?? 'PENDING') }}
                </td>
            </tr>
        </table>
    </div>

    <h3>Detail Tiket</h3>
    <table class="items-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Penumpang</th>
                <th>Umur</th>
                <th>No. Identitas</th>
                <th>No. HP</th>
                <th>Kursi</th>
                <th>Harga</th>
            </tr>
        </thead>
        <tbody>
            @php
                $passengersData = $booking->passengers_data ?? [];
                $seatIds = $booking->seat_ids ?? [];
                
                // Ambil data kursi dari database
                $seats = [];
                if (!empty($seatIds)) {
                    $seats = \App\Models\Seat::whereIn('id', $seatIds)
                        ->get()
                        ->keyBy('id')
                        ->toArray();
                }
            @endphp
            
            @forelse($passengersData as $index => $passenger)
                @php
                    $seatId = $seatIds[$index] ?? null;
                    $seatNumber = $seatId && isset($seats[$seatId]) 
                        ? $seats[$seatId]['seat_number'] 
                        : ($seatId ?? '-');
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $passenger['name'] ?? $passenger['passenger_name'] ?? '-' }}</td>
                    <td>{{ $passenger['age'] ?? $passenger['umur'] ?? '-' }}</td>
                    <td>{{ $passenger['id_card'] ?? $passenger['id_number'] ?? $passenger['identitas'] ?? '-' }}</td>
                    <td>{{ $passenger['phone'] ?? $passenger['no_hp'] ?? '-' }}</td>
                    <td><strong>{{ $seatNumber }}</strong></td>
                    <td>Rp {{ number_format($booking->schedule->price ?? 0, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 20px;">
                        <strong>Tidak ada data penumpang</strong>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="total">
        <p>Total: Rp {{ number_format($booking->total_price ?? 0, 0, ',', '.') }}</p>
    </div>

    <div class="footer">
        <p>Terima kasih telah menggunakan layanan CAN Travel</p>
        <p>Invoice ini sah dan diproses secara elektronik</p>
    </div>
</body>
</html>