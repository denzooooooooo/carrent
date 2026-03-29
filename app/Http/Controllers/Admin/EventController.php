<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventCategory;
use App\Models\EventType;
use App\Models\EventPackage;
use App\Imports\EventPackagesImport;
use App\Services\PdfEventDraftImportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Event::query()
            ->with(['category', 'type'])
            ->withCount(['allPackages as packages_count', 'seatZones as seat_zones_count']);

        if ($request->filled('search')) {
            $search = trim((string) $request->input('search'));

            $query->where(function ($builder) use ($search) {
                $builder
                    ->where('title_fr', 'like', '%' . $search . '%')
                    ->orWhere('title_en', 'like', '%' . $search . '%')
                    ->orWhere('slug', 'like', '%' . $search . '%')
                    ->orWhere('venue_name', 'like', '%' . $search . '%')
                    ->orWhere('city', 'like', '%' . $search . '%');
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->integer('category_id'));
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->input('status') === 'active');
        }

        if ($request->filled('featured')) {
            $query->where('is_featured', $request->input('featured') === '1');
        }

        $sort = $request->input('sort', 'newest');

        match ($sort) {
            'soonest' => $query->orderBy('event_date')->orderBy('event_time'),
            'title' => $query->orderBy('title_fr'),
            default => $query->latest(),
        };

        $events = $query->paginate(12)->withQueryString();

        $stats = [
            'total' => Event::query()->count(),
            'active' => Event::query()->where('is_active', true)->count(),
            'featured' => Event::query()->where('is_featured', true)->count(),
            'drafts' => Event::query()->where('is_active', false)->count(),
        ];

        $categories = EventCategory::query()
            ->where('is_active', true)
            ->orderBy('name_fr')
            ->get();

        return view('admin.events.index', compact('events', 'stats', 'categories', 'sort'));
    }

    /**
     * Show the import form
     */
    public function importForm()
    {
        $categories = EventCategory::query()
            ->where('is_active', true)
            ->orderBy('name_fr')
            ->get();

        $types = EventType::query()
            ->where('is_active', true)
            ->orderBy('name_fr')
            ->get();

        $events = Event::query()
            ->latest('event_date')
            ->limit(100)
            ->get();

        return view('admin.events.import', compact('categories', 'types', 'events'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = EventCategory::query()->where('is_active', true)->orderBy('name_fr')->get();
        $types = EventType::query()->where('is_active', true)->orderBy('name_fr')->get();
        $event = new Event();
        $pageTitle = 'Créer un nouvel Événement';

        return view('admin.events.form', compact('event', 'categories', 'types', 'pageTitle'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Debug: Afficher les données reçues
            \Log::info('Event Store - Request Data:', $request->all());

            $validatedData = $this->validateEvent($request);

            // Debug: Afficher les données validées
            \Log::info('Event Store - Validated Data:', $validatedData);

            DB::beginTransaction();

            // Création de l'événement
            $event = Event::create($validatedData);

            // Gestion de l'image (Spatie Media Library)
            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                $event->addMediaFromRequest('image')
                      ->toMediaCollection('avatar');
            }

            $packagesSkipped = false;
            if ($request->has('packages')) {
                $packagesSkipped = !$this->storePackages($event, $request->input('packages', []));
            }

            if ($request->has('seat_zones')) {
                $this->storeSeatZones($event, $request->input('seat_zones', []));
            }

            $this->refreshPricingAndInventory($event);

            DB::commit();

            $redirect = redirect()->route('admin.events.index')
                ->with('success', 'L\'événement **' . $event->title_fr . '** a été créé avec succès.');

            if ($packagesSkipped) {
                $redirect->with('warning', 'L\'événement a été créé, mais les packages n\'ont pas été enregistrés car la table `event_packages` est absente. Veuillez exécuter les migrations.');
            }

            return $redirect;

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            \Log::error('Event Store - Validation Error:', $e->errors());
            // Ne pas inclure les fichiers uploadés dans les données de debug
            $debugData = $request->except(['image']);
            // Récupérer les logs récents pour debug
            $logContent = '';
            if (file_exists(storage_path('logs/laravel.log'))) {
                $logContent = file_get_contents(storage_path('logs/laravel.log'));
                $logLines = explode("\n", $logContent);
                $logContent = implode("\n", array_slice($logLines, -20)); // Dernières 20 lignes
            }
            return back()->withInput()->withErrors($e->errors())->with('error', 'Erreurs de validation')->with('debug_data', $debugData)->with('debug_logs', $logContent);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Event Store - General Error:', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            // Ne pas inclure les fichiers uploadés dans les données de debug
            $debugData = $request->except(['image']);
            // Récupérer les logs récents pour debug
            $logContent = '';
            if (file_exists(storage_path('logs/laravel.log'))) {
                $logContent = file_get_contents(storage_path('logs/laravel.log'));
                $logLines = explode("\n", $logContent);
                $logContent = implode("\n", array_slice($logLines, -20)); // Dernières 20 lignes
            }
            return back()->withInput()->with('error', 'Erreur lors de la création de l\'événement : ' . $e->getMessage())->with('debug_data', $debugData)->with('debug_logs', $logContent);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        $event->load(['category', 'type', 'seatZones', 'allPackages']);
        return view('admin.events.show', compact('event'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event)
    {
        $event->load('seatZones', 'allPackages');
        $categories = EventCategory::query()->where('is_active', true)->orderBy('name_fr')->get();
        $types = EventType::query()->where('is_active', true)->orderBy('name_fr')->get();
        $pageTitle = 'Modifier l\'Événement : ' . $event->title_fr;

        return view('admin.events.form', compact('event', 'categories', 'types', 'pageTitle'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event)
    {
        try {
            // Debug: Afficher les données reçues
            \Log::info('Event Update - Request Data:', $request->all());

            $validatedData = $this->validateEvent($request, $event);

            // Debug: Afficher les données validées
            \Log::info('Event Update - Validated Data:', $validatedData);

            DB::beginTransaction();

            // Mise à jour de l'événement
            $event->update($validatedData);

            // Gestion de l'image (Spatie Media Library)
            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                // Supprime l'ancien avatar et ajoute le nouveau
                $event->clearMediaCollection('avatar');
                $event->addMediaFromRequest('image')
                      ->toMediaCollection('avatar');
            }

            // Gestion de la suppression de l'image
            if ($request->input('remove_image')) {
                $event->clearMediaCollection('avatar');
            }

            $packagesSkipped = false;
            if ($request->has('packages')) {
                $packagesSkipped = !$this->updatePackages($event, $request->input('packages', []));
            }

            if ($request->has('seat_zones')) {
                $this->updateSeatZones($event, $request->input('seat_zones', []));
            }

            $this->refreshPricingAndInventory($event);

            DB::commit();

            $redirect = redirect()->route('admin.events.index')
                ->with('success', 'L\'événement **' . $event->title_fr . '** a été mis à jour avec succès.');

            if ($packagesSkipped) {
                $redirect->with('warning', 'L\'événement a été mis à jour, mais les packages n\'ont pas été enregistrés car la table `event_packages` est absente. Veuillez exécuter les migrations.');
            }

            return $redirect;

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            \Log::error('Event Update - Validation Error:', $e->errors());
            // Ne pas inclure les fichiers uploadés dans les données de debug
            $debugData = $request->except(['image']);
            // Récupérer les logs récents pour debug
            $logContent = '';
            if (file_exists(storage_path('logs/laravel.log'))) {
                $logContent = file_get_contents(storage_path('logs/laravel.log'));
                $logLines = explode("\n", $logContent);
                $logContent = implode("\n", array_slice($logLines, -20)); // Dernières 20 lignes
            }
            return back()->withInput()->withErrors($e->errors())->with('error', 'Erreurs de validation')->with('debug_data', $debugData)->with('debug_logs', $logContent);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Event Update - General Error:', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            // Ne pas inclure les fichiers uploadés dans les données de debug
            $debugData = $request->except(['image']);
            // Récupérer les logs récents pour debug
            $logContent = '';
            if (file_exists(storage_path('logs/laravel.log'))) {
                $logContent = file_get_contents(storage_path('logs/laravel.log'));
                $logLines = explode("\n", $logContent);
                $logContent = implode("\n", array_slice($logLines, -20)); // Dernières 20 lignes
            }
            return back()->withInput()->with('error', 'Erreur lors de la mise à jour de l\'événement : ' . $e->getMessage())->with('debug_data', $debugData)->with('debug_logs', $logContent);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        $title = $event->title_fr;
        try {
            // Suppression des médias associés
            $event->clearMediaCollection('avatar');
            // Suppression de l'événement
            $event->delete();
            return redirect()->route('admin.events.index')->with('success', 'L\'événement **' . $title . '** a été supprimé avec succès.');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la suppression de l\'événement : ' . $e->getMessage());
        }
    }

    /**
     * Valide les données de la requête pour la création ou la mise à jour d'un événement.
     */
    protected function validateEvent(Request $request, ?Event $event = null)
    {
        // Filter out empty packages and zones before validation
        $packages = $request->input('packages', []);
        $filteredPackages = array_filter($packages, function($package) {
            return !empty($package['package_name_fr']);
        });
        $seatZones = $request->input('seat_zones', []);
        $filteredSeatZones = array_filter($seatZones, function ($zone) {
            return !empty($zone['zone_name_fr']);
        });
        $request->merge([
            'packages' => $filteredPackages,
            'seat_zones' => $filteredSeatZones,
        ]);

        $rules = [
            'category_id' => ['required', 'exists:event_categories,id'],
            'type_id' => ['nullable', 'exists:event_types,id'],
            'family' => ['nullable', 'in:sportif,culturel'],
            'title_fr' => ['required', 'string', 'max:255'],
            'title_en' => ['required', 'string', 'max:255'],
            'tagline_fr' => ['nullable', 'string', 'max:255'],
            'tagline_en' => ['nullable', 'string', 'max:255'],
            'description_fr' => ['nullable', 'string'],
            'description_en' => ['nullable', 'string'],
            'program_fr' => ['nullable', 'string'],
            'program_en' => ['nullable', 'string'],
            'conditions_fr' => ['nullable', 'string'],
            'conditions_en' => ['nullable', 'string'],
            'source_catalog' => ['nullable', 'string', 'max:255'],
            'venue_name' => ['required', 'string', 'max:255'],
            'venue_address' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:100'],
            'country' => ['required', 'string', 'max:100'],
            'event_date' => ['required', 'date'],
            'event_time' => ['required', 'regex:/^([01]?[0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?$/'],
            'end_date' => ['nullable', 'date', 'after_or_equal:event_date'],
            'end_time' => ['nullable', 'date_format:H:i', 'required_with:end_date'],
            'image' => ['nullable', 'image', 'max:2048'], // 2MB max
            'min_price' => ['required', 'numeric', 'min:0'],
            'max_price' => ['nullable', 'numeric', 'min:0', 'gte:min_price'],
            'total_seats' => ['required', 'integer', 'min:1'],
            'organizer' => ['nullable', 'string', 'max:255'],
            'is_featured' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'], 
            'meta_title_fr' => ['nullable', 'string', 'max:255'],
            'meta_title_en' => ['nullable', 'string', 'max:255'],
            'meta_description_fr' => ['nullable', 'string', 'max:500'],
            'meta_description_en' => ['nullable', 'string', 'max:500'],
            'packages' => ['nullable', 'array'],
            'packages.*.package_name_fr' => ['required', 'string', 'max:255'],
            'packages.*.package_name_en' => ['nullable', 'string', 'max:255'],
            'packages.*.package_code' => ['nullable', 'string', 'max:50'],
            'packages.*.description_fr' => ['nullable', 'string'],
            'packages.*.venue_details_fr' => ['nullable', 'string', 'max:255'],
            'packages.*.description_included_fr' => ['nullable', 'string'],
            'packages.*.price' => ['required', 'numeric', 'min:0'],
            'packages.*.currency' => ['nullable', 'string', 'max:10'],
            'packages.*.minimum_quantity' => ['nullable', 'integer', 'min:1'],
            'packages.*.available_quantity' => ['nullable', 'integer', 'min:0'],
            'packages.*.max_per_order' => ['nullable', 'integer', 'min:1'],
            'packages.*.is_active' => ['nullable', 'boolean'],
            'packages.*.sort_order' => ['nullable', 'integer', 'min:0'],
            'seat_zones' => ['nullable', 'array'],
            'seat_zones.*.id' => ['nullable', 'integer'],
            'seat_zones.*.zone_name_fr' => ['required', 'string', 'max:255'],
            'seat_zones.*.zone_name_en' => ['nullable', 'string', 'max:255'],
            'seat_zones.*.zone_code' => ['nullable', 'string', 'max:50'],
            'seat_zones.*.zone_type' => ['nullable', 'string', 'max:50'],
            'seat_zones.*.price' => ['required', 'numeric', 'min:0'],
            'seat_zones.*.total_seats' => ['required', 'integer', 'min:0'],
            'seat_zones.*.available_seats' => ['nullable', 'integer', 'min:0'],
            'seat_zones.*.description_fr' => ['nullable', 'string'],
            'seat_zones.*.description_en' => ['nullable', 'string'],
            'seat_zones.*.is_active' => ['nullable', 'boolean'],
        ];

        $messages = [
            'category_id.required' => 'La catégorie est obligatoire.',
            'category_id.exists' => 'La catégorie sélectionnée est invalide.',
            'title_fr.required' => 'Le titre en français est obligatoire.',
            'title_en.required' => 'Le titre en anglais est obligatoire.',
            'venue_name.required' => 'Le nom du lieu est obligatoire.',
            'venue_address.required' => 'L\'adresse du lieu est obligatoire.',
            'city.required' => 'La ville est obligatoire.',
            'country.required' => 'Le pays est obligatoire.',
            'event_date.required' => 'La date de l\'événement est obligatoire.',
            'event_date.date' => 'La date de l\'événement doit être une date valide.',
            'event_time.required' => 'L\'heure de l\'événement est obligatoire.',
            'event_time.regex' => 'L\'heure de l\'événement doit être au format HH:MM.',
            'end_date.after_or_equal' => 'La date de fin doit être égale ou postérieure à la date de début.',
            'image.image' => 'Le fichier doit être une image.',
            'image.max' => 'L\'image ne doit pas dépasser 2 Mo.',
            'min_price.required' => 'Le prix minimum est obligatoire.',
            'min_price.numeric' => 'Le prix minimum doit être un nombre.',
            'min_price.min' => 'Le prix minimum doit être supérieur ou égal à 0.',
            'max_price.numeric' => 'Le prix maximum doit être un nombre.',
            'max_price.min' => 'Le prix maximum doit être supérieur ou égal à 0.',
            'max_price.gte' => 'Le prix maximum (:value) doit être supérieur ou égal au prix minimum.',
            'total_seats.required' => 'Le nombre total de places est obligatoire.',
            'total_seats.integer' => 'Le nombre total de places doit être un entier.',
            'total_seats.min' => 'Le nombre total de places doit être au moins 1.',
            'packages.*.package_name_fr.required' => 'Le nom du package en français est obligatoire.',
            'packages.*.price.required' => 'Le prix du package est obligatoire.',
            'packages.*.price.numeric' => 'Le prix du package doit être un nombre.',
            'packages.*.price.min' => 'Le prix du package doit être supérieur ou égal à 0.',
            'packages.*.available_quantity.integer' => 'La quantité disponible doit être un entier.',
            'packages.*.max_per_order.integer' => 'Le maximum par commande doit être un entier.',
        ];

        $validated = $request->validate($rules, $messages);

        // Ajout/Mise à jour du slug
        $validated['slug'] = Str::slug($validated['title_fr']);

        if ($event && $event->slug !== $validated['slug']) {
            $validated['slug'] = $this->makeUniqueSlug($validated['slug'], $event->id);
        }

        if (!$event) {
            $validated['slug'] = $this->makeUniqueSlug($validated['slug']);
        }

        // Le champ 'available_seats' devrait être géré par un système d'inventaire. 
        // Pour l'instant, on le définit égal à total_seats à la création (si non défini)
        if (!$event) {
            $validated['available_seats'] = $validated['total_seats'];
        }

        // On s'assure que les booléens sont bien présents (même si la case n'est pas cochée)
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_active'] = $request->boolean('is_active');

        return $validated;
    }

    /**
     * Store seat zones for an event
     */
    protected function storeSeatZones(Event $event, array $seatZonesData)
    {
        foreach ($seatZonesData as $zoneData) {
            if (!empty($zoneData['zone_name_fr'])) {
                $event->seatZones()->create([
                    'zone_name_fr' => $zoneData['zone_name_fr'],
                    'zone_name_en' => $zoneData['zone_name_en'] ?? $zoneData['zone_name_fr'],
                    'zone_code' => $zoneData['zone_code'] ?? null,
                    'zone_type' => $zoneData['zone_type'] ?? 'standard',
                    'price' => $zoneData['price'] ?? 0,
                    'total_seats' => $zoneData['total_seats'] ?? 0,
                    'available_seats' => $zoneData['available_seats'] ?? ($zoneData['total_seats'] ?? 0),
                    'description_fr' => $zoneData['description_fr'] ?? null,
                    'description_en' => $zoneData['description_en'] ?? ($zoneData['description_fr'] ?? null),
                    'is_active' => isset($zoneData['is_active']) ? (bool)$zoneData['is_active'] : true,
                ]);
            }
        }
    }

    /**
     * Update seat zones for an event
     */
    protected function updateSeatZones(Event $event, array $seatZonesData)
    {
        $existingIds = collect($seatZonesData)->pluck('id')->filter()->toArray();
        $event->seatZones()->whereNotIn('id', $existingIds)->delete();

        foreach ($seatZonesData as $zoneData) {
            if (!empty($zoneData['zone_name_fr'])) {
                if (isset($zoneData['id']) && $zoneData['id']) {
                    $zone = $event->seatZones()->find($zoneData['id']);
                    if ($zone) {
                        $zone->update([
                            'zone_name_fr' => $zoneData['zone_name_fr'],
                            'zone_name_en' => $zoneData['zone_name_en'] ?? $zoneData['zone_name_fr'],
                            'zone_code' => $zoneData['zone_code'] ?? null,
                            'zone_type' => $zoneData['zone_type'] ?? 'standard',
                            'price' => $zoneData['price'] ?? 0,
                            'total_seats' => $zoneData['total_seats'] ?? 0,
                            'available_seats' => $zoneData['available_seats'] ?? ($zoneData['total_seats'] ?? 0),
                            'description_fr' => $zoneData['description_fr'] ?? null,
                            'description_en' => $zoneData['description_en'] ?? ($zoneData['description_fr'] ?? null),
                            'is_active' => isset($zoneData['is_active']) ? (bool)$zoneData['is_active'] : true,
                        ]);
                    }
                } else {
                    $event->seatZones()->create([
                        'zone_name_fr' => $zoneData['zone_name_fr'],
                        'zone_name_en' => $zoneData['zone_name_en'] ?? $zoneData['zone_name_fr'],
                        'zone_code' => $zoneData['zone_code'] ?? null,
                        'zone_type' => $zoneData['zone_type'] ?? 'standard',
                        'price' => $zoneData['price'] ?? 0,
                        'total_seats' => $zoneData['total_seats'] ?? 0,
                        'available_seats' => $zoneData['available_seats'] ?? ($zoneData['total_seats'] ?? 0),
                        'description_fr' => $zoneData['description_fr'] ?? null,
                        'description_en' => $zoneData['description_en'] ?? ($zoneData['description_fr'] ?? null),
                        'is_active' => isset($zoneData['is_active']) ? (bool)$zoneData['is_active'] : true,
                    ]);
                }
            }
        }
    }

    /**
     * Store packages for an event
     */
    protected function storePackages(Event $event, array $packagesData): bool
    {
        if (!Schema::hasTable('event_packages')) {
            \Log::warning("La table 'event_packages' est absente. Packages ignorés lors de la création de l'événement.", [
                'event_id' => $event->id,
                'packages_count' => count($packagesData),
            ]);
            return false;
        }

        foreach ($packagesData as $index => $packageData) {
            if (!empty($packageData['package_name_fr'])) {
                $event->packages()->create([
                    'package_name_fr' => $packageData['package_name_fr'],
                    'package_name_en' => $packageData['package_name_en'] ?? $packageData['package_name_fr'],
                    'package_code' => $packageData['package_code'] ?? null,
                    'description_fr' => $packageData['description_fr'] ?? null,
                    'venue_details_fr' => $packageData['venue_details_fr'] ?? null,
                    'venue_details_en' => $packageData['venue_details_en'] ?? ($packageData['venue_details_fr'] ?? null),
                    'description_included_fr' => $packageData['description_included_fr'] ?? null,
                    'description_included_en' => $packageData['description_included_en'] ?? ($packageData['description_included_fr'] ?? null),
                    'price' => $packageData['price'] ?? 0,
                    'currency' => $packageData['currency'] ?? 'XOF',
                    'minimum_quantity' => $packageData['minimum_quantity'] ?? 1,
                    'available_quantity' => $packageData['available_quantity'] ?? 100,
                    'max_per_order' => $packageData['max_per_order'] ?? 10,
                    'is_active' => isset($packageData['is_active']) ? (bool)$packageData['is_active'] : true,
                    'sort_order' => $packageData['sort_order'] ?? ($index + 1),
                ]);
            }
        }

        return true;
    }

    /**
     * Update packages for an event
     */
    protected function updatePackages(Event $event, array $packagesData): bool
    {
        if (!Schema::hasTable('event_packages')) {
            \Log::warning("La table 'event_packages' est absente. Packages ignorés lors de la mise à jour de l'événement.", [
                'event_id' => $event->id,
                'packages_count' => count($packagesData),
            ]);
            return false;
        }

        // Delete existing packages not in the new data
        $existingIds = collect($packagesData)->pluck('id')->filter()->toArray();
        $event->packages()->whereNotIn('id', $existingIds)->delete();

        foreach ($packagesData as $index => $packageData) {
            if (!empty($packageData['package_name_fr'])) {
                if (isset($packageData['id']) && $packageData['id']) {
                    // Update existing package
                    $package = $event->packages()->find($packageData['id']);
                    if ($package) {
                        $package->update([
                            'package_name_fr' => $packageData['package_name_fr'],
                            'package_name_en' => $packageData['package_name_en'] ?? $packageData['package_name_fr'],
                            'package_code' => $packageData['package_code'] ?? null,
                            'description_fr' => $packageData['description_fr'] ?? null,
                            'venue_details_fr' => $packageData['venue_details_fr'] ?? null,
                            'venue_details_en' => $packageData['venue_details_en'] ?? ($packageData['venue_details_fr'] ?? null),
                            'description_included_fr' => $packageData['description_included_fr'] ?? null,
                            'description_included_en' => $packageData['description_included_en'] ?? ($packageData['description_included_fr'] ?? null),
                            'price' => $packageData['price'] ?? 0,
                            'currency' => $packageData['currency'] ?? 'XOF',
                            'minimum_quantity' => $packageData['minimum_quantity'] ?? 1,
                            'available_quantity' => $packageData['available_quantity'] ?? 100,
                            'max_per_order' => $packageData['max_per_order'] ?? 10,
                            'is_active' => isset($packageData['is_active']) ? (bool)$packageData['is_active'] : true,
                            'sort_order' => $packageData['sort_order'] ?? ($index + 1),
                        ]);
                    }
                } else {
                    // Create new package
                    $event->packages()->create([
                        'package_name_fr' => $packageData['package_name_fr'],
                        'package_name_en' => $packageData['package_name_en'] ?? $packageData['package_name_fr'],
                        'package_code' => $packageData['package_code'] ?? null,
                        'description_fr' => $packageData['description_fr'] ?? null,
                        'venue_details_fr' => $packageData['venue_details_fr'] ?? null,
                        'venue_details_en' => $packageData['venue_details_en'] ?? ($packageData['venue_details_fr'] ?? null),
                        'description_included_fr' => $packageData['description_included_fr'] ?? null,
                        'description_included_en' => $packageData['description_included_en'] ?? ($packageData['description_included_fr'] ?? null),
                        'price' => $packageData['price'] ?? 0,
                        'currency' => $packageData['currency'] ?? 'XOF',
                        'minimum_quantity' => $packageData['minimum_quantity'] ?? 1,
                        'available_quantity' => $packageData['available_quantity'] ?? 100,
                        'max_per_order' => $packageData['max_per_order'] ?? 10,
                        'is_active' => isset($packageData['is_active']) ? (bool)$packageData['is_active'] : true,
                        'sort_order' => $packageData['sort_order'] ?? ($index + 1),
                    ]);
                }
            }
        }

        return true;
    }

    /**
     * Quick store a new event category from the form
     */
    public function quickStoreCat(Request $request)
    {
        $request->validate([
            'name_fr' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
        ]);

        $category = \App\Models\EventCategory::create([
            'name_fr' => $request->name_fr,
            'name_en' => $request->name_en ?? $request->name_fr,
            'is_active' => true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Catégorie créée avec succès',
            'category' => $category
        ]);
    }

    /**
     * Quick store a new event type from the form
     */
    public function quickStoreType(Request $request)
    {
        $request->validate([
            'name_fr' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
        ]);

        $type = \App\Models\EventType::create([
            'name_fr' => $request->name_fr,
            'name_en' => $request->name_en ?? $request->name_fr,
            'is_active' => true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Type créé avec succès',
            'type' => $type
        ]);
    }

    /**
     * Importer des packages depuis un fichier CSV (sans dépendance externe)
     */
    public function importPackages(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls,csv,txt|max:10240', // Excel + CSV, max 10MB
            'event_id'   => 'nullable|exists:events,id',
        ]);

        try {
            DB::beginTransaction();

            $file      = $request->file('excel_file');
            $filePath  = $file->getRealPath();
            $extension = $file->getClientOriginalExtension();

            $import = new EventPackagesImport();
            $import->setEventId($request->integer('event_id'));
            $import->import($filePath, $extension);

            $count  = $import->getRowCount();
            $errors = $import->getErrors();

            DB::commit();

            $message = $count . ' package(s) importé(s) avec succès!';
            if (!empty($errors)) {
                $message .= ' (' . count($errors) . ' ligne(s) ignorée(s) : ' . implode(', ', array_slice($errors, 0, 3)) . ')';
            }

            return redirect()->route('admin.events.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Import CSV Error: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors de l\'import : ' . $e->getMessage());
        }
    }

    public function importPdf(Request $request, PdfEventDraftImportService $pdfEventDraftImportService)
    {
        $validated = $request->validate([
            'pdf_file' => 'required|file|mimes:pdf|max:15360',
            'category_id' => 'nullable|exists:event_categories,id',
            'type_id' => 'nullable|exists:event_types,id',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'organizer' => 'nullable|string|max:255',
            'is_featured' => 'nullable|boolean',
            'publish_immediately' => 'nullable|boolean',
        ]);

        $result = $pdfEventDraftImportService->import($request->file('pdf_file'), [
            'category_id' => $validated['category_id'] ?? null,
            'type_id' => $validated['type_id'] ?? null,
            'city' => $validated['city'] ?? null,
            'country' => $validated['country'] ?? null,
            'organizer' => $validated['organizer'] ?? null,
            'is_featured' => $request->boolean('is_featured'),
            'publish_immediately' => $request->boolean('publish_immediately'),
        ]);

        $message = 'Événement importé depuis PDF : ' . $result['event']->title_fr . '.';
        $message .= ' ' . $result['summary']['packages_count'] . ' package(s) détecté(s).';

        if (!empty($result['summary']['warnings'])) {
            $message .= ' Vérifications manuelles conseillées : ' . implode(' ', $result['summary']['warnings']);
        }

        return redirect()
            ->route('admin.events.edit', $result['event'])
            ->with('success', $message);
    }

    protected function refreshPricingAndInventory(Event $event): void
    {
        $event->loadMissing(['allPackages', 'seatZones']);

        $prices = [];
        $totalSeats = 0;
        $availableSeats = 0;

        foreach ($event->allPackages as $package) {
            if ((float) $package->price > 0) {
                $prices[] = (float) $package->price;
            }

            $totalSeats += max(0, (int) $package->available_quantity);
            $availableSeats += max(0, (int) $package->available_quantity);
        }

        foreach ($event->seatZones as $zone) {
            if ((float) $zone->price > 0) {
                $prices[] = (float) $zone->price;
            }

            $totalSeats += max(0, (int) $zone->total_seats);
            $availableSeats += max(0, (int) $zone->available_seats);
        }

        $event->forceFill([
            'min_price' => !empty($prices) ? min($prices) : ($event->min_price ?? 0),
            'max_price' => !empty($prices) ? max($prices) : ($event->max_price ?? 0),
            'total_seats' => $totalSeats > 0 ? $totalSeats : ($event->total_seats ?? 0),
            'available_seats' => $availableSeats > 0 ? $availableSeats : ($event->available_seats ?? 0),
        ])->save();
    }

    protected function makeUniqueSlug(string $slug, ?int $ignoreEventId = null): string
    {
        $baseSlug = $slug !== '' ? $slug : 'event';
        $candidate = $baseSlug;
        $counter = 2;

        while (
            Event::query()
                ->when($ignoreEventId, fn ($query) => $query->where('id', '!=', $ignoreEventId))
                ->where('slug', $candidate)
                ->exists()
        ) {
            $candidate = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $candidate;
    }
}
