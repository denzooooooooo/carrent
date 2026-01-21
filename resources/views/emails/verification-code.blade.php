<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Code de vérification</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            background: linear-gradient(135deg, #9333ea 0%, #f59e0b 100%);
            padding: 40px 20px;
            text-align: center;
            color: white;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: bold;
        }
        .content {
            padding: 40px 30px;
        }
        .greeting {
            font-size: 18px;
            margin-bottom: 20px;
            color: #333;
        }
        .message {
            font-size: 16px;
            color: #666;
            margin-bottom: 30px;
            line-height: 1.8;
        }
        .code-container {
            background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
            border-radius: 10px;
            padding: 30px;
            text-align: center;
            margin: 30px 0;
            border: 2px dashed #9333ea;
        }
        .code-label {
            font-size: 14px;
            color: #666;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .code {
            font-size: 42px;
            font-weight: bold;
            color: #9333ea;
            letter-spacing: 8px;
            font-family: 'Courier New', monospace;
            margin: 10px 0;
        }
        .expiry {
            font-size: 13px;
            color: #999;
            margin-top: 15px;
        }
        .warning {
            background: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 15px 20px;
            margin: 25px 0;
            border-radius: 5px;
        }
        .warning-title {
            font-weight: bold;
            color: #92400e;
            margin-bottom: 5px;
        }
        .warning-text {
            font-size: 14px;
            color: #78350f;
            margin: 0;
        }
        .footer {
            background: #f9fafb;
            padding: 30px;
            text-align: center;
            border-top: 1px solid #e5e7eb;
        }
        .footer-text {
            font-size: 13px;
            color: #6b7280;
            margin: 5px 0;
        }
        .footer-link {
            color: #9333ea;
            text-decoration: none;
        }
        .footer-link:hover {
            text-decoration: underline;
        }
        .social-links {
            margin-top: 20px;
        }
        .social-links a {
            display: inline-block;
            margin: 0 10px;
            color: #9333ea;
            text-decoration: none;
            font-size: 14px;
        }
        @media only screen and (max-width: 600px) {
            .container {
                margin: 10px;
                border-radius: 5px;
            }
            .content {
                padding: 25px 20px;
            }
            .code {
                font-size: 32px;
                letter-spacing: 5px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>🔐 Carré Premium</h1>
            <p style="margin: 10px 0 0 0; font-size: 16px; opacity: 0.9;">Vérification de compte</p>
        </div>

        <!-- Content -->
        <div class="content">
            <div class="greeting">
                Bonjour {{ $userName }},
            </div>

            <div class="message">
                Merci de vous être inscrit sur <strong>Carré Premium</strong> ! Pour finaliser la création de votre compte et accéder à nos services exclusifs, veuillez utiliser le code de vérification ci-dessous :
            </div>

            <!-- Code Box -->
            <div class="code-container">
                <div class="code-label">Votre code de vérification</div>
                <div class="code">{{ $code }}</div>
                <div class="expiry">
                    ⏱️ Ce code expire le {{ $expiresAt->format('d/m/Y à H:i') }}
                    <br>
                    <small>(dans 15 minutes)</small>
                </div>
            </div>

            <div class="message">
                Entrez ce code sur la page de vérification pour activer votre compte et profiter de :
                <ul style="color: #666; margin-top: 10px;">
                    <li>Réservations de vols et packages touristiques</li>
                    <li>Services de conciergerie premium</li>
                    <li>Offres exclusives et promotions</li>
                    <li>Support client prioritaire 24/7</li>
                </ul>
            </div>

            <!-- Warning Box -->
            <div class="warning">
                <div class="warning-title">⚠️ Important - Sécurité</div>
                <p class="warning-text">
                    Ne partagez jamais ce code avec qui que ce soit. L'équipe Carré Premium ne vous demandera jamais votre code de vérification par téléphone ou email.
                </p>
            </div>

            <div class="message" style="margin-top: 30px; font-size: 14px; color: #999;">
                Si vous n'avez pas créé de compte sur Carré Premium, vous pouvez ignorer cet email en toute sécurité.
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p class="footer-text">
                <strong>Carré Premium</strong> - Votre partenaire voyage de luxe
            </p>
            <p class="footer-text">
                Besoin d'aide ? Contactez-nous à 
                <a href="mailto:support@carrepremium.ci" class="footer-link">support@carrepremium.ci</a>
                <br>
                ou appelez le <strong>+225 27 21 59 42 58</strong>
            </p>
            
            <div class="social-links">
                <a href="#">Facebook</a> • 
                <a href="#">Instagram</a> • 
                <a href="#">Twitter</a>
            </div>

            <p class="footer-text" style="margin-top: 20px; font-size: 11px;">
                © {{ date('Y') }} Carré Premium. Tous droits réservés.
                <br>
                Abidjan, Côte d'Ivoire
            </p>
        </div>
    </div>
</body>
</html>
