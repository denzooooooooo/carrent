<?php

// Nouvelles traductions à ajouter
$newTranslations = [
    // Page Home
    "Welcome to Carré Premium" => [
        "fr" => "Bienvenue chez Carré Premium",
        "en" => "Welcome to Carré Premium"
    ],
    "Discover our tailor-made tourist packages, private flights, and 24/7 concierge service to organize your most exclusive experiences. Our advisors are at your disposal to personalize every detail." => [
        "fr" => "Découvrez nos packages touristiques sur-mesure, nos vols privés, et notre conciergerie 24/7 pour organiser vos expériences les plus exclusives. Nos conseillers sont à votre disposition pour personnaliser chaque détail.",
        "en" => "Discover our tailor-made tourist packages, private flights, and 24/7 concierge service to organize your most exclusive experiences. Our advisors are at your disposal to personalize every detail."
    ],
    "Carré Premium, our limit is the reflection of our imagination." => [
        "fr" => "Carré Premium, notre limite est le reflet de notre imagination.",
        "en" => "Carré Premium, our limit is the reflection of our imagination."
    ],
    "Private Jets • Luxury Cars • Sports Cars • Premium 4x4" => [
        "fr" => "Jets Privés • Voitures de Luxe • Voitures de Sport • 4x4 Premium",
        "en" => "Private Jets • Luxury Cars • Sports Cars • Premium 4x4"
    ],
    "Premium Vehicles" => [
        "fr" => "Véhicules Premium",
        "en" => "Premium Vehicles" 
    ],
    "Premium Flights" => [
        "fr" => "Vols Premium",
        "en" => "Premium Flights"
    ],
    "Private jet rental for your travels" => [
        "fr" => "Location de jet privé pour vos voyages",
        "en" => "Private jet rental for your travels"
    ],
    
    // Page Location
    "Vehicle Rental - Quads, Cars and More" => [
        "fr" => "Location de Véhicules - Quads, Voitures et Plus",
        "en" => "Vehicle Rental - Quads, Cars and More"
    ],
    "Discover our vehicle rentals in Ivory Coast and Africa. Quads, cars, boats and premium vehicles with Carré Premium." => [
        "fr" => "Découvrez nos locations de véhicules en Côte d'Ivoire et Afrique. Quads, voitures, bateaux et véhicules premium avec Carré Premium.",
        "en" => "Discover our vehicle rentals in Ivory Coast and Africa. Quads, cars, boats and premium vehicles with Carré Premium."
    ],
    "vehicle rental, quads, luxury cars, boats, Ivory Coast, Africa, premium vehicles, Carré Premium" => [
        "fr" => "location véhicules, quads, voitures luxe, bateaux, Côte d'Ivoire, Afrique, véhicules premium, Carré Premium",
        "en" => "vehicle rental, quads, luxury cars, boats, Ivory Coast, Africa, premium vehicles, Carré Premium"
    ],
    "Book your vehicles in Ivory Coast. Quads, cars, boats and exclusive vehicles with our private concierge service." => [
        "fr" => "Réservez vos véhicules en Côte d'Ivoire. Quads, voitures, bateaux et véhicules exclusifs avec notre service de conciergerie privée.",
        "en" => "Book your vehicles in Ivory Coast. Quads, cars, boats and exclusive vehicles with our private concierge service."
    ],
    "Vehicle Rental" => [
        "fr" => "Location de Véhicules",
        "en" => "Vehicle Rental"
    ],
    "Discover our premium vehicle offers" => [
        "fr" => "Découvrez nos offres de véhicules premium",
        "en" => "Discover our premium vehicle offers"
    ],
    "Category" => [
        "fr" => "Catégorie",
        "en" => "Category"
    ],
    "All categories" => [
        "fr" => "Toutes les catégories",
        "en" => "All categories"
    ],
    "All types" => [
        "fr" => "Tous les types",
        "en" => "All types"
    ],
    "per day" => [
        "fr" => "par jour",
        "en" => "per day"
    ],
    "View details" => [
        "fr" => "Voir les détails",
        "en" => "View details"
    ],
    "Need a custom vehicle?" => [
        "fr" => "Besoin d'un véhicule personnalisé ?",
        "en" => "Need a custom vehicle?"
    ],
    "Contact our team for more details" => [
        "fr" => "Contactez notre équipe pour plus de détails",
        "en" => "Contact our team for more details"
    ],
    
    // Page Verification
    "Account Verification" => [
        "fr" => "Vérification du Compte",
        "en" => "Account Verification"
    ],
    "Verify your account" => [
        "fr" => "Vérifiez votre compte",
        "en" => "Verify your account"
    ],
    "A verification code has been sent to your email" => [
        "fr" => "Un code de vérification a été envoyé à votre email",
        "en" => "A verification code has been sent to your email"
    ],
    "Verification code" => [
        "fr" => "Code de vérification",
        "en" => "Verification code"
    ],
    "Enter the 6-digit code received by email" => [
        "fr" => "Entrez le code à 6 chiffres reçu par email",
        "en" => "Enter the 6-digit code received by email"
    ],
    "Verify my account" => [
        "fr" => "Vérifier mon compte",
        "en" => "Verify my account"
    ],
    "Did not receive the code?" => [
        "fr" => "Vous n'avez pas reçu le code ?",
        "en" => "Did not receive the code?"
    ],
    "Resend code" => [
        "fr" => "Renvoyer le code",
        "en" => "Resend code"
    ],
    "Prefer to receive the code by SMS?" => [
        "fr" => "Vous préférez recevoir le code par SMS ?",
        "en" => "Prefer to receive the code by SMS?"
    ],
    "Receive by SMS" => [
        "fr" => "Recevoir par SMS",
        "en" => "Receive by SMS"
    ],
    "Need help?" => [
        "fr" => "Besoin d'aide ?",
        "en" => "Need help?"
    ],
    "If you encounter difficulties, contact our support:" => [
        "fr" => "Si vous rencontrez des difficultés, contactez notre support :",
        "en" => "If you encounter difficulties, contact our support:"
    ],
];

// Charger les fichiers JSON
$frPath = __DIR__ . '/resources/lang/fr.json';
$enPath = __DIR__ . '/resources/lang/en.json';

$frTranslations = json_decode(file_get_contents($frPath), true);
$enTranslations = json_decode(file_get_contents($enPath), true);

$added = 0;

// Ajouter les nouvelles traductions
foreach ($newTranslations as $key => $translations) {
    if (!isset($frTranslations[$key])) {
        $frTranslations[$key] = $translations['fr'];
        $added++;
        echo "✅ Ajouté FR: $key\n";
    }
    
    if (!isset($enTranslations[$key])) {
        $enTranslations[$key] = $translations['en'];
        echo "✅ Ajouté EN: $key\n";
    }
}

// Sauvegarder les fichiers
file_put_contents(
    $frPath,
    json_encode($frTranslations, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
);

file_put_contents(
    $enPath,
    json_encode($enTranslations, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
);

echo "\n✅ Terminé ! $added nouvelles traductions ajoutées.\n";
echo "📝 Fichiers mis à jour:\n";
echo "   - resources/lang/fr.json\n";
echo "   - resources/lang/en.json\n";
