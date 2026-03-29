<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\EventCategory;
use App\Models\EventType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tests\TestCase;

class EventListingTest extends TestCase
{
    use RefreshDatabase;

    public function test_see_all_displays_sports_and_cultural_events(): void
    {
        $catalog = $this->seedEventCatalog();

        $sportEvent = $this->createEvent([
            'category_id' => $catalog['sport']['category_id'],
            'type_id' => $catalog['sport']['type_id'],
            'title_fr' => 'Finale de football',
            'title_en' => 'Football Final',
            'slug' => 'finale-football',
            'family' => 'sportif',
        ]);

        $culturalEvent = $this->createEvent([
            'category_id' => $catalog['culture']['category_id'],
            'type_id' => $catalog['culture']['type_id'],
            'title_fr' => 'Grand concert live',
            'title_en' => 'Live Concert',
            'slug' => 'grand-concert-live',
            'family' => 'culturel',
        ]);

        $response = $this->get('/events?family=all');

        $response->assertOk();
        $response->assertSee($sportEvent->title_fr);
        $response->assertSee($culturalEvent->title_fr);
    }

    public function test_family_filter_only_returns_matching_events(): void
    {
        $catalog = $this->seedEventCatalog();

        $sportEvent = $this->createEvent([
            'category_id' => $catalog['sport']['category_id'],
            'type_id' => $catalog['sport']['type_id'],
            'title_fr' => 'Match de gala',
            'slug' => 'match-de-gala',
            'family' => 'sportif',
        ]);

        $culturalEvent = $this->createEvent([
            'category_id' => $catalog['culture']['category_id'],
            'type_id' => $catalog['culture']['type_id'],
            'title_fr' => 'Festival des arts',
            'slug' => 'festival-des-arts',
            'family' => 'culturel',
        ]);

        $response = $this->get('/events?family=sportif');

        $response->assertOk();
        $response->assertSee($sportEvent->title_fr);
        $response->assertDontSee($culturalEvent->title_fr);
    }

    public function test_search_and_featured_filter_are_applied_together(): void
    {
        $catalog = $this->seedEventCatalog();

        $featuredConcert = $this->createEvent([
            'category_id' => $catalog['culture']['category_id'],
            'type_id' => $catalog['culture']['type_id'],
            'title_fr' => 'Classic night premium',
            'title_en' => 'Classic night premium',
            'slug' => 'classic-night-premium',
            'family' => 'culturel',
            'is_featured' => true,
        ]);

        $this->createEvent([
            'category_id' => $catalog['culture']['category_id'],
            'type_id' => $catalog['culture']['type_id'],
            'title_fr' => 'Classic session standard',
            'title_en' => 'Classic session standard',
            'slug' => 'classic-session-standard',
            'family' => 'culturel',
            'is_featured' => false,
        ]);

        $response = $this->get('/events?q=classic&featured=1');

        $response->assertOk();
        $response->assertSee($featuredConcert->title_fr);
        $response->assertDontSee('Classic session standard');
    }

    private function seedEventCatalog(): array
    {
        DB::table('categories')->insert([
            [
                'id' => 1,
                'name_fr' => 'Sport',
                'name_en' => 'Sport',
                'slug' => 'sport',
                'description_fr' => 'Sport',
                'description_en' => 'Sport',
                'icon' => null,
                'image' => null,
                'parent_id' => null,
                'order_position' => 1,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'name_fr' => 'Culture',
                'name_en' => 'Culture',
                'slug' => 'culture',
                'description_fr' => 'Culture',
                'description_en' => 'Culture',
                'icon' => null,
                'image' => null,
                'parent_id' => null,
                'order_position' => 2,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        EventCategory::insert([
            [
                'id' => 1,
                'name_fr' => 'Sport',
                'name_en' => 'Sport',
                'slug' => 'sport',
                'description' => 'Sport',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'name_fr' => 'Culture',
                'name_en' => 'Culture',
                'slug' => 'culture',
                'description' => 'Culture',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        EventType::insert([
            [
                'id' => 1,
                'category_id' => 1,
                'name_fr' => 'Football',
                'name_en' => 'Football',
                'slug' => 'football',
                'description' => 'Football',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'category_id' => 2,
                'name_fr' => 'Concert',
                'name_en' => 'Concert',
                'slug' => 'concert',
                'description' => 'Concert',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        return [
            'sport' => ['category_id' => 1, 'type_id' => 1],
            'culture' => ['category_id' => 2, 'type_id' => 2],
        ];
    }

    private function createEvent(array $overrides = []): Event
    {
        $title = $overrides['title_fr'] ?? 'Event ' . Str::random(6);

        return Event::create(array_merge([
            'category_id' => 1,
            'type_id' => 1,
            'title_fr' => $title,
            'title_en' => $overrides['title_en'] ?? $title,
            'slug' => $overrides['slug'] ?? Str::slug($title . '-' . Str::lower(Str::random(5))),
            'family' => $overrides['family'] ?? 'sportif',
            'description_fr' => 'Description de test',
            'description_en' => 'Test description',
            'venue_name' => 'Palais des Sports',
            'venue_address' => 'Abidjan',
            'city' => 'Abidjan',
            'country' => 'Côte d\'Ivoire',
            'event_date' => now()->addDays(10)->toDateString(),
            'event_time' => '20:00',
            'organizer' => 'Carré Premium',
            'min_price' => 15000,
            'max_price' => 45000,
            'total_seats' => 200,
            'available_seats' => 150,
            'is_featured' => false,
            'is_active' => true,
        ], $overrides));
    }
}
