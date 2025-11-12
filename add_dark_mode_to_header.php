<?php
// Script pour ajouter les classes dark mode au header
$headerFile = 'resources/views/layouts/header.blade.php';
$content = file_get_contents($headerFile);

// Remplacements pour ajouter les classes dark
$replacements = [
    // Dropdowns backgrounds
    'class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-50 origin-top-right"' 
        => 'class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 py-2 z-50 origin-top-right"',
    
    'class="absolute right-0 mt-2 w-40 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-50 origin-top-right"'
        => 'class="absolute right-0 mt-2 w-40 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 py-2 z-50 origin-top-right"',
    
    'class="absolute right-0 mt-2 w-32 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-50 origin-top-right"'
        => 'class="absolute right-0 mt-2 w-32 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 py-2 z-50 origin-top-right"',
    
    // Dropdown items
    'class="flex items-center space-x-3 w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors"'
        => 'class="flex items-center space-x-3 w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"',
    
    'class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors"'
        => 'class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"',
    
    // User menu
    'class="px-4 py-2 border-b border-gray-200"'
        => 'class="px-4 py-2 border-b border-gray-200 dark:border-gray-700"',
    
    'class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"'
        => 'class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700"',
    
    'class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100"'
        => 'class="block w-full text-left px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-gray-100 dark:hover:bg-gray-700"',
    
    // Mobile menu
    'class="lg:hidden bg-white border-t border-gray-200 shadow-xl"'
        => 'class="lg:hidden bg-white dark:bg-gray-900 border-t border-gray-200 dark:border-gray-700 shadow-xl"',
    
    'class="flex items-center space-x-3 px-4 py-3 rounded-xl font-semibold transition-all duration-300 text-gray-700 hover:bg-gray-100"'
        => 'class="flex items-center space-x-3 px-4 py-3 rounded-xl font-semibold transition-all duration-300 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800"',
    
    'class="px-4 py-3 border-t border-gray-200 space-y-3"'
        => 'class="px-4 py-3 border-t border-gray-200 dark:border-gray-700 space-y-3"',
    
    'class="text-xs font-semibold text-gray-500 uppercase"'
        => 'class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase"',
    
    'class="bg-gray-50 rounded-lg p-3"'
        => 'class="bg-gray-50 dark:bg-gray-800 rounded-lg p-3"',
    
    'class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-lg"'
        => 'class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg"',
    
    'class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100 rounded-lg"'
        => 'class="block w-full text-left px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg"',
    
    // Auth buttons
    'class="px-4 py-2 rounded-full font-semibold text-sm transition-all duration-300 bg-gray-100 text-gray-700 hover:bg-gray-200"'
        => 'class="px-4 py-2 rounded-full font-semibold text-sm transition-all duration-300 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700"',
    
    'class="p-2 rounded-full bg-gray-100 text-gray-700 hover:bg-gray-200 transition-all"'
        => 'class="p-2 rounded-full bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700 transition-all"',
    
    'class="p-2.5 rounded-full bg-gray-100 text-gray-700 transition-all duration-300 hover:scale-110 hover:bg-gray-200"'
        => 'class="p-2.5 rounded-full bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 transition-all duration-300 hover:scale-110 hover:bg-gray-200 dark:hover:bg-gray-700"',
];

foreach ($replacements as $search => $replace) {
    $content = str_replace($search, $replace, $content);
}

file_put_contents($headerFile, $content);
echo "Dark mode classes added to header successfully!\n";
?>
