<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Hotel Voucher</title>
<style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 12px; color: #1a1a1a; }
    .page { padding: 30px 40px; }
    .header { display: flex; justify-content: space-between; align-items: flex-start; border-bottom: 2px solid #1d4ed8; padding-bottom: 16px; margin-bottom: 20px; }
    .agency-name { font-size: 20px; font-weight: bold; color: #1d4ed8; }
    .doc-title { font-size: 22px; font-weight: bold; color: #1d4ed8; text-align: right; }
    .doc-meta { font-size: 10px; color: #555; text-align: right; margin-top: 4px; }
    .section { margin-bottom: 18px; }
    .section-title { font-size: 11px; font-weight: bold; text-transform: uppercase; color: #555; border-bottom: 1px solid #e5e7eb; padding-bottom: 4px; margin-bottom: 8px; }
    .info-grid { display: flex; flex-wrap: wrap; gap: 12px; }
    .info-item { min-width: 150px; }
    .info-label { font-size: 10px; color: #6b7280; }
    .info-value { font-weight: bold; margin-top: 2px; }
    .hotel-box { background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 6px; padding: 16px; margin-bottom: 12px; }
    .hotel-name { font-size: 18px; font-weight: bold; color: #15803d; }
    table { width: 100%; border-collapse: collapse; }
    th { background: #eff6ff; color: #1d4ed8; text-align: left; padding: 6px 8px; font-size: 11px; border: 1px solid #dbeafe; }
    td { padding: 6px 8px; border: 1px solid #e5e7eb; }
    .footer { margin-top: 30px; padding-top: 16px; border-top: 1px solid #e5e7eb; font-size: 10px; color: #555; }
</style>
</head>
<body>
<div class="page">
    <div class="header">
        <div>
            <div class="agency-name">{{ $setting->agency_name ?? config('app.name') }}</div>
            <div style="font-size:10px; color:#555; margin-top:4px;">{{ $setting->phone ?? '' }} · {{ $setting->email ?? '' }}</div>
        </div>
        <div>
            <div class="doc-title">HOTEL VOUCHER</div>
            <div class="doc-meta">Ref: {{ $booking->booking_ref }}<br>Issued: {{ now()->format('d M Y') }}</div>
        </div>
    </div>

    @php $hd = $booking->hotel_data ?? []; @endphp

    <div class="section">
        <div class="section-title">Hotel Details</div>
        <div class="hotel-box">
            <div class="hotel-name">{{ $hd['hotel_name'] ?? '—' }}</div>
        </div>
        <div class="info-grid">
            <div class="info-item"><div class="info-label">Check-In</div><div class="info-value">{{ $hd['check_in'] ?? '—' }}</div></div>
            <div class="info-item"><div class="info-label">Check-Out</div><div class="info-value">{{ $hd['check_out'] ?? '—' }}</div></div>
            <div class="info-item"><div class="info-label">Room Type</div><div class="info-value">{{ $hd['room_type'] ?? '—' }}</div></div>
            <div class="info-item"><div class="info-label">Adults</div><div class="info-value">{{ $hd['adults'] ?? '—' }}</div></div>
            <div class="info-item"><div class="info-label">Children</div><div class="info-value">{{ $hd['children'] ?? 0 }}</div></div>
            <div class="info-item"><div class="info-label">Vendor PNR</div><div class="info-value">{{ $booking->vendor_pnr ?? '—' }}</div></div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Guests</div>
        <table>
            <thead><tr><th>#</th><th>Name</th><th>Lead</th><th>DOB</th></tr></thead>
            <tbody>
                @foreach($booking->passengers as $i => $p)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $p->title }} {{ $p->first_name }} {{ $p->last_name }}</td>
                        <td>{{ $p->pivot->is_lead ? '✓' : '' }}</td>
                        <td>{{ $p->dob?->format('d M Y') ?? '—' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="footer">
        <p>This voucher is issued by {{ $setting->agency_name ?? config('app.name') }}. Present this voucher at hotel check-in.</p>
    </div>
</div>
</body>
</html>
