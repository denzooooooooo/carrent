<?php

namespace App\Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use Illuminate\Support\Facades\Log;

class PHPMailerService
{
    protected $mail;

    public function __construct()
    {
        $this->mail = new PHPMailer(true);
        $this->configure();
    }

    /**
     * Configure PHPMailer with settings from .env
     */
    protected function configure()
    {
        try {
            // Server settings
            $this->mail->isSMTP();
            $this->mail->Host = env('PHPMAILER_HOST', 'smtp.gmail.com');
            $this->mail->SMTPAuth = true;
            $this->mail->Username = env('PHPMAILER_USERNAME');
            $this->mail->Password = env('PHPMAILER_PASSWORD');
            $this->mail->SMTPSecure = env('PHPMAILER_ENCRYPTION', PHPMailer::ENCRYPTION_STARTTLS);
            $this->mail->Port = env('PHPMAILER_PORT', 587);
            $this->mail->CharSet = 'UTF-8';

            // Sender info
            // IMPORTANT: Pour Ionos (et la plupart des SMTP hébergés), l'adresse From
            // DOIT correspondre au compte authentifié (PHPMAILER_USERNAME).
            // Utiliser PHPMAILER_FROM_ADDRESS s'il est identique au username, sinon fallback sur username.
            $username    = env('PHPMAILER_USERNAME');
            $fromAddress = env('PHPMAILER_FROM_ADDRESS', $username);

            // Si l'adresse From ne correspond pas au username, Ionos rejette l'email.
            // On force donc l'utilisation du username comme adresse d'envoi.
            if ($fromAddress !== $username) {
                $fromAddress = $username;
            }

            $this->mail->setFrom(
                $fromAddress,
                env('PHPMAILER_FROM_NAME', 'Carré Premium')
            );

            // Debug mode (only in local)
            if (env('APP_DEBUG', false)) {
                $this->mail->SMTPDebug = SMTP::DEBUG_OFF; // Change to DEBUG_SERVER for verbose
                $this->mail->Debugoutput = function($str, $level) {
                    Log::debug("PHPMailer: $str");
                };
            }

        } catch (Exception $e) {
            Log::error('PHPMailer configuration error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Send verification code email
     *
     * @param string $to
     * @param string $name
     * @param string $code
     * @return bool
     */
    public function sendVerificationCode($to, $name, $code)
    {
        try {
            $this->mail->clearAddresses();
            $this->mail->clearAttachments();
            
            $this->mail->addAddress($to, $name);
            $this->mail->Subject = 'Code de vérification - Carré Premium';
            
            // HTML body
            $this->mail->isHTML(true);
            $this->mail->Body = $this->getVerificationEmailTemplate($name, $code);
            
            // Plain text alternative
            $this->mail->AltBody = "Bonjour $name,\n\nVotre code de vérification est: $code\n\nCe code expire dans 10 minutes.\n\nCordialement,\nL'équipe Carré Premium";

            $result = $this->mail->send();
            
            Log::info('Verification email sent successfully', [
                'to' => $to,
                'code' => $code
            ]);
            
            return $result;

        } catch (Exception $e) {
            Log::error('PHPMailer send error: ' . $e->getMessage(), [
                'to' => $to,
                'error' => $this->mail->ErrorInfo
            ]);
            return false;
        }
    }

    /**
     * Send booking confirmation email
     *
     * @param string $to
     * @param string $name
     * @param array $bookingData
     * @return bool
     */
    public function sendBookingConfirmation($to, $name, $bookingData)
    {
        try {
            $this->mail->clearAddresses();
            $this->mail->clearAttachments();
            
            $this->mail->addAddress($to, $name);
            $this->mail->Subject = 'Confirmation de réservation - Carré Premium';
            
            $this->mail->isHTML(true);
            $this->mail->Body = $this->getBookingEmailTemplate($name, $bookingData);
            $this->mail->AltBody = "Bonjour $name,\n\nVotre réservation a été confirmée.\n\nNuméro de réservation: {$bookingData['booking_number']}\n\nCordialement,\nL'équipe Carré Premium";

            return $this->mail->send();

        } catch (Exception $e) {
            Log::error('PHPMailer booking email error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send generic email
     *
     * @param string $to
     * @param string $subject
     * @param string $htmlBody
     * @param string $textBody
     * @param string $toName
     * @return bool
     */
    public function send($to, $subject, $htmlBody, $textBody = '', $toName = '')
    {
        try {
            $this->mail->clearAddresses();
            $this->mail->clearAttachments();
            
            $this->mail->addAddress($to, $toName);
            $this->mail->Subject = $subject;
            
            $this->mail->isHTML(true);
            $this->mail->Body = $htmlBody;
            $this->mail->AltBody = $textBody ?: strip_tags($htmlBody);

            return $this->mail->send();

        } catch (Exception $e) {
            Log::error('PHPMailer send error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get verification email HTML template
     *
     * @param string $name
     * @param string $code
     * @return string
     */
    protected function getVerificationEmailTemplate($name, $code)
    {
        $supportEmail = config('carre_premium.contact.support_email');
        $supportPhone = config('carre_premium.contact.landline_display');
        $companyName = config('carre_premium.company.name');
        $companyCity = config('carre_premium.company.city');
        $companyCountry = config('carre_premium.company.country');

        return "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
                .content { background: #f9f9f9; padding: 30px; border-radius: 0 0 10px 10px; }
                .code-box { background: white; border: 2px dashed #667eea; padding: 20px; text-align: center; margin: 20px 0; border-radius: 8px; }
                .code { font-size: 32px; font-weight: bold; color: #667eea; letter-spacing: 5px; }
                .footer { text-align: center; margin-top: 20px; color: #666; font-size: 12px; }
                .button { display: inline-block; padding: 12px 30px; background: #667eea; color: white; text-decoration: none; border-radius: 5px; margin: 10px 0; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>🔐 Code de Vérification</h1>
                    <p>$companyName</p>
                </div>
                <div class='content'>
                    <p>Bonjour <strong>$name</strong>,</p>
                    <p>Merci de vous être inscrit sur $companyName ! Pour finaliser votre inscription, veuillez utiliser le code de vérification ci-dessous :</p>
                    
                    <div class='code-box'>
                        <div class='code'>$code</div>
                        <p style='margin: 10px 0 0 0; color: #666;'>Code de vérification</p>
                    </div>
                    
                    <p><strong>⏰ Ce code expire dans 10 minutes.</strong></p>
                    
                    <p>Si vous n'avez pas créé de compte sur $companyName, vous pouvez ignorer cet email en toute sécurité.</p>
                    
                    <hr style='border: none; border-top: 1px solid #ddd; margin: 20px 0;'>
                    
                    <p style='color: #666; font-size: 14px;'>
                        <strong>Besoin d'aide ?</strong><br>
                        Contactez notre support : <a href='mailto:$supportEmail'>$supportEmail</a><br>
                        Téléphone : $supportPhone
                    </p>
                </div>
                <div class='footer'>
                    <p>&copy; " . date('Y') . " $companyName. Tous droits réservés.</p>
                    <p>$companyCity, $companyCountry</p>
                </div>
            </div>
        </body>
        </html>
        ";
    }

    /**
     * Get booking confirmation email template
     *
     * @param string $name
     * @param array $bookingData
     * @return string
     */
    protected function getBookingEmailTemplate($name, $bookingData)
    {
        $bookingNumber = $bookingData['booking_number'] ?? 'N/A';
        $amount = $bookingData['amount'] ?? '0';
        $currency = $bookingData['currency'] ?? 'XOF';

        return "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
                .content { background: #f9f9f9; padding: 30px; }
                .booking-details { background: white; padding: 20px; border-radius: 8px; margin: 20px 0; }
                .footer { text-align: center; margin-top: 20px; color: #666; font-size: 12px; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>✅ Réservation Confirmée</h1>
                </div>
                <div class='content'>
                    <p>Bonjour <strong>$name</strong>,</p>
                    <p>Votre réservation a été confirmée avec succès !</p>
                    
                    <div class='booking-details'>
                        <h3>Détails de la réservation</h3>
                        <p><strong>Numéro de réservation :</strong> $bookingNumber</p>
                        <p><strong>Montant :</strong> $amount $currency</p>
                    </div>
                    
                    <p>Merci d'avoir choisi Carré Premium !</p>
                </div>
                <div class='footer'>
                    <p>&copy; " . date('Y') . " Carré Premium</p>
                </div>
            </div>
        </body>
        </html>
        ";
    }

    /**
     * Test email configuration
     *
     * @param string $to
     * @return bool
     */
    public function testConnection($to = null)
    {
        try {
            if ($to) {
                return $this->send(
                    $to,
                    'Test PHPMailer - Carré Premium',
                    '<h1>Test réussi !</h1><p>PHPMailer fonctionne correctement.</p>',
                    'Test réussi ! PHPMailer fonctionne correctement.'
                );
            }
            
            // Just test SMTP connection
            return $this->mail->smtpConnect();

        } catch (Exception $e) {
            Log::error('PHPMailer test failed: ' . $e->getMessage());
            return false;
        }
    }
}
