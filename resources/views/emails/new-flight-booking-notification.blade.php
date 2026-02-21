<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouvelle réservation de vol</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #1f2937;
            background-color: #f9fafb;
            padding: 20px;
        }
        .email-container {
            max-width: 700px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .header {
            background: #111827;
            color: #ffffff;
            padding: 30px;
            border-bottom: 4px solid #3b82f6;
        }
        .header h1 {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 8px;
        }
        .header p {
            font-size: 14px;
            color: #d1d5db;
            margin: 0;
        }
        .content {
            padding: 30px;
        }
        .alert-box {
            background: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 20px;
            margin-bottom: 30px;
        }
        .alert-box h3 {
            color: #92400e;
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 12px;
        }
        .alert-box p {
            color: #78350f;
            font-size: 14px;
            margin: 6px 0;
        }
        .alert-box a {
            color: #92400e;
            font-weight: 600;
            text-decoration: none;
            border-bottom: 1px solid #f59e0b;
        }
        .section {
            margin-bottom: 30px;
        }
        .section-title {
            font-size: 16px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 16px;
            padding-bottom: 8px;
            border-bottom: 2px solid #e5e7eb;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
        }
        .info-table tr {
            border-bottom: 1px solid #f3f4f6;
        }
        .info-table tr:last-child {
            border-bottom: none;
        }
        .info-table td {
            padding: 12px 0;
            font-size: 14px;
        }
        .info-table td:first-child {
            color: #6b7280;
            font-weight: 600;
            width: 40%;
        }
        .info-table td:last-child {
            color: #111827;
            font-weight: 600;
        }
        .flight-route {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            padding: 20px;
            margin: 20px 0;
        }
        .flight-route-title {
            font-size: 14px;
            font-weight: 700;
            color: #3b82f6;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .route-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
        }
        .route-row:last-child {
            margin-bottom: 0;
        }
        .route-point {
            flex: 1;
        }
        .route-code {
            font-size: 24px;
            font-weight: 700;
            color: #111827;
        }
        .route-city {
            font-size: 13px;
            color: #6b7280;
            margin-top: 4px;
        }
        .route-time {
            font-size: 18px;
            font-weight: 600;
            color: #3b82f6;
        }
        .route-date {
            font-size: 12px;
            color: #6b7280;
            margin-top: 2px;
        }
        .route-arrow {
            padding: 0 20px;
            font-size: 20px;
            color: #9ca3af;
        }
        .passenger-list {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            overflow: hidden;
        }
        .passenger-item {
            padding: 16px;
            border-bottom: 1px solid #e5e7eb;
        }
        .passenger-item:last-child {
            border-bottom: none;
        }
        .passenger-name {
            font-size: 15px;
            font-weight: 600;
            color: #111827;
            margin-bottom: 6px;
        }
        .passenger-type {
            display: inline-block;
            background: #dbeafe;
            color: #1e40af;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            margin-left: 8px;
        }
        .passenger-contact {
            font-size: 13px;
            color: #6b7280;
            margin-top: 4px;
        }
        .price-summary {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 6px;
            padding: 20px;
        }
        .price-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            font-size: 15px;
        }
        .price-row:last-child {
            border-top: 2px solid #86efac;
            margin-top: 10px;
            padding-top: 16px;
        }
        .price-label {
            color: #166534;
            font-weight: 600;
        }
        .price-value {
            color: #166534;
            font-weight: 700;
        }
        .price-row:last-child .price-value {
            font-size: 20px;
            color: #14532d;
        }
        .action-list {
            background: #eff6ff;
            border-left: 4px solid #3b82f6;
            padding: 20px;
            border-radius: 6px;
        }
        .action-list h3 {
            color: #1e40af;
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 12px;
        }
        .action-list ol {
            margin-left: 20px;
            color: #1e3a8a;
        }
        .action-list li {
            margin: 8px 0;
            font-size: 14px;
        }
        .footer {
            background: #f9fafb;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #6b7280;
            border-top: 1px solid #e5e7eb;
        }
        @media (max-width: 600px) {
            .content {
                padding: 20px;
            }
            .route-row {
                flex-direction: column;
                text-align: center;
            }
            .route-arrow {
                transform: rotate(90deg);
                padding: 10px 0;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>Nouvelle demande de réservation</h1>
            <p>Référence : {{ $bookingNumber }} • {{ $bookingDate }}</p>
        </div>

        <div class="content">
            <!-- Contact Client -->
            <div class="alert-box">
                <h3>Coordonnées du client</h3>
                <p><strong>Email :</strong> <a href="mailto:{{ $customerEmail }}">{{ $customerEmail }}</a></p>
                <p><strong>Téléphone :</strong> <a href="tel:{{ $customerPhone }}">{{ $customerPhone }}</a></p>
                <p style="margin-top: 12px; font-weight: 600;">À contacter dans les 24 heures</p>
            </div>

            <!-- Informations générales -->
            <div class="section">
                <div class="section-title">Informations de la réservation</div>
                <table class="info-table">
                    <tr>
                        <td>Numéro de réservation</td>
                        <td>{{ $bookingNumber }}</td>
                    </tr>
                    <tr>
                        <td>Date de la demande</td>
                        <td>{{ $bookingDate }}</td>
                    </tr>
                    <tr>
                        <td>Type de vol</td>
                        <td>{{ $tripTypeLabel }}</td>
                    </tr>
                    <tr>
                        <td>Classe de voyage</td>
                        <td>{{ ucfirst(str_replace('_', ' ', $seatClass)) }}</td>
                    </tr>
                    <tr>
                        <td>Nombre de passagers</td>
                        <td>{{ $totalPassengers }}</td>
                    </tr>
                </table>
            </div>

            <!-- Détails du trajet ALLER -->
            <div class="section">
                <div class="section-title">Détails du trajet</div>
                
                <div class="flight-route">
                    <div class="flight-route-title">
                        <span>🛫</span>
                        <span>Vol Aller</span>
                    </div>
                    <div class="route-row">
                        <div class="route-point">
                            <div class="route-code">{{ $departureCode }}</div>
                            <div class="route-city">{{ $departureCity }}</div>
                            @if($departureTime)
                                <div class="route-time" style="margin-top: 12px;">
                                    {{ \Carbon\Carbon::parse($departureTime)->format('H:i') }}
                                </div>
                            @endif
                            <div class="route-date">{{ $outboundDate }}</div>
                        </div>
                        
                        <div class="route-arrow">→</div>
                        
                        <div class="route-point" style="text-align: right;">
                            <div class="route-code">{{ $arrivalCode }}</div>
                            <div class="route-city">{{ $arrivalCity }}</div>
                            @if($arrivalTime)
                                <div class="route-time" style="margin-top: 12px;">
                                    {{ \Carbon\Carbon::parse($arrivalTime)->format('H:i') }}
                                </div>
                            @endif
                            <div class="route-date">{{ $outboundDate }}</div>
                        </div>
                    </div>
                    @if(isset($outboundSegments) && count($outboundSegments) > 1)
                        <div style="text-align: center; padding: 8px; background: #fff; border-radius: 4px; font-size: 13px; color: #6b7280; margin-top: 12px;">
                            {{ count($outboundSegments) - 1 }} escale(s)
                        </div>
                    @endif
                </div>

                {{-- Vol RETOUR si existe --}}
                @if($hasReturnFlight && $returnDate)
                    <div class="flight-route" style="background: #f0fdf4; border-color: #bbf7d0;">
                        <div class="flight-route-title" style="color: #166534;">
                            <span>🛬</span>
                            <span>Vol Retour</span>
                        </div>
                        <div class="route-row">
                            <div class="route-point">
                                <div class="route-code">{{ $returnDepartureInfo['code'] ?? $arrivalCode }}</div>
                                <div class="route-city">{{ $returnDepartureInfo['city'] ?: $returnDepartureInfo['name'] ?? $arrivalCity }}</div>
                                @if($returnDepartureInfo && $returnDepartureInfo['time'])
                                    <div class="route-time" style="margin-top: 12px; color: #166534;">
                                        {{ \Carbon\Carbon::parse($returnDepartureInfo['time'])->format('H:i') }}
                                    </div>
                                @endif
                                <div class="route-date">{{ $returnDate }}</div>
                            </div>
                            
                            <div class="route-arrow">→</div>
                            
                            <div class="route-point" style="text-align: right;">
                                <div class="route-code">{{ $returnArrivalInfo['code'] ?? $departureCode }}</div>
                                <div class="route-city">{{ $returnArrivalInfo['city'] ?: $returnArrivalInfo['name'] ?? $departureCity }}</div>
                                @if($returnArrivalInfo && $returnArrivalInfo['time'])
                                    <div class="route-time" style="margin-top: 12px; color: #166534;">
                                        {{ \Carbon\Carbon::parse($returnArrivalInfo['time'])->format('H:i') }}
                                    </div>
                                @endif
                                <div class="route-date">{{ $returnDate }}</div>
                            </div>
                        </div>
                        @if(isset($returnSegments) && count($returnSegments) > 1)
                            <div style="text-align: center; padding: 8px; background: #fff; border-radius: 4px; font-size: 13px; color: #6b7280; margin-top: 12px;">
                                {{ count($returnSegments) - 1 }} escale(s)
                            </div>
                        @endif
                    </div>
                @endif
            </div>

            <!-- Liste des passagers -->
            <div class="section">
                <div class="section-title">Liste des passagers</div>
                <div class="passenger-list">
                    @foreach($passengerDetails as $index => $passenger)
                        <div class="passenger-item">
                            <div class="passenger-name">
                                {{ $passenger['name'] }}
                                <span class="passenger-type">
                                    @if($passenger['type'] == 'adult')
                                        Adulte
                                    @elseif($passenger['type'] == 'child')
                                        Enfant
                                    @else
                                        Bébé
                                    @endif
                                </span>
                            </div>
                            @if($passenger['email'] || $passenger['phone'])
                                <div class="passenger-contact">
                                    @if($passenger['email'])
                                        {{ $passenger['email'] }}
                                    @endif
                                    @if($passenger['email'] && $passenger['phone'])
                                        •
                                    @endif
                                    @if($passenger['phone'])
                                        {{ $passenger['phone'] }}
                                    @endif
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Segments de vol détaillés -->
            @if(isset($flightSegments) && count($flightSegments) > 0)
            <div class="section">
                <div class="section-title">Segments de vol détaillés</div>
                
                {{-- Segments ALLER --}}
                @if(isset($outboundSegments) && count($outboundSegments) > 0)
                    <div style="font-size: 14px; font-weight: 600; color: #3b82f6; margin: 16px 0 12px 0;">
                        🛫 Segments Aller ({{ count($outboundSegments) }})
                    </div>
                    @foreach($outboundSegments as $index => $segment)
                        <div style="background: #f0f9ff; border: 1px solid #bfdbfe; border-radius: 6px; padding: 16px; margin-bottom: 12px;">
                            <div style="font-weight: 600; color: #1e40af; margin-bottom: 8px;">
                                {{ $segment['airline'] ?? 'Compagnie aérienne' }} - Vol {{ $segment['flight_number'] ?? 'N/A' }}
                            </div>
                            <div style="font-size: 13px; color: #1e3a8a; line-height: 1.8;">
                                <div><strong>Départ :</strong> {{ $segment['departure_airport']['code'] ?? 'N/A' }} 
                                    @if(isset($segment['departure_airport']['time']))
                                        à {{ \Carbon\Carbon::parse($segment['departure_airport']['time'])->format('H:i') }}
                                        le {{ \Carbon\Carbon::parse($segment['departure_airport']['time'])->format('d/m/Y') }}
                                    @endif
                                </div>
                                <div><strong>Arrivée :</strong> {{ $segment['arrival_airport']['code'] ?? 'N/A' }} 
                                    @if(isset($segment['arrival_airport']['time']))
                                        à {{ \Carbon\Carbon::parse($segment['arrival_airport']['time'])->format('H:i') }}
                                        le {{ \Carbon\Carbon::parse($segment['arrival_airport']['time'])->format('d/m/Y') }}
                                    @endif
                                </div>
                                @if(isset($segment['duration']))
                                    @php
                                        $h = floor($segment['duration'] / 60);
                                        $m = $segment['duration'] % 60;
                                    @endphp
                                    <div><strong>Durée :</strong> {{ $h }}h {{ $m }}min</div>
                                @endif
                                @if(isset($segment['aircraft']))
                                    <div><strong>Appareil :</strong> {{ $segment['aircraft'] }}</div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                @endif
                
                {{-- Segments RETOUR --}}
                @if(isset($returnSegments) && count($returnSegments) > 0)
                    <div style="font-size: 14px; font-weight: 600; color: #166534; margin: 24px 0 12px 0;">
                        🛬 Segments Retour ({{ count($returnSegments) }})
                    </div>
                    @foreach($returnSegments as $index => $segment)
                        <div style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 6px; padding: 16px; margin-bottom: 12px;">
                            <div style="font-weight: 600; color: #166534; margin-bottom: 8px;">
                                {{ $segment['airline'] ?? 'Compagnie aérienne' }} - Vol {{ $segment['flight_number'] ?? 'N/A' }}
                            </div>
                            <div style="font-size: 13px; color: #15803d; line-height: 1.8;">
                                <div><strong>Départ :</strong> {{ $segment['departure_airport']['code'] ?? 'N/A' }} 
                                    @if(isset($segment['departure_airport']['time']))
                                        à {{ \Carbon\Carbon::parse($segment['departure_airport']['time'])->format('H:i') }}
                                        le {{ \Carbon\Carbon::parse($segment['departure_airport']['time'])->format('d/m/Y') }}
                                    @endif
                                </div>
                                <div><strong>Arrivée :</strong> {{ $segment['arrival_airport']['code'] ?? 'N/A' }} 
                                    @if(isset($segment['arrival_airport']['time']))
                                        à {{ \Carbon\Carbon::parse($segment['arrival_airport']['time'])->format('H:i') }}
                                        le {{ \Carbon\Carbon::parse($segment['arrival_airport']['time'])->format('d/m/Y') }}
                                    @endif
                                </div>
                                @if(isset($segment['duration']))
                                    @php
                                        $h = floor($segment['duration'] / 60);
                                        $m = $segment['duration'] % 60;
                                    @endphp
                                    <div><strong>Durée :</strong> {{ $h }}h {{ $m }}min</div>
                                @endif
                                @if(isset($segment['aircraft']))
                                    <div><strong>Appareil :</strong> {{ $segment['aircraft'] }}</div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
            @endif

            <!-- Récapitulatif financier -->
            <div class="section">
                <div class="section-title">Récapitulatif financier</div>
                <div class="price-summary">
                    <div class="price-row">
                        <span class="price-label">Prix de base</span>
                        <span class="price-value">{{ $basePrice }} {{ $currency }}</span>
                    </div>
                    <div class="price-row">
                        <span class="price-label">Taxes et frais</span>
                        <span class="price-value">{{ $taxes }} {{ $currency }}</span>
                    </div>
                    <div class="price-row">
                        <span class="price-label">MONTANT TOTAL</span>
                        <span class="price-value">{{ $totalPrice }} {{ $currency }}</span>
                    </div>
                </div>
            </div>

            <!-- Actions à effectuer -->
            <div class="action-list">
                <h3>Actions à effectuer</h3>
                <ol>
                    <li>Vérifier la disponibilité du vol</li>
                    <li>Contacter le client au {{ $customerPhone }} sous 24h</li>
                    <li>Confirmer les détails du voyage</li>
                    <li>Discuter du mode de paiement</li>
                    <li>Mettre à jour le statut dans le système</li>
                    <li>Envoyer les billets après paiement</li>
                </ol>
            </div>
        </div>  

        <div class="footer">
            <p><strong>Système de réservation Carré Premium</strong></p>
            <p>Email automatique - Ne pas répondre</p>
            <p>&copy; {{ date('Y') }} Carré Premium. Tous droits réservés.</p>
        </div>
    </div>
</body>
</html>