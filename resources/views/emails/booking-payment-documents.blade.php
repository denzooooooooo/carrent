<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Documents de paiement</title>
    <style>
        body {
            margin: 0;
            padding: 24px;
            font-family: Arial, sans-serif;
            background: #f5f5fb;
            color: #1f2937;
        }
        .wrapper {
            max-width: 640px;
            margin: 0 auto;
            background: #ffffff;
            border: 1px solid #ece7f8;
            border-radius: 18px;
            overflow: hidden;
        }
        .header {
            padding: 28px 32px;
            background: #5b21b6;
            color: #ffffff;
        }
        .header h1 {
            margin: 0 0 8px;
            font-size: 28px;
        }
        .content {
            padding: 32px;
        }
        .card {
            background: #faf7ff;
            border: 1px solid #ece7f8;
            border-radius: 16px;
            padding: 20px;
            margin: 20px 0;
        }
        .row {
            display: flex;
            justify-content: space-between;
            gap: 16px;
            padding: 8px 0;
            border-bottom: 1px solid #ece7f8;
        }
        .row:last-child {
            border-bottom: 0;
        }
        .label {
            color: #6b7280;
            font-size: 14px;
        }
        .value {
            font-weight: 700;
            text-align: right;
        }
        .note {
            color: #4b5563;
            font-size: 14px;
            line-height: 1.6;
        }
        .footer {
            padding: 24px 32px 32px;
            color: #6b7280;
            font-size: 13px;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <h1>Paiement confirme</h1>
            <p style="margin: 0;">Votre facture et votre recu sont joints a cet email.</p>
        </div>

        <div class="content">
            <p>Bonjour {{ $booking->customer_name }},</p>
            <p class="note">
                Nous vous confirmons la bonne reception de votre paiement pour la reservation
                <strong>{{ $booking->booking_number }}</strong>. Vous trouverez en pieces jointes:
                la facture PDF et le recu de paiement.
            </p>

            <div class="card">
                <div class="row">
                    <span class="label">Reservation</span>
                    <span class="value">{{ $booking->booking_number }}</span>
                </div>
                <div class="row">
                    <span class="label">Prestation</span>
                    <span class="value">{{ $booking->title }}</span>
                </div>
                <div class="row">
                    <span class="label">Type</span>
                    <span class="value">{{ ucfirst($booking->booking_type) }}</span>
                </div>
                <div class="row">
                    <span class="label">Montant paye</span>
                    <span class="value">{{ number_format((float) $booking->final_amount, 0, ',', ' ') }} {{ $booking->currency }}</span>
                </div>
                <div class="row">
                    <span class="label">Facture</span>
                    <span class="value">{{ $booking->invoice_number ?? 'En cours de generation' }}</span>
                </div>
                <div class="row">
                    <span class="label">Recu</span>
                    <span class="value">{{ $booking->receipt_number ?? 'En cours de generation' }}</span>
                </div>
            </div>

            <p class="note">
                Conservez ces documents pour votre suivi comptable ou toute demande aupres du service client.
                Si vous ne voyez pas les pieces jointes, reconnectez-vous a votre espace client pour les telecharger.
            </p>
        </div>

        <div class="footer">
            Carré Premium<br>
            support@carrepremium.ci<br>
            Cet email est envoye automatiquement, merci de ne pas y repondre directement.
        </div>
    </div>
</body>
</html>
