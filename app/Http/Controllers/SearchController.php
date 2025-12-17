<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TourPackage;
use App\Models\Event;
use App\Models\Flight;
use App\Models\Page;
use App\Models\Location;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\JsonResponse;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $q = trim($request->get('q', ''));
        if (empty($q)) {
            return view('search.results', compact('q'));
        }

        $like = '%' . $q . '%';

        // Packages search
        $packages = collect();
        if (Schema::hasTable('tour_packages')) {
            $packageColumns = ['title_fr', 'title_en', 'description_fr', 'description_en', 'destination', 'departure_city'];
            $existingCols = array_filter($packageColumns, fn($col) => Schema::hasColumn('tour_packages', $col));

            $packages = TourPackage::query()
                ->where(function($query) use ($like, $existingCols) {
                    foreach ($existingCols as $col) {
                        $query->orWhere($col, 'like', $like);
                    }
                })
                ->where('is_active', true)
                ->with('category')
                ->take(12)
                ->get();
        }

        // Events search
        $events = collect();
        if (Schema::hasTable('events')) {
            $eventColumns = ['title_fr', 'title_en', 'description_fr', 'description_en', 'venue_name', 'city', 'country', 'organizer'];
            $existingCols = array_filter($eventColumns, fn($col) => Schema::hasColumn('events', $col));

            $events = Event::query()
                ->where(function($query) use ($like, $existingCols) {
                    foreach ($existingCols as $col) {
                        $query->orWhere($col, 'like', $like);
                    }
                })
                ->where('is_active', true)
                ->where('event_date', '>=', now())
                ->with(['category', 'type'])
                ->take(10)
                ->get();
        }

        // Flights search
        $flights = collect();
        if (Schema::hasTable('flights')) {
            $flightColumns = ['flight_number', 'airline', 'departure_city', 'arrival_city'];
            $existingCols = array_filter($flightColumns, fn($col) => Schema::hasColumn('flights', $col));

            if (!empty($existingCols)) {
                $flights = Flight::query()
                    ->where(function($query) use ($like, $existingCols) {
                        foreach ($existingCols as $col) {
                            $query->orWhere($col, 'like', $like);
                        }
                    })
                    ->take(8)
                    ->get();
            }
        }

        // Pages search

        $pages = collect();
        if (Schema::hasTable('pages')) {
            $pageColumns = ['title', 'content', 'slug'];
            $existingCols = array_filter($pageColumns, fn($col) => Schema::hasColumn('pages', $col));

            if (!empty($existingCols)) {
                $pages = Page::query()
                    ->where(function($query) use ($like, $existingCols) {
                        foreach ($existingCols as $col) {
                            $query->orWhere($col, 'like', $like);
                        }
                    })
                    ->take(6)
                    ->get();
            }
        } else {
            $staticPages = [
                (object)['title' => 'À propos', 'slug' => 'about', 'type' => 'page'],
                (object)['title' => 'Contact', 'slug' => 'contact', 'type' => 'page'],
                (object)['title' => 'FAQ', 'slug' => 'faq', 'type' => 'page'],
                (object)['title' => 'Conditions d\'utilisation', 'slug' => 'terms', 'type' => 'page'],
                (object)['title' => 'Politique de confidentialité', 'slug' => 'privacy', 'type' => 'page'],
            ];

            $pages = collect($staticPages)
                ->filter(fn($page) => stripos($page->title, $q) !== false)
                ->take(6);
        }

        // Locations search
        $locations = collect();
        if (Schema::hasTable('locations')) {
            $locationColumns = ['name', 'description', 'city', 'country'];
            $existingCols = array_filter($locationColumns, fn($col) => Schema::hasColumn('locations', $col));

            $locations = Location::query()
                ->where(function($query) use ($like, $existingCols) {
                    foreach ($existingCols as $col) {
                        $query->orWhere($col, 'like', $like);
                    }
                })
                ->where('is_active', true)
                ->take(6)
                ->get();
        }

        return view('search.results', compact('q', 'packages', 'events', 'flights', 'pages', 'locations'));
    }

    public function suggest(Request $request): JsonResponse
    {
        $q = trim($request->get('q', ''));
        if (empty($q) || strlen($q) < 2) return response()->json(['results' => []]);

        $like = '%' . $q . '%';

        $packages = collect();
        if (Schema::hasTable('tour_packages')) {
            $packageColumns = ['title_fr', 'title_en', 'destination', 'departure_city'];
            $existingCols = array_filter($packageColumns, fn($col) => Schema::hasColumn('tour_packages', $col));

            if (!empty($existingCols)) {
                $packages = TourPackage::query()
                    ->where(function($query) use ($like, $existingCols) {
                        foreach ($existingCols as $col) {
                            $query->orWhere($col, 'like', $like);
                        }
                    })
                    ->where('is_active', true)
                    ->select('title_fr as title', 'title_en', 'slug', 'destination', DB::raw("'package' as type"))
                    ->take(6)
                    ->get();
            }
        }

        $events = collect();
        if (Schema::hasTable('events')) {
            $eventColumns = ['title_fr', 'title_en', 'venue_name', 'city', 'organizer'];
            $existingCols = array_filter($eventColumns, fn($col) => Schema::hasColumn('events', $col));

            if (!empty($existingCols)) {
                $events = Event::query()
                    ->where(function($query) use ($like, $existingCols) {
                        foreach ($existingCols as $col) {
                            $query->orWhere($col, 'like', $like);
                        }
                    })
                    ->where('is_active', true)
                    ->where('event_date', '>=', now())
                    ->select('title_fr as title', 'title_en', 'slug', 'venue_name', 'city', DB::raw("'event' as type"))
                    ->take(6)
                    ->get();
            }
        }




        // Use static pages instead of database table for now
        $staticPages = [
            (object)['title' => 'Accueil', 'slug' => '', 'type' => 'page'],
            (object)['title' => 'À propos', 'slug' => 'about', 'type' => 'page'],
            (object)['title' => 'Contact', 'slug' => 'contact', 'type' => 'page'],
            (object)['title' => 'FAQ', 'slug' => 'faq', 'type' => 'page'],
            (object)['title' => 'Conditions d\'utilisation', 'slug' => 'terms', 'type' => 'page'],
            (object)['title' => 'Politique de confidentialité', 'slug' => 'privacy', 'type' => 'page'],
        ];

        $pages = collect($staticPages)
            ->filter(fn($page) => stripos($page->title, $q) !== false)
            ->take(4);

        $locations = collect();
        if (Schema::hasTable('locations')) {
            $locationColumns = ['name', 'city', 'country'];
            $existingCols = array_filter($locationColumns, fn($col) => Schema::hasColumn('locations', $col));

            if (!empty($existingCols)) {
                $locations = Location::query()
                    ->where(function($query) use ($like, $existingCols) {
                        foreach ($existingCols as $col) {
                            $query->orWhere($col, 'like', $like);
                        }
                    })
                    ->where('is_active', true)
                    ->select('name', 'slug', 'city', 'country', DB::raw("'location' as type"))
                    ->take(4)
                    ->get();
            }
        }

        $results = collect()
            ->concat($packages)
            ->concat($events)
            ->concat($pages)
            ->concat($locations)
            ->map(function($item) use ($q) {
                $title = $item->title ?? ($item->title_fr ?? '');
                $highlightedTitle = preg_replace('/(' . preg_quote($q, '/') . ')/i', '<mark>$1</mark>', $title);

                return [
                    'title' => $highlightedTitle,
                    'original_title' => $title,
                    'slug' => $item->slug ?? null,
                    'type' => $item->type ?? 'item',
                    'subtitle' => $this->getSubtitle($item),
                    'url' => $this->getUrl($item),
                    'icon' => $this->getIcon($item->type ?? 'item')
                ];
            })
            ->sortByDesc(fn($item) => stripos($item['original_title'], $q) === 0 ? 100 : (stripos($item['original_title'], $q) !== false ? 50 : 0))
            ->values();

        return response()->json(['results' => $results]);
    }

    private function getSubtitle($item)
    {
        switch($item->type ?? '') {
            case 'package': return $item->destination ?? 'Package';
            case 'event': return $item->venue_name ?? ($item->city ?? 'Événement');
            case 'location': return $item->city ?? ($item->country ?? 'Location');
            case 'page': return 'Page';
            default: return '';
        }
    }

    private function getUrl($item)
    {
        switch($item->type ?? '') {
            case 'package': return $item->slug ? '/packages/' . $item->slug : '/packages';
            case 'event': return $item->slug ? '/events/' . $item->slug : '/events';
            case 'location': return $item->slug ? '/location/' . $item->slug : '/location';
            case 'page': return $item->slug ? '/' . $item->slug : '/';
            default: return '/search?q=' . urlencode($item->title ?? '');
        }
    }

    private function getIcon($type)
    {
        switch($type) {
            case 'package': return 'fas fa-suitcase';
            case 'event': return 'fas fa-calendar-alt';
            case 'location': return 'fas fa-map-marker-alt';
            case 'page': return 'fas fa-file-alt';
            case 'flight': return 'fas fa-plane';
            default: return 'fas fa-search';
        }
    }

    public function quick(Request $request)
    {
        $q = trim($request->get('q', ''));
        if (empty($q) || strlen($q) < 1) return response()->json(['results' => []]);

        $results = [];

        if (Schema::hasTable('tour_packages')) {
            $packages = TourPackage::query()
                ->where('title_fr', 'like', '%' . $q . '%')
                ->orWhere('title_en', 'like', '%' . $q . '%')
                ->where('is_active', true)
                ->limit(3)
                ->get();
            foreach ($packages as $p) {
                $results[] = [
                    'title' => $p->title_fr,
                    'type' => 'package',
                    'url' => '/packages/' . $p->slug,
                    'icon' => 'fas fa-suitcase'
                ];
            }
        }

        if (Schema::hasTable('events')) {
            $events = Event::query()
                ->where('title_fr', 'like', '%' . $q . '%')
                ->orWhere('title_en', 'like', '%' . $q . '%')
                ->where('is_active', true)
                ->where('event_date', '>=', now())
                ->limit(3)
                ->get();
            foreach ($events as $e) {
                $results[] = [
                    'title' => $e->title_fr,
                    'type' => 'event',
                    'url' => '/events/' . $e->slug,
                    'icon' => 'fas fa-calendar-alt'
                ];
            }
        }

        return response()->json(['results' => $results]);
    }
}
