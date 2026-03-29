<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Facture {{ $invoice_number }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            color: #1f2937;
            font-size: 12px;
            margin: 32px;
        }
        .header {
            width: 100%;
            margin-bottom: 28px;
        }
        .header td {
            vertical-align: top;
        }
        .title {
            font-size: 28px;
            font-weight: 700;
            color: #5b21b6;
            margin-bottom: 8px;
        }
        .muted {
            color: #6b7280;
        }
        .section-title {
            font-size: 14px;
            font-weight: 700;
            margin: 24px 0 10px;
            color: #111827;
        }
        .card {
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 16px;
        }
        .meta-table,
        .lines-table {
            width: 100%;
            border-collapse: collapse;
        }
        .meta-table td {
            padding: 6px 0;
        }
        .lines-table th,
        .lines-table td {
            padding: 10px 12px;
            border-bottom: 1px solid #e5e7eb;
            text-align: left;
        }
        .lines-table th {
            background: #f5f3ff;
            color: #5b21b6;
            font-weight: 700;
        }
        .align-right {
            text-align: right;
        }
        .totals {
            width: 280px;
            margin-left: auto;
            margin-top: 18px;
            border-collapse: collapse;
        }
        .totals td {
            padding: 8px 0;
        }
        .total-row td {
            border-top: 2px solid #111827;
            font-size: 14px;
            font-weight: 700;
            padding-top: 10px;
        }
        .footer {
            margin-top: 32px;
            font-size: 11px;
            color: #6b7280;
        }
    </style>
</head>
<body>
    <table class="header">
        <tr>
            <td>
                <div class="title">Facture</div>
                <div><strong>{{ $company['name'] }}</strong></div>
                <div class="muted">{{ $company['address'] }}</div>
                <div class="muted">{{ $company['city'] }}, {{ $company['country'] }}</div>
                <div class="muted">{{ $company['phone'] }} | {{ $company['email'] }}</div>
            </td>
            <td class="align-right">
                <div><strong>No facture:</strong> {{ $invoice_number }}</div>
                <div><strong>Date:</strong> {{ $invoice_date }}</div>
                <div><strong>Echeance:</strong> {{ $due_date }}</div>
                <div><strong>Reservation:</strong> {{ $booking->booking_number }}</div>
            </td>
        </tr>
    </table>

    <div class="section-title">Client facture</div>
    <div class="card">
        <table class="meta-table">
            <tr>
                <td><strong>Nom</strong></td>
                <td>{{ $customer_name }}</td>
            </tr>
            <tr>
                <td><strong>Email</strong></td>
                <td>{{ $customer_email ?: 'Non renseigne' }}</td>
            </tr>
            <tr>
                <td><strong>Telephone</strong></td>
                <td>{{ $customer_phone ?: 'Non renseigne' }}</td>
            </tr>
        </table>
    </div>

    <div class="section-title">Detail de la prestation</div>
    <table class="lines-table">
        <thead>
            <tr>
                <th>Description</th>
                <th>Reference</th>
                <th>Date de service</th>
                <th class="align-right">Montant</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $booking->title }}</td>
                <td>{{ strtoupper($booking->booking_type) }}</td>
                <td>{{ $booking->travel_date_label ?? 'Non renseignee' }}</td>
                <td class="align-right">{{ number_format((float) $booking->total_amount, 0, ',', ' ') }} {{ $booking->currency }}</td>
            </tr>
        </tbody>
    </table>

    <table class="totals">
        <tr>
            <td>Sous-total</td>
            <td class="align-right">{{ number_format((float) $booking->total_amount, 0, ',', ' ') }} {{ $booking->currency }}</td>
        </tr>
        <tr>
            <td>Reduction</td>
            <td class="align-right">-{{ number_format((float) $booking->discount_amount, 0, ',', ' ') }} {{ $booking->currency }}</td>
        </tr>
        <tr>
            <td>Taxes</td>
            <td class="align-right">{{ number_format((float) $booking->tax_amount, 0, ',', ' ') }} {{ $booking->currency }}</td>
        </tr>
        <tr class="total-row">
            <td>Total regle</td>
            <td class="align-right">{{ number_format((float) $booking->final_amount, 0, ',', ' ') }} {{ $booking->currency }}</td>
        </tr>
    </table>

    <div class="footer">
        Paiement reference: {{ $payment?->transaction_id ?? ($booking->payment_transaction_id ?: 'Non renseigne') }}<br>
        Merci pour votre confiance.
    </div>
</body>
</html>
