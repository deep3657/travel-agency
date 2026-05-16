<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Quotation</title>
<style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 12px; color: #1a1a1a; }
    .page { padding: 30px 40px; }
    .header { display: flex; justify-content: space-between; align-items: flex-start; border-bottom: 2px solid #1d4ed8; padding-bottom: 16px; margin-bottom: 20px; }
    .agency-name { font-size: 20px; font-weight: bold; color: #1d4ed8; }
    .agency-info { font-size: 10px; color: #555; margin-top: 4px; }
    .doc-title { font-size: 22px; font-weight: bold; color: #1d4ed8; text-align: right; }
    .doc-meta { font-size: 10px; color: #555; text-align: right; margin-top: 4px; }
    .section { margin-bottom: 18px; }
    .section-title { font-size: 11px; font-weight: bold; text-transform: uppercase; color: #555; border-bottom: 1px solid #e5e7eb; padding-bottom: 4px; margin-bottom: 8px; }
    .row { display: flex; gap: 8px; margin-bottom: 4px; }
    .label { color: #555; min-width: 100px; }
    table { width: 100%; border-collapse: collapse; }
    th { background: #eff6ff; color: #1d4ed8; text-align: left; padding: 6px 8px; font-size: 11px; border: 1px solid #dbeafe; }
    td { padding: 6px 8px; border: 1px solid #e5e7eb; vertical-align: top; }
    .text-right { text-align: right; }
    .totals { margin-left: auto; width: 280px; }
    .total-row { display: flex; justify-content: space-between; padding: 3px 0; }
    .grand-total { font-weight: bold; font-size: 14px; color: #1d4ed8; border-top: 2px solid #1d4ed8; padding-top: 6px; margin-top: 4px; }
    .footer { margin-top: 30px; padding-top: 16px; border-top: 1px solid #e5e7eb; font-size: 10px; color: #555; }
</style>
</head>
<body>
<div class="page">
    <div class="header">
        <div>
            <div class="agency-name">{{ $setting->agency_name ?? config('app.name', 'Maruti Travels') }}</div>
            <div class="agency-info">
                {{ $setting->address ?? '' }}<br>
                {{ $setting->phone ?? '' }} · {{ $setting->email ?? '' }}<br>
                {{ $setting->gstin ? 'GSTIN: '.$setting->gstin : '' }}
            </div>
        </div>
        <div>
            <div class="doc-title">QUOTATION</div>
            <div class="doc-meta">
                Ref: QT-{{ str_pad($version->quotation_id, 5, '0', STR_PAD_LEFT) }}/v{{ $version->version_number }}<br>
                Date: {{ now()->format('d M Y') }}<br>
                @if($version->quotation->validity_date)
                    Valid Until: {{ $version->quotation->validity_date->format('d M Y') }}
                @endif
            </div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Customer Details</div>
        <div class="row"><span class="label">Name:</span><span>{{ $customer->name }}</span></div>
        <div class="row"><span class="label">Email:</span><span>{{ $customer->user?->email ?? '' }}</span></div>
        <div class="row"><span class="label">Phone:</span><span>{{ $customer->phone ?? '' }}</span></div>
    </div>

    @if($version->customer_notes)
        <div class="section">
            <div class="section-title">Notes</div>
            <p style="color: #374151;">{{ $version->customer_notes }}</p>
        </div>
    @endif

    <div class="section">
        <div class="section-title">Services</div>
        <table>
            <thead>
                <tr>
                    <th style="width:40%">Description</th>
                    <th style="width:10%">Type</th>
                    <th class="text-right" style="width:15%">Qty</th>
                    <th class="text-right" style="width:15%">Unit Rate</th>
                    <th class="text-right" style="width:20%">Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($version->lines as $line)
                    <tr>
                        <td>{{ $line->description }}</td>
                        <td>{{ ucfirst($line->line_type) }}</td>
                        <td class="text-right">{{ $line->quantity }}</td>
                        <td class="text-right">₹{{ number_format($line->unit_rate?->toRupees() ?? 0, 2) }}</td>
                        <td class="text-right">₹{{ number_format($line->amount?->toRupees() ?? 0, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="totals">
        <div class="total-row"><span>Subtotal</span><span>₹{{ number_format($version->subtotal?->toRupees() ?? 0, 2) }}</span></div>
        @if($version->discount_amount && $version->discount_amount->toRupees() > 0)
            <div class="total-row" style="color: #16a34a;"><span>Discount</span><span>-₹{{ number_format($version->discount_amount->toRupees(), 2) }}</span></div>
        @endif
        @if($version->cgst && $version->cgst->toRupees() > 0)
            <div class="total-row"><span>CGST</span><span>₹{{ number_format($version->cgst->toRupees(), 2) }}</span></div>
            <div class="total-row"><span>SGST</span><span>₹{{ number_format($version->sgst->toRupees(), 2) }}</span></div>
        @endif
        @if($version->igst && $version->igst->toRupees() > 0)
            <div class="total-row"><span>IGST</span><span>₹{{ number_format($version->igst->toRupees(), 2) }}</span></div>
        @endif
        <div class="total-row grand-total"><span>Grand Total</span><span>₹{{ number_format($version->grand_total?->toRupees() ?? 0, 2) }}</span></div>
    </div>

    @if($version->terms)
        <div class="section" style="margin-top: 24px;">
            <div class="section-title">Terms & Conditions</div>
            <p style="color: #374151; font-size: 10px; line-height: 1.5;">{{ $version->terms }}</p>
        </div>
    @endif

    <div class="footer">
        <p>This is a computer-generated document. For any queries, please contact us at {{ $setting->email ?? '' }}.</p>
    </div>
</div>
</body>
</html>
