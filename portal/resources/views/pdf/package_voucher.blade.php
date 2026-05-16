<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Package Voucher</title>
<style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 12px; color: #1a1a1a; }
    .page { padding: 30px 40px; }
    .header { display: flex; justify-content: space-between; align-items: flex-start; border-bottom: 2px solid #7c3aed; padding-bottom: 16px; margin-bottom: 20px; }
    .agency-name { font-size: 20px; font-weight: bold; color: #7c3aed; }
    .doc-title { font-size: 22px; font-weight: bold; color: #7c3aed; text-align: right; }
    .doc-meta { font-size: 10px; color: #555; text-align: right; margin-top: 4px; }
    .section { margin-bottom: 18px; }
    .section-title { font-size: 11px; font-weight: bold; text-transform: uppercase; color: #555; border-bottom: 1px solid #e5e7eb; padding-bottom: 4px; margin-bottom: 8px; }
    .package-box { background: #faf5ff; border: 1px solid #e9d5ff; border-radius: 6px; padding: 16px; margin-bottom: 12px; }
    .package-name { font-size: 18px; font-weight: bold; color: #7c3aed; }
    .info-grid { display: flex; flex-wrap: wrap; gap: 12px; }
    .info-item { min-width: 150px; }
    .info-label { font-size: 10px; color: #6b7280; }
    .info-value { font-weight: bold; margin-top: 2px; }
    table { width: 100%; border-collapse: collapse; }
    th { background: #faf5ff; color: #7c3aed; text-align: left; padding: 6px 8px; font-size: 11px; border: 1px solid #e9d5ff; }
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
            <div class="doc-title">PACKAGE VOUCHER</div>
            <div class="doc-meta">Ref: {{ $booking->booking_ref }}<br>Issued: {{ now()->format('d M Y') }}</div>
        </div>
    </div>

    @php $pd = $booking->package_data ?? []; @endphp

    <div class="section">
        <div class="section-title">Package Details</div>
        <div class="package-box">
            <div class="package-name">{{ $pd['package_name'] ?? '—' }}</div>
        </div>
        <div class="info-grid">
            <div class="info-item"><div class="info-label">Travel Start</div><div class="info-value">{{ $pd['travel_start'] ?? '—' }}</div></div>
            <div class="info-item"><div class="info-label">Travel End</div><div class="info-value">{{ $pd['travel_end'] ?? '—' }}</div></div>
        </div>
        @if(!empty($pd['inclusions_summary']))
            <div style="margin-top: 12px;">
                <div class="info-label">Inclusions</div>
                <p style="margin-top: 4px; line-height: 1.5;">{{ $pd['inclusions_summary'] }}</p>
            </div>
        @endif
    </div>

    <div class="section">
        <div class="section-title">Travelers</div>
        <table>
            <thead><tr><th>#</th><th>Name</th><th>Lead</th><th>Passport No.</th><th>Nationality</th></tr></thead>
            <tbody>
                @foreach($booking->passengers as $i => $p)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $p->title }} {{ $p->first_name }} {{ $p->last_name }}</td>
                        <td>{{ $p->pivot->is_lead ? '✓' : '' }}</td>
                        <td>{{ $p->passport_number ?? '—' }}</td>
                        <td>{{ $p->nationality ?? '—' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="footer">
        <p>This voucher is issued by {{ $setting->agency_name ?? config('app.name') }}. Please carry this document during your travel.</p>
    </div>
</div>
</body>
</html>
