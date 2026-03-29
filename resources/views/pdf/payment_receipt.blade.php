<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Recu {{ $receipt_number }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            color: #1f2937;
            font-size: 12px;
            margin: 32px;
        }
        .header {
            margin-bottom: 24px;
        }
        .title {
            color: #5b21b6;
            font-size: 26px;
            font-weight: 700;
            margin-bottom: 8px;
        }
        .muted {
            color: #6b7280;
        }
        .box {
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 16px;
            margin-bottom: 18px;
        }
        .row {
            width: 100%;
            border-collapse: collapse;
        }
        .row td {
            padding: 6px 0;
            vertical-align: top;
        }
        .summary {
            width: 100%;
            border-collapse: collapse;
            margin-top: 12px;
        }
        .summary th,
        .summary td {
            padding: 10px 12px;
            border-bottom: 1px solid #e5e7eb;
        }
        .summary th {
            background: #f5f3ff;
            color: #5b21b6;
            text-align: left;
        }
        .align-right {
            text-align: right;
        }
        .total {
            font-weight: 700;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">Recu de paiement</div>
        <div><strong>{{ $company['name'] }}</strong></div>
        <div class="muted">{{ $company['address'] }}</div>
        <div class="muted">{{ $company['phone'] }} | {{ $company['email'] }}</div>
    </div>

    <div class="box">
        <table class="row">
            <tr>
                <td><strong>No recu</strong></td>
                <td class="align-right">{{ $receipt_number }}</td>
            </tr>
            <tr>
                <td><strong>Reservation</strong></td>
                <td class="align-right">{{ $booking->booking_number }}</td>
            </tr>
            <tr>
                <td><strong>Date d'emission</strong></td>
                <td class="align-right">{{ $issued_date }}</td>
            </tr>
            <tr>
                <td><strong>Mode de paiement</strong></td>
                <td class="align-right">{{ $booking->payment_method_label }}</td>
            </tr>
        </table>
    </div>

    <div class="box">
        <table class="row">
            <tr>
                <td><strong>Client</strong></td>
                <td class="align-right">{{ $customer_name }}</td>
            </tr>
            <tr>
                <td><strong>Email</strong></td>
                <td class="align-right">{{ $customer_email ?: 'Non renseigne' }}</td>
            </tr>
            <tr>
                <td><strong>Telephone</strong></td>
                <td class="align-right">{{ $customer_phone ?: 'Non renseigne' }}</td>
            </tr>
            <tr>
                <td><strong>Prestation</strong></td>
                <td class="align-right">{{ $booking->title }}</td>
            </tr>
        </table>
    </div>

    <table class="summary">
        <thead>
            <tr>
                <th>Libelle</th>
                <th class="align-right">Montant</th>
            </tr>
        </thead>
        <tbody>
            @foreach($breakdown as $label => $amount)
                <tr>
                    <td>{{ $label }}</td>
                    <td class="align-right">{{ number_format((float) $amount, 0, ',', ' ') }} {{ $booking->currency }}</td>
                </tr>
            @endforeach
            <tr class="total">
                <td>Total recu</td>
                <td class="align-right">{{ number_format((float) $booking->final_amount, 0, ',', ' ') }} {{ $booking->currency }}</td>
            </tr>
        </tbody>
    </table>

    <p class="muted" style="margin-top: 20px;">
        Transaction: {{ $payment?->transaction_id ?? ($booking->payment_transaction_id ?: 'Non renseignee') }}
    </p>
</body>
</html>
