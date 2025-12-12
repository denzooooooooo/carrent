<?php
/**
 * Installation de Composer
 * 
 * Ce fichier télécharge et installe Composer sur votre serveur.
 * Exécutez-le via votre navigateur: https://monnkama.shop/install-composer.php
 * 
 * IMPORTANT: Supprimez ce fichier après utilisation pour des raisons de sécurité!
 */

// Activer l'affichage des erreurs
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<!DOCTYPE html>
<html>
<head>
    <title>Installation de Composer</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 50px auto; padding: 20px; }
        h1 { color: #333; }
        .success { color: green; font-weight: bold; }
        .error { color: red; font-weight: bold; }
        .info { color: blue; }
        pre { background: #f4f4f4; padding: 15px; border-radius: 5px; overflow-x: auto; }
        .step { margin: 20px 0; padding: 15px; border-left: 4px solid #007bff; background: #f8f9fa; }
    </style>
</head>
<body>
    <h1>🚀 Installation de Composer</h1>";

echo "<div class='step'>";
echo "<h2>Étape 1: Téléchargement de l'installateur Composer</h2>";

// Télécharger l'installateur Composer
$installerUrl = 'https://getcomposer.org/installer';
$installerPath = __DIR__ . '/composer-setup.php';

echo "<p class='info'>Téléchargement depuis: $installerUrl</p>";

$installer = @file_get_contents($installerUrl);

if ($installer === false) {
    echo "<p class='error'>❌ Erreur: Impossible de télécharger l'installateur Composer.</p>";
    echo "<p>Vérifiez que votre serveur peut accéder à Internet.</p>";
    echo "</div></body></html>";
    exit;
}

file_put_contents($installerPath, $installer);
echo "<p class='success'>✅ Installateur téléchargé avec succès!</p>";
echo "</div>";

echo "<div class='step'>";
echo "<h2>Étape 2: Exécution de l'installateur</h2>";

// Exécuter l'installateur
ob_start();
include $installerPath;
$output = ob_get_clean();

echo "<pre>$output</pre>";

// Supprimer l'installateur
unlink($installerPath);

// Vérifier si composer.phar a été créé
if (file_exists(__DIR__ . '/composer.phar')) {
    echo "<p class='success'>✅ Composer installé avec succès!</p>";
    echo "<p class='info'>Fichier créé: composer.phar</p>";
    
    // Tester Composer
    echo "<h3>Test de Composer:</h3>";
    $version = shell_exec('php ' . __DIR__ . '/composer.phar --version 2>&1');
    echo "<pre>$version</pre>";
    
    echo "</div>";
    
    echo "<div class='step'>";
    echo "<h2>✅ Installation Terminée!</h2>";
    echo "<p><strong>Prochaine étape:</strong></p>";
    echo "<ol>";
    echo "<li>Accédez à <a href='install-dependencies.php' style='color: #007bff; font-weight: bold;'>install-dependencies.php</a> pour installer les dépendances Laravel</li>";
    echo "<li>Ou exécutez: <code>https://monnkama.shop/install-dependencies.php</code></li>";
    echo "</ol>";
    echo "<p class='error'><strong>⚠️ IMPORTANT:</strong> Supprimez ce fichier (install-composer.php) après utilisation!</p>";
    echo "</div>";
    
} else {
    echo "<p class='error'>❌ Erreur: composer.phar n'a pas été créé.</p>";
    echo "<p>Vérifiez les permissions de votre serveur.</p>";
    echo "</div>";
}

echo "</body></html>";
?>
