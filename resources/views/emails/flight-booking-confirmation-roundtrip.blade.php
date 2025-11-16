<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation de réservation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            background-color: #ffffff;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #9333ea 0%, #7e22ce 100%);
            color: white;
            padding: 20px;
            border-radius: 10px 10px 0 0;
            margin: -30px -30px 30px -30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .booking-number {
            background-color: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .booking-number strong {
            font-size: 18px;
            color: #92400e;
        }
        .flight-section {
            background-color: #f9fafb;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            border: 2px solid #e5e7eb;
        }
        .flight-section.outbound {
            border-left: 4px solid #3b82f6;
        }
        .flight-section.return {
            border-left: 4px solid #10b981;
        }
        .flight-section h2 {
            margin-top: 0;
            color: #1f2937;
            font-size: 20px;
        }
        .segment {
            background-color: white;
            padding: 15px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #e5e7eb;
        }
        .segment-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e5e7eb;
        }
        .flight-route {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 15px 0;
        }
        .airport {
            flex: 1;
        }
        .airport-time {
            font-size: 24px;
            font-weight: bold;
            color: #1f2937;
        }
        .airport-code {
            font-size: 16px;
            font-weight: bold;
            color: #6b7280;
            margin: 5px 0;
        }
        .airport-name {
            font-size: 12px;
            color: #9ca3af;
        }
        .flight-arrow {
            flex: 0 0 80px;
            text-align: center;
            color: #9ca3af;
        }
        .layover {
            background-color: #fef3c7;
            border-left: 3px solid #f59e0b;
            padding: 10px 15px;
            margin: 10px 0;
            border-radius: 5px;
            text-align: center;
            font-size: 14px;
            color: #92400e;
        }
        .passengers-section {
            background-color: #eff6ff;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .passengers-section h2 {
            margin-top: 0;
            color: #1e40af;
        }
        .passenger-item {
            background-color: white;
            padding: 10px;
            margin: 8px 0;
            border-radius: 5px;
            border-left: 3px solid #3b82f6;
        }
        .price-summary {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .price-row {
            display: flex;
            justify-content: space-between;
            margin: 10px 0;
            padding: 8px 0;
        }
        .price-row.total {
            border-top: 2px solid rgba(255,255,255,0.3);
            margin-top: 15px;
            padding-top: 15px;
            font-size: 20px;
            font-weight: bold;
        }
        .info-box {
            background-color: #dbeafe;
            border-left: 4px solid #3b82f6;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .footer {
            text-align: center;
            padding: 20px;
            color: #6b7280;
            font-size: 12px;
            border-top: 1px solid #e5e7eb;
            margin-top: 30px;
        }
        @media only screen and (max-width: 600px) {
            .flight-route {
                flex-direction: column;
            }
            .flight-arrow {
                transform: rotate(90deg);
                margin: 10px 0;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>✈️ Confirmation de votre réservation Aller-Retour</h1>
            <p style="margin: 10px 0 0 0;">Merci pour votre confiance !</p>
        </div>

        <p>Bonjour <strong>{{ $passengerName }}</strong>,</p>
        
        <p>Nous avons bien reçu votre demande de réservation de vol aller-retour. Voici le récapitulatif :</p>

        <div class="booking-number">
            <strong>📋 Numéro de réservation : {{ $booking->booking_number }}</strong><br>
            <small>Conservez précieusement ce numéro pour toute demande</small>
        </div>

        {{-- VOL ALLER --}}
        <div class="flight-section outbound">
            <h2>🛫 Vol Aller</h2>
            <p><strong>📅 Date :</strong> {{ \Carbon\Carbon::parse($outboundFlights[0]['departure_airport']['time'])->format('D d M Y') }}</p>

            @foreach($outboundFlights as $index => $segment)
                <div class="segment">
                    <div class="segment-header">
                        <div>
                            <strong>{{ $segment['airline'] ?? '' }}</strong> {{ $segment['flight_number'] ?? '' }}
                        </div>
                        <div style="color: #6b7280; font-size: 14px;">
                            @php
                                $duration = $segment['duration'] ?? 0;
                                $h = floor($duration / 60);
                                $m = $duration % 60;
                            @endphp
                            ⏱️ {{ $h > 0 ? "{$h}h " : "" }}{{ $m }}min
                        </div>
                    </div>

                    <div class="flight-route">
                        <div class="airport">
                            <div class="airport-time">
                                {{ \Carbon\Carbon::parse($segment['departure_airport']['time'])->format('H:i') }}
                            </div>
                            <div class="airport-code">{{ $segment['departure_airport']['id'] ?? '' }}</div>
                            <div class="airport-name">{{ $segment['departure_airport']['name'] ?? '' }}</div>
                        </div>

                        <div class="flight-arrow">
                            <svg width="60" height="20" viewBox="0 0 60 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M0 10 L50 10 M45 5 L50 10 L45 15" stroke="#9ca3af" stroke-width="2"/>
                            </svg>
                        </div>

                        <div class="airport" style="text-align: right;">
                            <div class="airport-time">
                                {{ \Carbon\Carbon::parse($segment['arrival_airport']['time'])->format('H:i') }}
                            </div>
                            <div class="airport-code">{{ $segment['arrival_airport']['id'] ?? '' }}</div>
                            <div class="airport-name">{{ $segment['arrival_airport']['name'] ?? '' }}</div>
                        </div>
                    </div>

                    @if(!empty($segment['aircraft']))
                        <div style="margin-top: 10px; font-size: 12px; color: #6b7280;">
                            ✈️ Appareil: {{ $segment['aircraft'] }}
                        </div>
                    @endif
                </div>

                @if(!empty($outboundLayovers[$index]))
                    <div class="layover">
                        ⏱️ Escale à {{ $outboundLayovers[$index]['name'] ?? $outboundLayovers[$index]['id'] ?? '' }}
                        @php
                            $layoverDuration = $outboundLayovers[$index]['duration'] ?? 0;
                            $lh = floor($layoverDuration / 60);
                            $lm = $layoverDuration % 60;
                        @endphp
                        ({{ $lh > 0 ? "{$lh}h " : "" }}{{ $lm }}min)
                    </div>
                @endif
            @endforeach
        </div>

        {{-- VOL RETOUR --}}
        <div class="flight-section return">
            <h2>🛬 Vol Retour</h2>
            <p><strong>📅 Date :</strong> {{ \Carbon\Carbon::parse($returnFlights[0]['departure_airport']['time'])->format('D d M Y') }}</p>

            @foreach($returnFlights as $index => $segment)
                <div class="segment">
                    <div class="segment-header">
                        <div>
                            <strong>{{ $segment['airline'] ?? '' }}</strong> {{ $segment['flight_number'] ?? '' }}
                        </div>
                        <div style="color: #6b7280; font-size: 14px;">
                            @php
                                $duration = $segment['duration'] ?? 0;
                                $h = floor($duration / 60);
                                $m = $duration % 60;
                            @endphp
                            ⏱️ {{ $h > 0 ? "{$h}h " : "" }}{{ $m }}min
                        </div>
                    </div>

                    <div class="flight-route">
                        <div class="airport">
                            <div class="airport-time">
                                {{ \Carbon\Carbon::parse($segment['departure_airport']['time'])->format('H:i') }}
                            </div>
                            <div class="airport-code">{{ $segment['departure_airport']['id'] ?? '' }}</div>
                            <div class="airport-name">{{ $segment['departure_airport']['name'] ?? '' }}</div>
                        </div>

                        <div class="flight-arrow">
                            <svg width="60" height="20" viewBox="0 0 60 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M0 10 L50 10 M45 5 L50 10 L45 15" stroke="#9ca3af" stroke-width="2"/>
                            </svg>
                        </div>

                        <div class="airport" style="text-align: right;">
                            <div class="airport-time">
                                {{ \Carbon\Carbon::parse($segment['arrival_airport']['time'])->format('H:i') }}
                            </div>
                            <div class="airport-code">{{ $segment['arrival_airport']['id'] ?? '' }}</div>
                            <div class="airport-name">{{ $segment['arrival_airport']['name'] ?? '' }}</div>
                        </div>
                    </div>

                    @if(!empty($segment['aircraft']))
                        <div style="margin-top: 10px; font-size: 12px; color: #6b7280;">
                            ✈️ Appareil: {{ $segment['aircraft'] }}
                        </div>
                    @endif
                </div>

                @if(!empty($returnLayovers[$index]))
                    <div class="layover">
                        ⏱️ Escale à {{ $returnLayovers[$index]['name'] ?? $returnLayovers[$index]['id'] ?? '' }}
                        @php
                            $layoverDuration = $returnLayovers[$index]['duration'] ?? 0;
                            $lh = floor($layoverDuration / 60);
                            $lm = $layoverDuration % 60;
                        @endphp
                        ({{ $lh > 0 ? "{$lh}h " : "" }}{{ $lm }}min)
                    </div>
                @endif
            @endforeach
        </div>

        {{-- PASSAGERS --}}
        <div class="passengers-section">
            <h2>👥 Passagers ({{ $booking->number_of_passengers }})</h2>
            @foreach($booking->passenger_details as $passenger)
                <div class="passenger-item">
                    <strong>{{ $passenger['name'] }}</strong>
                    <span style="color: #6b7280; margin-left: 10px; text-transform: capitalize;">
                        ({{ $passenger['type'] === 'adult' ? 'Adulte' : ($passenger['type'] === 'child' ? 'Enfant' : 'Bébé') }})
                    </span>
                </div>
            @endforeach
        </div>

        {{-- RÉSUMÉ PRIX --}}
        <div class="price-summary">
            <h2 style="margin-top: 0;">💰 Résumé du Prix</h2>
            <div class="price-row">
                <span>Sous-total</span>
                <span>{{ number_format($booking->total_amount, 0, ',', ' ') }} {{ $booking->currency }}</span>
            </div>
            <div class="price-row">
                <span>Taxes et frais</span>
                <span>{{ number_format($booking->tax_amount, 0, ',', ' ') }} {{ $booking->currency }}</span>
            </div>
            <div class="price-row total">
                <span>TOTAL</span>
                <span>{{ number_format($booking->final_amount, 0, ',', ' ') }} {{ $booking->currency }}</span>
            </div>
        </div>

        {{-- INFORMATIONS IMPORTANTES --}}
        <div class="info-box">
            <h3 style="margin-top: 0;">ℹ️ Informations importantes</h3>
            <ul style="margin: 0; padding-left: 20px;">
                <li>Votre demande est en cours de traitement (statut: <strong>{{ $booking->status }}</strong>)</li>
                <li>Nous vous contacterons pour confirmer la disponibilité et finaliser le paiement</li>
                <li>Conservez votre numéro de réservation : <strong>{{ $booking->booking_number }}</strong></li>
                <li>Prévoyez d'arriver à l'aéroport au moins 2-3h avant le décollage</li>
                <li>Vérifiez les exigences de visa et de santé pour votre destination</li>
            </ul>
        </div>

        <div class="footer">
            <p>Cet email a été envoyé automatiquement, merci de ne pas y répondre.</p>
            <p>Pour toute question, contactez-nous à {{ env('COMPANY_EMAIL', 'support@votresociete.com') }}</p>
            <p style="margin-top: 20px;">© {{ date('Y') }} Carré Premium. Tous droits réservés.</p>
        </div>
    </div>
</body>
</html>