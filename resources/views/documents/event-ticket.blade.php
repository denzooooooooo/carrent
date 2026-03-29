<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Billet {{ $ticket->ticket_number }}</title>
    <style>
        body {
            margin: 0;
            padding: 32px;
            font-family: Arial, sans-serif;
            background: #f6f3ff;
            color: #111827;
        }
        .ticket {
            max-width: 760px;
            margin: 0 auto;
            background: #ffffff;
            border: 1px solid #ddd6fe;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 20px 45px rgba(91, 33, 182, 0.12);
        }
        .hero {
            padding: 28px 32px;
            background: linear-gradient(135deg, #4c1d95, #d97706);
            color: #ffffff;
        }
        .hero h1 {
            margin: 0 0 10px;
            font-size: 30px;
        }
        .hero p {
            margin: 0;
            opacity: 0.9;
        }
        .content {
            padding: 32px;
        }
        .grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 16px;
        }
        .card {
            border: 1px solid #e5e7eb;
            border-radius: 16px;
            padding: 18px;
            background: #fafafa;
        }
        .label {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.12em;
            color: #6b7280;
        }
        .value {
            margin-top: 8px;
            font-size: 18px;
            font-weight: 700;
            color: #111827;
        }
        .meta {
            margin-top: 24px;
            padding-top: 24px;
            border-top: 1px dashed #d1d5db;
        }
        .meta table {
            width: 100%;
            border-collapse: collapse;
        }
        .meta td {
            padding: 10px 0;
            border-bottom: 1px solid #f3f4f6;
            vertical-align: top;
        }
        .meta td:first-child {
            color: #6b7280;
            width: 220px;
        }
        .footer {
            padding: 0 32px 32px;
            font-size: 13px;
            color: #6b7280;
        }
        .code {
            display: inline-block;
            margin-top: 6px;
            padding: 10px 14px;
            border-radius: 12px;
            background: #ede9fe;
            color: #5b21b6;
            font-family: monospace;
            font-size: 16px;
            font-weight: 700;
        }
    </style>
</head>
<body>
    <div class="ticket">
        <div class="hero">
            <h1>Billet d'événement</h1>
            <p>{{ $event->title }}</p>
        </div>

        <div class="content">
            <div class="grid">
                <div class="card">
                    <div class="label">Titulaire</div>
                    <div class="value">{{ $holder_name }}</div>
                </div>
                <div class="card">
                    <div class="label">Billet</div>
                    <div class="value">{{ $ticket->ticket_number }}</div>
                </div>
                <div class="card">
                    <div class="label">Date</div>
                    <div class="value">{{ optional($event->event_date)->format('d/m/Y') ?: 'A confirmer' }}</div>
                </div>
                <div class="card">
                    <div class="label">Heure</div>
                    <div class="value">{{ $event->event_time ?: 'A confirmer' }}</div>
                </div>
            </div>

            <div class="meta">
                <table>
                    <tr>
                        <td>Lieu</td>
                        <td>{{ $event->venue_name }}@if($event->city), {{ $event->city }}@endif @if($event->country), {{ $event->country }}@endif</td>
                    </tr>
                    <tr>
                        <td>Accès</td>
                        <td>{{ $seat_zone?->zone_name ?? $booking->event_selection_label }}</td>
                    </tr>
                    <tr>
                        <td>Réservation</td>
                        <td>{{ $booking->booking_number }}</td>
                    </tr>
                    <tr>
                        <td>Montant</td>
                        <td>{{ number_format((float) $ticket->final_price, 0, ',', ' ') }} {{ $booking->currency }}</td>
                    </tr>
                    <tr>
                        <td>Email</td>
                        <td>{{ $customer_email ?: 'Non renseigne' }}</td>
                    </tr>
                    <tr>
                        <td>Téléphone</td>
                        <td>{{ $customer_phone ?: 'Non renseigne' }}</td>
                    </tr>
                    <tr>
                        <td>Code de validation</td>
                        <td>
                            <span class="code">{{ $validation_code }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td>Émis le</td>
                        <td>{{ $issued_date }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="footer">
            {{ $company['name'] }} · {{ $company['email'] }} · {{ $company['phone'] }}<br>
            Présentez ce billet à l’entrée. Toute reproduction non autorisée peut entraîner un refus d’accès.
        </div>
    </div>
</body>
</html>
