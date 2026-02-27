<?php

namespace App\Imports;

use App\Models\Event;
use App\Models\EventPackage;
use Illuminate\Support\Str;

/**
 * Import de packages événements depuis un fichier CSV.
 * Implémentation sans dépendance externe (PHP natif uniquement).
 */
class EventPackagesImport
{
    protected int $rowCount = 0;
    protected array $errors = [];

    /**
     * Importe les packages depuis un fichier CSV.
     *
     * @param string $filePath Chemin absolu vers le fichier CSV
     * @throws \Exception
     */
    public function import(string $filePath): void
    {
        if (!file_exists($filePath)) {
            throw new \Exception("Fichier introuvable : {$filePath}");
        }

        $handle = fopen($filePath, 'r');
        if (!$handle) {
            throw new \Exception("Impossible d'ouvrir le fichier CSV.");
        }

        // Lire la ligne d'en-tête
        $rawHeaders = fgetcsv($handle, 0, ',');
        if (!$rawHeaders) {
            fclose($handle);
            throw new \Exception("Le fichier CSV est vide ou invalide.");
        }

        // Normaliser les en-têtes (minuscules, sans espaces)
        $headers = array_map(fn($h) => strtolower(trim($h)), $rawHeaders);

        $lineNumber = 1;
        while (($rawRow = fgetcsv($handle, 0, ',')) !== false) {
            $lineNumber++;
            if (count(array_filter($rawRow)) === 0) {
                continue; // Ignorer les lignes vides
            }

            // Associer les colonnes aux en-têtes
            $row = array_combine(
                $headers,
                array_pad($rawRow, count($headers), '')
            );

            try {
                $this->processRow($row);
            } catch (\Exception $e) {
                $this->errors[] = "Ligne {$lineNumber} : " . $e->getMessage();
            }
        }

        fclose($handle);
    }

    /**
     * Traite une ligne CSV et crée le package correspondant.
     */
    protected function processRow(array $row): void
    {
        // Rechercher l'événement associé
        $event = null;

        $eventTitle = trim($row['event_title'] ?? $row['event'] ?? '');
        if ($eventTitle !== '') {
            $event = Event::where('title_fr', 'like', '%' . $eventTitle . '%')
                ->orWhere('title_en', 'like', '%' . $eventTitle . '%')
                ->orWhere('slug', Str::slug($eventTitle))
                ->first();
        }

        // Fallback : recherche par ville
        if (!$event) {
            $city = trim($row['city'] ?? '');
            if ($city !== '') {
                $event = Event::where('city', 'like', '%' . $city . '%')->first();
            }
        }

        if (!$event) {
            return; // Ignorer si aucun événement trouvé
        }

        $packageNameFr = trim($row['package_name_fr'] ?? $row['package_name'] ?? $row['name'] ?? '');
        $price = floatval(str_replace([' ', ','], ['', '.'], $row['price'] ?? $row['prix'] ?? '0'));

        if ($packageNameFr === '' || $price <= 0) {
            return; // Ignorer les lignes sans nom ou prix
        }

        $packageCode = trim($row['package_code'] ?? $row['code'] ?? '')
            ?: ('PKG-' . Str::slug($packageNameFr) . '-' . rand(1000, 9999));

        EventPackage::create([
            'event_id'               => $event->id,
            'package_name_fr'        => $packageNameFr,
            'package_name_en'        => trim($row['package_name_en'] ?? $row['name_en'] ?? '') ?: $packageNameFr,
            'package_code'           => $packageCode,
            'description_fr'         => trim($row['description_fr'] ?? $row['description'] ?? '') ?: null,
            'description_included_fr'=> trim($row['included'] ?? $row['description_included_fr'] ?? $row['inclus'] ?? '') ?: null,
            'price'                  => $price,
            'currency'               => strtoupper(trim($row['currency'] ?? $row['devise'] ?? 'XOF')),
            'available_quantity'     => max(1, intval($row['available_quantity'] ?? $row['quantity'] ?? $row['quantite'] ?? 100)),
            'max_per_order'          => max(1, intval($row['max_per_order'] ?? $row['max_order'] ?? 10)),
            'is_active'              => true,
            'sort_order'             => intval($row['sort_order'] ?? $row['order'] ?? 1),
        ]);

        $this->rowCount++;
    }

    public function getRowCount(): int
    {
        return $this->rowCount;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}

