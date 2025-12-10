<?php
// Activer l'affichage des erreurs
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h1>Diagnostic du Serveur</h1>";

// 1. Version PHP
echo "<h2>1. Version PHP</h2>";
echo "Version PHP: " . phpversion() . "<br>";
echo "PHP SAPI: " . php_sapi_name() . "<br><br>";

// 2. Extensions requises
echo "<h2>2. Extensions PHP Requises</h2>";
$required_extensions = ['pdo', 'mbstring', 'openssl', 'tokenizer', 'xml', 'ctype', 'json', 'bcmath', 'fileinfo', 'gd'];
foreach ($required_extensions as $ext) {
    $status = extension_loaded($ext) ? '✅ Installée' : '❌ Manquante';
    echo "$ext: $status<br>";
}
echo "<br>";

// 3. Vérifier les fichiers Laravel
echo "<h2>3. Fichiers Laravel</h2>";
$files_to_check = [
    '../vendor/autoload.php' => 'Autoload Composer',
    '../bootstrap/app.php' => 'Bootstrap Laravel',
    '../.env' => 'Fichier .env',
    '../storage' => 'Dossier storage',
    '../bootstrap/cache' => 'Dossier cache'
];

foreach ($files_to_check as $file => $name) {
    $exists = file_exists(__DIR__ . '/' . $file);
    $status = $exists ? '✅ Existe' : '❌ Manquant';
    echo "$name: $status<br>";
    
    if ($exists && is_dir(__DIR__ . '/' . $file)) {
        $writable = is_writable(__DIR__ . '/' . $file);
        $write_status = $writable ? '✅ Accessible en écriture' : '❌ Pas accessible en écriture';
        echo "&nbsp;&nbsp;&nbsp;&nbsp;$write_status<br>";
    }
}
echo "<br>";

// 4. Permissions
echo "<h2>4. Permissions</h2>";
$dirs_to_check = ['../storage', '../bootstrap/cache'];
foreach ($dirs_to_check as $dir) {
    $full_path = __DIR__ . '/' . $dir;
    if (file_exists($full_path)) {
        $perms = substr(sprintf('%o', fileperms($full_path)), -4);
        echo "$dir: $perms<br>";
    }
}
echo "<br>";

// 5. Tester le chargement de Laravel
echo "<h2>5. Test de Chargement Laravel</h2>";
try {
    require __DIR__ . '/../vendor/autoload.php';
    echo "✅ Autoload chargé avec succès<br>";
    
    $app = require_once __DIR__ . '/../bootstrap/app.php';
    echo "✅ Application Laravel chargée avec succès<br>";
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "<br>";
    echo "Fichier: " . $e->getFile() . " (ligne " . $e->getLine() . ")<br>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}

echo "<br><h2>6. Variables d'Environnement</h2>";
echo "Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "<br>";
echo "Script Filename: " . $_SERVER['SCRIPT_FILENAME'] . "<br>";
echo "Current Dir: " . __DIR__ . "<br>";
?>
