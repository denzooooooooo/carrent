<?php

$lines = static fn(array $items): string => implode("\n", $items);

$option = static function (
    string $label,
    int $price,
    string $date,
    ?string $context = null,
    int $availableQuantity = 24,
    int $maxPerOrder = 6
): array {
    return [
        'label_fr' => $label,
        'context_fr' => $context,
        'date' => $date,
        'price' => $price,
        'available_quantity' => $availableQuantity,
        'max_per_order' => $maxPerOrder,
    ];
};

$f1Conditions = $lines([
    'Tarifs TTC, susceptibles de varier selon disponibilites.',
    'Option package vols + hebergement disponible sur demande.',
]);

$rolandGarrosConditions = <<<'TEXT'
Accès & billetterie
Les billets et hospitalités sont fournis par des partenaires officiels.
L’accès au stade et aux espaces VIP est soumis aux règles de l’organisateur.

Réservations & caractère définitif
Toute réservation confirmée est ferme et définitive.
Les billets et prestations d’hospitalitées ne sont ni remboursables, ni échangeables, ni modifiables après confirmation.

Programmation & déroulement des matchs
Les horaires, joueurs, affiches et ordre des matchs peuvent être modifiés selon le déroulement du tournoi.
La programmation ne peut être garantie.

Attribution des places
Les sièges sont attribués par l’organisateur selon disponibilité.
Les places côte à côte ne peuvent être garanties.

Report ou annulation
En cas de modification, report ou annulation, les conditions applicables sont celles de l’organisateur officiel.
Carré Premium agit en qualité d’intermédiaire et ne peut être tenue responsable des décisions prises.

Livraison des billets
Les billets sont généralement délivrés sous format électronique quelques jours avant l’événement.

Services complémentaires
Vols, hébergements et services additionnels peuvent être proposés en option.
Les conditions des prestataires concernés s’appliquent.

Responsabilité & assurance
Le détenteur du billet doit respecter les règles du stade.
Carré Premium ne peut être tenue responsable des retards, conditions météorologiques, décisions administratives ou cas de force majeure.
Une assurance voyage est recommandée.

Paiement & confirmation
La réservation est confirmée à réception du paiement.
Le règlement implique l’acceptation des présentes conditions.

Les hospitalités Roland-Garros sont proposées en nombre très limité et particulièrement recherchées.
TEXT;

$worldCupConditions = <<<'TEXT'
Accès & billetterie
Les billets et hospitalités sont fournis par des partenaires officiels.
L’accès aux stades et aux espaces VIP est soumis au règlement de l’événement et aux conditions d’utilisation des billets officielles.

Réservations & caractère définitif
Toute réservation confirmée est ferme et définitive.
La réservation devient définitive uniquement après confirmation écrite et réception du paiement.

Programmation & déroulement des matchs
Les horaires, équipes, affiches et ordre des matchs peuvent être modifiés selon le déroulement du tournoi.
La programmation ne peut être garantie et n’ouvre droit à aucune compensation.

Attribution des places
Les sièges et espaces hospitalité sont attribués par l’organisateur selon disponibilité.
Les emplacements précis et places côte à côte ne peuvent être garantis.

Report ou annulation
En cas de modification, report ou annulation, les conditions applicables sont celles de l’organisateur officiel.
Carré Premium agit en qualité d’intermédiaire et ne peut être tenue responsable des décisions prises par la FIFA ou son fournisseur officiel.

Livraison des billets
Les billets et accès hospitalité sont généralement délivrés sous format électronique avant l’événement.
Le client est responsable de fournir les informations nécessaires à leur émission.

Services complémentaires
Vols, hébergements et services additionnels peuvent être proposés en option.
Les conditions des prestataires concernés s’appliquent.

Responsabilité & assurance
Le détenteur du billet doit respecter les règles du stade.
Carré Premium ne peut être tenue responsable des retards, conditions météorologiques, décisions administratives ou cas de force majeure.
Une assurance voyage est recommandée.

Paiement & confirmation
La réservation est confirmée à réception du paiement.
Le règlement implique l’acceptation des présentes conditions.

Les hospitalités officielles de la Coupe du Monde 2026 sont en nombre très limité et particulièrement recherchées.
TEXT;

$uefaEuropaConditions = <<<'TEXT'
Accès & billetterie
Les billets et hospitalités sont fournis par des partenaires officiels agréés.
L’accès au stade et aux espaces VIP est soumis aux règles de sécurité et aux conditions de l’organisateur. Une pièce d’identité peut être exigée.

Réservations & caractère définitif
Toute réservation confirmée est ferme et définitive.
Les billets et prestations d’hospitalité ne sont ni remboursables, ni échangeables, ni modifiables après confirmation.

Attribution des places
Les sièges et espaces hospitalité sont attribués par l’organisateur selon disponibilité.
Les places côte à côte ne peuvent être garanties.

Modifications de programme
Les horaires, accès hospitalité, prestations et services peuvent être modifiés pour des raisons opérationnelles ou de sécurité.
La présence d’invités ou personnalités n’est jamais garantie.

Report ou annulation de l’événement
En cas de report, modification ou annulation, les conditions applicables sont celles de l’organisateur officiel.
Carré Premium agit en qualité d’intermédiaire et ne peut être tenue responsable des décisions prises par les organisateurs.

Livraison des billets
Les billets peuvent être électroniques ou physiques et sont généralement délivrés quelques jours avant l’événement.

Services complémentaires
Les vols, hébergements et services additionnels peuvent être proposés en option.
Les conditions des prestataires concernés s’appliquent.

Responsabilité & assurance
Le détenteur du billet doit respecter les règles du stade.
Une assurance voyage est fortement recommandée.
Carré Premium ne peut être tenue responsable des retards, grèves, décisions administratives ou cas de force majeure.

Paiement & confirmation
La réservation est confirmée à réception du paiement.
Le règlement implique l’acceptation des présentes conditions.

Les événements UEFA sont proposés en nombre limité et particulièrement recherchés.
Les conditions d’accès sont strictement encadrées par l’organisateur.
TEXT;

$uefaChampionsConditions = <<<'TEXT'
Accès & billetterie
Les billets et hospitalités sont fournis par des partenaires officiels agréés.
L’accès au stade et aux espaces VIP est soumis aux règles de sécurité et aux conditions de l’organisateur. Une pièce d’identité peut être exigée.

Réservations & caractère définitif
Toute réservation confirmée est ferme et définitive.
Les billets et prestations hospitalité sont non remboursables, non échangeables et non modifiables après confirmation.

Attribution des places
Les sièges et espaces hospitalité sont attribués par l’organisateur selon disponibilité.
Les places côte à côte ne peuvent être garanties.

Modifications de programme
Les horaires, accès hospitalité, prestations et services peuvent être modifiés pour des raisons opérationnelles ou de sécurité.
La présence d’invités ou personnalités n’est jamais garantie.

Report ou annulation de l’événement
En cas de report, modification ou annulation, les conditions applicables sont celles de l’organisateur officiel.
Carré Premium agit en qualité d’intermédiaire et ne peut être tenue responsable des décisions prises par les organisateurs.

Livraison des billets
Les billets peuvent être électroniques ou physiques et sont généralement délivrés quelques jours avant l’événement.

Services complémentaires
Les vols, hébergements et services additionnels peuvent être proposés en option.
Les conditions des prestataires concernés s’appliquent.

Responsabilité & assurance
Le détenteur du billet doit respecter les règles du stade.
Une assurance voyage est fortement recommandée.
Carré Premium ne peut être tenue responsable des retards, grèves, décisions administratives ou cas de force majeure.

Paiement & confirmation
La réservation est confirmée à réception du paiement.
Le règlement implique l’acceptation des présentes conditions.

Les hospitalités UEFA sont proposées en nombre limité et particulièrement recherchées.
Les conditions d’accès sont strictement encadrées par l’organisateur.
TEXT;

$leMansConditions = <<<'TEXT'
Accès & billetterie
Les billets et hospitalités sont fournis par des partenaires officiels.
L’accès au circuit et aux espaces VIP est soumis aux règles de l’organisateur.

Réservation & caractère définitif
Toute réservation confirmée est ferme et définitive.
Les billets et prestations d’hospitalités ne sont ni remboursables ni échangeables après confirmation, sauf annulation officielle.

Programmation & déroulement de l’événement
Le programme, les horaires et les activités peuvent être modifiés pour des raisons sportives, techniques ou organisationnelles.
Ces ajustements ne peuvent donner lieu à annulation ou indemnisation.

Attribution des accès
Les emplacements et accès hospitalités sont attribués selon disponibilité.
Les accès peuvent être ajustés pour des raisons opérationnelles ou de sécurité.

Report ou annulation
En cas de report, modification ou annulation, les conditions applicables sont celles de l’organisateur officiel.
Carré Premium agit en qualité d’intermédiaire et ne peut être tenue responsable des décisions prises.

Livraison des billets
Les titres d’accès sont généralement délivrés sous format électronique avant l’événement.
Chaque billet est nominatif ou personnel et valable pour une seule personne.

Services complémentaires
Vols, hébergements, transferts et services additionnels peuvent être proposés en option.
Les conditions des prestataires concernés s’appliquent.

Responsabilité & assurance
Le détenteur du billet doit respecter les règles du circuit et des espaces hospitalités.
Carré Premium ne peut être tenue responsable des retards, incidents, décisions administratives ou cas de force majeure.
Une assurance personnelle est recommandée.

Paiement & confirmation
La réservation est confirmée à réception du paiement.
Le règlement implique l’acceptation des présentes conditions.

Les hospitalités des 24 Heures du Mans sont proposées en nombre limité et particulièrement recherchées.
TEXT;

$worldCupCanadaDescriptions = [
    'pitchside' => $lines([
        'L’expérience la plus proche de l’action.',
        'Sieges premium en bord de pelouse ou a proximite immediate.',
        'Acces a un lounge exclusif avec accueil haut de gamme.',
        'Restauration gastronomique et boissons premium a discretion.',
        'Accreditation prioritaire et acces dedies au stade.',
        'Animations, invites speciaux et ambiance immersive d’avant-match.',
    ]),
    'lounge1930' => $lines([
        'Une experience prestigieuse melant heritage et modernite.',
        'Sieges premium avec visibilite exceptionnelle.',
        'Acces a un lounge exclusif au design elegant et contemporain.',
        'Restauration gastronomique inspiree de cuisines internationales.',
        'Boissons premium, vins selectionnes et service haut de gamme.',
        'Animations et atmosphere raffinee avant et apres le match.',
    ]),
    'trophy' => $lines([
        'Une immersion premium au coeur du prestige FIFA.',
        'Sieges premium dans une zone privilegiee du stade.',
        'Acces a un lounge haut de gamme a l’atmosphere elegante.',
        'Restauration raffinee et boissons premium incluses.',
        'Ambiances d’avant-match immersives et animations exclusives.',
        'Experience VIP inspiree du prestige de la Coupe du Monde.',
    ]),
    'champions' => $lines([
        'L’equilibre parfait entre prestige et convivialite.',
        'Sieges haut de gamme avec vue privilegiee.',
        'Acces a un lounge hospitality avec cuisine inspiree localement.',
        'Selection de vins, bieres et boissons premium.',
        'Animations et experiences d’avant-match.',
        'Ambiance raffinee ideale pour le networking.',
    ]),
    'pavilion' => $lines([
        'L’experience lifestyle & festive.',
        'Acces a un lounge d’hospitalite situe a proximite du stade.',
        'Animations, divertissements et ambiance internationale.',
        'Restauration variee et boissons incluses.',
        'Experience pre et post-match conviviale.',
        'Ideal pour vivre l’atmosphere unique de la Coupe du Monde.',
    ]),
];

$worldCupMexicoDescriptions = [
    'pitchside' => $lines([
        'L’expérience la plus proche de l’action.',
        'Sieges premium a proximite immediate de la pelouse.',
        'Acces a un lounge haut de gamme avec accueil personnalise.',
        'Cuisine gastronomique et boissons premium a discretion.',
        'Acces prioritaires et services dedies.',
        'Ambiance immersive avant et apres le match.',
    ]),
    'vip' => $lines([
        'Une experience premium accessible et confortable.',
        'Sieges premium offrant une visibilite optimale.',
        'Acces a un lounge elegant avant et apres le match.',
        'Service traiteur haut de gamme et bar premium.',
        'Acces dedies et accueil personnalise.',
        'Ambiance raffinee et conviviale.',
    ]),
    'trophy' => $lines([
        'Une immersion premium au coeur du prestige FIFA.',
        'Sieges premium dans une zone privilegiee du stade.',
        'Acces a un lounge haut de gamme a l’atmosphere elegante.',
        'Restauration raffinee et boissons premium incluses.',
        'Ambiances d’avant-match immersives et animations exclusives.',
        'Experience VIP inspiree du prestige de la Coupe du Monde.',
    ]),
    'champions' => $lines([
        'L’equilibre parfait entre prestige et convivialite.',
        'Sieges haut de gamme avec vue privilegiee.',
        'Acces a un lounge hospitality avec cuisine inspiree localement.',
        'Selection de vins, bieres et boissons premium.',
        'Animations et experiences d’avant-match.',
        'Ambiance raffinee ideale pour le networking.',
    ]),
    'pavilion' => $lines([
        'L’experience lifestyle & festive.',
        'Acces a un lounge d’hospitalite situe a proximite du stade.',
        'Animations, divertissements et ambiance internationale.',
        'Restauration variee et boissons incluses.',
        'Experience pre et post-match conviviale.',
        'Ideal pour vivre l’atmosphere unique de la Coupe du Monde.',
    ]),
];

return [
    'categories' => [
        'sport' => [
            'name_fr' => 'Sport',
            'name_en' => 'Sports',
            'description' => 'Evenements sportifs et hospitalites premium',
        ],
    ],
    'types' => [
        'tennis' => [
            'category_slug' => 'sport',
            'name_fr' => 'Tennis',
            'name_en' => 'Tennis',
        ],
        'football' => [
            'category_slug' => 'sport',
            'name_fr' => 'Football',
            'name_en' => 'Football',
        ],
        'formula-1' => [
            'category_slug' => 'sport',
            'name_fr' => 'Formule 1',
            'name_en' => 'Formula 1',
        ],
        'endurance-racing' => [
            'category_slug' => 'sport',
            'name_fr' => 'Course d’endurance',
            'name_en' => 'Endurance racing',
        ],
    ],
    'series' => [
        'roland-garros-2026' => [
            'name_fr' => 'Roland-Garros 2026',
            'description_fr' => 'Hospitalites Roland-Garros 2026 a Paris',
            'venue_name' => 'Stade Roland-Garros',
            'city' => 'Paris',
            'country' => 'France',
            'start_date' => '2026-05-24',
            'end_date' => '2026-06-07',
            'organizer' => 'Roland-Garros / Carre Premium',
            'sport_type' => 'Tennis',
            'cover_image' => 'public/catalog/event-covers/roland-garros-2026.jpg',
        ],
        'formula-1-2026' => [
            'name_fr' => 'Formule 1 2026',
            'description_fr' => 'Selection de Grands Prix 2026 disponibles au catalogue Carré Premium',
            'venue_name' => 'Circuits Formula 1',
            'city' => 'Bahrein / Djeddah / Miami / Montreal / Monaco',
            'country' => 'International',
            'start_date' => '2026-04-10',
            'end_date' => '2026-06-07',
            'organizer' => 'Formula 1 / Carre Premium',
            'sport_type' => 'Formula 1',
            'cover_image' => 'public/catalog/event-covers/formule-1-bahrein-2026.jpg',
        ],
        'fifa-world-cup-2026' => [
            'name_fr' => 'FIFA World Cup 2026',
            'description_fr' => 'Hospitalites officielles FIFA World Cup 2026 sur les matchs Canada et Mexique',
            'venue_name' => 'Stades hotes FIFA World Cup 2026',
            'city' => 'Canada / Mexique',
            'country' => 'Amerique du Nord',
            'start_date' => '2026-06-11',
            'end_date' => '2026-07-19',
            'organizer' => 'FIFA / Carre Premium',
            'sport_type' => 'Football',
            'cover_image' => 'public/catalog/event-covers/world-cup-2026-canada.jpg',
        ],
        'uefa-finals-2026' => [
            'name_fr' => 'Finales UEFA 2026',
            'description_fr' => 'Hospitalites premium pour les finales UEFA Europa League et UEFA Champions League 2026',
            'venue_name' => 'Stades UEFA',
            'city' => 'Istanbul / Budapest',
            'country' => 'Europe',
            'start_date' => '2026-05-20',
            'end_date' => '2026-05-30',
            'organizer' => 'UEFA / Carre Premium',
            'sport_type' => 'Football',
            'cover_image' => 'public/catalog/event-covers/finale-uefa-europa-league-2026.jpg',
        ],
        'le-mans-2026' => [
            'name_fr' => 'Les 24 Heures du Mans 2026',
            'description_fr' => 'Hospitalites et experiences premium pour Les 24 Heures du Mans 2026',
            'venue_name' => 'Circuit des 24 Heures du Mans',
            'city' => 'Le Mans',
            'country' => 'France',
            'start_date' => '2026-06-05',
            'end_date' => '2026-06-14',
            'organizer' => 'ACO / Carre Premium',
            'sport_type' => 'Endurance',
            'cover_image' => 'public/catalog/event-covers/les-24h-du-mans-2026.jpg',
        ],
    ],
    'events' => [
        [
            'slug' => 'roland-garros-paris-2026',
            'category_slug' => 'sport',
            'type_slug' => 'tennis',
            'series_slug' => 'roland-garros-2026',
            'family' => 'sportif',
            'title_fr' => 'Roland-Garros - Paris 2026',
            'title_en' => 'Roland-Garros - Paris 2026',
            'tagline_fr' => 'Hospitalites officielles sur courts Philippe-Chatrier et Suzanne-Lenglen',
            'description_fr' => 'Vivez Roland-Garros du 24 mai au 07 juin 2026 a Paris avec les hospitalites premium Carre Premium. Le catalogue PDF propose cinq formules distinctes, du salon partage a la loge privative, avec restauration, boissons, cadeaux Roland-Garros et options de places Catégorie 1 ou Or selon les sessions.',
            'program_fr' => $lines([
                'Du 24 mai au 07 juin 2026.',
                'Sessions journee, soiree ou journee + soiree selon la formule choisie.',
                'Formules disponibles : Le Comptoir, La Brasserie des Mousquetaires, Le Cercle, Club Chatrier et Le Club Gold.',
                'Les dates catalogue vont du 24 mai au 04 juin, avec 1er tour, 2eme tour, 3eme tour, 1/4 de finale et 1/2 finale selon les offres.',
            ]),
            'conditions_fr' => $rolandGarrosConditions,
            'source_catalog' => 'Roland Garros - Catalogue.pdf',
            'venue_name' => 'Stade Roland-Garros',
            'venue_address' => '2 avenue Gordon Bennett, 75016 Paris',
            'city' => 'Paris',
            'country' => 'France',
            'event_date' => '2026-05-24',
            'event_time' => '10:00:00',
            'end_date' => '2026-06-07',
            'end_time' => '22:00:00',
            'organizer' => 'Roland-Garros / Carre Premium',
            'cover_image' => 'public/catalog/event-covers/roland-garros-2026.jpg',
            'packages' => [
                [
                    'name_fr' => 'Le Comptoir',
                    'code' => 'RG-COMPTOIR',
                    'description_fr' => 'Salon partage situe au coeur du court Philippe-Chatrier avec acces direct a des places Catégorie 1 ou Or selon la session.',
                    'venue_details_fr' => 'Court Philippe-Chatrier',
                    'included_fr' => $lines([
                        'Accueil privatif',
                        'Ouverture des portes du stade des 10h pour la journee et des 18h pour la soiree',
                        'Session journee, session soiree ou session journee + soiree',
                        'Places Catégorie 1 ou Or en tribune laterale sur le court Philippe-Chatrier',
                        'Acces libre aux courts annexes selon la session choisie',
                        'Salon partage situe au coeur du court Philippe-Chatrier avec acces direct a vos places',
                        'Cocktail dejeûnatoire ou cocktail dinatoire en buffet',
                        'Boissons a discretion tout au long de la journee',
                        '1 cadeau griffe Roland-Garros par invite et par session',
                        'Acces wi-fi gratuit et illimite',
                        'Vestiaire',
                    ]),
                    'minimum_quantity' => 1,
                    'max_per_order' => 6,
                    'options' => [
                        $option('30 mai - 2eme tour', 605000, '2026-05-30', 'Catégorie 1 • Soiree', 32, 6),
                        $option('04 juin - 1/2 finale', 790000, '2026-06-04', 'Catégorie 1 • Journee', 24, 4),
                        $option('04 juin - 1/2 finale', 910000, '2026-06-04', 'Catégorie Or • Journee', 24, 4),
                    ],
                ],
                [
                    'name_fr' => 'La Brasserie des Mousquetaires',
                    'code' => 'RG-BRASSERIE',
                    'description_fr' => 'Espace de reception et salon partage au coeur du court Philippe-Chatrier avec places Catégorie 1.',
                    'venue_details_fr' => 'Court Philippe-Chatrier',
                    'included_fr' => $lines([
                        'Accueil privatif',
                        'Ouverture des portes du stade des 10h pour la journee et des 18h pour la soiree',
                        'Session journee, session soiree ou session journee + soiree',
                        'Places Catégorie 1 sur le court Philippe-Chatrier',
                        'Acces libre aux courts annexes selon la session choisie',
                        'Espace de reception et salon partage situe au coeur du court Philippe-Chatrier',
                        'Cocktail dejeûnatoire ou cocktail dinatoire en buffet',
                        'Boissons a discretion tout au long de la journee',
                        '1 cadeau griffe Roland-Garros par invite et par session',
                        'Vestiaire',
                        'Acces wi-fi gratuit et illimite',
                    ]),
                    'minimum_quantity' => 1,
                    'max_per_order' => 6,
                    'options' => [
                        $option('28 mai - 2eme tour', 665000, '2026-05-28', 'Catégorie 1 • Soiree', 32, 6),
                        $option('02 juin - 1/4 de finale', 870000, '2026-06-02', 'Catégorie 1 • Soiree', 24, 4),
                    ],
                ],
                [
                    'name_fr' => 'Le Cercle',
                    'code' => 'RG-CERCLE',
                    'description_fr' => 'Salon partage situe sous le court Suzanne-Lenglen avec table reservee, repas assis gastronomique et places Catégorie 1 ou Or en fond de court.',
                    'venue_details_fr' => 'Sous le court Suzanne-Lenglen, a quelques metres du court Philippe-Chatrier',
                    'included_fr' => $lines([
                        'Accueil privatif',
                        'Ouverture des portes du stade des 10h pour la journee et des 18h pour la soiree',
                        'Session journee, session soiree ou session journee + soiree',
                        'Places Catégorie 1 ou Or en fond de court sur le court Philippe-Chatrier',
                        'Acces libre aux courts annexes selon la session choisie',
                        'Salon partage situe sous le court Suzanne-Lenglen',
                        'Table reservee pour vous et vos invites',
                        'Dejeuner ou diner assis gastronomique par un traiteur de renom',
                        'Boissons a discretion tout au long de la journee',
                        '1 place de parking pour 4 personnes par session',
                        '1 cadeau griffe Roland-Garros par invite et par session',
                        'Acces wi-fi gratuit et illimite',
                    ]),
                    'minimum_quantity' => 4,
                    'max_per_order' => 8,
                    'options' => [
                        $option('24 mai - 1er tour', 265000, '2026-05-24', 'Catégorie 1 • Soiree', 24, 8),
                        $option('24 mai - 1er tour', 305000, '2026-05-24', 'Catégorie Or • Soiree', 24, 8),
                        $option('25 mai - 1er tour', 330000, '2026-05-25', 'Catégorie 1 • Soiree', 24, 8),
                        $option('25 mai - 1er tour', 385000, '2026-05-25', 'Catégorie Or • Soiree', 24, 8),
                        $option('28 mai - 2eme tour', 665000, '2026-05-28', 'Catégorie 1 • Soiree', 20, 8),
                        $option('28 mai - 2eme tour', 760000, '2026-05-28', 'Catégorie Or • Soiree', 20, 8),
                        $option('29 mai - 3eme tour', 580000, '2026-05-29', 'Catégorie 1 • Soiree', 20, 8),
                        $option('29 mai - 3eme tour', 665000, '2026-05-29', 'Catégorie Or • Soiree', 20, 8),
                        $option('30 mai - 3eme tour', 760000, '2026-05-30', 'Catégorie 1 • Soiree', 20, 8),
                        $option('30 mai - 3eme tour', 870000, '2026-05-30', 'Catégorie Or • Soiree', 20, 8),
                        $option('04 juin - 1/2 finale', 760000, '2026-06-04', 'Catégorie 1 • Journee', 16, 8),
                        $option('04 juin - 1/2 finale', 870000, '2026-06-04', 'Catégorie Or • Journee', 16, 8),
                    ],
                ],
                [
                    'name_fr' => 'Club Chatrier',
                    'code' => 'RG-CHATRIER',
                    'description_fr' => 'Loge de 4 places situee sur le court Philippe-Chatrier avec salon partage, table reservee et dejeuner + diner gastronomiques.',
                    'venue_details_fr' => 'Court Philippe-Chatrier',
                    'included_fr' => $lines([
                        'Accueil privatif',
                        'Ouverture des portes du stade des 10h pour la journee et des 18h pour la soiree',
                        'Session journee, session soiree ou session journee + soiree',
                        'Loge de 4 places situee sur le court Philippe-Chatrier',
                        'Salon partage situe au coeur du court Philippe-Chatrier',
                        'Table reservee pour vous et vos invites',
                        'Dejeuner et diner gastronomiques',
                        'Boissons a discretion tout au long de la journee',
                        '1 place de parking pour 4 personnes par session',
                        '1 cadeau griffe Roland-Garros par invite et par session',
                        'Acces wi-fi gratuit et illimite',
                        'Vestiaire',
                    ]),
                    'minimum_quantity' => 4,
                    'max_per_order' => 4,
                    'options' => [
                        $option('24 mai - 1er tour', 915000, '2026-05-24', null, 16, 4),
                        $option('25 mai - 1er tour', 1055000, '2026-05-25', null, 16, 4),
                        $option('26 mai - 1er tour', 1385000, '2026-05-26', null, 12, 4),
                        $option('27 mai - 2eme tour', 1570000, '2026-05-27', null, 12, 4),
                        $option('28 mai - 2eme tour', 1950000, '2026-05-28', null, 12, 4),
                        $option('29 mai - 3eme tour', 1950000, '2026-05-29', null, 12, 4),
                        $option('30 mai - 3eme tour', 1705000, '2026-05-30', null, 12, 4),
                        $option('31 mai - 4eme tour', 1685000, '2026-05-31', null, 12, 4),
                        $option('01 juin - 4eme tour', 2160000, '2026-06-01', null, 8, 4),
                    ],
                ],
                [
                    'name_fr' => 'Le Club Gold',
                    'code' => 'RG-GOLD',
                    'description_fr' => 'Formule premium avec table reservee, repas assis gastronomique, parking et places Catégorie 1 ou Or en fond de court.',
                    'venue_details_fr' => 'Sous le court Suzanne-Lenglen, a quelques metres du court Philippe-Chatrier',
                    'included_fr' => $lines([
                        'Accueil privatif',
                        'Ouverture des portes du stade des 10h pour la journee et des 18h pour la soiree',
                        'Session journee, session soiree ou session journee + soiree',
                        'Places Catégorie 1 ou Or en fond de court sur le court Philippe-Chatrier',
                        'Acces libre aux courts annexes selon la session choisie',
                        'Salon partage situe sous le court Suzanne-Lenglen',
                        'Table reservee pour vous et vos invites',
                        'Dejeuner ou diner assis gastronomique par un traiteur de renom',
                        'Boissons a discretion tout au long de la journee',
                        '1 place de parking pour 4 personnes par session',
                        '1 cadeau griffe Roland-Garros par invite et par session',
                        'Acces wi-fi gratuit et illimite',
                    ]),
                    'minimum_quantity' => 2,
                    'max_per_order' => 4,
                    'options' => [
                        $option('26 mai - 1er tour', 1785000, '2026-05-26', null, 12, 4),
                        $option('28 mai - 2eme tour', 2490000, '2026-05-28', null, 8, 4),
                        $option('29 mai - 3eme tour', 2490000, '2026-05-29', null, 8, 4),
                    ],
                ],
            ],
        ],
        [
            'slug' => 'grand-prix-bahrein-2026',
            'category_slug' => 'sport',
            'type_slug' => 'formula-1',
            'series_slug' => 'formula-1-2026',
            'family' => 'sportif',
            'title_fr' => 'Formule 1 - Bahrein 2026',
            'tagline_fr' => 'Ticket 3 jours a partir de 310 000 FCFA',
            'description_fr' => 'Grand Prix de Bahrein du 10 au 12 avril 2026. Le visuel catalogue indique un ticket 3 jours a partir de 310 000 FCFA avec possibilite d’ajouter un package vols + hebergement.',
            'program_fr' => $lines([
                'Du 10 au 12 avril 2026.',
                'Ticket 3 jours.',
                'Prix catalogue a partir de 310 000 FCFA.',
                'Option package vols + hebergement sur demande.',
            ]),
            'conditions_fr' => $f1Conditions,
            'source_catalog' => 'Copie de Visuels event sportif.pdf',
            'venue_name' => 'Bahrain International Circuit',
            'venue_address' => 'Sakhir',
            'city' => 'Sakhir',
            'country' => 'Bahrein',
            'event_date' => '2026-04-10',
            'event_time' => '10:00:00',
            'end_date' => '2026-04-12',
            'end_time' => '22:00:00',
            'organizer' => 'Formula 1 / Carre Premium',
            'cover_image' => 'public/catalog/event-covers/formule-1-bahrein-2026.jpg',
            'packages' => [[
                'name_fr' => 'Ticket 3 jours',
                'code' => 'F1-BAHRAIN-3D',
                'description_fr' => 'Acces 3 jours au Grand Prix de Bahrein 2026 selon disponibilites catalogue.',
                'venue_details_fr' => 'Bahrain International Circuit',
                'included_fr' => $lines([
                    'Ticket officiel 3 jours',
                    'Tarif catalogue a partir de 310 000 FCFA',
                    'Option package vols + hebergement',
                ]),
                'price' => 310000,
                'available_quantity' => 80,
                'minimum_quantity' => 1,
                'max_per_order' => 6,
            ]],
        ],
        [
            'slug' => 'grand-prix-djeddah-2026',
            'category_slug' => 'sport',
            'type_slug' => 'formula-1',
            'series_slug' => 'formula-1-2026',
            'family' => 'sportif',
            'title_fr' => 'Formule 1 - Djeddah 2026',
            'tagline_fr' => 'Ticket 3 jours a partir de 2 120 000 FCFA',
            'description_fr' => 'Grand Prix de Djeddah du 17 au 19 avril 2026. Le visuel catalogue indique un ticket 3 jours a partir de 2 120 000 FCFA avec possibilite d’ajouter un package vols + hebergement.',
            'program_fr' => $lines([
                'Du 17 au 19 avril 2026.',
                'Ticket 3 jours.',
                'Prix catalogue a partir de 2 120 000 FCFA.',
                'Option package vols + hebergement sur demande.',
            ]),
            'conditions_fr' => $f1Conditions,
            'source_catalog' => 'Copie de Visuels event sportif.pdf',
            'venue_name' => 'Jeddah Corniche Circuit',
            'venue_address' => 'Djeddah',
            'city' => 'Djeddah',
            'country' => 'Arabie Saoudite',
            'event_date' => '2026-04-17',
            'event_time' => '10:00:00',
            'end_date' => '2026-04-19',
            'end_time' => '22:00:00',
            'organizer' => 'Formula 1 / Carre Premium',
            'cover_image' => 'public/catalog/event-covers/formule-1-djeddah-2026.jpg',
            'packages' => [[
                'name_fr' => 'Ticket 3 jours',
                'code' => 'F1-DJEDDAH-3D',
                'description_fr' => 'Acces 3 jours au Grand Prix de Djeddah 2026 selon disponibilites catalogue.',
                'venue_details_fr' => 'Jeddah Corniche Circuit',
                'included_fr' => $lines([
                    'Ticket officiel 3 jours',
                    'Tarif catalogue a partir de 2 120 000 FCFA',
                    'Option package vols + hebergement',
                ]),
                'price' => 2120000,
                'available_quantity' => 40,
                'minimum_quantity' => 1,
                'max_per_order' => 4,
            ]],
        ],
        [
            'slug' => 'grand-prix-miami-2026',
            'category_slug' => 'sport',
            'type_slug' => 'formula-1',
            'series_slug' => 'formula-1-2026',
            'family' => 'sportif',
            'title_fr' => 'Formule 1 - Miami 2026',
            'tagline_fr' => 'Ticket 3 jours a partir de 650 000 FCFA',
            'description_fr' => 'Grand Prix de Miami du 01 au 03 mai 2026. Le visuel catalogue indique un ticket 3 jours a partir de 650 000 FCFA avec possibilite d’ajouter un package vols + hebergement.',
            'program_fr' => $lines([
                'Du 01 au 03 mai 2026.',
                'Ticket 3 jours.',
                'Prix catalogue a partir de 650 000 FCFA.',
                'Option package vols + hebergement sur demande.',
            ]),
            'conditions_fr' => $f1Conditions,
            'source_catalog' => 'Copie de Visuels event sportif.pdf',
            'venue_name' => 'Miami International Autodrome',
            'venue_address' => 'Miami Gardens',
            'city' => 'Miami',
            'country' => 'Etats-Unis',
            'event_date' => '2026-05-01',
            'event_time' => '10:00:00',
            'end_date' => '2026-05-03',
            'end_time' => '22:00:00',
            'organizer' => 'Formula 1 / Carre Premium',
            'cover_image' => 'public/catalog/event-covers/formule-1-miami-2026.jpg',
            'packages' => [[
                'name_fr' => 'Ticket 3 jours',
                'code' => 'F1-MIAMI-3D',
                'description_fr' => 'Acces 3 jours au Grand Prix de Miami 2026 selon disponibilites catalogue.',
                'venue_details_fr' => 'Miami International Autodrome',
                'included_fr' => $lines([
                    'Ticket officiel 3 jours',
                    'Tarif catalogue a partir de 650 000 FCFA',
                    'Option package vols + hebergement',
                ]),
                'price' => 650000,
                'available_quantity' => 70,
                'minimum_quantity' => 1,
                'max_per_order' => 6,
            ]],
        ],
        [
            'slug' => 'grand-prix-canada-2026',
            'category_slug' => 'sport',
            'type_slug' => 'formula-1',
            'series_slug' => 'formula-1-2026',
            'family' => 'sportif',
            'title_fr' => 'Formule 1 - Canada 2026',
            'tagline_fr' => 'Ticket 3 jours a partir de 1 020 000 FCFA',
            'description_fr' => 'Grand Prix du Canada du 22 au 24 mai 2026. Le visuel catalogue indique un ticket 3 jours a partir de 1 020 000 FCFA avec possibilite d’ajouter un package vols + hebergement.',
            'program_fr' => $lines([
                'Du 22 au 24 mai 2026.',
                'Ticket 3 jours.',
                'Prix catalogue a partir de 1 020 000 FCFA.',
                'Option package vols + hebergement sur demande.',
            ]),
            'conditions_fr' => $f1Conditions,
            'source_catalog' => 'Copie de Visuels event sportif.pdf',
            'venue_name' => 'Circuit Gilles-Villeneuve',
            'venue_address' => 'Montreal',
            'city' => 'Montreal',
            'country' => 'Canada',
            'event_date' => '2026-05-22',
            'event_time' => '10:00:00',
            'end_date' => '2026-05-24',
            'end_time' => '22:00:00',
            'organizer' => 'Formula 1 / Carre Premium',
            'cover_image' => 'public/catalog/event-covers/formule-1-canada-2026.jpg',
            'packages' => [[
                'name_fr' => 'Ticket 3 jours',
                'code' => 'F1-CANADA-3D',
                'description_fr' => 'Acces 3 jours au Grand Prix du Canada 2026 selon disponibilites catalogue.',
                'venue_details_fr' => 'Circuit Gilles-Villeneuve',
                'included_fr' => $lines([
                    'Ticket officiel 3 jours',
                    'Tarif catalogue a partir de 1 020 000 FCFA',
                    'Option package vols + hebergement',
                ]),
                'price' => 1020000,
                'available_quantity' => 50,
                'minimum_quantity' => 1,
                'max_per_order' => 4,
            ]],
        ],
        [
            'slug' => 'grand-prix-monaco-2026',
            'category_slug' => 'sport',
            'type_slug' => 'formula-1',
            'series_slug' => 'formula-1-2026',
            'family' => 'sportif',
            'title_fr' => 'Formule 1 - Monaco 2026',
            'tagline_fr' => 'Ticket 3 jours a partir de 2 330 000 FCFA',
            'description_fr' => 'Grand Prix de Monaco du 05 au 07 juin 2026. Le visuel catalogue indique un ticket 3 jours a partir de 2 330 000 FCFA avec possibilite d’ajouter un package vols + hebergement.',
            'program_fr' => $lines([
                'Du 05 au 07 juin 2026.',
                'Ticket 3 jours.',
                'Prix catalogue a partir de 2 330 000 FCFA.',
                'Option package vols + hebergement sur demande.',
            ]),
            'conditions_fr' => $f1Conditions,
            'source_catalog' => 'Copie de Visuels event sportif.pdf',
            'venue_name' => 'Circuit de Monaco',
            'venue_address' => 'Monte-Carlo',
            'city' => 'Monaco',
            'country' => 'Monaco',
            'event_date' => '2026-06-05',
            'event_time' => '10:00:00',
            'end_date' => '2026-06-07',
            'end_time' => '22:00:00',
            'organizer' => 'Formula 1 / Carre Premium',
            'cover_image' => 'public/catalog/event-covers/formule-1-monaco-2026.jpg',
            'packages' => [[
                'name_fr' => 'Ticket 3 jours',
                'code' => 'F1-MONACO-3D',
                'description_fr' => 'Acces 3 jours au Grand Prix de Monaco 2026 selon disponibilites catalogue.',
                'venue_details_fr' => 'Circuit de Monaco',
                'included_fr' => $lines([
                    'Ticket officiel 3 jours',
                    'Tarif catalogue a partir de 2 330 000 FCFA',
                    'Option package vols + hebergement',
                ]),
                'price' => 2330000,
                'available_quantity' => 40,
                'minimum_quantity' => 1,
                'max_per_order' => 4,
            ]],
        ],
        [
            'slug' => 'fifa-world-cup-canada-2026',
            'category_slug' => 'sport',
            'type_slug' => 'football',
            'series_slug' => 'fifa-world-cup-2026',
            'family' => 'sportif',
            'title_fr' => 'FIFA World Cup - Canada 2026',
            'tagline_fr' => 'Hospitalites officielles Canada a partir de 1 335 000 FCFA',
            'description_fr' => 'Selection d’hospitalites FIFA World Cup 2026 pour les matchs joues au Canada. Le catalogue detaille dix niveaux de salons, des FIFA Pavilion aux Pitchside Lounge, avec options match par match et tarifs en FCFA.',
            'program_fr' => $lines([
                '12 juin - Canada / Play-off A',
                '13 juin - Australie / Play-off C',
                '17 juin - Ghana / Panama',
                '18 juin - Canada / Qatar',
                '20 juin - Allemagne / Cote d’Ivoire',
                '21 juin - Nouvelle Zelande / Egypte',
                '23 juin - Panama / Croatie',
                '24 juin - Suisse / Canada',
                '26 juin - Senegal / Play-off 2',
                '26 juin - Nouvelle Zelande / Belgique',
                '02 juillet - 2K / 2L',
                '02 juillet - 1B / 3EFGIJ',
                '07 juillet - W85 / W87',
            ]),
            'conditions_fr' => $worldCupConditions,
            'source_catalog' => 'Coupe du monde 2026 - Catalogue.pdf',
            'venue_name' => 'Stades hotes FIFA World Cup 2026',
            'venue_address' => 'Selon le match et l’hospitalite selectionnee',
            'city' => 'Toronto / Vancouver',
            'country' => 'Canada',
            'event_date' => '2026-06-12',
            'event_time' => '12:00:00',
            'end_date' => '2026-07-19',
            'end_time' => '23:00:00',
            'organizer' => 'FIFA / Carre Premium',
            'cover_image' => 'public/catalog/event-covers/world-cup-2026-canada.jpg',
            'packages' => [
                [
                    'name_fr' => 'Pitchside Lounge',
                    'code' => 'WC26-CAN-PITCH',
                    'description_fr' => 'Hospitalite Pitchside Lounge Canada.',
                    'included_fr' => $worldCupCanadaDescriptions['pitchside'],
                    'minimum_quantity' => 1,
                    'max_per_order' => 6,
                    'options' => [
                        $option('17 juin - Ghana / Panama', 3480000, '2026-06-17'),
                        $option('21 juin - Nouvelle Zelande / Egypte', 3290000, '2026-06-21'),
                        $option('24 juin - Suisse / Canada', 4700000, '2026-06-24'),
                        $option('26 juin - Senegal / Play-off 2', 2900000, '2026-06-26'),
                        $option('26 juin - Nouvelle Zelande / Belgique', 3520000, '2026-06-26'),
                    ],
                ],
                [
                    'name_fr' => 'Pitchside Lounge +',
                    'code' => 'WC26-CAN-PITCH-PLUS',
                    'description_fr' => 'Version + du Pitchside Lounge selon la grille catalogue.',
                    'included_fr' => $worldCupCanadaDescriptions['pitchside'],
                    'minimum_quantity' => 1,
                    'max_per_order' => 6,
                    'options' => [
                        $option('13 juin - Australie / Play-off C', 3500000, '2026-06-13'),
                        $option('17 juin - Ghana / Panama', 3740000, '2026-06-17'),
                        $option('21 juin - Nouvelle Zelande / Egypte', 3495000, '2026-06-21'),
                        $option('26 juin - Senegal / Play-off 2', 3135000, '2026-06-26'),
                        $option('26 juin - Nouvelle Zelande / Belgique', 3730000, '2026-06-26'),
                        $option('07 juillet - W85 / W87', 4830000, '2026-07-07'),
                    ],
                ],
                [
                    'name_fr' => 'Lounge 1930',
                    'code' => 'WC26-CAN-1930',
                    'description_fr' => 'Hospitalite Lounge 1930 Canada.',
                    'included_fr' => $worldCupCanadaDescriptions['lounge1930'],
                    'minimum_quantity' => 1,
                    'max_per_order' => 6,
                    'options' => [
                        $option('12 juin - Canada / Play-off A', 4130000, '2026-06-12'),
                        $option('13 juin - Australie / Play-off C', 2510000, '2026-06-13'),
                        $option('17 juin - Ghana / Panama', 2960000, '2026-06-17'),
                        $option('18 juin - Canada / Qatar', 3250000, '2026-06-18'),
                        $option('21 juin - Nouvelle Zelande / Egypte', 2510000, '2026-06-21'),
                        $option('23 juin - Panama / Croatie', 2355000, '2026-06-23'),
                        $option('24 juin - Suisse / Canada', 3250000, '2026-06-24'),
                        $option('26 juin - Senegal / Play-off 2', 2485000, '2026-06-26'),
                        $option('26 juin - Nouvelle Zelande / Belgique', 2510000, '2026-06-26'),
                        $option('02 juillet - 2K / 2L', 2770000, '2026-07-02'),
                        $option('02 juillet - 1B / 3EFGIJ', 2740000, '2026-07-02'),
                        $option('07 juillet - W85 / W87', 3780000, '2026-07-07'),
                    ],
                ],
                [
                    'name_fr' => 'Lounge 1930 +',
                    'code' => 'WC26-CAN-1930-PLUS',
                    'description_fr' => 'Version + du Lounge 1930 selon la grille catalogue.',
                    'included_fr' => $worldCupCanadaDescriptions['lounge1930'],
                    'minimum_quantity' => 1,
                    'max_per_order' => 6,
                    'options' => [
                        $option('12 juin - Canada / Play-off A', 4490000, '2026-06-12'),
                        $option('13 juin - Australie / Play-off C', 3400000, '2026-06-13'),
                        $option('17 juin - Ghana / Panama', 3185000, '2026-06-17'),
                        $option('18 juin - Canada / Qatar', 3500000, '2026-06-18'),
                        $option('20 juin - Allemagne / Cote d’Ivoire', 2640000, '2026-06-20'),
                        $option('21 juin - Nouvelle Zelande / Egypte', 2705000, '2026-06-21'),
                        $option('23 juin - Panama / Croatie', 2540000, '2026-06-23'),
                        $option('24 juin - Suisse / Canada', 3495000, '2026-06-24'),
                        $option('26 juin - Senegal / Play-off 2', 2670000, '2026-06-26'),
                        $option('26 juin - Nouvelle Zelande / Belgique', 2705000, '2026-06-26'),
                        $option('02 juillet - 2K / 2L', 3185000, '2026-07-02'),
                        $option('02 juillet - 1B / 3EFGIJ', 3340000, '2026-07-02'),
                        $option('07 juillet - W85 / W87', 4400000, '2026-07-07'),
                    ],
                ],
                [
                    'name_fr' => 'Trophy Lounge',
                    'code' => 'WC26-CAN-TROPHY',
                    'description_fr' => 'Hospitalite Trophy Lounge Canada.',
                    'included_fr' => $worldCupCanadaDescriptions['trophy'],
                    'minimum_quantity' => 1,
                    'max_per_order' => 6,
                    'options' => [
                        $option('12 juin - Canada / Play-off A', 3610000, '2026-06-12'),
                        $option('17 juin - Ghana / Panama', 2440000, '2026-06-17'),
                        $option('23 juin - Panama / Croatie', 1995000, '2026-06-23'),
                        $option('26 juin - Senegal / Play-off 2', 2045000, '2026-06-26'),
                        $option('02 juillet - 2K / 2L', 2510000, '2026-07-02'),
                    ],
                ],
                [
                    'name_fr' => 'Trophy Lounge +',
                    'code' => 'WC26-CAN-TROPHY-PLUS',
                    'description_fr' => 'Version + du Trophy Lounge selon la grille catalogue.',
                    'included_fr' => $worldCupCanadaDescriptions['trophy'],
                    'minimum_quantity' => 1,
                    'max_per_order' => 6,
                    'options' => [
                        $option('12 juin - Canada / Play-off A', 3755000, '2026-06-12'),
                        $option('17 juin - Ghana / Panama', 2565000, '2026-06-17'),
                        $option('23 juin - Panama / Croatie', 2095000, '2026-06-23'),
                        $option('26 juin - Senegal / Play-off 2', 2150000, '2026-06-26'),
                        $option('02 juillet - 2K / 2L', 2640000, '2026-07-02'),
                    ],
                ],
                [
                    'name_fr' => 'Champions Club',
                    'code' => 'WC26-CAN-CHAMPIONS',
                    'description_fr' => 'Hospitalite Champions Club Canada.',
                    'included_fr' => $worldCupCanadaDescriptions['champions'],
                    'minimum_quantity' => 1,
                    'max_per_order' => 6,
                    'options' => [
                        $option('12 juin - Canada / Play-off A', 3300000, '2026-06-12'),
                        $option('13 juin - Australie / Play-off C', 1855000, '2026-06-13'),
                        $option('18 juin - Canada / Qatar', 2010000, '2026-06-18'),
                        $option('21 juin - Nouvelle Zelande / Egypte', 1855000, '2026-06-21'),
                        $option('23 juin - Panama / Croatie', 1790000, '2026-06-23'),
                        $option('24 juin - Suisse / Canada', 1915000, '2026-06-24'),
                        $option('26 juin - Senegal / Play-off 2', 1790000, '2026-06-26'),
                        $option('26 juin - Nouvelle Zelande / Belgique', 1855000, '2026-06-26'),
                        $option('02 juillet - 1B / 3EFGIJ', 2255000, '2026-07-02'),
                        $option('07 juillet - W85 / W87', 2485000, '2026-07-07'),
                    ],
                ],
                [
                    'name_fr' => 'Champions Club +',
                    'code' => 'WC26-CAN-CHAMPIONS-PLUS',
                    'description_fr' => 'Version + du Champions Club selon la grille catalogue.',
                    'included_fr' => $worldCupCanadaDescriptions['champions'],
                    'minimum_quantity' => 1,
                    'max_per_order' => 6,
                    'options' => [
                        $option('12 juin - Canada / Play-off A', 3510000, '2026-06-12'),
                        $option('13 juin - Australie / Play-off C', 2010000, '2026-06-13'),
                        $option('18 juin - Canada / Qatar', 2110000, '2026-06-18'),
                        $option('21 juin - Nouvelle Zelande / Egypte', 2010000, '2026-06-21'),
                        $option('23 juin - Panama / Croatie', 1945000, '2026-06-23'),
                        $option('24 juin - Suisse / Canada', 2015000, '2026-06-24'),
                        $option('26 juin - Nouvelle Zelande / Belgique', 2010000, '2026-06-26'),
                        $option('02 juillet - 2K / 2L', 2255000, '2026-07-02'),
                        $option('02 juillet - 1B / 3EFGIJ', 2460000, '2026-07-02'),
                        $option('07 juillet - W85 / W87', 2875000, '2026-07-07'),
                    ],
                ],
                [
                    'name_fr' => 'FIFA Pavilion',
                    'code' => 'WC26-CAN-PAVILION',
                    'description_fr' => 'Hospitalite FIFA Pavilion Canada.',
                    'included_fr' => $worldCupCanadaDescriptions['pavilion'],
                    'minimum_quantity' => 1,
                    'max_per_order' => 6,
                    'options' => [
                        $option('13 juin - Australie / Play-off C', 1530000, '2026-06-13'),
                        $option('18 juin - Canada / Qatar', 1855000, '2026-06-18'),
                        $option('21 juin - Nouvelle Zelande / Egypte', 1335000, '2026-06-21'),
                        $option('24 juin - Suisse / Canada', 1775000, '2026-06-24'),
                        $option('26 juin - Nouvelle Zelande / Belgique', 1335000, '2026-06-26'),
                        $option('07 juillet - W85 / W87', 1980000, '2026-07-07'),
                    ],
                ],
                [
                    'name_fr' => 'FIFA Pavilion +',
                    'code' => 'WC26-CAN-PAVILION-PLUS',
                    'description_fr' => 'Version + du FIFA Pavilion selon la grille catalogue.',
                    'included_fr' => $worldCupCanadaDescriptions['pavilion'],
                    'minimum_quantity' => 1,
                    'max_per_order' => 6,
                    'options' => [
                        $option('13 juin - Australie / Play-off C', 1695000, '2026-06-13'),
                        $option('18 juin - Canada / Qatar', 1905000, '2026-06-18'),
                        $option('21 juin - Nouvelle Zelande / Egypte', 1440000, '2026-06-21'),
                        $option('24 juin - Suisse / Canada', 1825000, '2026-06-24'),
                        $option('26 juin - Nouvelle Zelande / Belgique', 1440000, '2026-06-26'),
                        $option('07 juillet - W85 / W87', 2150000, '2026-07-07'),
                    ],
                ],
            ],
        ],
        [
            'slug' => 'fifa-world-cup-mexique-2026',
            'category_slug' => 'sport',
            'type_slug' => 'football',
            'series_slug' => 'fifa-world-cup-2026',
            'family' => 'sportif',
            'title_fr' => 'FIFA World Cup - Mexique 2026',
            'tagline_fr' => 'Hospitalites officielles Mexique a partir de 1 380 000 FCFA',
            'description_fr' => 'Selection d’hospitalites FIFA World Cup 2026 pour les matchs joues au Mexique. Le catalogue detaille onze niveaux de salons, avec alternatives + et suite, et des options disponibles match par match jusqu’aux phases finales.',
            'program_fr' => $lines([
                '11 juin - Coree / Play-off D',
                '14 juin - Play-off B / Tunisie',
                '17 juin - Uzbekistan / Colombie',
                '20 juin - Tunisie / Japon',
                '23 juin - Colombie / Play-off 1',
                '24 juin - Afrique du Sud / Coree',
                '26 juin - Uruguay / Espagne',
                '29 juin - 1F / 2C',
                '30 juin - 1A / 3CEFHI',
                '05 juillet - W79 / W80',
            ]),
            'conditions_fr' => $worldCupConditions,
            'source_catalog' => 'Coupe du monde 2026 - Catalogue.pdf',
            'venue_name' => 'Stades hotes FIFA World Cup 2026',
            'venue_address' => 'Selon le match et l’hospitalite selectionnee',
            'city' => 'Mexico City / Guadalajara / Monterrey',
            'country' => 'Mexique',
            'event_date' => '2026-06-11',
            'event_time' => '12:00:00',
            'end_date' => '2026-07-05',
            'end_time' => '23:00:00',
            'organizer' => 'FIFA / Carre Premium',
            'cover_image' => 'public/catalog/event-covers/world-cup-2026-mexique.jpg',
            'packages' => [
                [
                    'name_fr' => 'Pitchside Lounge',
                    'code' => 'WC26-MEX-PITCH',
                    'description_fr' => 'Hospitalite Pitchside Lounge Mexique.',
                    'included_fr' => $worldCupMexicoDescriptions['pitchside'],
                    'minimum_quantity' => 1,
                    'max_per_order' => 6,
                    'options' => [
                        $option('11 juin - Coree / Play-off D', 4150000, '2026-06-11'),
                        $option('14 juin - Play-off B / Tunisie', 3525000, '2026-06-14'),
                        $option('23 juin - Colombie / Play-off 1', 3865000, '2026-06-23'),
                        $option('24 juin - Afrique du Sud / Coree', 3525000, '2026-06-24'),
                        $option('29 juin - 1F / 2C', 4030000, '2026-06-29'),
                        $option('05 juillet - W79 / W80', 7850000, '2026-07-05'),
                    ],
                ],
                [
                    'name_fr' => 'Pitchside Lounge +',
                    'code' => 'WC26-MEX-PITCH-PLUS',
                    'description_fr' => 'Version + du Pitchside Lounge selon la grille catalogue.',
                    'included_fr' => $worldCupMexicoDescriptions['pitchside'],
                    'minimum_quantity' => 1,
                    'max_per_order' => 6,
                    'options' => [
                        $option('11 juin - Coree / Play-off D', 4430000, '2026-06-11'),
                        $option('23 juin - Colombie / Play-off 1', 3925000, '2026-06-23'),
                        $option('29 juin - 1F / 2C', 4575000, '2026-06-29'),
                        $option('05 juillet - W79 / W80', 8255000, '2026-07-05'),
                    ],
                ],
                [
                    'name_fr' => 'VIP Hospitality',
                    'code' => 'WC26-MEX-VIP',
                    'description_fr' => 'Hospitalite VIP selon stade.',
                    'included_fr' => $worldCupMexicoDescriptions['vip'],
                    'minimum_quantity' => 1,
                    'max_per_order' => 6,
                    'options' => [
                        $option('17 juin - Uzbekistan / Colombie', 3425000, '2026-06-17'),
                        $option('30 juin - 1A / 3CEFHI', 5225000, '2026-06-30'),
                        $option('05 juillet - W79 / W80', 6845000, '2026-07-05'),
                    ],
                ],
                [
                    'name_fr' => 'VIP Hospitality +',
                    'code' => 'WC26-MEX-VIP-PLUS',
                    'description_fr' => 'Version + de l’hospitalite VIP selon la grille catalogue.',
                    'included_fr' => $worldCupMexicoDescriptions['vip'],
                    'minimum_quantity' => 1,
                    'max_per_order' => 6,
                    'options' => [
                        $option('14 juin - Play-off B / Tunisie', 3510000, '2026-06-14'),
                        $option('17 juin - Uzbekistan / Colombie', 3625000, '2026-06-17'),
                        $option('29 juin - 1F / 2C', 4010000, '2026-06-29'),
                        $option('30 juin - 1A / 3CEFHI', 5630000, '2026-06-30'),
                        $option('05 juillet - W79 / W80', 7045000, '2026-07-05'),
                    ],
                ],
                [
                    'name_fr' => 'Trophy Lounge',
                    'code' => 'WC26-MEX-TROPHY',
                    'description_fr' => 'Hospitalite Trophy Lounge Mexique.',
                    'included_fr' => $worldCupMexicoDescriptions['trophy'],
                    'minimum_quantity' => 1,
                    'max_per_order' => 6,
                    'options' => [
                        $option('11 juin - Coree / Play-off D', 2700000, '2026-06-11'),
                        $option('17 juin - Uzbekistan / Colombie', 3020000, '2026-06-17'),
                        $option('23 juin - Colombie / Play-off 1', 2500000, '2026-06-23'),
                        $option('26 juin - Uruguay / Espagne', 4630000, '2026-06-26'),
                        $option('30 juin - 1A / 3CEFHI', 5015000, '2026-06-30'),
                        $option('05 juillet - W79 / W80', 6280000, '2026-07-05'),
                    ],
                ],
                [
                    'name_fr' => 'Trophy Lounge +',
                    'code' => 'WC26-MEX-TROPHY-PLUS',
                    'description_fr' => 'Version + du Trophy Lounge selon la grille catalogue.',
                    'included_fr' => $worldCupMexicoDescriptions['trophy'],
                    'minimum_quantity' => 1,
                    'max_per_order' => 6,
                    'options' => [
                        $option('11 juin - Coree / Play-off D', 2835000, '2026-06-11'),
                        $option('14 juin - Play-off B / Tunisie', 2675000, '2026-06-14'),
                        $option('17 juin - Uzbekistan / Colombie', 3220000, '2026-06-17'),
                        $option('23 juin - Colombie / Play-off 1', 2620000, '2026-06-23'),
                        $option('24 juin - Afrique du Sud / Coree', 2595000, '2026-06-24'),
                        $option('26 juin - Uruguay / Espagne', 4875000, '2026-06-26'),
                        $option('30 juin - 1A / 3CEFHI', 5220000, '2026-06-30'),
                        $option('05 juillet - W79 / W80', 6485000, '2026-07-05'),
                    ],
                ],
                [
                    'name_fr' => 'Champions Club',
                    'code' => 'WC26-MEX-CHAMPIONS',
                    'description_fr' => 'Hospitalite Champions Club Mexique.',
                    'included_fr' => $worldCupMexicoDescriptions['champions'],
                    'minimum_quantity' => 1,
                    'max_per_order' => 6,
                    'options' => [
                        $option('17 juin - Uzbekistan / Colombie', 2720000, '2026-06-17'),
                        $option('30 juin - 1A / 3CEFHI', 3160000, '2026-06-30'),
                        $option('05 juillet - W79 / W80', 3970000, '2026-07-05'),
                    ],
                ],
                [
                    'name_fr' => 'Champions Club +',
                    'code' => 'WC26-MEX-CHAMPIONS-PLUS',
                    'description_fr' => 'Version + du Champions Club selon la grille catalogue.',
                    'included_fr' => $worldCupMexicoDescriptions['champions'],
                    'minimum_quantity' => 1,
                    'max_per_order' => 6,
                    'options' => [
                        $option('17 juin - Uzbekistan / Colombie', 2820000, '2026-06-17'),
                        $option('30 juin - 1A / 3CEFHI', 3365000, '2026-06-30'),
                        $option('05 juillet - W79 / W80', 4255000, '2026-07-05'),
                    ],
                ],
                [
                    'name_fr' => 'Champions Club Suite',
                    'code' => 'WC26-MEX-CHAMPIONS-SUITE',
                    'description_fr' => 'Version suite du Champions Club selon la grille catalogue.',
                    'included_fr' => $worldCupMexicoDescriptions['champions'],
                    'minimum_quantity' => 1,
                    'max_per_order' => 4,
                    'options' => [
                        $option('11 juin - Coree / Play-off D', 2230000, '2026-06-11', null, 16, 4),
                        $option('14 juin - Play-off B / Tunisie', 2470000, '2026-06-14', null, 16, 4),
                        $option('20 juin - Tunisie / Japon', 2510000, '2026-06-20', null, 16, 4),
                        $option('23 juin - Colombie / Play-off 1', 2405000, '2026-06-23', null, 16, 4),
                        $option('24 juin - Afrique du Sud / Coree', 2230000, '2026-06-24', null, 16, 4),
                        $option('26 juin - Uruguay / Espagne', 3120000, '2026-06-26', null, 16, 4),
                        $option('29 juin - 1F / 2C', 3120000, '2026-06-29', null, 16, 4),
                    ],
                ],
                [
                    'name_fr' => 'FIFA Pavilion',
                    'code' => 'WC26-MEX-PAVILION',
                    'description_fr' => 'Hospitalite FIFA Pavilion Mexique.',
                    'included_fr' => $worldCupMexicoDescriptions['pavilion'],
                    'minimum_quantity' => 1,
                    'max_per_order' => 6,
                    'options' => [
                        $option('11 juin - Coree / Play-off D', 1380000, '2026-06-11'),
                        $option('23 juin - Colombie / Play-off 1', 1480000, '2026-06-23'),
                        $option('26 juin - Uruguay / Espagne', 2180000, '2026-06-26'),
                    ],
                ],
                [
                    'name_fr' => 'FIFA Pavilion +',
                    'code' => 'WC26-MEX-PAVILION-PLUS',
                    'description_fr' => 'Version + du FIFA Pavilion selon la grille catalogue.',
                    'included_fr' => $worldCupMexicoDescriptions['pavilion'],
                    'minimum_quantity' => 1,
                    'max_per_order' => 6,
                    'options' => [
                        $option('11 juin - Coree / Play-off D', 1560000, '2026-06-11'),
                        $option('23 juin - Colombie / Play-off 1', 1590000, '2026-06-23'),
                        $option('26 juin - Uruguay / Espagne', 2220000, '2026-06-26'),
                    ],
                ],
            ],
        ],
        [
            'slug' => 'finale-uefa-europa-league-2026',
            'category_slug' => 'sport',
            'type_slug' => 'football',
            'series_slug' => 'uefa-finals-2026',
            'family' => 'sportif',
            'title_fr' => 'Finale UEFA Europa League - Istanbul 2026',
            'tagline_fr' => 'Shared Skybox a partir de 675 000 FCFA par personne',
            'description_fr' => 'Vivez la finale UEFA Europa League du 20 mai 2026 a Istanbul dans une Shared Skybox. Trois formules sont proposees: Platinum, Gold et Silver, avec acces hospitalite premium avant et apres le match.',
            'program_fr' => $lines([
                '20 mai 2026.',
                'Acces a l’hospitalite premium 3 heures avant le coup d’envoi et 90 minutes apres le match.',
                'Cocktail dinatoire debout, boissons incluses, diffusion TV et Wi-Fi.',
            ]),
            'conditions_fr' => $uefaEuropaConditions,
            'source_catalog' => 'Finale UEFA Europa League - Catalogue.pdf',
            'venue_name' => 'Stade de la finale UEFA Europa League',
            'venue_address' => 'Istanbul',
            'city' => 'Istanbul',
            'country' => 'Turquie',
            'event_date' => '2026-05-20',
            'event_time' => '20:00:00',
            'end_date' => '2026-05-20',
            'end_time' => '23:45:00',
            'organizer' => 'UEFA / Carre Premium',
            'cover_image' => 'public/catalog/event-covers/finale-uefa-europa-league-2026.jpg',
            'packages' => [
                [
                    'name_fr' => 'Platinum',
                    'code' => 'UEL-PLATINUM',
                    'description_fr' => 'Emplacement central offrant la meilleure visibilite avec experience VIP exclusive en loge privee.',
                    'venue_details_fr' => 'Shared Skybox',
                    'included_fr' => $lines([
                        'Siege situe devant la loge skybox',
                        'Acces a l’hospitalite premium 3 heures avant le coup d’envoi et 90 minutes apres le match',
                        'Cocktail dinatoire debout avec specialites locales et internationales',
                        'Boisson de bienvenue a l’arrivee',
                        'Vins, bieres et boissons sans alcool inclus',
                        'Service de boissons continu',
                        'Diffusion TV avant, pendant et apres le match',
                        'Wi-Fi disponible',
                        'Parking non inclus et non disponible',
                    ]),
                    'price' => 975000,
                    'available_quantity' => 24,
                    'minimum_quantity' => 2,
                    'max_per_order' => 6,
                ],
                [
                    'name_fr' => 'Gold',
                    'code' => 'UEL-GOLD',
                    'description_fr' => 'Excellente vue panoramique sur le terrain avec loge privee premium et acces a l’hospitalite VIP.',
                    'venue_details_fr' => 'Shared Skybox',
                    'included_fr' => $lines([
                        'Siege situe devant la loge skybox',
                        'Acces a l’hospitalite premium 3 heures avant le coup d’envoi et 90 minutes apres le match',
                        'Cocktail dinatoire debout avec specialites locales et internationales',
                        'Boisson de bienvenue a l’arrivee',
                        'Vins, bieres et boissons sans alcool inclus',
                        'Service de boissons continu',
                        'Diffusion TV avant, pendant et apres le match',
                        'Wi-Fi disponible',
                        'Parking non inclus et non disponible',
                    ]),
                    'price' => 825000,
                    'available_quantity' => 32,
                    'minimum_quantity' => 2,
                    'max_per_order' => 6,
                ],
                [
                    'name_fr' => 'Silver',
                    'code' => 'UEL-SILVER',
                    'description_fr' => 'Vue immersive proche de l’action, loge privee confortable et experience VIP plus accessible.',
                    'venue_details_fr' => 'Shared Skybox',
                    'included_fr' => $lines([
                        'Siege situe devant la loge skybox',
                        'Acces a l’hospitalite premium 3 heures avant le coup d’envoi et 90 minutes apres le match',
                        'Cocktail dinatoire debout avec specialites locales et internationales',
                        'Boisson de bienvenue a l’arrivee',
                        'Vins, bieres et boissons sans alcool inclus',
                        'Service de boissons continu',
                        'Diffusion TV avant, pendant et apres le match',
                        'Wi-Fi disponible',
                        'Parking non inclus et non disponible',
                    ]),
                    'price' => 675000,
                    'available_quantity' => 40,
                    'minimum_quantity' => 2,
                    'max_per_order' => 6,
                ],
            ],
        ],
        [
            'slug' => 'finale-uefa-champions-league-2026',
            'category_slug' => 'sport',
            'type_slug' => 'football',
            'series_slug' => 'uefa-finals-2026',
            'family' => 'sportif',
            'title_fr' => 'Finale UEFA Champions League - Budapest 2026',
            'tagline_fr' => 'Hospitalites lounge a partir de 6 615 000 FCFA par personne',
            'description_fr' => 'Vivez la finale UEFA Champions League du 30 mai 2026 a Budapest avec cinq offres lounge ou stadium hospitality. Le catalogue detaille les lieux, niveaux de service et experiences associees a chaque formule.',
            'program_fr' => $lines([
                '30 mai 2026.',
                'Hospitalites lounge dans ou a proximite du stade selon la formule.',
                'Acces avant et apres match, restauration premium, boissons et animations d’avant-match.',
            ]),
            'conditions_fr' => $uefaChampionsConditions,
            'source_catalog' => 'Finale UEFA Champions League - Catalogue.pdf',
            'venue_name' => 'Puskas Arena / BOK Hall',
            'venue_address' => 'Budapest',
            'city' => 'Budapest',
            'country' => 'Hongrie',
            'event_date' => '2026-05-30',
            'event_time' => '20:00:00',
            'end_date' => '2026-05-30',
            'end_time' => '23:45:00',
            'organizer' => 'UEFA / Carre Premium',
            'cover_image' => 'public/catalog/event-covers/finale-uefa-champions-league-2026.jpg',
            'packages' => [
                [
                    'name_fr' => 'Prestige',
                    'code' => 'UCL-PRESTIGE',
                    'description_fr' => 'Sieges Catégorie 1 avec lounge hospitality situe dans le stade et restauration buffet contemporaine.',
                    'venue_details_fr' => 'Puskas Arena (dans le stade)',
                    'included_fr' => $lines([
                        'Sieges Catégorie 1 offrant une excellente vue sur le terrain',
                        'Acces a un lounge hospitalite situe dans le stade',
                        'Accueil hospitalite avant et apres le match',
                        'Restauration sous forme de buffet contemporain',
                        'Selection de vins, bieres et boissons sans alcool',
                        'Animations et ambiance d’avant-match',
                        'Wi-Fi disponible dans l’espace hospitality',
                    ]),
                    'price' => 7100000,
                    'available_quantity' => 12,
                    'minimum_quantity' => 2,
                    'max_per_order' => 4,
                ],
                [
                    'name_fr' => 'Prestige Lounge - Outside',
                    'code' => 'UCL-PRESTIGE-OUTSIDE',
                    'description_fr' => 'Sieges Catégorie 1 dans le stade avec lounge hospitalite situe au BOK Hall a proximite immediate du stade.',
                    'venue_details_fr' => 'BOK Hall / BOK Arena',
                    'included_fr' => $lines([
                        'Sieges Catégorie 1 dans le stade',
                        'Acces a un lounge hospitalite situe dans un batiment evenementiel voisin',
                        'Acces lounge 3 heures avant et 3 heures apres le match',
                        'Restauration buffet dans une atmosphere conviviale',
                        'Boisson de bienvenue et boissons incluses',
                        'Animations d’avant-match',
                        'Wi-Fi dans l’espace hospitality',
                    ]),
                    'price' => 6615000,
                    'available_quantity' => 12,
                    'minimum_quantity' => 2,
                    'max_per_order' => 4,
                ],
                [
                    'name_fr' => 'Prestige Lounge - Through Glass',
                    'code' => 'UCL-PRESTIGE-GLASS',
                    'description_fr' => 'Sieges Catégorie 1 dans le stade avec acces a un lounge premium a facade vitree.',
                    'venue_details_fr' => 'BOK Hall / BOK Arena',
                    'included_fr' => $lines([
                        'Sieges Catégorie 1 dans le stade',
                        'Acces a un lounge premium avec facade vitree',
                        'Acces lounge 3 heures avant et apres le match',
                        'Restauration buffet contemporaine',
                        'Selection de vins, bieres et boissons sans alcool',
                        'Animations d’avant-match',
                        'Wi-Fi dans l’espace hospitality',
                    ]),
                    'price' => 6635000,
                    'available_quantity' => 12,
                    'minimum_quantity' => 2,
                    'max_per_order' => 4,
                ],
                [
                    'name_fr' => 'Prestige Lounge - Served Dinner',
                    'code' => 'UCL-SERVED-DINNER',
                    'description_fr' => 'Sieges Catégorie 1 premium avec table reservee, diner gastronomique servi a table et service hospitality dedie.',
                    'venue_details_fr' => 'Puskas Arena (dans le stade)',
                    'included_fr' => $lines([
                        'Sieges Catégorie 1 premium',
                        'Acces a un lounge hospitalite situe dans le stade',
                        'Table reservee dans l’espace lounge',
                        'Diner gastronomique servi a table',
                        'Selection de vins et boissons premium',
                        'Service hospitality dedie',
                        'Acces avant et apres le match',
                    ]),
                    'price' => 7100000,
                    'available_quantity' => 10,
                    'minimum_quantity' => 2,
                    'max_per_order' => 4,
                ],
                [
                    'name_fr' => 'Sky Club',
                    'code' => 'UCL-SKY-CLUB',
                    'description_fr' => 'Sieges premium dans les meilleures zones du stade avec acces au Sky Club VIP et diner gastronomique complet.',
                    'venue_details_fr' => 'Puskas Arena (dans le stade)',
                    'included_fr' => $lines([
                        'Sieges premium dans les meilleures zones du stade',
                        'Acces au Sky Club VIP du stade',
                        'Diner gastronomique complet',
                        'Tables reservees pour les invites',
                        'Selection premium de vins et de champagne',
                        'Service VIP et hotes dedies',
                        'Acces hospitality prolonge',
                    ]),
                    'price' => 6615000,
                    'available_quantity' => 10,
                    'minimum_quantity' => 2,
                    'max_per_order' => 4,
                ],
            ],
        ],
        [
            'slug' => 'les-24h-du-mans-2026',
            'category_slug' => 'sport',
            'type_slug' => 'endurance-racing',
            'series_slug' => 'le-mans-2026',
            'family' => 'sportif',
            'title_fr' => 'Les 24 Heures du Mans - Le Mans 2026',
            'tagline_fr' => 'Hospitalites premium a partir de 410 000 FCFA',
            'description_fr' => 'Vivez Les 24 Heures du Mans 2026 du 05 au 14 juin avec une selection de cinq hospitalites premium allant du Starter 24 a la Gold Experience. Le catalogue detaille egalement le programme quotidien complet de la semaine de course.',
            'program_fr' => $lines([
                '05 et 06 juin: pesage en centre-ville et inspection officielle des voitures.',
                '06 juin: pesage & parade dans les rues du Mans.',
                '07 juin: courses supports et premieres actions en piste.',
                '09 juin: pitlane walk et rencontre avec les pilotes.',
                '10 juin: essais, qualifications et concert Jean-Louis Aubert.',
                '11 juin: hyperpole, visites paddock, pit walk et animations.',
                '12 juin: parade officielle des pilotes, public en piste et concert.',
                '13 juin: warm-up, grid walk, ceremonie officielle et depart des 24 Heures a 16h00.',
                '14 juin: dernieres heures de course, arrivee a 16h00 et podium.',
            ]),
            'conditions_fr' => $leMansConditions,
            'source_catalog' => 'Les 24h du Mans - Catalogue.pdf',
            'venue_name' => 'Circuit des 24 Heures du Mans',
            'venue_address' => 'Circuit de la Sarthe, Le Mans',
            'city' => 'Le Mans',
            'country' => 'France',
            'event_date' => '2026-06-05',
            'event_time' => '09:00:00',
            'end_date' => '2026-06-14',
            'end_time' => '17:00:00',
            'organizer' => 'ACO / Carre Premium',
            'cover_image' => 'public/catalog/event-covers/les-24h-du-mans-2026.jpg',
            'packages' => [
                [
                    'name_fr' => 'Starter 24',
                    'code' => 'LM24-STARTER',
                    'description_fr' => 'Espace convivial au coeur de la fan zone Karting avec terrasse panoramique sur le virage Corvette et espace divertissement.',
                    'venue_details_fr' => 'Fan zone Karting / virage Corvette',
                    'included_fr' => $lines([
                        'Acces au receptif avec terrasse vue sur piste',
                        'Prestation traiteur et open bar servi en terrasse',
                        'Acces enceinte generale et place en tribune',
                        'Acces Grande Roue',
                        'Acces a l’espace divertissement: simulateurs, flechettes, billard',
                    ]),
                    'minimum_quantity' => 1,
                    'max_per_order' => 6,
                    'options' => [
                        $option('Starter 24 - Mercredi', 410000, '2026-06-10', null, 30, 6),
                        $option('Starter 24 - Jeudi', 410000, '2026-06-11', null, 30, 6),
                        $option('Starter 24 - Essais + course', 1315000, '2026-06-13', null, 24, 4),
                    ],
                ],
                [
                    'name_fr' => 'Pavillon 24',
                    'code' => 'LM24-PAVILLON',
                    'description_fr' => 'Espace prive au coeur du village avec ambiance guinguette, animations culinaires et musicales et vue privilegiee sur la ceremonie de depart.',
                    'venue_details_fr' => 'Village du circuit',
                    'included_fr' => $lines([
                        'Receptif au coeur du village',
                        'Prestation traiteur et open bar champagne inclus',
                        'Animations culinaires et musicales',
                        'Acces paddocks',
                        'Place en tribune avec vue privilegiee sur la ceremonie de depart',
                    ]),
                    'minimum_quantity' => 1,
                    'max_per_order' => 6,
                    'options' => [
                        $option('Pavillon 24 - Mercredi', 475000, '2026-06-10', null, 30, 6),
                        $option('Pavillon 24 - Jeudi', 475000, '2026-06-11', null, 30, 6),
                        $option('Pavillon 24 - Essais + course', 1950000, '2026-06-13', null, 20, 4),
                    ],
                ],
                [
                    'name_fr' => 'Le Mans Spirit Club',
                    'code' => 'LM24-SPIRIT',
                    'description_fr' => 'Terrasse d’exception a quelques pas des paddocks et du batiment des stands, avec vue sur le virage du Raccordement Motul.',
                    'venue_details_fr' => 'A proximite des paddocks et des stands',
                    'included_fr' => $lines([
                        'Receptif avec terrasse vue sur piste',
                        'Prestation traiteur haut de gamme et open bar champagne inclus',
                        'Acces paddocks',
                        'Acces libre aux simulateurs',
                        'Animations musicales',
                    ]),
                    'minimum_quantity' => 1,
                    'max_per_order' => 6,
                    'options' => [
                        $option('Le Mans Spirit Club - Mercredi', 555000, '2026-06-10', null, 30, 6),
                        $option('Le Mans Spirit Club - Jeudi', 555000, '2026-06-11', null, 30, 6),
                        $option('Le Mans Spirit Club - Semaine', 2690000, '2026-06-10', null, 18, 4),
                    ],
                ],
                [
                    'name_fr' => 'Panoramic 24',
                    'code' => 'LM24-PANORAMIC',
                    'description_fr' => 'Salon premium en hauteur face aux garages avec vue panoramique sur la pitlane, la ligne droite des stands et la courbe Dunlop.',
                    'venue_details_fr' => 'Pitlane / ligne droite des stands / courbe Dunlop',
                    'included_fr' => $lines([
                        'Receptif haut de gamme avec vue panoramique sur la piste et la voie des stands',
                        'Vue imprenable sur la ceremonie de depart',
                        'Prestation traiteur haut de gamme et open bar champagne inclus',
                        'Animations musicales et culinaires',
                        'Acces paddocks',
                        'Acces Gridwalk offre semaine uniquement',
                        'Acces a des points de vue exclusifs',
                        'Visite guidee',
                    ]),
                    'minimum_quantity' => 1,
                    'max_per_order' => 6,
                    'options' => [
                        $option('Panoramic 24 - Mercredi', 635000, '2026-06-10', null, 24, 6),
                        $option('Panoramic 24 - Jeudi', 635000, '2026-06-11', null, 24, 6),
                        $option('Panoramic 24 - Semaine + grille de depart', 3180000, '2026-06-10', null, 16, 4),
                    ],
                ],
                [
                    'name_fr' => 'Gold Experience',
                    'code' => 'LM24-GOLD',
                    'description_fr' => 'Experience ultime limitee a 24 places, avec acces a deux espaces receptifs, accompagnement personnalise, helicoptere, bapteme Porsche et acces exclusifs.',
                    'venue_details_fr' => 'Panoramic 24 et loge premium',
                    'included_fr' => $lines([
                        'Acces a deux espaces receptifs Panoramic 24 et loge',
                        'Prestation traiteur haut de gamme et open bar champagne inclus',
                        'Accompagnement personnalise et photographe dedie',
                        'Acces Gridwalk offres semaine et week-end',
                        'Survol du circuit en helicoptere',
                        'Bapteme de piste en Porsche',
                        'Acces exclusifs',
                        'Visite de box',
                        'Visite guidee des coulisses',
                        'Acces a la zone VIP concert',
                        'Acces a des points de vue exclusifs',
                    ]),
                    'minimum_quantity' => 1,
                    'max_per_order' => 4,
                    'options' => [
                        $option('Gold Experience - Mercredi', 2335000, '2026-06-10', null, 8, 4),
                        $option('Gold Experience - Jeudi', 2335000, '2026-06-11', null, 8, 4),
                        $option('Gold Experience - 3 jours + grille de depart', 7235000, '2026-06-12', null, 8, 4),
                    ],
                ],
            ],
        ],
    ],
];
