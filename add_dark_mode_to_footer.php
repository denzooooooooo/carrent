<?php
// Script pour ajouter les classes dark mode au footer
$footerFile = 'resources/views/layouts/footer.blade.php';
$content = file_get_contents($footerFile);

// Remplacements pour ajouter les classes dark
$replacements = [
    'class="bg-gradient-to-br from-gray-900 via-purple-900 to-gray-900 text-white'
        => 'class="bg-gradient-to-br from-gray-900 via-purple-900 to-gray-900 dark:from-gray-950 dark:via-purple-950 dark:to-gray-950 text-white',
    
    'class="text-gray-400'
        => 'class="text-gray-400 dark:text-gray-500',
    
    'class="text-white'
        => 'class="text-white dark:text-gray-100',
    
    'hover:text-purple-400'
        => 'hover:text-purple-400 dark:hover:text-purple-300',
    
    'class="border-t border-gray-800'
        => 'class="border-t border-gray-800 dark:border-gray-700',
];

foreach ($replacements as $search => $replace) {
    $content = str_replace($search, $replace, $content);
}

file_put_contents($footerFile, $content);
echo "Dark mode classes added to footer successfully!\n";
?>
