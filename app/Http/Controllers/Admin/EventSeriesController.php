<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EventSeries;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EventSeriesController extends Controller
{
    /**
     * Display a listing of the event series.
     */
    public function index()
    {
        $series = EventSeries::withCount('events')
            ->latest()
            ->paginate(12);
        
        return view('admin.event-series.index', compact('series'));
    }

    /**
     * Show the form for creating a new event series.
     */
    public function create()
    {
        $eventSeries = new EventSeries();
        $pageTitle = 'Créer une nouvelle Série d\'événements';
        
        return view('admin.event-series.form', compact('eventSeries', 'pageTitle'));
    }

    /**
     * Store a newly created event series in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name_fr' => 'required|string|max:255',
                'name_en' => 'required|string|max:255',
                'description_fr' => 'nullable|string',
                'description_en' => 'nullable|string',
                'venue_name' => 'nullable|string|max:255',
                'venue_address' => 'nullable|string',
                'city' => 'nullable|string|max:100',
                'country' => 'nullable|string|max:100',
                'start_date' => 'nullable|date',
                'end_date' => 'nullable|date|after_or_equal:start_date',
                'organizer' => 'nullable|string|max:255',
                'sport_type' => 'nullable|string|max:100',
                'main_image' => 'nullable|image|max:2048',
                'cover_image' => 'nullable|image|max:2048',
                'is_featured' => 'nullable|boolean',
                'is_active' => 'nullable|boolean',
            ]);

            // Generate slug
            $validated['slug'] = Str::slug($validated['name_fr']);
            
            // Ensure unique slug
            $count = EventSeries::where('slug', 'like', $validated['slug'] . '%')->count();
            if ($count > 0) {
                $validated['slug'] = $validated['slug'] . '-' . ($count + 1);
            }

            // Handle booleans
            $validated['is_featured'] = $request->boolean('is_featured');
            $validated['is_active'] = $request->boolean('is_active', true);

            DB::beginTransaction();

            $eventSeries = EventSeries::create($validated);

            // Handle images
            if ($request->hasFile('main_image') && $request->file('main_image')->isValid()) {
                $eventSeries->addMediaFromRequest('main_image')
                    ->toMediaCollection('main_image');
            }
            if ($request->hasFile('cover_image') && $request->file('cover_image')->isValid()) {
                $eventSeries->addMediaFromRequest('cover_image')
                    ->toMediaCollection('cover_image');
            }

            DB::commit();

            return redirect()->route('admin.event-series.index')
                ->with('success', 'La série d\'événements **' . $eventSeries->name_fr . '** a été créée avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Erreur lors de la création : ' . $e->getMessage());
        }
    }

    /**
     * Display the specified event series.
     */
    public function show(EventSeries $eventSeries)
    {
        $eventSeries->load(['events.category', 'events.packages']);
        
        return view('admin.event-series.show', compact('eventSeries'));
    }

    /**
     * Show the form for editing the specified event series.
     */
    public function edit(EventSeries $eventSeries)
    {
        $pageTitle = 'Modifier la Série : ' . $eventSeries->name_fr;
        
        return view('admin.event-series.form', compact('eventSeries', 'pageTitle'));
    }

    /**
     * Update the specified event series in storage.
     */
    public function update(Request $request, EventSeries $eventSeries)
    {
        try {
            $validated = $request->validate([
                'name_fr' => 'required|string|max:255',
                'name_en' => 'required|string|max:255',
                'description_fr' => 'nullable|string',
                'description_en' => 'nullable|string',
                'venue_name' => 'nullable|string|max:255',
                'venue_address' => 'nullable|string',
                'city' => 'nullable|string|max:100',
                'country' => 'nullable|string|max:100',
                'start_date' => 'nullable|date',
                'end_date' => 'nullable|date|after_or_equal:start_date',
                'organizer' => 'nullable|string|max:255',
                'sport_type' => 'nullable|string|max:100',
                'main_image' => 'nullable|image|max:2048',
                'cover_image' => 'nullable|image|max:2048',
                'remove_main_image' => 'nullable|boolean',
                'remove_cover_image' => 'nullable|boolean',
                'is_featured' => 'nullable|boolean',
                'is_active' => 'nullable|boolean',
            ]);

            // Update slug if name changed
            if ($eventSeries->name_fr !== $validated['name_fr']) {
                $newSlug = Str::slug($validated['name_fr']);
                $count = EventSeries::where('slug', 'like', $newSlug . '%')
                    ->where('id', '!=', $eventSeries->id)
                    ->count();
                if ($count > 0) {
                    $newSlug = $newSlug . '-' . ($count + 1);
                }
                $validated['slug'] = $newSlug;
            }

            // Handle booleans
            $validated['is_featured'] = $request->boolean('is_featured');
            $validated['is_active'] = $request->boolean('is_active', true);

            DB::beginTransaction();

            $eventSeries->update($validated);

            // Handle images
            if ($request->hasFile('main_image') && $request->file('main_image')->isValid()) {
                $eventSeries->clearMediaCollection('main_image');
                $eventSeries->addMediaFromRequest('main_image')
                    ->toMediaCollection('main_image');
            }
            if ($request->remove_main_image) {
                $eventSeries->clearMediaCollection('main_image');
            }

            if ($request->hasFile('cover_image') && $request->file('cover_image')->isValid()) {
                $eventSeries->clearMediaCollection('cover_image');
                $eventSeries->addMediaFromRequest('cover_image')
                    ->toMediaCollection('cover_image');
            }
            if ($request->remove_cover_image) {
                $eventSeries->clearMediaCollection('cover_image');
            }

            DB::commit();

            return redirect()->route('admin.event-series.index')
                ->with('success', 'La série d\'événements **' . $eventSeries->name_fr . '** a été mise à jour avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Erreur lors de la mise à jour : ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified event series from storage.
     */
    public function destroy(EventSeries $eventSeries)
    {
        try {
            $name = $eventSeries->name_fr;
            
            // Clear media
            $eventSeries->clearMediaCollection('main_image');
            $eventSeries->clearMediaCollection('cover_image');
            
            // Delete - events will have event_series_id set to null due to onDelete('set null')
            $eventSeries->delete();
            
            return redirect()->route('admin.event-series.index')
                ->with('success', 'La série d\'événements **' . $name . '** a été supprimée.');
                
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la suppression : ' . $e->getMessage());
        }
    }

    /**
     * Add an event to this series (create new event)
     */
    public function addEvent(Request $request, EventSeries $eventSeries)
    {
        // Redirect to event creation with series pre-selected
        return redirect()->route('admin.events.create', ['series_id' => $eventSeries->id]);
    }
}
