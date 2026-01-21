<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class TranslationService
{
    protected $provider;
    protected $apiKey;

    public function __construct()
    {
        $this->provider = config('services.translation.provider', 'libretranslate');
        $this->apiKey = config('services.translation.' . $this->provider . '.api_key');
    }

    /**
     * Traduire un texte
     *
     * @param string $text Texte à traduire
     * @param string $targetLang Langue cible (fr, en, etc.)
     * @param string $sourceLang Langue source (auto-détection si null)
     * @return string|null
     */
    public function translate(string $text, string $targetLang, ?string $sourceLang = null): ?string
    {
        // Cache key pour éviter les appels répétés
        $cacheKey = 'translation_' . md5($text . $targetLang . $sourceLang);
        
        // Vérifier le cache (24h)
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        try {
            $translation = match($this->provider) {
                'google' => $this->translateWithGoogle($text, $targetLang, $sourceLang),
                'libretranslate' => $this->translateWithLibreTranslate($text, $targetLang, $sourceLang),
                default => $this->translateWithLibreTranslate($text, $targetLang, $sourceLang),
            };

            // Mettre en cache
            if ($translation) {
                Cache::put($cacheKey, $translation, 86400); // 24h
            }

            return $translation;

        } catch (\Exception $e) {
            Log::error('Translation error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Traduire avec Google Translate API
     */
    protected function translateWithGoogle(string $text, string $targetLang, ?string $sourceLang): ?string
    {
        if (empty($this->apiKey)) {
            return null;
        }

        $response = Http::post('https://translation.googleapis.com/language/translate/v2', [
            'q' => $text,
            'target' => $targetLang,
            'source' => $sourceLang ?? 'auto',
            'key' => $this->apiKey,
        ]);

        if ($response->successful()) {
            $data = $response->json();
            return $data['data']['translations'][0]['translatedText'] ?? null;
        }

        return null;
    }

    /**
     * Traduire avec LibreTranslate (Gratuit)
     */
    protected function translateWithLibreTranslate(string $text, string $targetLang, ?string $sourceLang): ?string
    {
        $apiUrl = config('services.translation.libretranslate.api_url', 'https://libretranslate.de');
        
        $payload = [
            'q' => $text,
            'target' => $targetLang,
            'source' => $sourceLang ?? 'auto',
            'format' => 'text',
        ];

        // Ajouter la clé API si disponible
        if ($this->apiKey) {
            $payload['api_key'] = $this->apiKey;
        }

        $response = Http::post($apiUrl . '/translate', $payload);

        if ($response->successful()) {
            $data = $response->json();
            return $data['translatedText'] ?? null;
        }

        return null;
    }

    /**
     * Traduire un tableau de clés
     *
     * @param array $keys Tableau de clés à traduire
     * @param string $targetLang Langue cible
     * @return array
     */
    public function translateKeys(array $keys, string $targetLang): array
    {
        $translations = [];

        foreach ($keys as $key => $value) {
            if (is_string($value)) {
                $translated = $this->translate($value, $targetLang, 'fr');
                $translations[$key] = $translated ?? $value;
            } else {
                $translations[$key] = $value;
            }
        }

        return $translations;
    }

    /**
     * Vérifier si le service est configuré
     */
    public function isConfigured(): bool
    {
        return $this->provider === 'libretranslate' || !empty($this->apiKey);
    }
}
