<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageUploadService
{
    /**
     * Upload une image et retourne le chemin
     *
     * @param UploadedFile $file
     * @param string $folder
     * @param bool $returnFullUrl Si true, retourne l'URL complète
     * @return string
     */
    public function uploadImage(UploadedFile $file, string $folder = 'images', bool $returnFullUrl = false): string
    {
        // Générer un nom unique
        $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
        
        // Chemin complet
        $path = $folder . '/' . $filename;
        
        // Sauvegarder l'image
        $file->storeAs('public/' . $folder, $filename);
        
        // Retourner l'URL complète si demandé
        if ($returnFullUrl) {
            return $this->getImageUrl($path);
        }
        
        return $path;
    }

    /**
     * Upload plusieurs images
     *
     * @param array $files
     * @param string $folder
     * @return array
     */
    public function uploadMultipleImages(array $files, string $folder = 'images'): array
    {
        $paths = [];
        
        foreach ($files as $file) {
            if ($file instanceof UploadedFile) {
                $paths[] = $this->uploadImage($file, $folder);
            }
        }
        
        return $paths;
    }

    /**
     * Supprimer une image
     *
     * @param string $path
     * @return bool
     */
    public function deleteImage(string $path): bool
    {
        if (Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->delete($path);
        }
        
        return false;
    }

    /**
     * Supprimer plusieurs images
     *
     * @param array $paths
     * @return bool
     */
    public function deleteMultipleImages(array $paths): bool
    {
        foreach ($paths as $path) {
            $this->deleteImage($path);
        }
        
        return true;
    }

    /**
     * Obtenir l'URL complète d'une image
     *
     * @param string|null $path
     * @param bool $useApiRoute Si true, utilise la route API pour servir l'image
     * @return string|null
     */
    public function getImageUrl(?string $path, bool $useApiRoute = true): ?string
    {
        if (!$path) {
            return null;
        }
        
        // Si c'est déjà une URL complète, la retourner
        if (Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }
        
        // Utiliser la route API pour servir l'image (meilleur pour CORS)
        if ($useApiRoute) {
            $baseUrl = config('app.url');
            return $baseUrl . '/api/v1/images/' . urlencode($path);
        }
        
        // Utiliser le chemin storage standard
        return asset('storage/' . $path);
    }

    /**
     * Valider une image
     *
     * @param UploadedFile $file
     * @param int $maxSize (en Ko)
     * @return array
     */
    public function validateImage(UploadedFile $file, int $maxSize = 5120): array
    {
        $errors = [];
        
        // Vérifier le type MIME
        $allowedMimes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'];
        if (!in_array($file->getMimeType(), $allowedMimes)) {
            $errors[] = 'Le fichier doit être une image (JPEG, PNG, GIF, WebP)';
        }
        
        // Vérifier la taille
        if ($file->getSize() > $maxSize * 1024) {
            $errors[] = "La taille du fichier ne doit pas dépasser " . ($maxSize / 1024) . " Mo";
        }
        
        return [
            'valid' => empty($errors),
            'errors' => $errors
        ];
    }
}
