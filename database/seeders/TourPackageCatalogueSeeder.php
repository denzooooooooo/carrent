<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TourPackage;
use App\Models\Category;

class TourPackageCatalogueSeeder extends Seeder
{
    public function run(): void
    {
        // ── Catégories ─────────────────────────────────────────────────────
        $catSport = Category::firstOrCreate(
            ['slug' => 'sport'],
            ['name_fr' => 'Sport', 'name_en' => 'Sport', 'is_active' => true,
             'description_fr' => 'Packages hospitalite sportive', 'description_en' => 'Sports hospitality packages']
        );

        // ── 3 packages (un par catalogue PDF) ──────────────────────────────
        $packages = [

            // ── 1. ROLAND GARROS ──────────────────────────────────────────
            [
                'slug'             => 'roland-garros-2026',
                'category_id'      => $catSport->id,
                'title_fr'         => 'Roland Garros 2026 – Hospitalite VIP',
                'title_en'         => 'Roland Garros 2026 – VIP Hospitality',
                'description_fr'   => "Vivez Roland-Garros comme jamais avec nos packages hospitalite VIP. Acces aux salons prives au coeur du court Philippe-Chatrier, restauration gastronomique, boissons a discretion et cadeaux griffes Roland-Garros. Plusieurs formules disponibles selon vos dates et votre budget, a partir de 265 000 FCFA par personne.\n\nFormules disponibles :\n• Le Comptoir – Salon partage, buffet dejeunatoire/dinatoire, places Cat. 1 ou Or\n• La Brasserie des Mousquetaires – Table reservee, repas assis gastronomique, parking inclus\n• Le Cercle – Salon sous le court Suzanne-Lenglen, Cat. 1 ou Or\n• Club Chatrier – Loge privee 4 personnes sur le court Philippe-Chatrier\n• Le Club Gold – Formule Gold premium avec parking inclus",
                'description_en'   => "Experience Roland-Garros like never before with our VIP hospitality packages. Access to private lounges at the heart of Philippe-Chatrier court, gastronomic catering, drinks and Roland-Garros branded gifts. Multiple formulas available from 265,000 FCFA per person.",
                'package_type'     => 'sport_event',
                'destination'      => 'Paris, France',
                'departure_city'   => 'Abidjan',
                'duration'         => 1,
                'duration_text_fr' => '24 mai – 7 juin 2026',
                'duration_text_en' => 'May 24 – June 7, 2026',
                'price'            => 265000,
                'currency'         => 'XOF',
                'event_date_start' => '2026-05-24',
                'event_date_end'   => '2026-06-07',
                'min_participants' => 1,
                'max_participants' => 500,
                'is_active'        => true,
                'is_featured'      => true,
                'included_services_fr' => [
                    'Accueil privatif',
                    'Places en tribune sur le court Philippe-Chatrier ou Suzanne-Lenglen',
                    'Acces libre aux courts annexes selon la session choisie',
                    'Salon partage ou loge privee selon la formule',
                    'Restauration (buffet ou repas assis gastronomique selon formule)',
                    'Boissons a discretion tout au long de la journee',
                    '1 cadeau griffe Roland-Garros par invite et par session',
                    'Acces wi-fi gratuit et illimite',
                    'Vestiaire',
                    'Possibilite d\'ajouter les vols + hotel',
                ],
                'included_services_en' => [
                    'Private welcome',
                    'Seats on Philippe-Chatrier or Suzanne-Lenglen court',
                    'Free access to side courts',
                    'Shared lounge or private box depending on formula',
                    'Catering (buffet or seated gastronomic meal)',
                    'Drinks throughout the day',
                    '1 Roland-Garros branded gift per guest per session',
                    'Free unlimited Wi-Fi',
                    'Cloakroom',
                    'Option to add flights + hotel',
                ],
                'excluded_services_fr' => [
                    'Vols et hebergement (disponibles en option)',
                    'Parking (inclus uniquement dans la formule Brasserie et Club Gold)',
                    'Depenses personnelles',
                ],
                'excluded_services_en' => [
                    'Flights and accommodation (available as option)',
                    'Parking (included only in Brasserie and Club Gold formulas)',
                    'Personal expenses',
                ],
                'available_dates' => [
                    // Le Comptoir
                    ['formule' => 'Le Comptoir', 'date' => '2026-05-30', 'label' => '2eme tour – Cat. 1 – Soiree',   'price' => 605000],
                    ['formule' => 'Le Comptoir', 'date' => '2026-06-04', 'label' => '1/2 finale – Cat. 1 – Journee', 'price' => 790000],
                    ['formule' => 'Le Comptoir', 'date' => '2026-06-04', 'label' => '1/2 finale – Cat. Or – Journee','price' => 910000],
                    // La Brasserie des Mousquetaires
                    ['formule' => 'La Brasserie des Mousquetaires', 'date' => '2026-05-28', 'label' => '2eme tour – Cat. 1 – Soiree',     'price' => 665000],
                    ['formule' => 'La Brasserie des Mousquetaires', 'date' => '2026-06-02', 'label' => '1/4 de finale – Cat. 1 – Soiree', 'price' => 870000],
                    // Le Cercle
                    ['formule' => 'Le Cercle', 'date' => '2026-05-24', 'label' => '1er tour – Cat. 1 – Soiree',    'price' => 265000],
                    ['formule' => 'Le Cercle', 'date' => '2026-05-24', 'label' => '1er tour – Cat. Or – Soiree',   'price' => 305000],
                    ['formule' => 'Le Cercle', 'date' => '2026-05-25', 'label' => '1er tour – Cat. 1 – Soiree',    'price' => 330000],
                    ['formule' => 'Le Cercle', 'date' => '2026-05-25', 'label' => '1er tour – Cat. Or – Soiree',   'price' => 385000],
                    ['formule' => 'Le Cercle', 'date' => '2026-05-28', 'label' => '2eme tour – Cat. 1 – Soiree',   'price' => 665000],
                    ['formule' => 'Le Cercle', 'date' => '2026-05-28', 'label' => '2eme tour – Cat. Or – Soiree',  'price' => 760000],
                    ['formule' => 'Le Cercle', 'date' => '2026-05-29', 'label' => '3eme tour – Cat. 1 – Soiree',   'price' => 580000],
                    ['formule' => 'Le Cercle', 'date' => '2026-05-29', 'label' => '3eme tour – Cat. Or – Soiree',  'price' => 665000],
                    ['formule' => 'Le Cercle', 'date' => '2026-05-30', 'label' => '3eme tour – Cat. 1 – Soiree',   'price' => 760000],
                    ['formule' => 'Le Cercle', 'date' => '2026-05-30', 'label' => '3eme tour – Cat. Or – Soiree',  'price' => 870000],
                    ['formule' => 'Le Cercle', 'date' => '2026-06-04', 'label' => '1/2 finale – Cat. 1 – Journee', 'price' => 760000],
                    ['formule' => 'Le Cercle', 'date' => '2026-06-04', 'label' => '1/2 finale – Cat. Or – Journee','price' => 870000],
                    // Club Chatrier
                    ['formule' => 'Club Chatrier (Loge 4 pers.)', 'date' => '2026-05-24', 'label' => '1er tour',  'price' => 915000],
                    ['formule' => 'Club Chatrier (Loge 4 pers.)', 'date' => '2026-05-25', 'label' => '1er tour',  'price' => 1055000],
                    ['formule' => 'Club Chatrier (Loge 4 pers.)', 'date' => '2026-05-26', 'label' => '1er tour',  'price' => 1385000],
                    ['formule' => 'Club Chatrier (Loge 4 pers.)', 'date' => '2026-05-27', 'label' => '1er tour',  'price' => 1570000],
                    ['formule' => 'Club Chatrier (Loge 4 pers.)', 'date' => '2026-05-28', 'label' => '2eme tour', 'price' => 1950000],
                    ['formule' => 'Club Chatrier (Loge 4 pers.)', 'date' => '2026-05-29', 'label' => '2eme tour', 'price' => 1950000],
                    ['formule' => 'Club Chatrier (Loge 4 pers.)', 'date' => '2026-05-30', 'label' => '3eme tour', 'price' => 1705000],
                    ['formule' => 'Club Chatrier (Loge 4 pers.)', 'date' => '2026-05-31', 'label' => '3eme tour', 'price' => 1685000],
                    ['formule' => 'Club Chatrier (Loge 4 pers.)', 'date' => '2026-06-01', 'label' => '4eme tour', 'price' => 2160000],
                    // Le Club Gold
                    ['formule' => 'Le Club Gold', 'date' => '2026-05-26', 'label' => '1er tour',  'price' => 1785000],
                    ['formule' => 'Le Club Gold', 'date' => '2026-05-28', 'label' => '2eme tour', 'price' => 2490000],
                    ['formule' => 'Le Club Gold', 'date' => '2026-05-29', 'label' => '2eme tour', 'price' => 2490000],
                ],
                'itinerary_fr' => [],
                'itinerary_en' => [],
                'gallery'      => [],
            ],

            // ── 2. FORMULE 1 2026 ─────────────────────────────────────────
            [
                'slug'             => 'formule-1-2026',
                'category_id'      => $catSport->id,
                'title_fr'         => 'Formule 1 2026 – Packages Hospitalite',
                'title_en'         => 'Formula 1 2026 – Hospitality Packages',
                'description_fr'   => "Vivez la Formule 1 2026 dans les meilleures conditions avec nos packages hospitalite officiels. Disponibles sur 6 Grands Prix : Shanghai, Bahrain, Djeddah, Miami, Canada et Monaco. Plusieurs formules par GP, de la tribune couverte au Paddock Club exclusif.\n\nGrands Prix disponibles :\n• Shanghai (13-15 mars 2026) : Champion Club a partir de 3 280 000 FCFA, Gordon Ramsay Paddock a partir de 11 500 000 FCFA\n• Bahrain (10-12 avril 2026) : Le Dome a partir de 890 000 FCFA, Turn 1 Lounge a partir de 530 000 FCFA, Tribune Principale a partir de 430 000 FCFA, Tribune Virage 1 a partir de 315 000 FCFA\n• Djeddah (17-19 avril 2026) : Premium Lounge a partir de 2 120 000 FCFA, Paddock Club a partir de 5 330 000 FCFA\n• Miami (1-3 mai 2026) : Start/Finish a partir de 1 590 000 FCFA, North Beach a partir de 650 000 FCFA\n• Canada (22-24 mai 2026) : VIP Elite Suite a partir de 3 860 000 FCFA, Elite Club a partir de 3 280 000 FCFA, La Jamaique a partir de 1 265 000 FCFA, Privilege 12 a partir de 1 245 000 FCFA, La Toundra a partir de 1 180 000 FCFA, La Terrasse 21 a partir de 1 020 000 FCFA\n• Monaco (5-7 juin 2026) : Platinum Terraces a partir de 4 965 000 FCFA, Gold VIP Terrace a partir de 4 100 000 FCFA, VIP Race Garden a partir de 3 760 000 FCFA, Silver Terraces a partir de 3 235 000 FCFA, Bronze Terrace a partir de 2 845 000 FCFA, Trackside Experience a partir de 2 330 000 FCFA",
                'description_en'   => "Experience Formula 1 2026 in the best conditions with our official hospitality packages. Available on 6 Grand Prix: Shanghai, Bahrain, Jeddah, Miami, Canada and Monaco. Multiple formulas per GP, from covered grandstands to the exclusive Paddock Club.",
                'package_type'     => 'motorsport',
                'destination'      => 'Shanghai / Bahrain / Djeddah / Miami / Canada / Monaco',
                'departure_city'   => 'Abidjan',
                'duration'         => 3,
                'duration_text_fr' => 'Mars – Juin 2026',
                'duration_text_en' => 'March – June 2026',
                'price'            => 315000,
                'currency'         => 'XOF',
                'event_date_start' => '2026-03-13',
                'event_date_end'   => '2026-06-07',
                'min_participants' => 1,
                'max_participants' => 1000,
                'is_active'        => true,
                'is_featured'      => true,
                'included_services_fr' => [
                    'Acces au circuit pendant 3 jours (selon formule)',
                    'Places assises garanties (selon formule)',
                    'Restauration et boissons (selon formule)',
                    'Acces hospitalite VIP (selon formule)',
                    'Acces paddock (formules Paddock Club uniquement)',
                    'Tickets officiels Formula 1',
                    'Possibilite d\'ajouter les vols + hotel',
                ],
                'included_services_en' => [
                    '3-day circuit access (depending on formula)',
                    'Guaranteed seating (depending on formula)',
                    'Catering and drinks (depending on formula)',
                    'VIP hospitality access (depending on formula)',
                    'Paddock access (Paddock Club formulas only)',
                    'Official Formula 1 tickets',
                    'Option to add flights + hotel',
                ],
                'excluded_services_fr' => [
                    'Vols et hebergement (disponibles en option)',
                    'Depenses personnelles',
                    'Transport vers le circuit',
                ],
                'excluded_services_en' => [
                    'Flights and accommodation (available as option)',
                    'Personal expenses',
                    'Transport to the circuit',
                ],
                'available_dates' => [
                    // Shanghai
                    ['gp' => 'Shanghai', 'formule' => 'Champion Club',              'date' => '2026-03-13', 'label' => 'Shanghai 13-15 mars – Champion Club',              'price' => 3280000],
                    ['gp' => 'Shanghai', 'formule' => 'Gordon Ramsay dans le Paddock','date' => '2026-03-13','label' => 'Shanghai 13-15 mars – Gordon Ramsay Paddock',       'price' => 11500000],
                    // Bahrain
                    ['gp' => 'Bahrain',  'formule' => 'Le Dome',                    'date' => '2026-04-10', 'label' => 'Bahrain 10-12 avr – Le Dome',                       'price' => 890000],
                    ['gp' => 'Bahrain',  'formule' => 'Turn 1 & Corporate Lounge',  'date' => '2026-04-10', 'label' => 'Bahrain 10-12 avr – Turn 1 & Corporate Lounge',     'price' => 530000],
                    ['gp' => 'Bahrain',  'formule' => 'Tribune Principale',         'date' => '2026-04-10', 'label' => 'Bahrain 10-12 avr – Tribune Principale',            'price' => 430000],
                    ['gp' => 'Bahrain',  'formule' => 'Tribune Virage 1',           'date' => '2026-04-10', 'label' => 'Bahrain 10-12 avr – Tribune Virage 1',              'price' => 315000],
                    // Djeddah
                    ['gp' => 'Djeddah',  'formule' => 'Premium Lounge',             'date' => '2026-04-17', 'label' => 'Djeddah 17-19 avr – Premium Lounge',               'price' => 2120000],
                    ['gp' => 'Djeddah',  'formule' => 'Paddock Club',               'date' => '2026-04-17', 'label' => 'Djeddah 17-19 avr – Paddock Club',                 'price' => 5330000],
                    // Miami
                    ['gp' => 'Miami',    'formule' => 'Start/Finish Grandstand',    'date' => '2026-05-01', 'label' => 'Miami 1-3 mai – Start/Finish Grandstand',           'price' => 1590000],
                    ['gp' => 'Miami',    'formule' => 'North Beach Grandstand',     'date' => '2026-05-01', 'label' => 'Miami 1-3 mai – North Beach Grandstand',            'price' => 650000],
                    // Canada
                    ['gp' => 'Canada',   'formule' => "VIP Fan's Elite Suite",      'date' => '2026-05-22', 'label' => "Canada 22-24 mai – VIP Fan's Elite Suite",          'price' => 3860000],
                    ['gp' => 'Canada',   'formule' => 'Elite Club',                 'date' => '2026-05-22', 'label' => 'Canada 22-24 mai – Elite Club',                    'price' => 3280000],
                    ['gp' => 'Canada',   'formule' => 'La Jamaique',                'date' => '2026-05-22', 'label' => 'Canada 22-24 mai – La Jamaique',                   'price' => 1265000],
                    ['gp' => 'Canada',   'formule' => 'Privilege 12',               'date' => '2026-05-22', 'label' => 'Canada 22-24 mai – Privilege 12',                  'price' => 1245000],
                    ['gp' => 'Canada',   'formule' => 'La Toundra',                 'date' => '2026-05-22', 'label' => 'Canada 22-24 mai – La Toundra',                    'price' => 1180000],
                    ['gp' => 'Canada',   'formule' => 'La Terrasse 21',             'date' => '2026-05-22', 'label' => 'Canada 22-24 mai – La Terrasse 21',                'price' => 1020000],
                    // Monaco
                    ['gp' => 'Monaco',   'formule' => 'Platinum Terraces',          'date' => '2026-06-05', 'label' => 'Monaco 5-7 juin – Platinum Terraces',              'price' => 4965000],
                    ['gp' => 'Monaco',   'formule' => 'Gold VIP Terrace',           'date' => '2026-06-05', 'label' => 'Monaco 5-7 juin – Gold VIP Terrace',               'price' => 4100000],
                    ['gp' => 'Monaco',   'formule' => 'VIP Race Garden',            'date' => '2026-06-05', 'label' => 'Monaco 5-7 juin – VIP Race Garden',                'price' => 3760000],
                    ['gp' => 'Monaco',   'formule' => 'Silver Terraces',            'date' => '2026-06-05', 'label' => 'Monaco 5-7 juin – Silver Terraces',                'price' => 3235000],
                    ['gp' => 'Monaco',   'formule' => 'Bronze Terrace',             'date' => '2026-06-05', 'label' => 'Monaco 5-7 juin – Bronze Terrace',                 'price' => 2845000],
                    ['gp' => 'Monaco',   'formule' => 'Trackside Experience',       'date' => '2026-06-05', 'label' => 'Monaco 5-7 juin – Trackside Experience',           'price' => 2330000],
                ],
                'itinerary_fr' => [],
                'itinerary_en' => [],
                'gallery'      => [],
            ],

            // ── 3. UEFA EUROPA LEAGUE – FINALE ISTANBUL ───────────────────
            [
                'slug'             => 'finale-uefa-europa-league-istanbul-2026',
                'category_id'      => $catSport->id,
                'title_fr'         => 'Finale UEFA Europa League – Istanbul 2026',
                'title_en'         => 'UEFA Europa League Final – Istanbul 2026',
                'description_fr'   => "Vivez la Finale de l'UEFA Europa League le 20 mai 2026 a Istanbul dans une loge privee Shared Skybox. Trois formules disponibles : Platinum, Gold et Silver. Chaque formule inclut un acces hospitalite premium 3 heures avant le coup d'envoi, cocktail dinatoire avec specialites locales et internationales, boissons incluses et diffusion TV.\n\nFormules disponibles :\n• Platinum – Emplacement central, meilleure visibilite, loge privee VIP exclusive : a partir de 975 000 FCFA/pers.\n• Gold – Vue panoramique, loge privee premium, acces hospitalite VIP : a partir de 825 000 FCFA/pers.\n• Silver – Vue immersive proche de l'action, loge privee, experience VIP accessible : a partir de 675 000 FCFA/pers.",
                'description_en'   => "Experience the UEFA Europa League Final on May 20, 2026 in Istanbul in a private Shared Skybox. Three formulas available: Platinum, Gold and Silver. Each formula includes premium hospitality access 3 hours before kick-off, dinner cocktail with local and international specialties, drinks included and TV broadcast.",
                'package_type'     => 'football',
                'destination'      => 'Istanbul, Turquie',
                'departure_city'   => 'Abidjan',
                'duration'         => 1,
                'duration_text_fr' => '20 mai 2026',
                'duration_text_en' => 'May 20, 2026',
                'price'            => 675000,
                'currency'         => 'XOF',
                'event_date_start' => '2026-05-20',
                'event_date_end'   => '2026-05-20',
                'min_participants' => 1,
                'max_participants' => 500,
                'is_active'        => true,
                'is_featured'      => true,
                'included_services_fr' => [
                    'Billet pour le match (Shared Skybox)',
                    'Loge privee avec confort premium',
                    'Acces hospitalite premium 3 heures avant le coup d\'envoi',
                    'Cocktail dinatoire debout avec specialites locales et internationales',
                    'Boisson de bienvenue a l\'arrivee',
                    'Vins, bieres et boissons sans alcool inclus',
                    'Service de boissons continu',
                    'Diffusion TV avant, pendant et apres le match',
                    'Wi-Fi disponible',
                    'Acces hospitalite 90 minutes apres le match',
                    'Possibilite d\'ajouter les vols + hotel',
                ],
                'included_services_en' => [
                    'Match ticket (Shared Skybox)',
                    'Private box with premium comfort',
                    'Premium hospitality access 3 hours before kick-off',
                    'Standing dinner cocktail with local and international specialties',
                    'Welcome drink on arrival',
                    'Wines, beers and soft drinks included',
                    'Continuous drinks service',
                    'TV broadcast before, during and after the match',
                    'Wi-Fi available',
                    'Hospitality access 90 minutes after the match',
                    'Option to add flights + hotel',
                ],
                'excluded_services_fr' => [
                    'Vols et hebergement (disponibles en option)',
                    'Parking (non inclus et non disponible)',
                    'Depenses personnelles',
                ],
                'excluded_services_en' => [
                    'Flights and accommodation (available as option)',
                    'Parking (not included and not available)',
                    'Personal expenses',
                ],
                'available_dates' => [
                    ['formule' => 'Platinum', 'date' => '2026-05-20', 'label' => 'Finale – Shared Skybox Platinum – Emplacement central, meilleure visibilite', 'price' => 975000],
                    ['formule' => 'Gold',     'date' => '2026-05-20', 'label' => 'Finale – Shared Skybox Gold – Vue panoramique, loge privee premium',          'price' => 825000],
                    ['formule' => 'Silver',   'date' => '2026-05-20', 'label' => 'Finale – Shared Skybox Silver – Vue immersive, experience VIP accessible',    'price' => 675000],
                ],
                'itinerary_fr' => [],
                'itinerary_en' => [],
                'gallery'      => [],
            ],
        ];

        foreach ($packages as $data) {
            TourPackage::updateOrCreate(['slug' => $data['slug']], $data);
        }

        $this->command->info('OK 3 packages touristiques inseres : Roland Garros, F1 2026, UEFA Europa League.');
    }
}
