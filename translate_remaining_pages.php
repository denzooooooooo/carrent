<?php

/**
 * Script pour traduire automatiquement toutes les pages restantes
 * Usage: php translate_remaining_pages.php
 */

// Fonction pour traduire un fichier
function translateFile($filePath, $translations) {
    if (!file_exists($filePath)) {
        echo "❌ Fichier non trouvé: $filePath\n";
        return false;
    }
    
    $content = file_get_contents($filePath);
    $originalContent = $content;
    
    // Appliquer toutes les traductions
    foreach ($translations as $french => $english) {
        // Échapper les caractères spéciaux pour regex
        $frenchEscaped = preg_quote($french, '/');
        
        // Remplacer uniquement si ce n'est pas déjà traduit
        if (strpos($content, "{{ __('" . addslashes($english) . "') }}") === false &&
            strpos($content, '{{ __("' . addslashes($english) . '") }}') === false) {
            // Remplacer dans les balises HTML
            $content = preg_replace(
                '/>' . $frenchEscaped . '</',
                '>{{ __(\''. addslashes($english) . '\') }}<',
                $content
            );
            
            // Remplacer dans les attributs
            $content = preg_replace(
                '/(placeholder|title|alt)="' . $frenchEscaped . '"/',
                '$1="{{ __(\''. addslashes($english) . '\') }}"',
                $content
            );
        }
    }
    
    // Sauvegarder seulement si modifié
    if ($content !== $originalContent) {
        file_put_contents($filePath, $content);
        echo "✅ Traduit: $filePath\n";
        return true;
    }
    
    echo "⏭️  Déjà traduit: $filePath\n";
    return false;
}

// Traductions communes
$commonTranslations = [
    // Titres de pages
    'À Propos' => 'About',
    'Contact' => 'Contact',
    'FAQ' => 'FAQ',
    'Conditions Générales' => 'Terms and Conditions',
    'Politique de Confidentialité' => 'Privacy Policy',
    'Cookies' => 'Cookies',
    
    // Textes communs
    'Votre partenaire de confiance pour tous vos voyages' => 'Your trusted partner for all your travels',
    'Notre Histoire' => 'Our Story',
    'Nos Valeurs' => 'Our Values',
    'Nos Chiffres' => 'Our Numbers',
    'Nos Partenaires' => 'Our Partners',
    'Prêt à Voyager avec Nous ?' => 'Ready to Travel with Us?',
    'Rejoignez des milliers de voyageurs satisfaits' => 'Join thousands of satisfied travelers',
    'Réserver un Vol' => 'Book a Flight',
    'Nous Contacter' => 'Contact Us',
    
    // Valeurs
    'Excellence' => 'Excellence',
    'Confiance' => 'Trust',
    'Innovation' => 'Innovation',
    'Accessibilité' => 'Accessibility',
    'Rapidité' => 'Speed',
    'Sécurité' => 'Security',
    
    // Stats
    'Clients Satisfaits' => 'Satisfied Customers',
    'Destinations' => 'Destinations',
    'Événements' => 'Events',
    'Support Client' => 'Customer Support',
];

// Liste des fichiers à traduire
$filesToTranslate = [
    'resources/views/pages/about.blade.php',
    'resources/views/pages/contact.blade.php',
    'resources/views/pages/faq.blade.php',
    'resources/views/pages/terms.blade.php',
    'resources/views/pages/privacy.blade.php',
    'resources/views/pages/cookies.blade.php',
];

echo "🚀 Début de la traduction automatique...\n\n";

$translated = 0;
foreach ($filesToTranslate as $file) {
    if (translateFile($file, $commonTranslations)) {
        $translated++;
    }
}

echo "\n✨ Traduction terminée! $translated fichiers modifiés.\n";
echo "📝 N'oubliez pas de lancer: python3 sync_translations.py\n";
