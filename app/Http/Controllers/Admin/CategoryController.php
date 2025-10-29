<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\EventCategory;
use App\Models\EventType;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    /**
     * Display a listing of all categories types
     */
    public function index(Request $request)
    {
        $type = $request->get('type', 'category'); // category, event_category, event_type
        
        switch ($type) {
            case 'event_category':
                return $this->indexEventCategories($request);
            case 'event_type':
                return $this->indexEventTypes($request);
            default:
                return $this->indexCategories($request);
        }
    }

    /**
     * Liste des catégories simples
     */
    private function indexCategories(Request $request)
    {
        $query = Category::with(['parent', 'children']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name_fr', 'like', "%{$search}%")
                  ->orWhere('name_en', 'like', "%{$search}%");
            });
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        $categories = $query->orderBy('order_position')->paginate(15);
        
        return view('admin.categories.index', [
            'categories' => $categories,
            'type' => 'category'
        ]);
    }

    /**
     * Liste des catégories d'événements
     */
    private function indexEventCategories(Request $request)
    {
        $query = EventCategory::withCount(['types', 'events']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name_fr', 'like', "%{$search}%")
                  ->orWhere('name_en', 'like', "%{$search}%");
            });
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        $categories = $query->latest()->paginate(15);
        
        return view('admin.categories.index', [
            'categories' => $categories,
            'type' => 'event_category'
        ]);
    }

    /**
     * Liste des types d'événements
     */
    private function indexEventTypes(Request $request)
    {
        $query = EventType::with(['category'])->withCount('events');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name_fr', 'like', "%{$search}%")
                  ->orWhere('name_en', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        $types = $query->latest()->paginate(15);
        $eventCategories = EventCategory::where('is_active', true)->get();
        
        return view('admin.categories.index', [
            'categories' => $types,
            'type' => 'event_type',
            'eventCategories' => $eventCategories
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $type = $request->get('type', 'category');
        
        $data = [
            'type' => $type,
            'parentCategories' => Category::whereNull('parent_id')->where('is_active', true)->get(),
            'eventCategories' => EventCategory::where('is_active', true)->get(),
        ];
        
        return view('admin.categories.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $type = $request->input('type', 'category');
        
        switch ($type) {
            case 'event_category':
                return $this->storeEventCategory($request);
            case 'event_type':
                return $this->storeEventType($request);
            default:
                return $this->storeCategory($request);
        }
    }

    /**
     * Créer une catégorie simple
     */
    private function storeCategory(Request $request)
    {
        $validated = $request->validate([
            'name_fr' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'description_fr' => 'nullable|string',
            'description_en' => 'nullable|string',
            'icon' => 'nullable|string|max:100',
            'parent_id' => 'nullable|exists:categories,id',
            'order_position' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        DB::beginTransaction();
        try {
            $validated['slug'] = $this->generateUniqueSlug($validated['name_fr'], Category::class);
            
            $category = Category::create($validated);

            if ($request->hasFile('avatar')) {
                $category->addMediaFromRequest('avatar')->toMediaCollection('avatar');
            }

            DB::commit();

            return redirect()->route('admin.categories.index', ['type' => 'category'])
                ->with('success', 'Catégorie créée avec succès !');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()
                ->with('error', 'Erreur : ' . $e->getMessage());
        }
    }

    /**
     * Créer une catégorie d'événement
     */
    private function storeEventCategory(Request $request)
    {
        $validated = $request->validate([
            'name_fr' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        DB::beginTransaction();
        try {
            $validated['slug'] = $this->generateUniqueSlug($validated['name_fr'], EventCategory::class);
            
            $category = EventCategory::create($validated);

            if ($request->hasFile('avatar')) {
                $category->addMediaFromRequest('avatar')->toMediaCollection('avatar');
            }

            DB::commit();

            return redirect()->route('admin.categories.index', ['type' => 'event_category'])
                ->with('success', 'Catégorie d\'événement créée avec succès !');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()
                ->with('error', 'Erreur : ' . $e->getMessage());
        }
    }

    /**
     * Créer un type d'événement
     */
    private function storeEventType(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:event_categories,id',
            'name_fr' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        DB::beginTransaction();
        try {
            $validated['slug'] = $this->generateUniqueSlug($validated['name_fr'], EventType::class);
            
            $type = EventType::create($validated);

            if ($request->hasFile('avatar')) {
                $type->addMediaFromRequest('avatar')->toMediaCollection('avatar');
            }

            DB::commit();

            return redirect()->route('admin.categories.index', ['type' => 'event_type'])
                ->with('success', 'Type d\'événement créé avec succès !');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()
                ->with('error', 'Erreur : ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $id)
    {
        $type = $request->get('type', 'category');
        
        switch ($type) {
            case 'event_category':
                $category = EventCategory::with(['types', 'events'])->findOrFail($id);
                break;
            case 'event_type':
                $category = EventType::with(['category', 'events'])->findOrFail($id);
                break;
            default:
                $category = Category::with(['parent', 'children', 'events', 'packages'])->findOrFail($id);
        }
        
        return view('admin.categories.show', [
            'category' => $category,
            'type' => $type
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, $id)
    {
        $type = $request->get('type', 'category');
        
        switch ($type) {
            case 'event_category':
                $category = EventCategory::findOrFail($id);
                break;
            case 'event_type':
                $category = EventType::findOrFail($id);
                break;
            default:
                $category = Category::findOrFail($id);
        }
        
        $data = [
            'category' => $category,
            'type' => $type,
            'parentCategories' => Category::whereNull('parent_id')
                ->where('is_active', true)
                ->where('id', '!=', $id)
                ->get(),
            'eventCategories' => EventCategory::where('is_active', true)->get(),
        ];
        
        return view('admin.categories.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $type = $request->input('type', 'category');
        
        switch ($type) {
            case 'event_category':
                return $this->updateEventCategory($request, $id);
            case 'event_type':
                return $this->updateEventType($request, $id);
            default:
                return $this->updateCategory($request, $id);
        }
    }

    /**
     * Mettre à jour une catégorie simple
     */
    private function updateCategory(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        
        $validated = $request->validate([
            'name_fr' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'description_fr' => 'nullable|string',
            'description_en' => 'nullable|string',
            'icon' => 'nullable|string|max:100',
            'parent_id' => 'nullable|exists:categories,id',
            'order_position' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        DB::beginTransaction();
        try {
            if ($category->name_fr !== $validated['name_fr']) {
                $validated['slug'] = $this->generateUniqueSlug($validated['name_fr'], Category::class, $id);
            }
            
            $category->update($validated);

            if ($request->hasFile('avatar')) {
                $category->clearMediaCollection('avatar');
                $category->addMediaFromRequest('avatar')->toMediaCollection('avatar');
            }

            DB::commit();

            return redirect()->route('admin.categories.index', ['type' => 'category'])
                ->with('success', 'Catégorie mise à jour avec succès !');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()
                ->with('error', 'Erreur : ' . $e->getMessage());
        }
    }

    /**
     * Mettre à jour une catégorie d'événement
     */
    private function updateEventCategory(Request $request, $id)
    {
        $category = EventCategory::findOrFail($id);
        
        $validated = $request->validate([
            'name_fr' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        DB::beginTransaction();
        try {
            if ($category->name_fr !== $validated['name_fr']) {
                $validated['slug'] = $this->generateUniqueSlug($validated['name_fr'], EventCategory::class, $id);
            }
            
            $category->update($validated);

            if ($request->hasFile('avatar')) {
                $category->clearMediaCollection('avatar');
                $category->addMediaFromRequest('avatar')->toMediaCollection('avatar');
            }

            DB::commit();

            return redirect()->route('admin.categories.index', ['type' => 'event_category'])
                ->with('success', 'Catégorie d\'événement mise à jour avec succès !');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()
                ->with('error', 'Erreur : ' . $e->getMessage());
        }
    }

    /**
     * Mettre à jour un type d'événement
     */
    private function updateEventType(Request $request, $id)
    {
        $type = EventType::findOrFail($id);
        
        $validated = $request->validate([
            'category_id' => 'required|exists:event_categories,id',
            'name_fr' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        DB::beginTransaction();
        try {
            if ($type->name_fr !== $validated['name_fr']) {
                $validated['slug'] = $this->generateUniqueSlug($validated['name_fr'], EventType::class, $id);
            }
            
            $type->update($validated);

            if ($request->hasFile('avatar')) {
                $type->clearMediaCollection('avatar');
                $type->addMediaFromRequest('avatar')->toMediaCollection('avatar');
            }

            DB::commit();

            return redirect()->route('admin.categories.index', ['type' => 'event_type'])
                ->with('success', 'Type d\'événement mis à jour avec succès !');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()
                ->with('error', 'Erreur : ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id)
    {
        $type = $request->get('type', 'category');
        
        DB::beginTransaction();
        try {
            switch ($type) {
                case 'event_category':
                    $category = EventCategory::findOrFail($id);
                    
                    if ($category->types()->count() > 0 || $category->events()->count() > 0) {
                        return redirect()->back()
                            ->with('error', 'Impossible de supprimer cette catégorie car elle contient des types ou des événements.');
                    }
                    break;
                    
                case 'event_type':
                    $category = EventType::findOrFail($id);
                    
                    if ($category->events()->count() > 0) {
                        return redirect()->back()
                            ->with('error', 'Impossible de supprimer ce type car il contient des événements.');
                    }
                    break;
                    
                default:
                    $category = Category::findOrFail($id);
                    
                    if ($category->children()->count() > 0 || $category->events()->count() > 0 || $category->packages()->count() > 0) {
                        return redirect()->back()
                            ->with('error', 'Impossible de supprimer cette catégorie car elle contient des sous-catégories, événements ou packages.');
                    }
            }
            
            $category->clearMediaCollection('avatar');
            $category->delete();
            
            DB::commit();

            return redirect()->route('admin.categories.index', ['type' => $type])
                ->with('success', 'Suppression effectuée avec succès !');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Erreur : ' . $e->getMessage());
        }
    }

    /**
     * Toggle active status
     */
    public function toggleStatus(Request $request, $id)
    {
        $type = $request->get('type', 'category');
        
        switch ($type) {
            case 'event_category':
                $item = EventCategory::findOrFail($id);
                break;
            case 'event_type':
                $item = EventType::findOrFail($id);
                break;
            default:
                $item = Category::findOrFail($id);
        }
        
        $item->update(['is_active' => !$item->is_active]);
        
        $status = $item->is_active ? 'activé' : 'désactivé';
        return redirect()->back()
            ->with('success', "Élément {$status} avec succès !");
    }

    /**
     * Générer un slug unique
     */
    private function generateUniqueSlug($name, $model, $excludeId = null)
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $count = 1;
        
        $query = $model::where('slug', $slug);
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }
        
        while ($query->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
            $query = $model::where('slug', $slug);
            if ($excludeId) {
                $query->where('id', '!=', $excludeId);
            }
        }
        
        return $slug;
    }
}