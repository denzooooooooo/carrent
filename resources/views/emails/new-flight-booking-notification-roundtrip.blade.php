<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouvelle réservation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 700px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            background-color: #ffffff;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%);
            color: white;
            padding: 20px;
            border-radius: 10px;
            margin: -30px -30px 30px -30px;
            text-align: center;
        }
        .alert-badge {
            display: inline-block;
            background-color: #fef3c7;
            color: #92400e;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: bold;
            margin-bottom: 15px;
        }
        .booking-info {
            background-color: #f9fafb;
            border-left: 4px solid #3b82f6;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin: 20px 0;
        }
        .info-item {
            background-color: #f9fafb;
            padding: 12px;
            border-radius: 5px;
            border: 1px solid #e5e7eb;
        }
        .info-label {
            font-size: 12px;
            color: #6b7280;
            text-transform: uppercase;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .info-value {
            font-size: 16px;
            color: #1f2937;
            font-weight: bold;
        }
        .flight-details {
            background-color: #eff6ff;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .flight-segment {
            background-color: white;
            padding: 15px;
            margin: 10px 0;
            border-radius: 5px;
            border-left: 3px solid #3b82f6;
        }
        .passengers-list {
            background-color: #fef3c7;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .passenger-item {
            background-color: white;
            padding: 10px;
            margin: 8px 0;
            border-radius: 5px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .price-box {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            margin: 20px 0;
        }
        .price-amount {
            font-size: 32px;
            font-weight: bold;
            margin: 10px 0;
        }
        .action-buttons {
            display: flex;
            gap: 10px;
            margin: 20px 0;
        }
        .btn {
            flex: 1;
            padding: 12px 20px;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            display: inline-block;
        }
        .btn-primary {
            background-color: #3b82f6;
            color: white;
        }
        .btn-secondary {
            background-color: #6b7280;
            color: white;
        }
        .contact-info {
            background-color: #dbeafe;
            border-left: 4px solid #3b82f6;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }
        th {
            background-color: #f3f4f6;
            font-weight: bold;
            color: #374151;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="alert-badge">🔔 NOUVELLE RÉSERVATION</div>
            <h1 style="margin: 10px 0;">Vol Aller-Retour</h1>
            <p style="margin: 5px 0; opacity: 0.9;">{{ \Carbon\Carbon::now()->format('d/m/Y à H:i') }}</p>
        </div>

        <div class="booking-info">
            <h2 style="margin-top: 0; color: #1f2937;">📋 Référence: {{ $booking->booking_number }}</h2>
            <p style="margin: 5px 0;"><strong>Statut:</strong> <span style="color: #f59e0b;">{{ strtoupper($booking->status) }}</span></p>
            <p style="margin: 5px 0;"><strong>Type:</strong> Vol Aller-Retour 🔄</p>
        </div>

        {{-- INFORMATIONS CLIENT --}}
        <div class="contact-info">
            <h3 style="margin-top: 0;">👤 Informations Client</h3>
            <p><strong>Passager principal:</strong> {{ $booking->passenger_details[0]['name'] ?? 'N/A' }}</p>
            <p><strong>Email:</strong> <a href="mailto:{{ $customerEmail }}">{{ $customerEmail }}</a></p>
            <p><strong>Téléphone:</strong> <a href="tel:{{ $customerPhone }}">{{ $customerPhone }}</a></p>
            <p><strong>Nombre de passagers:</strong> {{ $booking->number_of_passengers }}</p>
        </div>

        {{-- GRID INFORMATIONS --}}
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">🛫 Départ</div>
                <div class="info-value">{{ $flightBooking->departure_id }}</div>
                <div style="font-size: 12px; color: #6b7280; margin-top: 5px;">
                    {{ \Carbon\Carbon::parse($flightBooking->outbound_date)->format('d/m/Y') }}
                </div>
            </div>
            <div class="info-item">
                <div class="info-label">🛬 Arrivée</div>
                <div class="info-value">{{ $flightBooking->arrival_id }}</div>
                <div style="font-size: 12px; color: #6b7280; margin-top: 5px;">
                    {{ \Carbon\Carbon::parse($flightBooking->outbound_date)->format('d/m/Y') }}
                </div>
            </div>
            <div class="info-item">
                <div class="info-label">🔄 Retour</div>
                <div class="info-value">{{ $flightBooking->departure_id }}</div>
                <div style="font-size: 12px; color: #6b7280; margin-top: 5px;">
                    {{ \Carbon\Carbon::parse($flightBooking->return_date)->format('d/m/Y') }}
                </div>
            </div>
            <div class="info-item">
                <div class="info-label">💺 Classe</div>
                <div class="info-value">{{ $booking->seat_class }}</div>
            </div>
        </div>

        {{-- DÉTAILS VOLS ALLER --}}
        <div class="flight-details">
            <h3 style="margin-top: 0; color: #1e40af;">🛫 Vol Aller - {{ \Carbon\Carbon::parse($outboundFlights[0]['departure_airport']['time'])->format('d/m/Y') }}</h3>
            @foreach($outboundFlights as $segment)
                <div class="flight-segment">
                    <div style="display: flex; justify-between; align-items: center; margin-bottom: 10px;">
                        <strong>{{ $segment['airline'] ?? '' }} {{ $segment['flight_number'] ?? '' }}</strong>
                        <span style="color: #6b7280;">
                            @php
                                $duration = $segment['duration'] ?? 0;
                                $h = floor($duration / 60);
                                $m = $duration % 60;
                            @endphp
                            {{ $h > 0 ? "{$h}h " : "" }}{{ $m }}min
                        </span>
                    </div>
                    <div style="display: flex; justify-between;">
                        <div>
                            <strong>{{ \Carbon\Carbon::parse($segment['departure_airport']['time'])->format('H:i') }}</strong>
                            <span style="margin: 0 10px;">→</span>
                            <strong>{{ \Carbon\Carbon::parse($segment['arrival_airport']['time'])->format('H:i') }}</strong>
                        </div>
                        <div style="text-align: right;">
                            {{ $segment['departure_airport']['id'] }} → {{ $segment['arrival_airport']['id'] }}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- DÉTAILS VOLS RETOUR --}}
        <div class="flight-details" style="background-color: #f0fdf4;">
            <h3 style="margin-top: 0; color: #059669;">🛬 Vol Retour - {{ \Carbon\Carbon::parse($returnFlights[0]['departure_airport']['time'])->format('d/m/Y') }}</h3>
            @foreach($returnFlights as $segment)
                <div class="flight-segment" style="border-left-color: #10b981;">
                    <div style="display: flex; justify-between; align-items: center; margin-bottom: 10px;">
                        <strong>{{ $segment['airline'] ?? '' }} {{ $segment['flight_number'] ?? '' }}</strong>
                        <span style="color: #6b7280;">
                            @php
                                $duration = $segment['duration'] ?? 0;
                                $h = floor($duration / 60);
                                $m = $duration % 60;
                            @endphp
                            {{ $h > 0 ? "{$h}h " : "" }}{{ $m }}min
                        </span>
                    </div>
                    <div style="display: flex; justify-between;">
                        <div>
                            <strong>{{ \Carbon\Carbon::parse($segment['departure_airport']['time'])->format('H:i') }}</strong>
                            <span style="margin: 0 10px;">→</span>
                            <strong>{{ \Carbon\Carbon::parse($segment['arrival_airport']['time'])->format('H:i') }}</strong>
                        </div>
                        <div style="text-align: right;">
                            {{ $segment['departure_airport']['id'] }} → {{ $segment['arrival_airport']['id'] }}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- LISTE PASSAGERS --}}
        <div class="passengers-list">
            <h3 style="margin-top: 0;">👥 Liste des {{ $booking->number_of_passengers }} passagers</h3>
            @foreach($booking->passenger_details as $index => $passenger)
                <div class="passenger-item">
                    <span>
                        <strong>{{ $index + 1 }}.</strong> {{ $passenger['name'] }}
                    </span>
                    <span style="color: #92400e; font-size: 12px; font-weight: bold; text-transform: uppercase;">
                        {{ $passenger['type'] === 'adult' ? 'Adulte' : ($passenger['type'] === 'child' ? 'Enfant' : 'Bébé') }}
                    </span>
                </div>
            @endforeach
        </div>

        {{-- PRIX --}}
        <div class="price-box">
            <div style="font-size: 16px; opacity: 0.9;">Montant Total A/R</div>
            <div class="price-amount">
                {{ number_format($booking->final_amount, 0, ',', ' ') }} {{ $booking->currency }}
            </div>
            <div style="font-size: 14px; opacity: 0.8;">
                (dont {{ number_format($booking->tax_amount, 0, ',', ' ') }} {{ $booking->currency }} de taxes)
            </div>
        </div>

        {{-- TABLEAU RÉCAPITULATIF --}}
        <table>
            <thead>
                <tr>
                    <th>Détail</th>
                    <th style="text-align: right;">Montant</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Base tarifaire</td>
                    <td style="text-align: right;">{{ number_format($booking->total_amount, 0, ',', ' ') }} {{ $booking->currency }}</td>
                </tr>
                <tr>
                    <td>Taxes et frais</td>
                    <td style="text-align: right;">{{ number_format($booking->tax_amount, 0, ',', ' ') }} {{ $booking->currency }}</td>
                </tr>
                <tr style="font-weight: bold; background-color: #f3f4f6;">
                    <td>TOTAL À FACTURER</td>
                    <td style="text-align: right;">{{ number_format($booking->final_amount, 0, ',', ' ') }} {{ $booking->currency }}</td>
                </tr>
            </tbody>
        </table>

        {{-- ACTIONS --}}
        <div style="background-color: #fef3c7; padding: 15px; border-radius: 8px; margin: 20px 0;">
            <h3 style="margin-top: 0; color: #92400e;">⚠️ Actions à effectuer</h3>
            <ul style="margin: 10px 0; padding-left: 20px;">
                <li>Contacter le client par téléphone: <strong>{{ $customerPhone }}</strong></li>
                <li>Vérifier la disponibilité des vols</li>
                <li>Confirmer les tarifs exacts</li>
                <li>Envoyer une facture proforma</li>
                <li>Mettre à jour le statut de la réservation dans le système</li>
            </ul>
        </div>

        <div style="text-align: center; padding: 20px; background-color: #f9fafb; border-radius: 8px; margin-top: 30px;">
            <p style="margin: 0; color: #6b7280; font-size: 12px;">
                Email automatique envoyé le {{ \Carbon\Carbon::now()->format('d/m/Y à H:i') }}<br>
                Système de réservation Carré Premium
            </p>
        </div>
    </div>
</body>
</html>