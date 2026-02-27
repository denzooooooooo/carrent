<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\VerificationCode;
use App\Services\SMSService;
use App\Services\PHPMailerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\RateLimiter;

class VerificationController extends Controller
{
    protected $smsService;
    protected $phpMailer;

    public function __construct(SMSService $smsService, PHPMailerService $phpMailer)
    {
        $this->smsService = $smsService;
        $this->phpMailer  = $phpMailer;
    }

    /**
     * Afficher la page de vérification
     */
    public function show()
    {
        $user = Auth::user();

        // Si déjà vérifié, rediriger vers home
        if ($user->is_verified) {
            return redirect()->route('home');
        }

        return view('pages.verify-account', [
            'user' => $user,
            'email' => $user->email,
            'phone' => $user->phone,
        ]);
    }

    /**
     * Envoyer le code de vérification par email (via PHPMailer — fonctionne en local et en prod)
     */
    public function sendEmailVerification(Request $request)
    {
        $user = Auth::user();

        // Rate limiting: max 3 envois par heure
        $key = 'send-verification-email:' . $user->id;
        if (RateLimiter::tooManyAttempts($key, 3)) {
            $seconds = RateLimiter::availableIn($key);
            return back()->withErrors([
                'rate_limit' => "Trop de tentatives. Réessayez dans " . ceil($seconds / 60) . " minutes."
            ]);
        }

        try {
            // Générer le code
            $verificationCode = VerificationCode::generate($user, 'email', $user->email);

            // Envoyer via PHPMailer (SMTP Ionos configuré dans .env)
            $fullName = trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? ''));
            $sent = $this->phpMailer->sendVerificationCode(
                $user->email,
                $fullName ?: 'Cher utilisateur',
                $verificationCode->code
            );

            if (!$sent) {
                Log::error('VerificationController: PHPMailer échec envoi email', [
                    'user_id' => $user->id,
                    'email'   => $user->email,
                ]);
                return back()->withErrors([
                    'email' => 'Impossible d\'envoyer l\'email de vérification. Veuillez contacter le support.'
                ]);
            }

            RateLimiter::hit($key, 3600); // 1 heure

            Log::info('VerificationController: Code envoyé par email', [
                'user_id' => $user->id,
                'email'   => $user->email,
            ]);

            Session::flash('success', 'Code de vérification envoyé par email à ' . $user->email);
            Session::put('last_verification_sent', now());
            Session::put('verification_method', 'email');

            return back();

        } catch (\Exception $e) {
            Log::error('VerificationController: Exception envoi email', [
                'user_id' => $user->id ?? null,
                'message' => $e->getMessage(),
            ]);
            return back()->withErrors([
                'email' => 'Erreur lors de l\'envoi de l\'email. Veuillez réessayer ou contacter le support.'
            ]);
        }
    }

    /**
     * Envoyer le code de vérification par SMS
     */
    public function sendSMSVerification(Request $request)
    {
        $user = Auth::user();

        // Vérifier que le service SMS est configuré
        if (!$this->smsService->isConfigured()) {
            return back()->withErrors([
                'sms' => 'Le service SMS n\'est pas configuré. Veuillez utiliser la vérification par email.'
            ]);
        }

        // Rate limiting: max 3 envois par heure
        $key = 'send-verification-sms:' . $user->id;
        if (RateLimiter::tooManyAttempts($key, 3)) {
            $seconds = RateLimiter::availableIn($key);
            return back()->withErrors([
                'rate_limit' => "Trop de tentatives. Réessayez dans " . ceil($seconds / 60) . " minutes."
            ]);
        }

        try {
            // Générer le code
            $verificationCode = VerificationCode::generate($user, 'sms', $user->phone);

            // Envoyer le SMS
            $result = $this->smsService->sendVerificationCode($user->phone, $verificationCode->code);

            if (!$result['success']) {
                return back()->withErrors([
                    'sms' => 'Erreur lors de l\'envoi du SMS: ' . ($result['message'] ?? 'Erreur inconnue')
                ]);
            }

            RateLimiter::hit($key, 3600); // 1 heure

            Session::flash('success', 'Code de vérification envoyé par SMS au ' . $user->phone);
            Session::put('last_verification_sent', now());
            Session::put('verification_method', 'sms');

            return back();

        } catch (\Exception $e) {
            return back()->withErrors([
                'sms' => 'Erreur lors de l\'envoi du SMS: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Vérifier le code saisi
     */
    public function verify(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6',
        ]);

        $user = Auth::user();
        $code = $request->input('code');

        // Rate limiting: max 5 tentatives par 15 minutes
        $key = 'verify-code:' . $user->id;
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            return back()->withErrors([
                'code' => "Trop de tentatives. Réessayez dans " . ceil($seconds / 60) . " minutes."
            ]);
        }

        // Chercher un code valide
        $verificationCode = VerificationCode::where('user_id', $user->id)
            ->where('code', $code)
            ->valid()
            ->first();

        if (!$verificationCode) {
            RateLimiter::hit($key, 900); // 15 minutes
            
            return back()->withErrors([
                'code' => 'Code invalide ou expiré. Veuillez demander un nouveau code.'
            ])->withInput();
        }

        // Incrémenter les tentatives
        $verificationCode->incrementAttempts();

        // Vérifier si le code est toujours valide après incrémentation
        if (!$verificationCode->isValid()) {
            return back()->withErrors([
                'code' => 'Ce code a expiré ou a atteint le nombre maximum de tentatives.'
            ])->withInput();
        }

        // Marquer le code comme utilisé
        $verificationCode->markAsUsed();

        // Marquer l'utilisateur comme vérifié
        $user->update([
            'is_verified' => true,
            'email_verified_at' => $verificationCode->type === 'email' ? now() : $user->email_verified_at,
            'phone_verified_at' => $verificationCode->type === 'sms' ? now() : $user->phone_verified_at,
        ]);

        // Nettoyer le rate limiter
        RateLimiter::clear($key);

        Session::flash('success', 'Votre compte a été vérifié avec succès ! Bienvenue sur Carré Premium.');

        return redirect()->route('home');
    }

    /**
     * Renvoyer le code (même méthode que la dernière fois)
     */
    public function resend(Request $request)
    {
        $lastMethod = Session::get('verification_method', 'email');
        $lastSent = Session::get('last_verification_sent');

        // Vérifier le délai de 60 secondes
        if ($lastSent && now()->diffInSeconds($lastSent) < 60) {
            $remaining = 60 - now()->diffInSeconds($lastSent);
            return back()->withErrors([
                'resend' => "Veuillez attendre {$remaining} secondes avant de renvoyer le code."
            ]);
        }

        if ($lastMethod === 'sms') {
            return $this->sendSMSVerification($request);
        }

        return $this->sendEmailVerification($request);
    }

    /**
     * Changer la méthode de vérification
     */
    public function changeMethod(Request $request)
    {
        $request->validate([
            'method' => 'required|in:email,sms',
        ]);

        $method = $request->input('method');

        if ($method === 'sms') {
            return $this->sendSMSVerification($request);
        }

        return $this->sendEmailVerification($request);
    }
}
