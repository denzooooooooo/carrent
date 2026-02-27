<?php

namespace App\Imports;

use App\Models\Event;
use App\Models\EventPackage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class EventPackagesImport implements ToModel, WithHeadingRow, WithValidation
{
    protected $rowCount = 0;

    /**
     * @param array $row
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Trouver l'événement par son titre ou slug
        $event = null;
        
        if (!empty($row['event_title']) || !empty($row['event'])) {
            $eventTitle = $row['event_title'] ?? $row['event'] ?? '';
            $event = Event::where('title_fr', 'like', '%' . $eventTitle . '%')
                ->orWhere('title_en', 'like', '%' . $eventTitle . '%')
                ->orWhere('slug', Str::slug($eventTitle))
                ->first();
        }
        
        // Si pas trouvé par titre, essayer par ville
        if (!$event && !empty($row['city'])) {
            $event = Event::where('city', 'like', '%' . $row['city'] . '%')->first();
        }

        if (!$event) {
            return null; // Ignorer si aucun événement trouvé
        }

        // Générer le code package automatiquement si non fourni
        $packageCode = $row['package_code'] ?? $row['code'] ?? ('PKG-' . Str::slug($row['package_name_fr'] ?? 'package') . '-' . rand(1000, 9999));

        $this->rowCount++;

        return new EventPackage([
            'event_id' => $event->id,
            'package_name_fr' => $row['package_name_fr'] ?? $row['package_name'] ?? $row['name'] ?? 'Package',
            'package_name_en' => $row['package_name_en'] ?? $row['package_name_fr'] ?? $row['name_en'] ?? $row['name'] ?? 'Package',
            'package_code' => $packageCode,
            'description_fr' => $row['description_fr'] ?? $row['description'] ?? null,
            'description_included_fr' => $row['included'] ?? $row['description_included_fr'] ?? $row['inclus'] ?? null,
            'price' => floatval($row['price'] ?? $row['prix'] ?? 0),
            'currency' => strtoupper($row['currency'] ?? $row['devise'] ?? 'XOF'),
            'available_quantity' => intval($row['available_quantity'] ?? $row['quantity'] ?? $row['quantite'] ?? 100),
            'max_per_order' => intval($row['max_per_order'] ?? $row['max_order'] ?? 10),
            'is_active' => true,
            'sort_order' => intval($row['sort_order'] ?? $row['order'] ?? 1),
        ]);
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'package_name_fr' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
        ];
    }

    /**
     * @return array
     */
    public function customValidationAttributes()
    {
        return [
            'package_name_fr' => 'Nom du package (Français)',
            'price' => 'Prix',
            'event_title' => 'Événement',
        ];
    }

    /**
     * Get the number of imported rows
     */
    public function getRowCount(): int
    {
        return $this->rowCount;
    }
}

