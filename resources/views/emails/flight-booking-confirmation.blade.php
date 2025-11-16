<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation de réservation</title>
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
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .header {
            background: #111827;
            color: #ffffff;
            padding: 35px 30px;
            text-align: center;
        }
        .header h1 {
            font-size: 26px;
            font-weight: 700;
            margin-bottom: 8px;
        }
        .header p {
            font-size: 15px;
            color: #d1d5db;
        }
        .content {
            padding: 30px;
        }
        .greeting {
            font-size: 16px;
            color: #111827;
            margin-bottom: 20px;
            line-height: 1.8;
        }
        .booking-ref {
            background: #f3f4f6;
            border-left: 4px solid #3b82f6;
            padding: 18px;
            margin: 25px 0;
            border-radius: 6px;
        }
        .booking-ref-label {
            font-size: 12px;
            color: #6b7280;
            font-weight: 600;
            text-transform: uppercase;
            margin-bottom: 6px;
        }
        .booking-ref-value {
            font-size: 22px;
            font-weight: 700;
            color: #3b82f6;
            letter-spacing: 1px;
        }
        .route-display {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            padding: 20px;
            margin: 25px 0;
            text-align: center;
        }
        .route-cities {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 20px;
            margin-bottom: 16px;
        }
        .route-city {
            font-size: 20px;
            font-weight: 700;
            color: #111827;
        }
        .route-code {
            font-size: 14px;
            color: #6b7280;
            font-weight: 600;
            margin-top: 4px;
        }
        .route-arrow {
            font-size: 24px;
            color: #9ca3af;
        }
        .route-type {
            font-size: 13px;
            color: #6b7280;
            font-weight: 600;
        }
        .flight-section {
            background: #f0f9ff;
            border: 1px solid #bfdbfe;
            border-radius: 6px;
            padding: 16px;
            margin: 20px 0;
        }
        .flight-section-title {
            font-size: 14px;
            font-weight: 700;
            color: #1e40af;
            margin-bottom: 12px;
        }
        .flight-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #dbeafe;
        }
        .flight-row:last-child {
            border-bottom: none;
        }
        .flight-label {
            font-size: 13px;
            color: #1e3a8a;
            font-weight: 600;
        }
        .flight-value {
            font-size: 13px;
            color: #1e40af;
            font-weight: 700;
        }
        .info-box {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            padding: 20px;
            margin: 25px 0;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #e5e7eb;
        }
        .info-row:last-child {
            border-bottom: none;
        }
        .info-label {
            font-size: 14px;
            color: #6b7280;
            font-weight: 600;
        }
        .info-value {
            font-size: 14px;
            color: #111827;
            font-weight: 700;
        }
        .next-steps {
            background: #eff6ff;
            border-left: 4px solid #3b82f6;
            padding: 20px;
            margin: 25px 0;
            border-radius: 6px;
        }
        .next-steps-title {
            font-size: 16px;
            font-weight: 700;
            color: #1e40af;
            margin-bottom: 14px;
        }
        .next-steps ul {
            margin-left: 20px;
        }
        .next-steps li {
            margin: 8px 0;
            color: #1e3a8a;
            font-size: 14px;
        }
        .contact-info {
            background: #f9fafb;
            border-radius: 6px;
            padding: 20px;
            margin: 25px 0;
            text-align: center;
        }
        .contact-info-title {
            font-size: 15px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 12px;
        }
        .contact-info p {
            font-size: 14px;
            color: #6b7280;
            margin: 6px 0;
        }
        .footer {
            background: #f9fafb;
            padding: 25px;
            text-align: center;
            border-top: 1px solid #e5e7eb;
        }
        .footer-text {
            font-size: 13px;
            color: #6b7280;
            margin: 6px 0;
        }
        .footer-brand {
            font-size: 15px;
            font-weight: 700;
            color: #111827;
            margin-top: 12px;
        }
        @media (max-width: 600px) {
            .content {
                padding: 20px;
            }
            .route-cities {
                flex-direction: column;
                gap: 10px;
            }
            .route-arrow {
                transform: rotate(90deg);
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>Demande de réservation confirmée</h1>
            <p>Nous avons bien reçu votre demande</p>
        </div>

        <div class="content">
            <div class="greeting">
                Bonjour <strong>{{ $passengerName }}</strong>,
                <br><br>
                Nous vous confirmons la bonne réception de votre demande de réservation de vol.
            </div>

            <div class="booking-ref">
                <div class="booking-ref-label">Numéro de réservation</div>
                <div class="booking-ref-value">{{ $bookingNumber }}</div>
            </div>

            {{-- Trajet principal --}}
            <div class="route-display">
                <div class="route-cities">
                    <div>
                        <div class="route-city">{{ $departureCity }}</div>
                        <div class="route-code">{{ $departureCode }}</div>
                    </div>
                    <span class="route-arrow">{{ $isRoundTrip ? '⇄' : '→' }}</span>
                    <div>
                        <div class="route-city">{{ $arrivalCity }}</div>
                        <div class="route-code">{{ $arrivalCode }}</div>
                    </div>
                </div>
                <div class="route-type">
                    {{ $isRoundTrip ? 'Vol aller-retour' : 'Vol aller simple' }}
                </div>
            </div>

            {{-- Détails du vol ALLER --}}
            <div class="flight-section">
                <div class="flight-section-title">🛫 Vol Aller</div>
                <div class="flight-row">
                    <span class="flight-label">Départ</span>
                    <span class="flight-value">{{ $departureCity }} ({{ $departureCode }})</span>
                </div>
                @if($departureTime)
                <div class="flight-row">
                    <span class="flight-label">Heure de départ</span>
                    <span class="flight-value">{{ \Carbon\Carbon::parse($departureTime)->format('H:i') }}</span>
                </div>
                @endif
                <div class="flight-row">
                    <span class="flight-label">Date</span>
                    <span class="flight-value">{{ \Carbon\Carbon::parse($outboundDate)->format('d/m/Y') }}</span>
                </div>
                <div class="flight-row">
                    <span class="flight-label">Arrivée</span>
                    <span class="flight-value">{{ $arrivalCity }} ({{ $arrivalCode }})</span>
                </div>
                @if($arrivalTime)
                <div class="flight-row">
                    <span class="flight-label">Heure d'arrivée</span>
                    <span class="flight-value">{{ \Carbon\Carbon::parse($arrivalTime)->format('H:i') }}</span>
                </div>
                @endif
                @if(isset($outboundSegments) && count($outboundSegments) > 1)
                <div class="flight-row">
                    <span class="flight-label">Escales</span>
                    <span class="flight-value">{{ count($outboundSegments) - 1 }}</span>
                </div>
                @endif
            </div>

            {{-- Détails du vol RETOUR si existe --}}
            @if($isRoundTrip && $returnDate)
            <div class="flight-section" style="background: #f0fdf4; border-color: #bbf7d0;">
                <div class="flight-section-title" style="color: #166534;">🛬 Vol Retour</div>
                <div class="flight-row" style="border-color: #dcfce7;">
                    <span class="flight-label" style="color: #15803d;">Départ</span>
                    <span class="flight-value" style="color: #166534;">{{ $arrivalCity }} ({{ $arrivalCode }})</span>
                </div>
                @if($returnDepartureTime)
                <div class="flight-row" style="border-color: #dcfce7;">
                    <span class="flight-label" style="color: #15803d;">Heure de départ</span>
                    <span class="flight-value" style="color: #166534;">{{ \Carbon\Carbon::parse($returnDepartureTime)->format('H:i') }}</span>
                </div>
                @endif
                <div class="flight-row" style="border-color: #dcfce7;">
                    <span class="flight-label" style="color: #15803d;">Date</span>
                    <span class="flight-value" style="color: #166534;">{{ \Carbon\Carbon::parse($returnDate)->format('d/m/Y') }}</span>
                </div>
                <div class="flight-row" style="border-color: #dcfce7;">
                    <span class="flight-label" style="color: #15803d;">Arrivée</span>
                    <span class="flight-value" style="color: #166534;">{{ $departureCity }} ({{ $departureCode }})</span>
                </div>
                @if(isset($returnSegments) && count($returnSegments) > 1)
                <div class="flight-row" style="border-color: #dcfce7;">
                    <span class="flight-label" style="color: #15803d;">Escales</span>
                    <span class="flight-value" style="color: #166534;">{{ count($returnSegments) - 1 }}</span>
                </div>
                @endif
            </div>
            @endif

            {{-- Informations générales --}}
            <div class="info-box">
                <div class="info-row">
                    <span class="info-label">Nombre de passagers</span>
                    <span class="info-value">{{ $totalPassengers }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Classe</span>
                    <span class="info-value">{{ ucfirst(str_replace('_', ' ', $seatClass)) }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Montant total</span>
                    <span class="info-value">{{ $totalPrice }} {{ $currency }}</span>
                </div>
            </div>

            {{-- Prochaines étapes --}}
            <div class="next-steps">
                <div class="next-steps-title">Prochaines étapes</div>
                <ul>
                    <li>Notre équipe va vérifier la disponibilité de votre vol</li>
                    <li>Vous serez contacté sous 24 heures par téléphone ou email</li>
                    <li>Conservez votre numéro de réservation : <strong>{{ $bookingNumber }}</strong></li>
                    <li>Un email de confirmation vous sera envoyé après validation</li>
                </ul>
            </div>

            {{-- Contact --}}
            <div class="contact-info">
                <div class="contact-info-title">Besoin d'aide ?</div>
                <p>N'hésitez pas à nous contacter en mentionnant</p>
                <p>votre numéro de réservation <strong>{{ $bookingNumber }}</strong></p>
            </div>

            <p style="margin-top: 30px; font-size: 14px; color: #6b7280; line-height: 1.8;">
                Cordialement,<br>
                <strong style="color: #111827;">L'équipe Carré Premium</strong>
            </p>
        </div>

        <div class="footer">
            <p class="footer-text">Cet email a été envoyé automatiquement</p>
            <p class="footer-text">Merci de ne pas y répondre</p>
            <p class="footer-brand">Carré Premium</p>
            <p class="footer-text">&copy; {{ date('Y') }} Tous droits réservés</p>
        </div>
    </div>
</body>
</html>