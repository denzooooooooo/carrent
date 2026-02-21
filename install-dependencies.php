<?php
/**
 * Installation des Dépendances Laravel
 * 
 * Ce fichier installe toutes les dépendances Composer nécessaires pour Laravel.
 * Exécutez-le via votre navigateur: https://monnkama.shop/install-dependencies.php
 * 
 * IMPORTANT: Supprimez ce fichier après utilisation pour des raisons de sécurité!
 */

// Augmenter les limites
set_time_limit(600); // 10 minutes
ini_set('memory_limit', '512M');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<!DOCTYPE html>   
<html>
<head>
    <title>Installation des Dépendances Laravel</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 1000px; margin: 50px auto; padding: 20px; }
        h1 { color: #333; }
        .success { color: green; font-weight: bold; }
        .error { color: red; font-weight: bold; }
        .info { color: blue; }
        .warning { color: orange; font-weight: bold; }
        pre { background: #f4f4f4; padding: 15px; border-radius: 5px; overflow-x: auto; max-height: 400px; overflow-y: auto; }
        .step { margin: 20px 0; padding: 15px; border-left: 4px solid #007bff; background: #f8f9fa; }
        .progress { background: #e9ecef; height: 30px; border-radius: 5px; overflow: hidden; margin: 20px 0; }
        .progress-bar { background: #007bff; height: 100%; line-height: 30px; color: white; text-align: center; transition: width 0.3s; }
    </style>
</head>
<body>
    <h1>🚀 Installation des Dépendances Laravel</h1>";

// Vérifier si composer.phar existe
if (!file_exists(__DIR__ . '/composer.phar')) {
    echo "<div class='step'>";
    echo "<p class='error'>❌ Erreur: composer.phar n'existe pas!</p>";
    echo "<p>Vous devez d'abord exécuter <a href='install-composer.php'>install-composer.php</a></p>";
    echo "</div></body></html>";
    exit;
}

echo "<div class='step'>";
echo "<h2>Étape 1: Vérification de l'environnement</h2>";
echo "<p class='info'>PHP Version: " . phpversion() . "</p>";
echo "<p class='info'>Répertoire: " . __DIR__ . "</p>";
echo "<p class='info'>Mémoire disponible: " . ini_get('memory_limit') . "</p>";
echo "<p class='success'>✅ Environnement vérifié</p>";
echo "</div>";

echo "<div class='step'>";
echo "<h2>Étape 2: Installation des dépendances (cela peut prendre 5-10 minutes)</h2>";
echo "<div class='progress'><div class='progress-bar' style='width: 10%;'>10% - Démarrage...</div></div>";
echo "<p class='warning'>⏳ Veuillez patienter, ne fermez pas cette page...</p>";

// Forcer le flush pour afficher immédiatement
flush();
ob_flush();

// Exécuter composer install
$command = 'cd ' . escapeshellarg(__DIR__) . ' && php composer.phar install --no-dev --optimize-autoloader --no-interaction 2>&1';

echo "<h3>Commande exécutée:</h3>";
echo "<pre>$command</pre>";

echo "<h3>Sortie de l'installation:</h3>";
echo "<pre>";

// Exécuter la commande et afficher la sortie en temps réel
$descriptorspec = array(
   0 => array("pipe", "r"),
   1 => array("pipe", "w"),
   2 => array("pipe", "w")
);

$process = proc_open($command, $descriptorspec, $pipes);

if (is_resource($process)) {
    fclose($pipes[0]);
    
    $output = '';
    while ($line = fgets($pipes[1])) {
        echo htmlspecialchars($line);
        $output .= $line;
        flush();
        ob_flush();
    }
    
    $errors = stream_get_contents($pipes[2]);
    
    fclose($pipes[1]);
    fclose($pipes[2]);
    
    $return_value = proc_close($process);
    
    echo "</pre>";
    
    if ($return_value === 0) {
        echo "<p class='success'>✅ Installation terminée avec succès!</p>";
    } else {
        echo "<p class='error'>❌ L'installation s'est terminée avec des erreurs (code: $return_value)</p>";
        if (!empty($errors)) {
            echo "<h3>Erreurs:</h3>";
            echo "<pre>" . htmlspecialchars($errors) . "</pre>";
        }
    }
} else {
    echo "</pre>";
    echo "<p class='error'>❌ Impossible d'exécuter la commande</p>";
}

echo "</div>";

// Vérifier si le dossier vendor existe maintenant
echo "<div class='step'>";
echo "<h2>Étape 3: Vérification de l'installation</h2>";

if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    echo "<p class='success'>✅ Dossier vendor/ créé avec succès!</p>";
    echo "<p class='success'>✅ Fichier autoload.php présent!</p>";
    
    // Tester le chargement de Laravel
    try {
        require __DIR__ . '/vendor/autoload.php';
        echo "<p class='success'>✅ Autoload fonctionne correctement!</p>";
        
        $app = require_once __DIR__ . '/bootstrap/app.php';
        echo "<p class='success'>✅ Application Laravel chargée avec succès!</p>";
        
    } catch (Exception $e) {
        echo "<p class='error'>❌ Erreur lors du chargement: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
    
} else {
    echo "<p class='error'>❌ Le dossier vendor/ n'a pas été créé!</p>";
    echo "<p>Vérifiez les erreurs ci-dessus.</p>";
}

echo "</div>";

// Instructions finales
echo "<div class='step'>";
echo "<h2>✅ Prochaines Étapes</h2>";

if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    echo "<ol>";
    echo "<li><strong>Testez votre site:</strong> <a href='/' target='_blank'>https://monnkama.shop</a></li>";
    echo "<li><strong>Supprimez les fichiers de diagnostic:</strong>";
    echo "<ul>";
    echo "<li>install-composer.php</li>";
    echo "<li>install-dependencies.php (ce fichier)</li>";
    echo "<li>public/debug.php</li>";
    echo "<li>composer.phar (optionnel, vous pouvez le garder pour les futures mises à jour)</li>";
    echo "</ul>";
    echo "</li>";
    echo "<li><strong>Optimisez Laravel (optionnel):</strong>";
    echo "<pre>php artisan config:cache\nphp artisan route:cache\nphp artisan view:cache</pre>";
    echo "</li>";
    echo "</ol>";
    
    echo "<p class='success'><strong>🎉 Félicitations! Votre site Laravel devrait maintenant fonctionner!</strong></p>";
} else {
    echo "<p class='error'>L'installation a échoué. Contactez votre hébergeur pour obtenir de l'aide.</p>";
}

echo "<p class='error'><strong>⚠️ IMPORTANT:</strong> Supprimez ce fichier immédiatement après utilisation pour des raisons de sécurité!</p>";
echo "</div>";

echo "</body></html>";
?>
