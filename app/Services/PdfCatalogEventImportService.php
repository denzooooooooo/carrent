<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Event;
use App\Models\EventBooking;
use App\Models\EventCategory;
use App\Models\EventPackage;
use App\Models\EventPackageOption;
use App\Models\EventSeries;
use App\Models\EventType;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class PdfCatalogEventImportService
{
    public function import(): array
    {
        $catalog = require base_path('database/data/pdf_event_catalog.php');

        return DB::transaction(function () use ($catalog) {
            $this->purgeExistingEventData();

            $categories = $this->syncCategories($catalog['categories'] ?? []);
            $types = $this->syncTypes($catalog['types'] ?? [], $categories);
            $seriesMap = $this->syncSeries($catalog['series'] ?? []);

            $summary = [
                'events' => 0,
                'packages' => 0,
                'options' => 0,
            ];

            foreach ($catalog['events'] ?? [] as $eventData) {
                [$packageCount, $optionCount] = $this->createEvent($eventData, $categories, $types, $seriesMap);
                $summary['events']++;
                $summary['packages'] += $packageCount;
                $summary['options'] += $optionCount;
            }

            return $summary;
        });
    }

    protected function purgeExistingEventData(): void
    {
        if (Schema::hasTable('bookings')) {
            Booking::query()->where('booking_type', 'event')->delete();
        }

        if (Schema::hasTable('event_bookings')) {
            EventBooking::query()->delete();
        }

        if (Schema::hasTable('event_packages')) {
            EventPackage::query()->get()->each(function (EventPackage $package) {
                $package->clearMediaCollection('image');
            });
        }

        if (Schema::hasTable('events')) {
            Event::query()->get()->each(function (Event $event) {
                $event->clearMediaCollection('avatar');
            });
        }

        if (Schema::hasTable('event_series')) {
            EventSeries::query()->get()->each(function (EventSeries $series) {
                $series->clearMediaCollection('main_image');
                $series->clearMediaCollection('cover_image');
            });
        }

        if (Schema::hasTable('event_package_options')) {
            EventPackageOption::query()->delete();
        }

        if (Schema::hasTable('event_packages')) {
            EventPackage::query()->delete();
        }

        if (Schema::hasTable('events')) {
            Event::query()->delete();
        }

        if (Schema::hasTable('event_series')) {
            EventSeries::query()->delete();
        }
    }

    protected function syncCategories(array $categories): array
    {
        $map = [];

        foreach ($categories as $slug => $data) {
            $category = EventCategory::query()->updateOrCreate(
                ['slug' => $slug],
                [
                    'name_fr' => $data['name_fr'],
                    'name_en' => $data['name_en'] ?? $data['name_fr'],
                    'description' => $data['description'] ?? null,
                    'is_active' => $data['is_active'] ?? true,
                ]
            );

            $map[$slug] = $category;
        }

        return $map;
    }

    protected function syncTypes(array $types, array $categories): array
    {
        $map = [];

        foreach ($types as $slug => $data) {
            $category = $categories[$data['category_slug']] ?? null;

            if (!$category) {
                throw new \RuntimeException("Catégorie introuvable pour le type {$slug}.");
            }

            $type = EventType::query()->updateOrCreate(
                ['slug' => $slug],
                [
                    'category_id' => $category->id,
                    'name_fr' => $data['name_fr'],
                    'name_en' => $data['name_en'] ?? $data['name_fr'],
                    'description' => $data['description'] ?? null,
                    'is_active' => $data['is_active'] ?? true,
                ]
            );

            $map[$slug] = $type;
        }

        return $map;
    }

    protected function syncSeries(array $series): array
    {
        $map = [];

        foreach ($series as $slug => $data) {
            $record = EventSeries::query()->updateOrCreate(
                ['slug' => $slug],
                [
                    'name_fr' => $data['name_fr'],
                    'name_en' => $data['name_en'] ?? $data['name_fr'],
                    'description_fr' => $data['description_fr'] ?? null,
                    'description_en' => $data['description_en'] ?? ($data['description_fr'] ?? null),
                    'venue_name' => $data['venue_name'] ?? null,
                    'venue_address' => $data['venue_address'] ?? null,
                    'city' => $data['city'] ?? null,
                    'country' => $data['country'] ?? null,
                    'start_date' => $data['start_date'] ?? null,
                    'end_date' => $data['end_date'] ?? null,
                    'organizer' => $data['organizer'] ?? null,
                    'sport_type' => $data['sport_type'] ?? null,
                    'is_featured' => $data['is_featured'] ?? true,
                    'is_active' => $data['is_active'] ?? true,
                ]
            );

            $this->attachMediaIfPresent($record, 'main_image', $data['cover_image'] ?? null);
            $this->attachMediaIfPresent($record, 'cover_image', $data['cover_image'] ?? null);

            $map[$slug] = $record;
        }

        return $map;
    }

    protected function createEvent(array $data, array $categories, array $types, array $seriesMap): array
    {
        $category = $categories[$data['category_slug']] ?? null;
        $type = $types[$data['type_slug']] ?? null;
        $series = filled($data['series_slug'] ?? null) ? ($seriesMap[$data['series_slug']] ?? null) : null;

        if (!$category || !$type) {
            throw new \RuntimeException('Impossible de créer un événement sans catégorie et type valides.');
        }

        $event = Event::query()->create([
            'category_id' => $category->id,
            'type_id' => $type->id,
            'event_series_id' => $series?->id,
            'slug' => $data['slug'] ?? Str::slug($data['title_fr']),
            'family' => $data['family'] ?? 'sportif',
            'title_fr' => $data['title_fr'],
            'title_en' => $data['title_en'] ?? $data['title_fr'],
            'tagline_fr' => $data['tagline_fr'] ?? null,
            'tagline_en' => $data['tagline_en'] ?? ($data['tagline_fr'] ?? null),
            'description_fr' => $data['description_fr'] ?? null,
            'description_en' => $data['description_en'] ?? ($data['description_fr'] ?? null),
            'program_fr' => $data['program_fr'] ?? null,
            'program_en' => $data['program_en'] ?? ($data['program_fr'] ?? null),
            'conditions_fr' => $data['conditions_fr'] ?? null,
            'conditions_en' => $data['conditions_en'] ?? ($data['conditions_fr'] ?? null),
            'source_catalog' => $data['source_catalog'] ?? null,
            'venue_name' => $data['venue_name'],
            'venue_address' => $data['venue_address'] ?? null,
            'city' => $data['city'],
            'country' => $data['country'],
            'event_date' => $data['event_date'],
            'event_time' => $data['event_time'] ?? '10:00:00',
            'end_date' => $data['end_date'] ?? null,
            'end_time' => $data['end_time'] ?? null,
            'organizer' => $data['organizer'] ?? null,
            'min_price' => 0,
            'max_price' => 0,
            'total_seats' => 0,
            'available_seats' => 0,
            'is_featured' => $data['is_featured'] ?? true,
            'is_active' => $data['is_active'] ?? true,
            'meta_title_fr' => $data['meta_title_fr'] ?? $data['title_fr'],
            'meta_title_en' => $data['meta_title_en'] ?? ($data['title_en'] ?? $data['title_fr']),
            'meta_description_fr' => $data['meta_description_fr'] ?? Str::limit(strip_tags((string) ($data['description_fr'] ?? '')), 150),
            'meta_description_en' => $data['meta_description_en'] ?? Str::limit(strip_tags((string) ($data['description_en'] ?? $data['description_fr'] ?? '')), 150),
        ]);

        $this->attachMediaIfPresent($event, 'avatar', $data['cover_image'] ?? null);

        $packageCount = 0;
        $optionCount = 0;
        $allPrices = [];
        $totalAvailability = 0;

        foreach ($data['packages'] ?? [] as $index => $packageData) {
            $packageCount++;

            $packagePayload = $this->normalizePackagePayload($packageData);
            $package = $event->allPackages()->create([
                'package_name_fr' => $packagePayload['package_name_fr'],
                'package_name_en' => $packagePayload['package_name_en'],
                'package_code' => $packagePayload['package_code'],
                'description_fr' => $packagePayload['description_fr'],
                'venue_details_fr' => $packagePayload['venue_details_fr'],
                'venue_details_en' => $packagePayload['venue_details_en'],
                'description_included_fr' => $packagePayload['description_included_fr'],
                'description_included_en' => $packagePayload['description_included_en'],
                'price' => $packagePayload['price'],
                'currency' => $packagePayload['currency'],
                'minimum_quantity' => $packagePayload['minimum_quantity'],
                'available_quantity' => $packagePayload['available_quantity'],
                'max_per_order' => $packagePayload['max_per_order'],
                'is_active' => $packagePayload['is_active'],
                'sort_order' => $packagePayload['sort_order'] ?? ($index + 1),
            ]);

            $this->attachMediaIfPresent($package, 'image', $packagePayload['image'] ?? null);

            foreach ($packagePayload['options'] as $optionIndex => $optionData) {
                $package->allOptions()->create([
                    'option_label_fr' => $optionData['option_label_fr'],
                    'option_label_en' => $optionData['option_label_en'],
                    'option_context_fr' => $optionData['option_context_fr'],
                    'option_context_en' => $optionData['option_context_en'],
                    'option_date' => $optionData['option_date'],
                    'price' => $optionData['price'],
                    'currency' => $optionData['currency'],
                    'available_quantity' => $optionData['available_quantity'],
                    'max_per_order' => $optionData['max_per_order'],
                    'is_active' => $optionData['is_active'],
                    'sort_order' => $optionData['sort_order'] ?? ($optionIndex + 1),
                ]);

                $allPrices[] = $optionData['price'];
                $optionCount++;
            }

            if ($packagePayload['options'] === []) {
                $allPrices[] = $packagePayload['price'];
            }

            $totalAvailability += $packagePayload['available_quantity'];
        }

        $event->update([
            'min_price' => $allPrices === [] ? 0 : min($allPrices),
            'max_price' => $allPrices === [] ? 0 : max($allPrices),
            'total_seats' => $data['total_seats'] ?? $totalAvailability,
            'available_seats' => $data['available_seats'] ?? $totalAvailability,
        ]);

        return [$packageCount, $optionCount];
    }

    protected function normalizePackagePayload(array $data): array
    {
        $options = [];
        $prices = [];
        $totalAvailable = 0;

        foreach ($data['options'] ?? [] as $option) {
            $normalized = [
                'option_label_fr' => $option['label_fr'],
                'option_label_en' => $option['label_en'] ?? $option['label_fr'],
                'option_context_fr' => $option['context_fr'] ?? null,
                'option_context_en' => $option['context_en'] ?? ($option['context_fr'] ?? null),
                'option_date' => $option['date'] ?? null,
                'price' => $option['price'],
                'currency' => $option['currency'] ?? ($data['currency'] ?? 'XOF'),
                'available_quantity' => $option['available_quantity'] ?? ($data['default_option_quantity'] ?? 24),
                'max_per_order' => $option['max_per_order'] ?? ($data['max_per_order'] ?? 6),
                'is_active' => $option['is_active'] ?? true,
                'sort_order' => $option['sort_order'] ?? null,
            ];

            $options[] = $normalized;
            $prices[] = $normalized['price'];
            $totalAvailable += $normalized['available_quantity'];
        }

        if ($prices === []) {
            $prices[] = $data['price'];
            $totalAvailable = $data['available_quantity'] ?? 100;
        }

        return [
            'package_name_fr' => $data['name_fr'],
            'package_name_en' => $data['name_en'] ?? $data['name_fr'],
            'package_code' => $data['code'] ?? Str::upper(Str::slug($data['name_fr'], '-')),
            'description_fr' => $data['description_fr'] ?? null,
            'venue_details_fr' => $data['venue_details_fr'] ?? null,
            'venue_details_en' => $data['venue_details_en'] ?? ($data['venue_details_fr'] ?? null),
            'description_included_fr' => $data['included_fr'] ?? null,
            'description_included_en' => $data['included_en'] ?? ($data['included_fr'] ?? null),
            'price' => $data['price'] ?? min($prices),
            'currency' => $data['currency'] ?? 'XOF',
            'minimum_quantity' => max(1, (int) ($data['minimum_quantity'] ?? 1)),
            'available_quantity' => $data['available_quantity'] ?? $totalAvailable,
            'max_per_order' => $data['max_per_order'] ?? 6,
            'is_active' => $data['is_active'] ?? true,
            'sort_order' => $data['sort_order'] ?? null,
            'image' => $data['image'] ?? null,
            'options' => $options,
        ];
    }

    protected function attachMediaIfPresent($model, string $collection, ?string $path): void
    {
        if (blank($path)) {
            return;
        }

        $absolutePath = str_starts_with($path, '/')
            ? $path
            : base_path($path);

        if (!File::exists($absolutePath)) {
            return;
        }

        $model->clearMediaCollection($collection);
        $model->addMedia($absolutePath)
            ->preservingOriginal()
            ->toMediaCollection($collection);
    }
}
