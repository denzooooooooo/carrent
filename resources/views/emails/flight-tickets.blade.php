<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vos Billets d'Avion</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            background-color: #ffffff;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            padding-bottom: 20px;
            border-bottom: 3px solid #4F46E5;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #4F46E5;
            margin: 0;
            font-size: 28px;
        }
        .booking-ref {
            background-color: #EEF2FF;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
            text-align: center;
        }
        .booking-ref strong {
            font-size: 24px;
            color: #4F46E5;
            letter-spacing: 2px;
        }
        .flight-details {
            background-color: #F9FAFB;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .flight-segment {
            padding: 15px 0;
            border-bottom: 1px solid #E5E7EB;
        }
        .flight-segment:last-child {
            border-bottom: none;
        }
        .route {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 10px 0;
        }
        .airport {
            text-align: center;
            flex: 1;
        }
        .airport-code {
            font-size: 24px;
            font-weight: bold;
            color: #1F2937;
        }
        .airport-name {
            font-size: 12px;
            color: #6B7280;
        }
        .arrow {
            flex: 0 0 40px;
            text-align: center;
            color: #9CA3AF;
            font-size: 20px;
        }
        .passengers {
            margin: 20px 0;
        }
        .passenger {
            background-color: #F9FAFB;
            padding: 10px 15px;
            border-radius: 6px;
            margin: 10px 0;
        }
        .important-info {
            background-color: #FEF3C7;
            border-left: 4px solid #F59E0B;
            padding: 15px;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #E5E7EB;
            color: #6B7280;
            font-size: 14px;
        }
        .button {
            display: inline-block;
            padding: 12px 30px;
            background-color: #4F46E5;
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 6px;
            margin: 10px 0;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
        }
        .info-label {
            color: #6B7280;
            font-size: 14px;
        }
        .info-value {
            font-weight: bold;
            color: #1F2937;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>✈️ Vos Billets d'Avion</h1>
            <p style="color: #6B7280; margin: 10px 0 0 0;">Réservation confirmée</p>
        </div>

        <p>Bonjour,</p>
        <p>Votre réservation de vol a été confirmée avec succès ! Vous trouverez ci-dessous tous les détails de votre voyage.</p>

        <!-- Booking Reference -->
        <div class="booking-ref">
            <p style="margin: 0 0 5px 0; color: #6B7280; font-size: 14px;">Numéro de réservation</p>
            <strong>{{ $bookingReference }}</strong>
            <p style="margin: 5px 0 0 0; color: #6B7280; font-size: 12px;">Référence: {{ $booking->booking_number }}</p>
        </div>

        <!-- Flight Details -->
        @foreach($slices as $index => $slice)
        <div class="flight-details">
            <h3 style="margin-top: 0; color: #4F46E5;">
                @if($index == 0)
                    Vol Aller
                @else
                    Vol Retour
                @endif
            </h3>

            @foreach($slice['segments'] as $segment)
            <div class="flight-segment">
                <div class="route">
                    <div class="airport">
                        <div class="airport-code">{{ $segment['origin']['iata_code'] }}</div>
                        <div class="airport-name">{{ $segment['origin']['city_name'] ?? $segment['origin']['name'] }}</div>
                        <div style="font-size: 18px; font-weight: bold; margin-top: 5px;">
                            {{ \Carbon\Carbon::parse($segment['departing_at'])->format('H:i') }}
                        </div>
                        <div style="font-size: 12px; color: #6B7280;">
                            {{ \Carbon\Carbon::parse($segment['departing_at'])->format('d M Y') }}
                        </div>
                    </div>
                    
                    <div class="arrow">→</div>
                    
                    <div class="airport">
                        <div class="airport-code">{{ $segment['destination']['iata_code'] }}</div>
                        <div class="airport-name">{{ $segment['destination']['city_name'] ?? $segment['destination']['name'] }}</div>
                        <div style="font-size: 18px; font-weight: bold; margin-top: 5px;">
                            {{ \Carbon\Carbon::parse($segment['arriving_at'])->format('H:i') }}
                        </div>
                        <div style="font-size: 12px; color: #6B7280;">
                            {{ \Carbon\Carbon::parse($segment['arriving_at'])->format('d M Y') }}
                        </div>
                    </div>
                </div>

                <div class="info-row">
                    <span class="info-label">Compagnie:</span>
                    <span class="info-value">{{ $segment['marketing_carrier']['name'] ?? 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Vol:</span>
                    <span class="info-value">{{ $segment['marketing_carrier']['iata_code'] ?? '' }}{{ $segment['marketing_carrier_flight_number'] ?? '' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Avion:</span>
                    <span class="info-value">{{ $segment['aircraft']['name'] ?? 'N/A' }}</span>
                </div>
            </div>
            @endforeach
        </div>
        @endforeach

        <!-- Passengers -->
        <div class="passengers">
            <h3 style="color: #4F46E5;">Passagers ({{ count($passengers) }})</h3>
            @foreach($passengers as $passenger)
            <div class="passenger">
                <strong>{{ ucfirst($passenger['title'] ?? '') }} {{ $passenger['first_name'] ?? '' }} {{ $passenger['last_name'] ?? '' }}</strong>
                <div style="font-size: 14px; color: #6B7280; margin-top: 5px;">
                    {{ ucfirst($passenger['type'] ?? 'adult') }} • Né(e) le {{ \Carbon\Carbon::parse($passenger['born_on'])->format('d/m/Y') }}
                </div>
            </div>
            @endforeach
        </div>

        <!-- Important Information -->
        <div class="important-info">
            <strong style="display: block; margin-bottom: 10px;">⚠️ Informations importantes</strong>
            <ul style="margin: 0; padding-left: 20px;">
                <li>Présentez-vous à l'aéroport <strong>2 heures avant</strong> le départ pour les vols internationaux</li>
                <li>Munissez-vous de votre <strong>passeport</strong> et de ce numéro de réservation</li>
                <li>Vérifiez les <strong>restrictions de bagages</strong> de votre compagnie aérienne</li>
                <li>Effectuez votre <strong>check-in en ligne</strong> 24h avant le départ</li>
            </ul>
        </div>

        <!-- Documents Link -->
        @if(isset($duffelOrder['documents']) && count($duffelOrder['documents']) > 0)
        <div style="text-align: center; margin: 30px 0;">
            <p><strong>Vos billets électroniques sont disponibles:</strong></p>
            @foreach($duffelOrder['documents'] as $document)
                @if(isset($document['url']))
                <a href="{{ $document['url'] }}" class="button">
                    📄 Télécharger le billet
                </a>
                @endif
            @endforeach
        </div>
        @endif

        <!-- Support -->
        <div style="background-color: #F9FAFB; padding: 20px; border-radius: 8px; margin: 20px 0;">
            <h4 style="margin-top: 0; color: #4F46E5;">Besoin d'aide ?</h4>
            <p style="margin: 5px 0;">Notre équipe est à votre disposition 24/7</p>
            <p style="margin: 5px 0;">📧 Email: {{ config('carre_premium.contact.support_email') }}</p>
            <p style="margin: 5px 0;">📞 Téléphone: {{ config('carre_premium.contact.landline_display') }}</p>
        </div>

        <div class="footer">
            <p><strong>Carré Premium</strong></p>
            <p>Votre agence de voyage de confiance</p>
            <p style="font-size: 12px; color: #9CA3AF;">
                Cet email a été envoyé automatiquement, merci de ne pas y répondre.
            </p>
        </div>
    </div>
</body>
</html>
