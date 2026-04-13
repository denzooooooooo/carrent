<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $query = Review::query()->with(['user', 'booking', 'event', 'package']);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($builder) use ($search) {
                $builder->where('title', 'like', "%{$search}%")
                    ->orWhere('comment', 'like', "%{$search}%")
                    ->orWhere('admin_response', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('item_type')) {
            $query->where('item_type', $request->input('item_type'));
        }

        if ($request->filled('is_approved')) {
            $query->where('is_approved', $request->boolean('is_approved'));
        }

        if ($request->filled('is_verified')) {
            $query->where('is_verified', $request->boolean('is_verified'));
        }

        if ($request->filled('rating')) {
            $query->where('rating', (int) $request->input('rating'));
        }

        $reviews = $query->latest()->paginate(15)->withQueryString();

        $stats = [
            'total' => Review::count(),
            'approved' => Review::where('is_approved', true)->count(),
            'pending' => Review::where('is_approved', false)->count(),
            'verified' => Review::where('is_verified', true)->count(),
            'average_rating' => round((float) Review::avg('rating'), 1),
        ];

        return view('admin.reviews.index', compact('reviews', 'stats'));
    }

    public function create()
    {
        return redirect()
            ->route('admin.reviews.index')
            ->with('error', 'Les avis sont générés par les clients et ne se créent pas manuellement.');
    }

    public function store(Request $request)
    {
        return redirect()
            ->route('admin.reviews.index')
            ->with('error', 'Les avis sont générés par les clients et ne se créent pas manuellement.');
    }

    public function show(string $id)
    {
        return redirect()->route('admin.reviews.edit', $id);
    }

    public function edit(string $id)
    {
        $review = Review::with(['user', 'booking', 'event', 'package'])->findOrFail($id);

        return view('admin.reviews.edit', compact('review'));
    }

    public function update(Request $request, string $id)
    {
        $review = Review::findOrFail($id);

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'nullable|string|max:255',
            'comment' => 'nullable|string',
            'pros' => 'nullable|string',
            'cons' => 'nullable|string',
            'admin_response' => 'nullable|string',
            'is_verified' => 'boolean',
            'is_approved' => 'boolean',
        ]);

        $validated['is_verified'] = $request->boolean('is_verified');
        $validated['is_approved'] = $request->boolean('is_approved');

        try {
            $review->update($validated);

            return redirect()
                ->route('admin.reviews.edit', $review->id)
                ->with('success', 'Avis mis à jour avec succès.');
        } catch (\Throwable $e) {
            Log::error('Admin review update failed', [
                'admin_id' => auth('admin')->id(),
                'review_id' => $review->id,
                'message' => $e->getMessage(),
            ]);

            return back()
                ->withInput()
                ->with('error', 'La mise à jour de l’avis a échoué. Vérifie les champs puis réessaie.');
        }
    }

    public function destroy(string $id)
    {
        $review = Review::findOrFail($id);

        try {
            $review->delete();

            return redirect()
                ->route('admin.reviews.index')
                ->with('success', 'Avis supprimé avec succès.');
        } catch (\Throwable $e) {
            Log::error('Admin review deletion failed', [
                'admin_id' => auth('admin')->id(),
                'review_id' => $review->id,
                'message' => $e->getMessage(),
            ]);

            return back()->with('error', 'La suppression de l’avis a échoué.');
        }
    }
}
