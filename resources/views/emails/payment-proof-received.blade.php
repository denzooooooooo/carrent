<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preuve de paiement reçue</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
        .content { background: #f9f9f9; padding: 30px; border-radius: 0 0 10px 10px; }
        .booking-details { background: white; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #667eea; }
        .footer { text-align: center; margin-top: 30px; color: #666; font-size: 12px; }
        .highlight { background: #e8f4f8; padding: 15px; border-radius: 5px; margin: 15px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Carré Premium</h1>
            <h2>Preuve de paiement reçue</h2>
        </div>

        <div class="content">
            <p>Bonjour,</p>

            <p>Nous avons bien reçu votre preuve de paiement pour la réservation <strong>{{ $booking->booking_number }}</strong>.</p>

            <div class="booking-details">
                <h3>Détails de votre réservation :</h3>
                <p><strong>Référence :</strong> {{ $booking->booking_number }}</p>

                @if($booking->booking_type === 'event')
                    <p><strong>Événement :</strong> {{ $booking->event->title_fr }}</p>
                    <p><strong>{{ $booking->event_selection_type_label }} :</strong> {{ $booking->event_selection_label }}</p>
                    <p><strong>Nombre de places :</strong> {{ $booking->number_of_passengers }}</p>
                    <p><strong>Date :</strong> {{ $booking->travel_date_label }}</p>
                @elseif($booking->booking_type === 'location')
                    <p><strong>Location :</strong> {{ $booking->location->name }}</p>
                    <p><strong>Période :</strong> {{ \Carbon\Carbon::parse($booking->travel_date)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($booking->locationBooking->end_date)->format('d/m/Y') }}</p>
                    <p><strong>Durée :</strong> {{ $booking->locationBooking->days }} jours</p>
                @endif

                <p><strong>Montant :</strong> {{ \App\Helpers\CurrencyHelper::format($booking->final_amount) }}</p>
            </div>

            <div class="highlight">
                <p><strong>✅ Statut :</strong> Votre preuve de paiement est en cours de vérification.</p>
                <p>Notre équipe va traiter votre paiement dans les 24-48 heures ouvrables.</p>
                <p>Vous recevrez un email de confirmation avec votre ticket/récu une fois le paiement validé.</p>
            </div>

            <p>Si vous avez des questions, n'hésitez pas à nous contacter :</p>
            <ul>
                <li>Email : support@carrepremium.ci</li>
                <li>Téléphone : +225 27 21 59 42 58</li>
            </ul>

            <p>Cordialement,<br>
            L'équipe Carré Premium</p>
        </div>

        <div class="footer">
            <p>Cette adresse email est surveillée 24/7. Ne pas répondre directement à cet email.</p>
            <p>&copy; 2024 Carré Premium - Tous droits réservés</p>
        </div>
    </div>
</body>
</html>
