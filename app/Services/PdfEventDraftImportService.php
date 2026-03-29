<?php

namespace App\Services;

use App\Models\Event;
use App\Models\EventCategory;
use App\Models\EventPackage;
use App\Models\EventType;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use RuntimeException;
use Smalot\PdfParser\Parser;

class PdfEventDraftImportService
{
    public function __construct(
        protected Parser $parser = new Parser()
    ) {
    }

    public function import(UploadedFile $file, array $options = []): array
    {
        $text = trim((string) $this->parser->parseFile($file->getRealPath())->getText());

        if ($text === '') {
            throw new RuntimeException('Le PDF ne contient aucun texte exploitable. Utilisez un catalogue PDF texte et non un scan image brut.');
        }

        $categories = EventCategory::query()->where('is_active', true)->get();
        $types = EventType::query()->where('is_active', true)->get();

        if ($categories->isEmpty() || $types->isEmpty()) {
            throw new RuntimeException('Impossible d’importer un événement sans catégories et types actifs en base.');
        }

        $parsed = $this->parsePayload($text, $file->getClientOriginalName(), $categories, $types, $options);

        return DB::transaction(function () use ($parsed, $file, $options) {
            $event = $this->persistEvent($parsed, $file, $options);

            return [
                'event' => $event,
                'summary' => [
                    'packages_count' => count($parsed['packages']),
                    'warnings' => $parsed['warnings'],
                ],
            ];
        });
    }

    protected function parsePayload(
        string $text,
        string $originalFilename,
        Collection $categories,
        Collection $types,
        array $options
    ): array {
        $normalized = Str::of($text)->ascii()->lower()->value();
        $lines = collect(preg_split('/\R+/', $text))
            ->map(fn ($line) => trim((string) $line))
            ->filter()
            ->values();

        $profile = $this->detectProfile($normalized, $originalFilename);
        [$category, $type] = $this->resolveCategoryAndType($categories, $types, $profile, $normalized, $options);
        [$eventDate, $endDate] = $this->detectDates($normalized);
        $packages = $this->detectPackages($lines);
        $moneyMatches = $this->extractMoneyMatches($normalized);
        $moneyValues = collect($moneyMatches)->pluck('amount')->filter()->values();
        $title = $this->detectTitle($lines, $originalFilename, $profile);
        $description = $this->buildDescription($lines, $title);
        $venue = $options['venue_name'] ?? $profile['venue_name'] ?? $this->detectVenue($lines) ?? 'Lieu à confirmer';
        $city = $options['city'] ?? $profile['city'] ?? $this->detectCity($lines) ?? 'Ville à confirmer';
        $country = $options['country'] ?? $profile['country'] ?? $this->detectCountry($lines) ?? 'Pays à confirmer';

        $warnings = [];

        if (!$eventDate) {
            $eventDate = now()->addMonth()->startOfDay();
            $warnings[] = 'date non détectée automatiquement';
        }

        if (empty($packages)) {
            $warnings[] = 'aucun package fiable détecté, vérifie les tarifs importés';
        }

        if ($city === 'Ville à confirmer' || $country === 'Pays à confirmer') {
            $warnings[] = 'localisation à compléter';
        }

        $prices = collect($packages)->pluck('price')->filter()->values();

        if ($prices->isEmpty() && $moneyValues->isNotEmpty()) {
            $prices = $moneyValues;
        }

        return [
            'category' => $category,
            'type' => $type,
            'family' => $profile['family'] ?? 'sportif',
            'title' => $title,
            'tagline' => $profile['tagline'] ?? Str::limit($description, 140, ''),
            'description' => $description,
            'program' => null,
            'conditions' => null,
            'venue_name' => $venue,
            'venue_address' => $this->detectVenueAddress($lines, $venue, $city, $country),
            'city' => $city,
            'country' => $country,
            'event_date' => $eventDate,
            'end_date' => $endDate,
            'event_time' => $profile['event_time'] ?? '18:00:00',
            'organizer' => $options['organizer'] ?? 'Carré Premium',
            'min_price' => $prices->isNotEmpty() ? (float) $prices->min() : 0,
            'max_price' => $prices->isNotEmpty() ? (float) $prices->max() : 0,
            'packages' => $packages,
            'warnings' => $warnings,
        ];
    }

    protected function persistEvent(array $parsed, UploadedFile $file, array $options): Event
    {
        $slug = $this->makeUniqueSlug(Str::slug($parsed['title']));
        $packageCapacity = collect($parsed['packages'])->sum('available_quantity');

        $event = Event::query()->create([
            'category_id' => $parsed['category']->id,
            'type_id' => $parsed['type']?->id,
            'slug' => $slug,
            'family' => $parsed['family'],
            'title_fr' => $parsed['title'],
            'title_en' => $parsed['title'],
            'tagline_fr' => $parsed['tagline'],
            'tagline_en' => $parsed['tagline'],
            'description_fr' => $parsed['description'],
            'description_en' => $parsed['description'],
            'program_fr' => $parsed['program'],
            'program_en' => $parsed['program'],
            'conditions_fr' => $parsed['conditions'],
            'conditions_en' => $parsed['conditions'],
            'source_catalog' => $file->getClientOriginalName(),
            'venue_name' => $parsed['venue_name'],
            'venue_address' => $parsed['venue_address'],
            'city' => $parsed['city'],
            'country' => $parsed['country'],
            'event_date' => $parsed['event_date']->toDateString(),
            'event_time' => $parsed['event_time'],
            'end_date' => $parsed['end_date']?->toDateString(),
            'end_time' => $parsed['end_date'] ? '23:00:00' : null,
            'organizer' => $parsed['organizer'],
            'min_price' => $parsed['min_price'],
            'max_price' => $parsed['max_price'],
            'total_seats' => max(0, $packageCapacity),
            'available_seats' => max(0, $packageCapacity),
            'is_featured' => (bool) ($options['is_featured'] ?? false),
            'is_active' => (bool) ($options['publish_immediately'] ?? false),
            'meta_title_fr' => $parsed['title'],
            'meta_title_en' => $parsed['title'],
            'meta_description_fr' => Str::limit(strip_tags($parsed['description']), 150),
            'meta_description_en' => Str::limit(strip_tags($parsed['description']), 150),
        ]);

        foreach ($parsed['packages'] as $index => $packageData) {
            $event->allPackages()->create([
                'package_name_fr' => $packageData['package_name_fr'],
                'package_name_en' => $packageData['package_name_en'] ?? $packageData['package_name_fr'],
                'package_code' => $packageData['package_code'] ?? null,
                'description_fr' => $packageData['description_fr'] ?? null,
                'description_included_fr' => $packageData['description_included_fr'] ?? null,
                'price' => $packageData['price'],
                'currency' => $packageData['currency'] ?? 'XOF',
                'minimum_quantity' => $packageData['minimum_quantity'] ?? 1,
                'available_quantity' => $packageData['available_quantity'] ?? 20,
                'max_per_order' => $packageData['max_per_order'] ?? 6,
                'is_active' => true,
                'sort_order' => $index + 1,
                'venue_details_fr' => $packageData['venue_details_fr'] ?? $parsed['venue_name'],
                'venue_details_en' => $packageData['venue_details_en'] ?? ($packageData['venue_details_fr'] ?? $parsed['venue_name']),
                'description_included_en' => $packageData['description_included_en'] ?? ($packageData['description_included_fr'] ?? null),
            ]);
        }

        return $event->fresh(['category', 'type', 'packages']);
    }

    protected function detectProfile(string $normalized, string $originalFilename): ?array
    {
        $haystack = Str::of($originalFilename . ' ' . $normalized)->ascii()->lower()->value();

        $profiles = [
            [
                'keywords' => ['roland garros', 'roland-garros'],
                'title' => 'Roland-Garros',
                'city' => 'Paris',
                'country' => 'France',
                'venue_name' => 'Stade Roland-Garros',
                'family' => 'sportif',
                'category_hints' => ['sport'],
                'type_hints' => ['tennis'],
            ],
            [
                'keywords' => ['coupe du monde', 'world cup', 'fifa'],
                'title' => 'Coupe du Monde 2026',
                'country' => 'Canada / États-Unis / Mexique',
                'venue_name' => 'Stade à confirmer',
                'family' => 'sportif',
                'category_hints' => ['sport'],
                'type_hints' => ['football', 'fifa'],
            ],
            [
                'keywords' => ['grand prix de monaco', 'monaco grand prix', 'circuit de monaco'],
                'title' => 'Grand Prix de Monaco',
                'city' => 'Monaco',
                'country' => 'Monaco',
                'venue_name' => 'Circuit de Monaco',
                'family' => 'sportif',
                'category_hints' => ['sport'],
                'type_hints' => ['motorsport', 'formule', 'f1'],
            ],
            [
                'keywords' => ['bahrain', 'bahrein'],
                'title' => 'Grand Prix de Bahreïn',
                'city' => 'Sakhir',
                'country' => 'Bahreïn',
                'venue_name' => 'Bahrain International Circuit',
                'family' => 'sportif',
                'category_hints' => ['sport'],
                'type_hints' => ['motorsport', 'formule', 'f1'],
            ],
            [
                'keywords' => ['djeddah', 'jeddah'],
                'title' => 'Grand Prix de Djeddah',
                'city' => 'Djeddah',
                'country' => 'Arabie Saoudite',
                'venue_name' => 'Jeddah Corniche Circuit',
                'family' => 'sportif',
                'category_hints' => ['sport'],
                'type_hints' => ['motorsport', 'formule', 'f1'],
            ],
            [
                'keywords' => ['miami'],
                'title' => 'Grand Prix de Miami',
                'city' => 'Miami',
                'country' => 'États-Unis',
                'venue_name' => 'Miami International Autodrome',
                'family' => 'sportif',
                'category_hints' => ['sport'],
                'type_hints' => ['motorsport', 'formule', 'f1'],
            ],
            [
                'keywords' => ['montreal', 'canada'],
                'title' => 'Grand Prix du Canada',
                'city' => 'Montréal',
                'country' => 'Canada',
                'venue_name' => 'Circuit Gilles-Villeneuve',
                'family' => 'sportif',
                'category_hints' => ['sport'],
                'type_hints' => ['motorsport', 'formule', 'f1'],
            ],
            [
                'keywords' => ['uefa europa', 'europa league'],
                'title' => 'Finale UEFA Europa League',
                'city' => 'Istanbul',
                'country' => 'Turquie',
                'venue_name' => 'Stade à confirmer',
                'family' => 'sportif',
                'category_hints' => ['sport'],
                'type_hints' => ['football', 'uefa'],
            ],
            [
                'keywords' => ['uefa champions', 'champions league'],
                'title' => 'Finale UEFA Champions League',
                'city' => 'Budapest',
                'country' => 'Hongrie',
                'venue_name' => 'Puskas Arena',
                'family' => 'sportif',
                'category_hints' => ['sport'],
                'type_hints' => ['football', 'uefa'],
            ],
            [
                'keywords' => ['le mans'],
                'title' => '24 Heures du Mans',
                'city' => 'Le Mans',
                'country' => 'France',
                'venue_name' => 'Circuit des 24 Heures du Mans',
                'family' => 'sportif',
                'category_hints' => ['sport'],
                'type_hints' => ['motorsport', 'endurance'],
            ],
        ];

        foreach ($profiles as $profile) {
            foreach ($profile['keywords'] as $keyword) {
                if (str_contains($haystack, $keyword)) {
                    return $profile;
                }
            }
        }

        return null;
    }

    protected function resolveCategoryAndType(
        Collection $categories,
        Collection $types,
        ?array $profile,
        string $normalizedText,
        array $options
    ): array {
        $category = null;
        $type = null;

        if (!empty($options['category_id'])) {
            $category = $categories->firstWhere('id', (int) $options['category_id']);
        }

        if (!empty($options['type_id'])) {
            $type = $types->firstWhere('id', (int) $options['type_id']);
        }

        if (!$category && $profile) {
            $category = $this->matchByHints($categories, $profile['category_hints'] ?? []);
        }

        if (!$type && $profile) {
            $type = $this->matchByHints($types, $profile['type_hints'] ?? []);
        }

        if (!$category) {
            $category = str_contains($normalizedText, 'concert') || str_contains($normalizedText, 'festival')
                ? $this->matchByHints($categories, ['culture'])
                : $this->matchByHints($categories, ['sport']);
        }

        if (!$type && $category) {
            $type = $types->firstWhere('category_id', $category->id);
        }

        return [$category ?? $categories->first(), $type];
    }

    protected function matchByHints(Collection $models, array $hints)
    {
        foreach ($hints as $hint) {
            $needle = Str::of($hint)->ascii()->lower()->value();

            $match = $models->first(function ($model) use ($needle) {
                $haystacks = collect([
                    $model->name_fr ?? null,
                    $model->name_en ?? null,
                    $model->slug ?? null,
                    $model->description ?? null,
                ])
                    ->filter()
                    ->map(fn ($value) => Str::of((string) $value)->ascii()->lower()->value());

                return $haystacks->contains(fn ($value) => str_contains($value, $needle));
            });

            if ($match) {
                return $match;
            }
        }

        return null;
    }

    protected function detectDates(string $normalized): array
    {
        $months = [
            'janvier' => 1, 'january' => 1,
            'fevrier' => 2, 'février' => 2, 'february' => 2,
            'mars' => 3, 'march' => 3,
            'avril' => 4, 'april' => 4,
            'mai' => 5, 'may' => 5,
            'juin' => 6, 'june' => 6,
            'juillet' => 7, 'july' => 7,
            'aout' => 8, 'août' => 8, 'august' => 8,
            'septembre' => 9, 'september' => 9,
            'octobre' => 10, 'october' => 10,
            'novembre' => 11, 'november' => 11,
            'decembre' => 12, 'décembre' => 12, 'december' => 12,
        ];

        if (preg_match('/(\d{1,2})\s+([a-zéûôî]+)\s+(?:au|-|to)\s+(\d{1,2})\s+([a-zéûôî]+)\s+(\d{4})/iu', $normalized, $rangeMatch)) {
            $startMonth = $months[Str::lower($rangeMatch[2])] ?? null;
            $endMonth = $months[Str::lower($rangeMatch[4])] ?? null;

            if ($startMonth && $endMonth) {
                return [
                    Carbon::create((int) $rangeMatch[5], $startMonth, (int) $rangeMatch[1]),
                    Carbon::create((int) $rangeMatch[5], $endMonth, (int) $rangeMatch[3]),
                ];
            }
        }

        preg_match_all('/(\d{1,2})\s+([a-zéûôî]+)\s+(\d{4})/iu', $normalized, $matches, PREG_SET_ORDER);

        $dates = collect($matches)
            ->map(function ($match) use ($months) {
                $month = $months[Str::lower($match[2])] ?? null;

                return $month
                    ? Carbon::create((int) $match[3], $month, (int) $match[1])
                    : null;
            })
            ->filter()
            ->sort()
            ->values();

        return [$dates->first(), $dates->count() > 1 ? $dates->last() : null];
    }

    protected function detectTitle(Collection $lines, string $originalFilename, ?array $profile): string
    {
        if ($profile['title'] ?? null) {
            return $profile['title'];
        }

        $candidate = $lines->take(12)->first(function ($line) {
            $normalized = Str::of($line)->ascii()->lower()->value();

            return strlen($line) >= 8
                && strlen($line) <= 80
                && !preg_match('/fcfa|xof|eur|usd|tarif|price|package|hospitality|catalogue|catalog/i', $normalized);
        });

        if ($candidate) {
            return trim($candidate);
        }

        return Str::headline(pathinfo($originalFilename, PATHINFO_FILENAME));
    }

    protected function buildDescription(Collection $lines, string $title): string
    {
        return $lines
            ->reject(fn ($line) => trim($line) === trim($title))
            ->reject(fn ($line) => strlen($line) < 20)
            ->reject(fn ($line) => preg_match('/fcfa|xof|eur|usd/i', $line))
            ->take(6)
            ->implode("\n");
    }

    protected function detectVenue(Collection $lines): ?string
    {
        return $lines->first(function ($line) {
            return preg_match('/arena|stadium|stade|circuit|court|autodrome|hall|skybox|village/i', $line);
        });
    }

    protected function detectVenueAddress(Collection $lines, string $venue, string $city, string $country): string
    {
        $addressLine = $lines->first(function ($line) use ($venue) {
            return str_contains(Str::lower($line), Str::lower($venue)) && preg_match('/,|avenue|street|boulevard|road|rue/i', $line);
        });

        return $addressLine ?: trim($venue . ' - ' . $city . ' - ' . $country);
    }

    protected function detectCity(Collection $lines): ?string
    {
        $knownCities = ['Paris', 'Monaco', 'Istanbul', 'Budapest', 'Miami', 'Montréal', 'Montreal', 'Djeddah', 'Jeddah', 'Sakhir', 'Le Mans'];

        foreach ($knownCities as $city) {
            if ($lines->contains(fn ($line) => str_contains(Str::of($line)->ascii()->lower()->value(), Str::of($city)->ascii()->lower()->value()))) {
                return $city;
            }
        }

        return null;
    }

    protected function detectCountry(Collection $lines): ?string
    {
        $knownCountries = ['France', 'Monaco', 'Turquie', 'Turkey', 'Hongrie', 'Hungary', 'États-Unis', 'United States', 'Canada', 'Arabie Saoudite', 'Saudi Arabia', 'Bahreïn', 'Bahrain'];

        foreach ($knownCountries as $country) {
            if ($lines->contains(fn ($line) => str_contains(Str::of($line)->ascii()->lower()->value(), Str::of($country)->ascii()->lower()->value()))) {
                return $country;
            }
        }

        return null;
    }

    protected function detectPackages(Collection $lines): array
    {
        $packages = [];
        $seen = [];

        foreach ($lines as $index => $line) {
            $priceMatch = $this->extractPrimaryPrice($line);

            if (!$priceMatch) {
                continue;
            }

            $name = trim(preg_replace('/((\d{1,3}(?:[\s.,]\d{3})+|\d+)(?:\s*(fcfa|xof|eur|usd|€|\$)))/iu', '', $line));

            if (!$this->isUsablePackageName($name)) {
                for ($offset = 1; $offset <= 2; $offset++) {
                    $candidate = $lines[$index - $offset] ?? null;

                    if ($candidate && $this->isUsablePackageName($candidate)) {
                        $name = $candidate;
                        break;
                    }
                }
            }

            if (!$this->isUsablePackageName($name)) {
                continue;
            }

            $key = Str::of($name)->ascii()->lower()->value() . '|' . $priceMatch['amount'];

            if (isset($seen[$key])) {
                continue;
            }

            $description = collect([
                $lines[$index + 1] ?? null,
                $lines[$index + 2] ?? null,
            ])
                ->filter(fn ($value) => filled($value) && !preg_match('/fcfa|xof|eur|usd|€|\$/i', $value))
                ->filter(fn ($value) => strlen($value) > 18)
                ->take(2)
                ->implode("\n");

            $packages[] = [
                'package_name_fr' => Str::limit(trim($name), 120, ''),
                'package_name_en' => Str::limit(trim($name), 120, ''),
                'package_code' => Str::upper(Str::slug(Str::limit($name, 18, ''), '-')),
                'description_fr' => $description ?: null,
                'description_included_fr' => $description ?: null,
                'price' => $priceMatch['amount'],
                'currency' => $priceMatch['currency'],
                'minimum_quantity' => 1,
                'available_quantity' => 20,
                'max_per_order' => 6,
                'venue_details_fr' => null,
            ];

            $seen[$key] = true;
        }

        return $packages;
    }

    protected function extractPrimaryPrice(string $line): ?array
    {
        if (!preg_match('/(\d{1,3}(?:[\s.,]\d{3})+|\d+)\s*(fcfa|xof|eur|usd|€|\$)/iu', $line, $match)) {
            return null;
        }

        $amount = (float) preg_replace('/[^\d]/', '', $match[1]);
        $currency = match (Str::upper($match[2])) {
            '€', 'EUR' => 'EUR',
            '$', 'USD' => 'USD',
            default => 'XOF',
        };

        return [
            'amount' => $amount,
            'currency' => $currency,
        ];
    }

    protected function extractMoneyMatches(string $normalized): array
    {
        preg_match_all('/(\d{1,3}(?:[\s.,]\d{3})+|\d+)\s*(fcfa|xof|eur|usd|€|\$)/iu', $normalized, $matches, PREG_SET_ORDER);

        return collect($matches)
            ->map(function ($match) {
                return [
                    'amount' => (float) preg_replace('/[^\d]/', '', $match[1]),
                    'currency' => $match[2],
                ];
            })
            ->all();
    }

    protected function isUsablePackageName(?string $value): bool
    {
        if (blank($value)) {
            return false;
        }

        $normalized = Str::of($value)->ascii()->lower()->trim()->value();

        return strlen($normalized) >= 4
            && strlen($normalized) <= 90
            && !preg_match('/fcfa|xof|eur|usd|tarif|price|page|catalogue|hospitality|officiel|official/i', $normalized)
            && preg_match('/[a-z]/i', $normalized);
    }

    protected function makeUniqueSlug(string $slug): string
    {
        $base = $slug !== '' ? $slug : 'event-import';
        $candidate = $base;
        $counter = 2;

        while (Event::query()->where('slug', $candidate)->exists()) {
            $candidate = $base . '-' . $counter;
            $counter++;
        }

        return $candidate;
    }
}
