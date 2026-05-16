<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Flight Voucher</title>
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
    .flight-route { background: #eff6ff; border: 1px solid #dbeafe; border-radius: 6px; padding: 16px; margin-bottom: 12px; }
    .route-line { display: flex; justify-content: space-between; align-items: center; }
    .airport { font-size: 22px; font-weight: bold; color: #1d4ed8; }
    .arrow { color: #93c5fd; font-size: 18px; }
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
            <div style="font-size:10px; color:#555; margin-top:4px;">
                {{ $setting->phone ?? '' }} · {{ $setting->email ?? '' }}
            </div>
        </div>
        <div>
            <div class="doc-title">FLIGHT VOUCHER</div>
            <div class="doc-meta">Ref: {{ $booking->booking_ref }}<br>Issued: {{ now()->format('d M Y') }}</div>
        </div>
    </div>

    @php $fd = $booking->flight_data ?? []; @endphp

    <div class="section">
        <div class="section-title">Flight Details</div>
        <div class="flight-route">
            <div class="route-line">
                <div><div class="airport">{{ $fd['origin'] ?? '—' }}</div><div style="font-size:10px; color:#555;">Origin</div></div>
                <div class="arrow">✈ ──────── →</div>
                <div style="text-align:right;"><div class="airport">{{ $fd['destination'] ?? '—' }}</div><div style="font-size:10px; color:#555;">Destination</div></div>
            </div>
        </div>
        <div class="info-grid">
            <div class="info-item"><div class="info-label">Airline</div><div class="info-value">{{ $fd['airline'] ?? '—' }}</div></div>
            <div class="info-item"><div class="info-label">Flight No</div><div class="info-value">{{ $fd['flight_no'] ?? '—' }}</div></div>
            <div class="info-item"><div class="info-label">Departure</div><div class="info-value">{{ $fd['departure_datetime'] ?? '—' }}</div></div>
            <div class="info-item"><div class="info-label">Arrival</div><div class="info-value">{{ $fd['arrival_datetime'] ?? '—' }}</div></div>
            <div class="info-item"><div class="info-label">Class</div><div class="info-value">{{ $fd['class'] ?? '—' }}</div></div>
            <div class="info-item"><div class="info-label">Baggage</div><div class="info-value">{{ $fd['baggage'] ?? '—' }}</div></div>
            <div class="info-item"><div class="info-label">Agency PNR</div><div class="info-value">{{ $booking->agency_pnr ?? '—' }}</div></div>
            <div class="info-item"><div class="info-label">Vendor PNR</div><div class="info-value">{{ $booking->vendor_pnr ?? '—' }}</div></div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Passengers</div>
        <table>
            <thead><tr><th>#</th><th>Name</th><th>Lead</th><th>Passport No.</th><th>Meal Preference</th></tr></thead>
            <tbody>
                @foreach($booking->passengers as $i => $p)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $p->title }} {{ $p->first_name }} {{ $p->last_name }}</td>
                        <td>{{ $p->pivot->is_lead ? '✓' : '' }}</td>
                        <td>{{ $p->passport_number ?? '—' }}</td>
                        <td>{{ $p->meal_pref ?? '—' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="footer">
        <p>This voucher is issued by {{ $setting->agency_name ?? config('app.name') }}. Please carry this document for check-in.</p>
    </div>
</div>
</body>
</html>
