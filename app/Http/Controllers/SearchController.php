<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TourPackage;
use App\Models\Event;
use App\Models\Flight;
use App\Models\Page;
use App\Models\Location;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;

class SearchController extends Controller
{
    /**
     * Display search results for query q across packages, events, flights and pages.
     */
    public function index(Request $request)
    {
        $q = trim($request->get('q', ''));

        if (empty($q)) {
            return view('search.results', compact('q'));
        }

        $like = '%' . $q . '%';

        // Packages search - enhanced
        $packages = TourPackage::query()
            ->where(function($query) use ($like, $q) {
                $query->where('title_fr', 'like', $like)
                      ->orWhere('title_en', 'like', $like)
                      ->orWhere('description_fr', 'like', $like)
                      ->orWhere('description_en', 'like', $like)
                      ->orWhere('destination', 'like', $like)
                      ->orWhere('departure_city', 'like', $like);
            })
            ->where('is_active', true)
            ->with('category')
            ->take(12)
            ->get();

        // Events search - enhanced
        $events = Event::query()
            ->where(function($query) use ($like, $q) {
                $query->where('title_fr', 'like', $like)
                      ->orWhere('title_en', 'like', $like)
                      ->orWhere('description_fr', 'like', $like)
                      ->orWhere('description_en', 'like', $like)
                      ->orWhere('venue_name', 'like', $like)
                      ->orWhere('city', 'like', $like)
                      ->orWhere('country', 'like', $like)
                      ->orWhere('organizer', 'like', $like);
            })
            ->where('is_active', true)
            ->where('event_date', '>=', now())
            ->with(['category', 'type'])
            ->take(10)
            ->get();

        // Flights search - enhanced
        $flights = Flight::query()
            ->where(function($query) use ($like, $q) {
                $query->where('flight_number', 'like', $like)
                      ->orWhere('departure_city', 'like', $like)
                      ->orWhere('arrival_city', 'like', $like)
                      ->orWhere('airline', 'like', $like);
            })
            ->take(8)
            ->get();

        // Pages search - enhanced
        $pages = collect();
        try {
            $pages = Page::query()
                ->where(function($query) use ($like, $q) {
                    $query->where('title', 'like', $like)
                          ->orWhere('content', 'like', $like)
                          ->orWhere('slug', 'like', $like);
                })
                ->take(6)
                ->get();
        } catch (\Exception $e) {
            // If Page model doesn't exist, create static pages
            $staticPages = [
                (object)['title' => 'À propos', 'slug' => 'about', 'type' => 'page'],
                (object)['title' => 'Contact', 'slug' => 'contact', 'type' => 'page'],
                (object)['title' => 'FAQ', 'slug' => 'faq', 'type' => 'page'],
                (object)['title' => 'Conditions d\'utilisation', 'slug' => 'terms', 'type' => 'page'],
                (object)['title' => 'Politique de confidentialité', 'slug' => 'privacy', 'type' => 'page'],
            ];
            
            $filteredStatic = collect($staticPages)->filter(function($page) use ($like) {
                return stripos($page->title, str_replace('%', '', $like)) !== false;
            });
            
            $pages = $filteredStatic->take(6);
        }

        // Locations search
        $locations = Location::query()
            ->where(function($query) use ($like, $q) {
                $query->where('name', 'like', $like)
                      ->orWhere('description', 'like', $like)
                      ->orWhere('city', 'like', $like)
                      ->orWhere('country', 'like', $like);
            })
            ->where('is_active', true)
            ->take(6)
            ->get();

        return view('search.results', compact('q', 'packages', 'events', 'flights', 'pages', 'locations'));
    }

    /**
     * Suggest endpoint for AJAX autocomplete - Enhanced
     */
    public function suggest(Request $request): JsonResponse
    {
        $q = trim($request->get('q', ''));
        if (empty($q) || strlen($q) < 2) {
            return response()->json(['results' => []]);
        }

        $like = '%' . $q . '%';

        // Packages suggestions
        $packages = TourPackage::query()
            ->where(function($query) use ($like, $q) {
                $query->where('title_fr', 'like', $like)
                      ->orWhere('title_en', 'like', $like)
                      ->orWhere('destination', 'like', $like)
                      ->orWhere('departure_city', 'like', $like);
            })
            ->where('is_active', true)
            ->select('title_fr as title', 'title_en', 'slug', 'destination', DB::raw("'package' as type"))
            ->take(6)
            ->get();

        // Events suggestions
        $events = Event::query()
            ->where(function($query) use ($like, $q) {
                $query->where('title_fr', 'like', $like)
                      ->orWhere('title_en', 'like', $like)
                      ->orWhere('venue_name', 'like', $like)
                      ->orWhere('city', 'like', $like)
                      ->orWhere('organizer', 'like', $like);
            })
            ->where('is_active', true)
            ->where('event_date', '>=', now())
            ->select('title_fr as title', 'title_en', 'slug', 'venue_name', 'city', DB::raw("'event' as type"))
            ->take(6)
            ->get();

        // Pages suggestions
        $pages = collect();
        try {
            $pages = Page::query()
                ->where(function($query) use ($like, $q) {
                    $query->where('title', 'like', $like)
                          ->orWhere('slug', 'like', $like);
                })
                ->select('title', 'slug', DB::raw("'page' as type"))
                ->take(4)
                ->get();
        } catch (\Exception $e) {
            // Static pages if Page model doesn't exist
            $staticPages = [
                (object)['title' => 'Accueil', 'slug' => '', 'type' => 'page'],
                (object)['title' => 'À propos', 'slug' => 'about', 'type' => 'page'],
                (object)['title' => 'Contact', 'slug' => 'contact', 'type' => 'page'],
                (object)['title' => 'FAQ', 'slug' => 'faq', 'type' => 'page'],
            ];
            
            $filteredStatic = collect($staticPages)->filter(function($page) use ($q) {
                return stripos($page->title, $q) !== false;
            });
            
            $pages = $filteredStatic->take(4);
        }

        // Locations suggestions
        $locations = Location::query()
            ->where(function($query) use ($like, $q) {
                $query->where('name', 'like', $like)
                      ->orWhere('city', 'like', $like)
                      ->orWhere('country', 'like', $like);
            })
            ->where('is_active', true)
            ->select('name', 'slug', 'city', 'country', DB::raw("'location' as type"))
            ->take(4)
            ->get();

        // Build unified suggestions list
        $results = collect()
            ->concat($packages)
            ->concat($events)
            ->concat($pages)
            ->concat($locations)
            ->map(function ($item) use ($q) {
                $title = $item->title ?? ($item->title_fr ?? '');
                
                // Highlight search term
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
            ->sortByDesc(function($item) use ($q) {
                // Sort by relevance (exact match first, then contains)
                if (stripos($item['original_title'], $q) === 0) return 100;
                if (stripos($item['original_title'], $q) !== false) return 50;
                return 0;
            })
            ->values();

        return response()->json(['results' => $results]);
    }

    /**
     * Get subtitle for search result
     */
    private function getSubtitle($item)
    {
        switch($item->type ?? '') {
            case 'package':
                return $item->destination ?? 'Package';
            case 'event':
                return $item->venue_name ?? ($item->city ?? 'Événement');
            case 'location':
                return $item->city ?? ($item->country ?? 'Location');
            case 'page':
                return 'Page';
            default:
                return '';
        }
    }

    /**
     * Get URL for search result
     */
    private function getUrl($item)
    {
        switch($item->type ?? '') {
            case 'package':
                return $item->slug ? '/packages/' . $item->slug : '/packages';
            case 'event':
                return $item->slug ? '/events/' . $item->slug : '/events';
            case 'location':
                return $item->slug ? '/location/' . $item->slug : '/location';
            case 'page':
                return $item->slug ? '/' . $item->slug : '/';
            default:
                return '/search?q=' . urlencode($item->title ?? '');
        }
    }

    /**
     * Get icon for search result type
     */
    private function getIcon($type)
    {
        switch($type) {
            case 'package':
                return 'fas fa-suitcase';
            case 'event':
                return 'fas fa-calendar-alt';
            case 'location':
                return 'fas fa-map-marker-alt';
            case 'page':
                return 'fas fa-file-alt';
            case 'flight':
                return 'fas fa-plane';
            default:
                return 'fas fa-search';
        }
    }

    /**
     * Quick search for mobile header
     */
    public function quick(Request $request)
    {
        $q = trim($request->get('q', ''));
        
        if (empty($q) || strlen($q) < 1) {
            return response()->json(['results' => []]);
        }

        // Very fast search for mobile - only most relevant results
        $results = [];
        
        // Quick package search
        $packages = TourPackage::where('title_fr', 'like', '%' . $q . '%')
            ->orWhere('title_en', 'like', '%' . $q . '%')
            ->where('is_active', true)
            ->limit(3)
            ->get();

        foreach ($packages as $package) {
            $results[] = [
                'title' => $package->title_fr,
                'type' => 'package',
                'url' => '/packages/' . $package->slug,
                'icon' => 'fas fa-suitcase'
            ];
        }

        // Quick event search
        $events = Event::where('title_fr', 'like', '%' . $q . '%')
            ->orWhere('title_en', 'like', '%' . $q . '%')
            ->where('is_active', true)
            ->where('event_date', '>=', now())
            ->limit(3)
            ->get();

        foreach ($events as $event) {
            $results[] = [
                'title' => $event->title_fr,
                'type' => 'event',
                'url' => '/events/' . $event->slug,
                'icon' => 'fas fa-calendar-alt'
            ];
        }

        return response()->json(['results' => $results]);
    }
}
