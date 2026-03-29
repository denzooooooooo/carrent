<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $hasLegacyEventType = Schema::hasColumn('events', 'event_type');
        $hasLegacySportType = Schema::hasColumn('events', 'sport_type');

        if (!Schema::hasColumn('events', 'family')) {
            Schema::table('events', function (Blueprint $table) {
                $table->string('family', 20)->nullable()->after('slug');
                $table->index('family');
            });
        }

        $categoryLabels = Schema::hasTable('event_categories')
            ? DB::table('event_categories')
                ->select('id', 'name_fr', 'name_en', 'slug')
                ->get()
                ->mapWithKeys(fn ($category) => [
                    $category->id => strtolower(trim(implode(' ', array_filter([
                        $category->name_fr,
                        $category->name_en,
                        $category->slug,
                    ])))),
                ])
                ->all()
            : [];

        $typeLabels = Schema::hasTable('event_types')
            ? DB::table('event_types')
                ->select('id', 'name_fr', 'name_en', 'slug')
                ->get()
                ->mapWithKeys(fn ($type) => [
                    $type->id => strtolower(trim(implode(' ', array_filter([
                        $type->name_fr,
                        $type->name_en,
                        $type->slug,
                    ])))),
                ])
                ->all()
            : [];

        $eventSelect = ['id', 'category_id', 'type_id', 'family'];

        if ($hasLegacyEventType) {
            $eventSelect[] = 'event_type';
        }

        if ($hasLegacySportType) {
            $eventSelect[] = 'sport_type';
        }

        DB::table('events')
            ->select($eventSelect)
            ->orderBy('id')
            ->get()
            ->each(function ($event) use ($categoryLabels, $typeLabels) {
                $family = $this->resolveFamily($event, $categoryLabels, $typeLabels);

                DB::table('events')
                    ->where('id', $event->id)
                    ->update(['family' => $family]);
            });
    }

    public function down(): void
    {
        if (!Schema::hasColumn('events', 'family')) {
            return;
        }

        Schema::table('events', function (Blueprint $table) {
            $table->dropIndex(['family']);
            $table->dropColumn('family');
        });
    }

    private function resolveFamily(object $event, array $categoryLabels, array $typeLabels): string
    {
        $family = strtolower(trim((string) $event->family));

        if (in_array($family, ['sportif', 'culturel'], true)) {
            return $family;
        }

        $haystack = strtolower(trim(implode(' ', array_filter([
            $event->event_type ?? null,
            $event->sport_type ?? null,
            $categoryLabels[$event->category_id] ?? null,
            $typeLabels[$event->type_id] ?? null,
        ]))));

        foreach (['sport', 'football', 'tennis', 'basket', 'rugby', 'formula', 'match', 'moto', 'golf'] as $keyword) {
            if (str_contains($haystack, $keyword)) {
                return 'sportif';
            }
        }

        return 'culturel';
    }
};
