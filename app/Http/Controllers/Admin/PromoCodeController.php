<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PromoCode;
use App\Models\PromoCodeUsage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class PromoCodeController extends Controller
{
    public function index(Request $request)
    {
        $query = PromoCode::query()->withCount('usages');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($builder) use ($search) {
                $builder->where('code', 'like', "%{$search}%")
                    ->orWhere('description_fr', 'like', "%{$search}%")
                    ->orWhere('description_en', 'like', "%{$search}%");
            });
        }

        if ($request->filled('applicable_to')) {
            $query->where('applicable_to', $request->input('applicable_to'));
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        if ($request->filled('status')) {
            $now = now();
            $status = $request->input('status');

            $query->where(function ($builder) use ($status, $now) {
                match ($status) {
                    'current' => $builder->where('is_active', true)
                        ->where('valid_from', '<=', $now)
                        ->where('valid_until', '>=', $now),
                    'upcoming' => $builder->where('valid_from', '>', $now),
                    'expired' => $builder->where('valid_until', '<', $now),
                    'exhausted' => $builder->whereNotNull('usage_limit')->whereColumn('used_count', '>=', 'usage_limit'),
                    default => null,
                };
            });
        }

        $promoCodes = $query->latest()->paginate(15)->withQueryString();

        $stats = [
            'total' => PromoCode::count(),
            'active' => PromoCode::where('is_active', true)->count(),
            'current' => PromoCode::where('is_active', true)
                ->where('valid_from', '<=', now())
                ->where('valid_until', '>=', now())
                ->count(),
            'usages' => (int) PromoCode::sum('used_count'),
            'discount_amount' => (float) PromoCodeUsage::sum('discount_amount'),
        ];

        return view('admin.promo-codes.index', compact('promoCodes', 'stats'));
    }

    public function create()
    {
        return view('admin.promo-codes.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validatePromoCode($request);
        $validated['code'] = strtoupper($validated['code']);
        $validated['is_active'] = $request->boolean('is_active');

        try {
            $promoCode = PromoCode::create($validated);

            return redirect()
                ->route('admin.promo-codes.edit', $promoCode->id)
                ->with('success', 'Code promo créé avec succès.');
        } catch (\Throwable $e) {
            Log::error('Admin promo code creation failed', [
                'admin_id' => auth('admin')->id(),
                'code' => $request->input('code'),
                'message' => $e->getMessage(),
            ]);

            return back()
                ->withInput()
                ->with('error', 'La création du code promo a échoué. Vérifie les champs puis réessaie.');
        }
    }

    public function show(string $id)
    {
        $promoCode = PromoCode::with(['usages.user', 'usages.booking'])->findOrFail($id);

        return view('admin.promo-codes.show', compact('promoCode'));
    }

    public function edit(string $id)
    {
        $promoCode = PromoCode::findOrFail($id);

        return view('admin.promo-codes.edit', compact('promoCode'));
    }

    public function update(Request $request, string $id)
    {
        $promoCode = PromoCode::findOrFail($id);

        $validated = $this->validatePromoCode($request, $promoCode->id);
        $validated['code'] = strtoupper($validated['code']);
        $validated['is_active'] = $request->boolean('is_active');

        try {
            $promoCode->update($validated);

            return redirect()
                ->route('admin.promo-codes.edit', $promoCode->id)
                ->with('success', 'Code promo mis à jour avec succès.');
        } catch (\Throwable $e) {
            Log::error('Admin promo code update failed', [
                'admin_id' => auth('admin')->id(),
                'promo_code_id' => $promoCode->id,
                'message' => $e->getMessage(),
            ]);

            return back()
                ->withInput()
                ->with('error', 'La mise à jour du code promo a échoué. Vérifie les champs puis réessaie.');
        }
    }

    public function destroy(string $id)
    {
        $promoCode = PromoCode::withCount('usages')->findOrFail($id);

        if ($promoCode->usages_count > 0) {
            return back()->with('error', 'Ce code promo a déjà été utilisé. Désactive-le au lieu de le supprimer.');
        }

        try {
            $promoCode->delete();

            return redirect()
                ->route('admin.promo-codes.index')
                ->with('success', 'Code promo supprimé avec succès.');
        } catch (\Throwable $e) {
            Log::error('Admin promo code deletion failed', [
                'admin_id' => auth('admin')->id(),
                'promo_code_id' => $promoCode->id,
                'message' => $e->getMessage(),
            ]);

            return back()->with('error', 'La suppression du code promo a échoué.');
        }
    }

    private function validatePromoCode(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('promo_codes', 'code')->ignore($ignoreId),
            ],
            'description_fr' => 'nullable|string',
            'description_en' => 'nullable|string',
            'discount_type' => ['required', Rule::in(['percentage', 'fixed'])],
            'discount_value' => 'required|numeric|min:0.01',
            'min_purchase_amount' => 'nullable|numeric|min:0',
            'max_discount_amount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'valid_from' => 'required|date',
            'valid_until' => 'required|date|after_or_equal:valid_from',
            'applicable_to' => ['required', Rule::in(['all', 'flights', 'events', 'packages'])],
            'is_active' => 'boolean',
        ]);
    }
}
