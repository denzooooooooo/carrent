<?php

namespace App\Services;

use App\Models\Event;
use App\Models\EventCategory;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class EventDiscoveryService
{
    public function buildIndexPayload(Request $request): array
    {
        $viewMode = $this->resolveViewMode($request);
        $selectedFamily = Event::normalizeFamily($request->input('family', $request->input('type')));
        $selectedSort = Event::normalizeSort($request->input('sort'));
        $familyReferences = $this->resolveFamilyReferences();

        $query = $this->makeQuery(
            request: $request,
            family: $selectedFamily,
            sort: $selectedSort,
            familyReferences: $familyReferences,
            withRelations: true,
        );

        $events = (clone $query)
            ->paginate($viewMode === 'calendar' ? 18 : 12)
            ->appends($request->query());

        $calendarMonth = $this->resolveCalendarMonth($request, clone $query);
        $calendarStart = $calendarMonth->copy()->startOfMonth()->startOfWeek(Carbon::MONDAY);
        $calendarEnd = $calendarMonth->copy()->endOfMonth()->endOfWeek(Carbon::SUNDAY);

        $calendarEvents = (clone $query)
            ->whereBetween('event_date', [$calendarStart->toDateString(), $calendarEnd->toDateString()])
            ->get()
            ->sortBy(fn (Event $event) => sprintf(
                '%s %s',
                optional($event->event_date)->toDateString() ?? '',
                $event->event_time ?? '00:00'
            ))
            ->values();

        $eventsByDate = $calendarEvents->groupBy(function (Event $event) {
            return optional($event->event_date)->toDateString()
                ?? Carbon::parse($event->event_date)->toDateString();
        });

        $calendarMonthEvents = $calendarEvents
            ->filter(fn (Event $event) => $event->event_date && $event->event_date->isSameMonth($calendarMonth))
            ->values();

        $filterOptions = $this->getFilterOptions($selectedFamily, $familyReferences);
        $familyCounts = $this->getFamilyCounts($request, $familyReferences);
        $featuredEvent = $this->getFeaturedEvent($request, $selectedFamily, $familyReferences);
        $quickPresets = $this->getQuickPresets($request, $calendarMonth);
        $sortOptions = $this->getSortOptions();
        $activeFilterCount = $this->countActiveFilters($request, $selectedFamily, $selectedSort);

        return [
            'events' => $events,
            'categories' => $filterOptions['categories'],
            'cities' => $filterOptions['cities'],
            'countries' => $filterOptions['countries'],
            'venues' => $filterOptions['venues'],
            'viewMode' => $viewMode,
            'selectedFamily' => $selectedFamily,
            'selectedSort' => $selectedSort,
            'activeFilterCount' => $activeFilterCount,
            'calendarMonth' => $calendarMonth,
            'eventsByDate' => $eventsByDate,
            'calendarMonthEvents' => $calendarMonthEvents,
            'familyCounts' => $familyCounts,
            'featuredEvent' => $featuredEvent,
            'quickPresets' => $quickPresets,
            'sortOptions' => $sortOptions,
        ];
    }

    private function makeQuery(
        Request $request,
        string $family,
        string $sort,
        array $familyReferences,
        bool $withRelations = false,
        bool $applyFamily = true
    ): Builder {
        $query = Event::query();

        if ($withRelations) {
            $query->catalog();
        }

        $query
            ->active()
            ->matchingSearch($request->input('q'))
            ->inCategory($request->input('category'))
            ->inCity($request->input('city'))
            ->inCountry($request->input('country'))
            ->atVenue($request->input('venue'))
            ->withinBudget($request->input('min_price'), $request->input('max_price'))
            ->onDate($request->input('date'))
            ->betweenDates($request->input('start_date'), $request->input('end_date'))
            ->availableOnly($request->boolean('available_only'))
            ->featuredOnly($request->boolean('featured'));

        if ($applyFamily) {
            $query->inFamily(
                $family,
                $familyReferences[$family]['category_ids'] ?? [],
                $familyReferences[$family]['type_ids'] ?? [],
            );
        }

        return $query->sortForCatalog($sort);
    }

    private function getFilterOptions(string $family, array $familyReferences): array
    {
        $cacheKey = 'events:filters:' . $family;

        return Cache::remember($cacheKey, now()->addMinutes(15), function () use ($family, $familyReferences) {
            $query = Event::query()->active();

            $query->inFamily(
                $family,
                $familyReferences[$family]['category_ids'] ?? [],
                $familyReferences[$family]['type_ids'] ?? [],
            );

            $categories = EventCategory::query()
                ->where('is_active', true)
                ->whereHas('events', function (Builder $builder) use ($family, $familyReferences) {
                    $builder->active()->inFamily(
                        $family,
                        $familyReferences[$family]['category_ids'] ?? [],
                        $familyReferences[$family]['type_ids'] ?? [],
                    );
                })
                ->orderBy('name_fr')
                ->get();

            return [
                'categories' => $categories,
                'cities' => (clone $query)
                    ->whereNotNull('city')
                    ->where('city', '!=', '')
                    ->distinct()
                    ->orderBy('city')
                    ->pluck('city')
                    ->values(),
                'countries' => (clone $query)
                    ->whereNotNull('country')
                    ->where('country', '!=', '')
                    ->distinct()
                    ->orderBy('country')
                    ->pluck('country')
                    ->values(),
                'venues' => (clone $query)
                    ->whereNotNull('venue_name')
                    ->where('venue_name', '!=', '')
                    ->distinct()
                    ->orderBy('venue_name')
                    ->pluck('venue_name')
                    ->values(),
            ];
        });
    }

    private function getFamilyCounts(Request $request, array $familyReferences): array
    {
        $cacheKey = 'events:counts:' . md5(json_encode([
            'q' => (string) $request->input('q'),
            'category' => (string) $request->input('category'),
            'city' => (string) $request->input('city'),
            'country' => (string) $request->input('country'),
            'venue' => (string) $request->input('venue'),
            'min_price' => (string) $request->input('min_price'),
            'max_price' => (string) $request->input('max_price'),
            'date' => (string) $request->input('date'),
            'start_date' => (string) $request->input('start_date'),
            'end_date' => (string) $request->input('end_date'),
            'featured' => (bool) $request->boolean('featured'),
            'available_only' => (bool) $request->boolean('available_only'),
        ]));

        return Cache::remember($cacheKey, now()->addMinutes(10), function () use ($request, $familyReferences) {
            $baseQuery = $this->makeQuery(
                request: $request,
                family: 'all',
                sort: 'soonest',
                familyReferences: $familyReferences,
                withRelations: false,
                applyFamily: false,
            );

            return [
                'all' => (clone $baseQuery)->count(),
                'sportif' => (clone $baseQuery)->inFamily(
                    'sportif',
                    $familyReferences['sportif']['category_ids'] ?? [],
                    $familyReferences['sportif']['type_ids'] ?? [],
                )->count(),
                'culturel' => (clone $baseQuery)->inFamily(
                    'culturel',
                    $familyReferences['culturel']['category_ids'] ?? [],
                    $familyReferences['culturel']['type_ids'] ?? [],
                )->count(),
            ];
        });
    }

    private function getFeaturedEvent(Request $request, string $family, array $familyReferences): ?Event
    {
        return $this->makeQuery(
            request: $request,
            family: $family,
            sort: 'soonest',
            familyReferences: $familyReferences,
            withRelations: true,
        )
            ->reorder()
            ->orderByDesc('is_featured')
            ->orderByRaw('CASE WHEN event_date < ? THEN 1 ELSE 0 END', [Carbon::today()->toDateString()])
            ->orderBy('event_date')
            ->orderBy('event_time')
            ->first();
    }

    private function getQuickPresets(Request $request, Carbon $calendarMonth): array
    {
        $now = now();
        $weekendStart = $now->copy()->startOfWeek(Carbon::MONDAY)->addDays(5);
        if ($weekendStart->lt($now->copy()->startOfDay())) {
            $weekendStart->addWeek();
        }

        $weekendEnd = $weekendStart->copy()->addDay();
        $monthStart = $now->copy()->startOfMonth();
        $monthEnd = $now->copy()->endOfMonth();

        return [
            [
                'key' => 'weekend',
                'label' => app()->getLocale() === 'fr' ? 'Ce week-end' : 'This weekend',
                'icon' => 'fa-calendar-week',
                'filters' => [
                    'date' => null,
                    'start_date' => $weekendStart->toDateString(),
                    'end_date' => $weekendEnd->toDateString(),
                    'month' => $calendarMonth->format('Y-m'),
                ],
                'is_active' => $request->input('start_date') === $weekendStart->toDateString()
                    && $request->input('end_date') === $weekendEnd->toDateString(),
            ],
            [
                'key' => 'month',
                'label' => app()->getLocale() === 'fr' ? 'Ce mois-ci' : 'This month',
                'icon' => 'fa-calendar-days',
                'filters' => [
                    'date' => null,
                    'start_date' => $monthStart->toDateString(),
                    'end_date' => $monthEnd->toDateString(),
                    'month' => $monthStart->format('Y-m'),
                ],
                'is_active' => $request->input('start_date') === $monthStart->toDateString()
                    && $request->input('end_date') === $monthEnd->toDateString(),
            ],
            [
                'key' => 'abidjan',
                'label' => 'Abidjan',
                'icon' => 'fa-location-dot',
                'filters' => [
                    'city' => 'Abidjan',
                ],
                'is_active' => strtolower((string) $request->input('city')) === 'abidjan',
            ],
            [
                'key' => 'featured',
                'label' => app()->getLocale() === 'fr' ? 'À la une' : 'Featured',
                'icon' => 'fa-star',
                'filters' => [
                    'featured' => 1,
                    'sort' => 'featured',
                ],
                'is_active' => $request->boolean('featured'),
            ],
            [
                'key' => 'available',
                'label' => app()->getLocale() === 'fr' ? 'Disponibles' : 'Available',
                'icon' => 'fa-ticket',
                'filters' => [
                    'available_only' => 1,
                ],
                'is_active' => $request->boolean('available_only'),
            ],
        ];
    }

    private function getSortOptions(): array
    {
        $isFrench = app()->getLocale() === 'fr';

        return [
            'soonest' => $isFrench ? 'Les plus proches' : 'Soonest first',
            'latest' => $isFrench ? 'Les plus lointains' : 'Latest first',
            'price_low' => $isFrench ? 'Prix croissant' : 'Lowest price',
            'price_high' => $isFrench ? 'Prix décroissant' : 'Highest price',
            'featured' => $isFrench ? 'Mise en avant' : 'Featured first',
        ];
    }

    private function countActiveFilters(Request $request, string $family, string $sort): int
    {
        return collect([
            $family !== 'all' ? $family : null,
            $request->input('q'),
            $request->input('category'),
            $request->input('city'),
            $request->input('country'),
            $request->input('venue'),
            $request->input('min_price'),
            $request->input('max_price'),
            $request->input('date'),
            $request->input('start_date'),
            $request->input('end_date'),
            $request->boolean('featured') ? 1 : null,
            $request->boolean('available_only') ? 1 : null,
            $sort !== 'soonest' ? $sort : null,
        ])->filter(fn ($value) => filled($value))->count();
    }

    private function resolveCalendarMonth(Request $request, Builder $query): Carbon
    {
        $monthParam = $request->input('month');

        if ($monthParam) {
            try {
                return Carbon::createFromFormat('Y-m', $monthParam)->startOfMonth();
            } catch (\Throwable $exception) {
                // Ignore invalid month and fallback below.
            }
        }

        foreach (['date', 'start_date'] as $field) {
            if ($request->filled($field)) {
                return Carbon::parse($request->input($field))->startOfMonth();
            }
        }

        $nextEvent = (clone $query)
            ->whereDate('event_date', '>=', Carbon::now()->toDateString())
            ->orderBy('event_date')
            ->first();

        if ($nextEvent?->event_date) {
            return $nextEvent->event_date->copy()->startOfMonth();
        }

        $firstEvent = (clone $query)
            ->orderBy('event_date')
            ->first();

        return $firstEvent?->event_date?->copy()->startOfMonth() ?? Carbon::now()->startOfMonth();
    }

    private function resolveViewMode(Request $request): string
    {
        return in_array($request->get('view'), ['list', 'calendar'], true)
            ? $request->get('view')
            : 'list';
    }

    private function resolveFamilyReferences(): array
    {
        return Cache::remember('events:family-references:v1', now()->addHour(), function () {
            return [
                'all' => ['category_ids' => [], 'type_ids' => []],
                'sportif' => [
                    'category_ids' => Event::resolveFamilyCategoryIds('sportif'),
                    'type_ids' => Event::resolveFamilyTypeIds('sportif'),
                ],
                'culturel' => [
                    'category_ids' => Event::resolveFamilyCategoryIds('culturel'),
                    'type_ids' => Event::resolveFamilyTypeIds('culturel'),
                ],
            ];
        });
    }
}
