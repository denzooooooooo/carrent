<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\EventCategory;
use App\Models\EventPackage;
use App\Models\EventSeries;
use App\Models\EventType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class EventGrilleTarifaireSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Starting event import from grille tarifaire...');

        // Get existing event categories
        $sportCategory = EventCategory::where('name_fr', 'Sport')->first();
        $musicCategory = EventCategory::where('name_fr', 'Musique')->first();

        if (!$sportCategory || !$musicCategory) {
            $this->command->error('Categories not found! Please run EventCategorySeeder first.');
            return;
        }

        // Create event types with category_id
        $worldCupType = EventType::firstOrCreate(
            ['slug' => 'world-cup'],
            [
                'category_id' => $sportCategory->id,
                'name_fr' => 'Coupe du Monde',
                'name_en' => 'World Cup',
                'is_active' => true
            ]
        );

        $concertType = EventType::firstOrCreate(
            ['slug' => 'concert'],
            [
                'category_id' => $musicCategory->id,
                'name_fr' => 'Concert',
                'name_en' => 'Concert',
                'is_active' => true
            ]
        );

        // Import Coupe du Monde 2026
        $this->importCoupeDuMonde($sportCategory, $worldCupType);

        // Import Concerts
        $this->importConcerts($musicCategory, $concertType);

        $this->command->info('Event import completed!');
    }

    private function importCoupeDuMonde($category, $type)
    {
        $this->command->info('Importing Coupe du Monde 2026...');

        // Data from Excel - Coupe du Monde 2026
        $cities = [
            [
                'city' => 'San Francisco',
                'venue' => 'Levi\'s Stadium, Santa Clara',
                'country' => 'USA',
                'date' => '2026-06-13',
                'packages' => [
                    ['name' => 'Single Match Pitchside Lounge Standard', 'price' => 2154530, 'description' => 'Places haut de gamme le long de la ligne de touche'],
                    ['name' => 'Single Match Pitchside Lounge Premium', 'price' => 2500000, 'description' => 'Package premium avec services additionnels'],
                    ['name' => 'VIP Standard', 'price' => 1876055, 'description' => 'Places surélevées avec services VIP'],
                    ['name' => 'VIP Premium', 'price' => 3200000, 'description' => 'Package VIP premium avec accès special'],
                ]
            ],
            [
                'city' => 'Los Angeles',
                'venue' => 'SoFi Stadium, Los Angeles',
                'country' => 'USA',
                'date' => '2026-06-15',
                'packages' => [
                    ['name' => 'Single Match Pitchside Lounge Standard', 'price' => 2131300, 'description' => 'Places haut de gamme le long de la ligne de touche'],
                    ['name' => 'Single Match Pitchside Lounge Premium', 'price' => 2620700, 'description' => 'Package premium avec services additionnels'],
                    ['name' => 'VIP Standard', 'price' => 1876055, 'description' => 'Places surélevées avec services VIP'],
                ]
            ],
            [
                'city' => 'Seattle',
                'venue' => 'Lumen Field, Seattle',
                'country' => 'USA',
                'date' => '2026-06-15',
                'packages' => [
                    ['name' => 'Single Match Pitchside Lounge Standard', 'price' => 2543625, 'description' => 'Places haut de gamme le long de la ligne de touche'],
                    ['name' => 'VIP Standard', 'price' => 2100000, 'description' => 'Places surélevées avec services VIP'],
                ]
            ],
            [
                'city' => 'Atlanta',
                'venue' => 'Mercedes-Benz Stadium, Atlanta',
                'country' => 'USA',
                'date' => '2026-06-15',
                'packages' => [
                    ['name' => 'Single Match Pitchside Lounge Standard', 'price' => 2200000, 'description' => 'Places haut de gamme le long de la ligne de touche'],
                    ['name' => 'VIP Standard', 'price' => 1850000, 'description' => 'Places surélevées avec services VIP'],
                ]
            ],
            [
                'city' => 'Boston',
                'venue' => 'Gillette Stadium, Foxborough',
                'country' => 'USA',
                'date' => '2026-06-13',
                'packages' => [
                    ['name' => 'Single Match Pitchside Lounge Standard', 'price' => 2138570, 'description' => 'Places haut de gamme le long de la ligne de touche'],
                    ['name' => 'Single Match Pitchside Lounge Premium', 'price' => 2298150, 'description' => 'Package premium avec services additionnels'],
                    ['name' => 'VIP Standard', 'price' => 1819145, 'description' => 'Places surélevées avec services VIP'],
                ]
            ],
            [
                'city' => 'Miami',
                'venue' => 'Hard Rock Stadium, Miami Gardens',
                'country' => 'USA',
                'date' => '2026-06-15',
                'packages' => [
                    ['name' => 'Single Match Pitchside Lounge Standard', 'price' => 2244890, 'description' => 'Places haut de gamme le long de la ligne de touche'],
                    ['name' => 'Single Match Pitchside Lounge Premium', 'price' => 2489545, 'description' => 'Package premium avec services additionnels'],
                    ['name' => 'VIP Standard', 'price' => 1787800, 'description' => 'Places surélevées avec services VIP'],
                ]
            ],
        ];

        // Create the main series for Coupe du Monde
        $series = EventSeries::firstOrCreate(
            ['slug' => 'coupe-du-monde-2026'],
            [
                'name_fr' => 'Coupe du Monde FIFA 2026 - États-Unis',
                'name_en' => 'FIFA World Cup 2026 - United States',
                'description_fr' => 'La première Coupe du Monde à se tenir conjointement entre trois pays: États-Unis, Canada et Mexique. Venez vivre l\'événement le plus attendu au monde!',
                'description_en' => 'The first World Cup to be hosted jointly by three countries: United States, Canada and Mexico. Experience the world\'s most anticipated event!',
                'venue_name' => 'Multiple Venues',
                'city' => 'Various Cities',
                'country' => 'USA',
                'start_date' => '2026-06-11',
                'end_date' => '2026-07-19',
                'organizer' => 'FIFA',
                'sport_type' => 'Football',
                'is_featured' => true,
                'is_active' => true,
            ]
        );

        foreach ($cities as $cityData) {
            // Create event for each city
            $event = Event::firstOrCreate(
                ['slug' => Str::slug('coupe-du-monde-2026-' . $cityData['city'])],
                [
                    'category_id' => $category->id,
                    'type_id' => $type->id,
                    'event_series_id' => $series->id,
                    'title_fr' => 'Coupe du Monde 2026 - ' . $cityData['city'],
                    'title_en' => 'World Cup 2026 - ' . $cityData['city'],
                    'description_fr' => 'Match de la Coupe du Monde FIFA 2026 à ' . $cityData['city'],
                    'description_en' => 'FIFA World Cup 2026 match in ' . $cityData['city'],
                    'venue_name' => $cityData['venue'],
                    'city' => $cityData['city'],
                    'country' => $cityData['country'],
                    'event_date' => $cityData['date'],
'event_time' => '21:00',
                    'min_price' => collect($cityData['packages'])->min('price'),
                    'max_price' => collect($cityData['packages'])->max('price'),
                    'is_featured' => true,
                    'is_active' => true,
                ]
            );

            // Create packages for each event
            foreach ($cityData['packages'] as $index => $package) {
                EventPackage::firstOrCreate(
                    [
                        'event_id' => $event->id,
                        'package_code' => 'CDM2026-' . Str::slug($cityData['city']) . '-' . ($index + 1),
                    ],
                    [
                        'package_name_fr' => $package['name'],
                        'package_name_en' => $package['name'],
                        'description_fr' => $package['description'],
                        'description_included_fr' => 'Accès au match, Services hospitality, Boissons incluses, Cadeau souvenir',
                        'price' => $package['price'],
                        'currency' => 'XOF',
                        'available_quantity' => 100,
                        'max_per_order' => 10,
                        'is_active' => true,
                        'sort_order' => $index + 1,
                    ]
                );
            }
        }

        $this->command->info('Coupe du Monde 2026 imported successfully!');
    }

    private function importConcerts($category, $type)
    {
        $this->command->info('Importing Concerts...');

        $exchangeRate = 655.957; // 1 EUR = 655.957 XOF

        // Data from Excel - Concerts Paris Accor Arena
        $concerts = [
            [
                'name' => 'KATY PERRY',
                'date' => '2025-11-04',
                'venue' => 'Accor Arena, Paris',
                'country' => 'France',
                'packages' => [
                    ['name' => 'VIP1 - INFINITY CIRCLE VIP PACKAGE', 'price_eur' => 364.50, 'description' => ' billet en fosse or debout, produit dérivé VIP, laminé commemoratif'],
                    ['name' => 'VIP2 - LIFETIMES FRONT OF STAGE VIP PACKAGE', 'price_eur' => 309.50, 'description' => 'billet en fosse or debout, produit derive VIP, accesoires'],
                ]
            ],
            [
                'name' => 'ONE REPUBLIC',
                'date' => '2025-10-07',
                'venue' => 'Accor Arena, Paris',
                'country' => 'France',
                'packages' => [
                    ['name' => 'VIP - EXPERIENCE DU SALON VIP', 'price_eur' => 304.00, 'description' => 'billet premium, acces salon VIP, cadeau VIP'],
                    ['name' => 'VIP - FORFAIT VIP ENTREE ANTICIPEE', 'price_eur' => 172.00, 'description' => 'billet Gold Circle, entree anticipée, laminé VIP'],
                ]
            ],
            [
                'name' => 'STING',
                'date' => '2025-10-09',
                'venue' => 'Accor Arena, Paris',
                'country' => 'France',
                'packages' => [
                    ['name' => 'PLATINUM 1', 'price_eur' => 555.00, 'description' => 'Package Platinum'],
                    ['name' => 'PLATINUM 2', 'price_eur' => 545.00, 'description' => 'Package Platinum'],
                ]
            ],
            [
                'name' => 'M. POKORA',
                'date' => '2025-11-15',
                'venue' => 'Accor Arena, Paris',
                'country' => 'France',
                'packages' => [
                    ['name' => 'VIP PACKAGE', 'price_eur' => 250.00, 'description' => 'Package VIP special'],
                ]
            ],
            [
                'name' => 'AMIR',
                'date' => '2025-11-20',
                'venue' => 'Accor Arena, Paris',
                'country' => 'France',
                'packages' => [
                    ['name' => 'VIP PACKAGE', 'price_eur' => 220.00, 'description' => 'Package VIP special'],
                ]
            ],
            [
                'name' => 'SOPRANO',
                'date' => '2025-11-28',
                'venue' => 'Accor Arena, Paris',
                'country' => 'France',
                'packages' => [
                    ['name' => 'VIP PACKAGE', 'price_eur' => 200.00, 'description' => 'Package VIP special'],
                ]
            ],
            [
                'name' => 'DINOS',
                'date' => '2025-12-05',
                'venue' => 'Accor Arena, Paris',
                'country' => 'France',
                'packages' => [
                    ['name' => 'VIP PACKAGE', 'price_eur' => 180.00, 'description' => 'Package VIP special'],
                ]
            ],
            [
                'name' => 'KENDJI GIRAC',
                'date' => '2026-03-20',
                'venue' => 'Accor Arena, Paris',
                'country' => 'France',
                'packages' => [
                    ['name' => 'VIP PACKAGE', 'price_eur' => 190.00, 'description' => 'Package VIP special'],
                ]
            ],
            [
                'name' => 'INDOCHINE',
                'date' => '2026-06-12',
                'venue' => 'Accor Arena, Paris',
                'country' => 'France',
                'packages' => [
                    ['name' => 'VIP PACKAGE', 'price_eur' => 280.00, 'description' => 'Package VIP special'],
                ]
            ],
            [
                'name' => 'NEJ',
                'date' => '2026-04-10',
                'venue' => 'Accor Arena, Paris',
                'country' => 'France',
                'packages' => [
                    ['name' => 'VIP PACKAGE', 'price_eur' => 150.00, 'description' => 'Package VIP special'],
                ]
            ],
            [
                'name' => 'WILL SMITH',
                'date' => '2025-11-10',
                'venue' => 'Accor Arena, Paris',
                'country' => 'France',
                'packages' => [
                    ['name' => 'VIP PACKAGE', 'price_eur' => 350.00, 'description' => 'Package VIP special'],
                ]
            ],
            [
                'name' => 'YSEULT',
                'date' => '2025-11-22',
                'venue' => 'Accor Arena, Paris',
                'country' => 'France',
                'packages' => [
                    ['name' => 'VIP PACKAGE', 'price_eur' => 160.00, 'description' => 'Package VIP special'],
                ]
            ],
            [
                'name' => 'PIT BACCARDI',
                'date' => '2025-11-30',
                'venue' => 'Accor Arena, Paris',
                'country' => 'France',
                'packages' => [
                    ['name' => 'VIP PACKAGE', 'price_eur' => 175.00, 'description' => 'Package VIP special'],
                ]
            ],
            [
                'name' => 'GAD ELMALEH',
                'date' => '2025-12-18',
                'venue' => 'Accor Arena, Paris',
                'country' => 'France',
                'packages' => [
                    ['name' => 'VIP PACKAGE', 'price_eur' => 200.00, 'description' => 'Package VIP special'],
                ]
            ],
            [
                'name' => 'ANGELIQUE KIDJO',
                'date' => '2025-10-25',
                'venue' => 'Accor Arena, Paris',
                'country' => 'France',
                'packages' => [
                    ['name' => 'VIP PACKAGE', 'price_eur' => 180.00, 'description' => 'Package VIP special'],
                ]
            ],
            [
                'name' => 'VYBZ KARTEL',
                'date' => '2025-10-15',
                'venue' => 'Accor Arena, Paris',
                'country' => 'France',
                'packages' => [
                    ['name' => 'VIP PACKAGE', 'price_eur' => 165.00, 'description' => 'Package VIP special'],
                ]
            ],
        ];

        foreach ($concerts as $concertData) {
            // Create event for each concert
            $event = Event::firstOrCreate(
                ['slug' => Str::slug($concertData['name'] . '-paris-' . $concertData['date'])],
                [
                    'category_id' => $category->id,
                    'type_id' => $type->id,
                    'title_fr' => 'Concert ' . $concertData['name'] . ' - Paris',
                    'title_en' => 'Concert ' . $concertData['name'] . ' - Paris',
                    'description_fr' => 'Concert de ' . $concertData['name'] . ' à l\'Accor Arena de Paris',
                    'description_en' => $concertData['name'] . ' concert at Accor Arena Paris',
                    'venue_name' => $concertData['venue'],
                    'city' => 'Paris',
                    'country' => $concertData['country'],
                    'event_date' => $concertData['date'],
                    'event_time' => '20:00',
                    'min_price' => round(collect($concertData['packages'])->min('price_eur') * $exchangeRate),
                    'max_price' => round(collect($concertData['packages'])->max('price_eur') * $exchangeRate),
                    'is_featured' => true,
                    'is_active' => true,
                ]
            );

            // Create packages for each concert
            foreach ($concertData['packages'] as $index => $package) {
                $priceXof = round($package['price_eur'] * $exchangeRate);
                
                EventPackage::firstOrCreate(
                    [
                        'event_id' => $event->id,
                        'package_code' => 'CONCERT-' . Str::slug($concertData['name']) . '-' . ($index + 1),
                    ],
                    [
                        'package_name_fr' => $package['name'],
                        'package_name_en' => $package['name'],
                        'description_fr' => $package['description'],
                        'description_included_fr' => 'Billet d\'accès, Accès VIP, Cadeau souvenir',
                        'price' => $priceXof,
                        'currency' => 'XOF',
                        'available_quantity' => 50,
                        'max_per_order' => 6,
                        'is_active' => true,
                        'sort_order' => $index + 1,
                    ]
                );
            }
        }

        $this->command->info('Concerts imported successfully!');
    }
}

