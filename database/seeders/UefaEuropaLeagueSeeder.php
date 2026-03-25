<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;
use App\Models\EventCategory;
use App\Models\EventType;
use App\Models\EventPackage;
use Carbon\Carbon;

class UefaEuropaLeagueSeeder extends Seeder
{
    public function run(): void
    {
        // Créer catégorie Sport si absente
        $category = EventCategory::firstOrCreate(
            ['name_fr' => 'Sport'],
            ['name_en' => 'Sports', 'is_active' => true]
        );


            ['name_en' => 'Football', 'is_active' => true]
        );

        // Event Finale UEFA Europa League
        $event = Event::updateOrCreate(
            ['slug' => 'finale-uefa-europa-league'],
            [
                'category_id' => $category->id,
                'type_id' => $type->id,
                'title_fr' => 'Finale UEFA Europa League – Istanbul 2026',
                'title_en' => 'UEFA Europa League Final – Istanbul 2026',
                'description_fr' => "Vivez la Finale de l'UEFA Europa League le 20 mai 2026 à Istanbul dans une loge privée Shared Skybox. Trois formules: Platinum, Gold et Silver avec accès hospitality premium 3h avant match, cocktail dinatoire, boissons incluses.",
                'description_en' => "Experience the UEFA Europa League Final on May 20, 2026 in Istanbul in a private Shared Skybox. Three formulas: Platinum, Gold and Silver with premium hospitality access 3h before kick-off.",
                'venue_name' => 'Atatürk Olympic Stadium',
                'venue_address' => 'Yeşilköy, 34149 Bakirköy/İstanbul, Türkiye',
                'city' => 'Istanbul',
                'country' => 'Turquie',
                'event_date' => '2026-05-20',
                'event_time' => '20:00',
                'min_price' => 675000,
                'max_price' => 975000,
                'total_seats' => 1000,
                'available_seats' => 1000,
                'organizer' => 'UEFA / Carré Premium',
                'is_featured' => true,
                'is_active' => true,
            ]
        );

        // Packages Skybox (PDF grille)
        $packages = [
            [
                'package_name_fr' => 'Platinum Skybox',
                'package_code' => 'UEFA-PLAT-26',
                'description_fr' => 'Emplacement central, meilleure visibilité, loge privée VIP exclusive',
                'description_included_fr' => 'Hospitality 3h avant, cocktail dinatoire premium, boissons illimitées',
                'price' => 975000,
                'available_quantity' => 50,
                'sort_order' => 1,
            ],
            [
                'package_name_fr' => 'Gold Skybox',
                'package_code' => 'UEFA-GOLD-26',
                'description_fr' => 'Vue panoramique, loge privée premium, accès hospitality VIP',
                'description_included_fr' => 'Hospitality 3h avant, cocktail dinatoire, vins/bières inclus',
                'price' => 825000,
                'available_quantity' => 100,
                'sort_order' => 2,
            ],
            [
                'package_name_fr' => 'Silver Skybox',
                'package_code' => 'UEFA-SILV-26',
                'description_fr' => 'Vue immersive, loge privée, expérience VIP accessible',
                'description_included_fr' => 'Accès hospitality, restauration/boissons selon formule',
                'price' => 675000,
                'available_quantity' => 200,
                'sort_order' => 3,
            ],
        ];

        EventPackage::where('event_id', $event->id)->delete(); // Clear old

        foreach ($packages as $data) {
            $event->packages()->create(array_merge($data, [
                'event_id' => $event->id,
                'is_active' => true,
                'currency' => 'XOF',
                'max_per_order' => 10,
            ]));
        }

        $this->command->info('✅ UEFA Europa League Event + 3 Skybox Packages créés!');
        $this->command->info("Slug: finale-uefa-europa-league");
        $this->command->info("Page: /events/finale-uefa-europa-league");
    }
}

