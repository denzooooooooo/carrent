<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventCategory;
use App\Models\EventType;
use App\Models\EventPackage;
use App\Imports\EventPackagesImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // On charge la catégorie pour l'affichage dans la carte.
        $events = Event::with('category', 'type')->latest()->paginate(12);
        return view('admin.events.index', compact('events'));
    }

    /**
     * Show the import form
     */
    public function importForm()
    {
        return view('admin.events.import');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = EventCategory::where('is_active', true)->pluck('name_fr', 'id');
        $types = EventType::where('is_active', true)->pluck('name_fr', 'id');
        $event = new Event(); // Crée une instance vide pour le formulaire
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

            // Gestion des packages (grilles tarifaires)
            if ($request->has('packages')) {
                $this->storePackages($event, $request->input('packages'));
            }

            DB::commit();

            return redirect()->route('admin.events.index')->with('success', 'L\'événement **' . $event->title_fr . '** a été créé avec succès.');

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
        $event->load('category', 'type'); // Charge les relations pour l'affichage
        return view('admin.events.show', compact('event'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event)
    {
        $event->load('seatZones', 'packages'); // Load seat zones and packages for the form
        $categories = EventCategory::where('is_active', true)->pluck('name_fr', 'id');
        $types = EventType::where('is_active', true)->pluck('name_fr', 'id');
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

            // Gestion des packages (grilles tarifaires)
            if ($request->has('packages')) {
                $this->updatePackages($event, $request->input('packages'));
            }

            DB::commit();

            return redirect()->route('admin.events.index')->with('success', 'L\'événement **' . $event->title_fr . '** a été mis à jour avec succès.');

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
        // Filter out empty packages before validation
        $packages = $request->input('packages', []);
        $filteredPackages = array_filter($packages, function($package) {
            return !empty($package['package_name_fr']);
        });
        $request->merge(['packages' => $filteredPackages]);

        $rules = [
            'category_id' => ['required', 'exists:event_categories,id'],
            'type_id' => ['nullable', 'exists:event_types,id'], // Ajouté 'type_id' si vous utilisez EventType
            'title_fr' => ['required', 'string', 'max:255'],
            'title_en' => ['required', 'string', 'max:255'],
            'description_fr' => ['nullable', 'string'],
            'description_en' => ['nullable', 'string'],
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
            'packages.*.description_included_fr' => ['nullable', 'string'],
            'packages.*.price' => ['required', 'numeric', 'min:0'],
            'packages.*.currency' => ['nullable', 'string', 'max:10'],
            'packages.*.available_quantity' => ['nullable', 'integer', 'min:0'],
            'packages.*.max_per_order' => ['nullable', 'integer', 'min:1'],
            'packages.*.is_active' => ['nullable', 'boolean'],
            'packages.*.sort_order' => ['nullable', 'integer', 'min:0'],
        ];

        $validated = $request->validate($rules);

        // Ajout/Mise à jour du slug
        $validated['slug'] = Str::slug($validated['title_fr']);

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
            if (!empty($zoneData['zone_name_fr']) && !empty($zoneData['zone_name_en'])) {
                $event->seatZones()->create([
                    'zone_name_fr' => $zoneData['zone_name_fr'],
                    'zone_name_en' => $zoneData['zone_name_en'],
                    'zone_code' => $zoneData['zone_code'] ?? null,
                    'zone_type' => $zoneData['zone_type'] ?? 'standard',
                    'price' => $zoneData['price'] ?? 0,
                    'total_seats' => $zoneData['total_seats'] ?? 0,
                    'available_seats' => $zoneData['total_seats'] ?? 0,
                    'description_fr' => $zoneData['description_fr'] ?? null,
                    'description_en' => $zoneData['description_en'] ?? null,
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
        // Delete existing zones not in the new data
        $existingIds = collect($seatZonesData)->pluck('id')->filter()->toArray();
        $event->seatZones()->whereNotIn('id', $existingIds)->delete();

        foreach ($seatZonesData as $zoneData) {
            if (!empty($zoneData['zone_name_fr']) && !empty($zoneData['zone_name_en'])) {
                if (isset($zoneData['id']) && $zoneData['id']) {
                    // Update existing zone
                    $zone = $event->seatZones()->find($zoneData['id']);
                    if ($zone) {
                        $zone->update([
                            'zone_name_fr' => $zoneData['zone_name_fr'],
                            'zone_name_en' => $zoneData['zone_name_en'],
                            'zone_code' => $zoneData['zone_code'] ?? null,
                            'zone_type' => $zoneData['zone_type'] ?? 'standard',
                            'price' => $zoneData['price'] ?? 0,
                            'total_seats' => $zoneData['total_seats'] ?? 0,
                            'available_seats' => $zoneData['total_seats'] ?? 0,
                            'description_fr' => $zoneData['description_fr'] ?? null,
                            'description_en' => $zoneData['description_en'] ?? null,
                            'is_active' => isset($zoneData['is_active']) ? (bool)$zoneData['is_active'] : true,
                        ]);
                    }
                } else {
                    // Create new zone
                    $event->seatZones()->create([
                        'zone_name_fr' => $zoneData['zone_name_fr'],
                        'zone_name_en' => $zoneData['zone_name_en'],
                        'zone_code' => $zoneData['zone_code'] ?? null,
                        'zone_type' => $zoneData['zone_type'] ?? 'standard',
                        'price' => $zoneData['price'] ?? 0,
                        'total_seats' => $zoneData['total_seats'] ?? 0,
                        'available_seats' => $zoneData['total_seats'] ?? 0,
                        'description_fr' => $zoneData['description_fr'] ?? null,
                        'description_en' => $zoneData['description_en'] ?? null,
                        'is_active' => isset($zoneData['is_active']) ? (bool)$zoneData['is_active'] : true,
                    ]);
                }
            }
        }
    }

    /**
     * Store packages for an event
     */
    protected function storePackages(Event $event, array $packagesData)
    {
        foreach ($packagesData as $index => $packageData) {
            if (!empty($packageData['package_name_fr'])) {
                $event->packages()->create([
                    'package_name_fr' => $packageData['package_name_fr'],
                    'package_name_en' => $packageData['package_name_en'] ?? $packageData['package_name_fr'],
                    'package_code' => $packageData['package_code'] ?? null,
                    'description_fr' => $packageData['description_fr'] ?? null,
                    'description_included_fr' => $packageData['description_included_fr'] ?? null,
                    'price' => $packageData['price'] ?? 0,
                    'currency' => $packageData['currency'] ?? 'XOF',
                    'available_quantity' => $packageData['available_quantity'] ?? 100,
                    'max_per_order' => $packageData['max_per_order'] ?? 10,
                    'is_active' => isset($packageData['is_active']) ? (bool)$packageData['is_active'] : true,
                    'sort_order' => $packageData['sort_order'] ?? ($index + 1),
                ]);
            }
        }
    }

    /**
     * Update packages for an event
     */
    protected function updatePackages(Event $event, array $packagesData)
    {
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
                            'description_included_fr' => $packageData['description_included_fr'] ?? null,
                            'price' => $packageData['price'] ?? 0,
                            'currency' => $packageData['currency'] ?? 'XOF',
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
                        'description_included_fr' => $packageData['description_included_fr'] ?? null,
                        'price' => $packageData['price'] ?? 0,
                        'currency' => $packageData['currency'] ?? 'XOF',
                        'available_quantity' => $packageData['available_quantity'] ?? 100,
                        'max_per_order' => $packageData['max_per_order'] ?? 10,
                        'is_active' => isset($packageData['is_active']) ? (bool)$packageData['is_active'] : true,
                        'sort_order' => $packageData['sort_order'] ?? ($index + 1),
                    ]);
                }
            }
        }
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
}
