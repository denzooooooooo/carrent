<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation de réservation de package</title>
    <style>
        body { font-family: Arial, sans-serif; color: #1f2937; background: #f8fafc; padding: 24px; }
        .container { max-width: 620px; margin: 0 auto; background: #ffffff; border-radius: 12px; overflow: hidden; }
        .header { background: #0f172a; color: #ffffff; padding: 28px; text-align: center; }
        .content { padding: 28px; }
        .card { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 18px; margin: 18px 0; }
        .label { color: #64748b; font-size: 13px; }
        .value { color: #0f172a; font-size: 20px; font-weight: 700; }
        .footer { padding: 24px 28px; background: #f8fafc; color: #64748b; font-size: 13px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Réservation de package enregistrée</h1>
            <p>Votre demande a bien été prise en compte</p>
        </div>
        <div class="content">
            <p>Bonjour <strong>{{ $passengerName }}</strong>,</p>
            <p>Votre réservation de package a bien été créée. Vous trouverez ci-dessous les informations principales.</p>

            <div class="card">
                <div class="label">Référence</div>
                <div class="value">{{ $bookingNumber }}</div>
            </div>

            <div class="card">
                <p><strong>Package:</strong> {{ $packageName }}</p>
                <p><strong>Date:</strong> {{ $travelDate ? \Carbon\Carbon::parse($travelDate)->format('d/m/Y') : 'N/A' }}</p>
                <p><strong>Participants:</strong> {{ $numberOfPassengers }}</p>
                <p><strong>Montant:</strong> {{ $totalPrice }} {{ $currency }}</p>
            </div>

            <p>Un email complémentaire vous sera envoyé dès confirmation du paiement.</p>
        </div>
        <div class="footer">
            Carré Premium<br>
            support@carrepremium.ci
        </div>
    </div>
</body>
</html>
