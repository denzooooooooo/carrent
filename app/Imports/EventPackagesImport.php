<?php

namespace App\Imports;

use App\Models\Event;
use App\Models\EventPackage;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\IOFactory;

/**
 * Import de packages événements depuis un fichier CSV ou Excel (.xlsx/.xls).
 * - CSV  : PHP natif (fgetcsv)
 * - XLSX/XLS : PhpSpreadsheet (déjà dans composer.lock)
 */
class EventPackagesImport
{
    protected int $rowCount = 0;
    protected array $errors = [];

    /**
     * Point d'entrée : détecte le format et délègue au bon lecteur.
     *
     * @param string $filePath  Chemin absolu vers le fichier
     * @param string $extension Extension du fichier original (csv, xlsx, xls)
     * @throws \Exception
     */
    public function import(string $filePath, string $extension = 'csv'): void
    {
        if (!file_exists($filePath)) {
            throw new \Exception("Fichier introuvable : {$filePath}");
        }

        $ext = strtolower(trim($extension, '.'));

        if ($ext === 'csv' || $ext === 'txt') {
            $this->importCsv($filePath);
        } elseif (in_array($ext, ['xlsx', 'xls', 'ods'])) {
            $this->importSpreadsheet($filePath);
        } else {
            throw new \Exception("Format de fichier non supporté : .{$ext}. Utilisez .csv, .xlsx ou .xls.");
        }
    }

    // -------------------------------------------------------------------------
    // Lecteur CSV (PHP natif)
    // -------------------------------------------------------------------------

    protected function importCsv(string $filePath): void
    {
        $handle = fopen($filePath, 'r');
        if (!$handle) {
            throw new \Exception("Impossible d'ouvrir le fichier CSV.");
        }

        // Détecter le séparateur (virgule ou point-virgule)
        $firstLine = fgets($handle);
        rewind($handle);
        $separator = substr_count($firstLine, ';') > substr_count($firstLine, ',') ? ';' : ',';

        $rawHeaders = fgetcsv($handle, 0, $separator);
        if (!$rawHeaders) {
            fclose($handle);
            throw new \Exception("Le fichier CSV est vide ou invalide.");
        }

        $headers    = array_map(fn($h) => strtolower(trim($h)), $rawHeaders);
        $lineNumber = 1;

        while (($rawRow = fgetcsv($handle, 0, $separator)) !== false) {
            $lineNumber++;
            if (count(array_filter($rawRow)) === 0) {
                continue;
            }

            $row = array_combine($headers, array_pad($rawRow, count($headers), ''));

            try {
                $this->processRow($row);
            } catch (\Exception $e) {
                $this->errors[] = "Ligne {$lineNumber} : " . $e->getMessage();
            }
        }

        fclose($handle);
    }

    // -------------------------------------------------------------------------
    // Lecteur Excel via PhpSpreadsheet
    // -------------------------------------------------------------------------

    protected function importSpreadsheet(string $filePath): void
    {
        $spreadsheet = IOFactory::load($filePath);
        $sheet       = $spreadsheet->getActiveSheet();
        $rows        = $sheet->toArray(null, true, true, false);

        if (empty($rows)) {
            throw new \Exception("Le fichier Excel est vide.");
        }

        // Première ligne = en-têtes
        $rawHeaders = array_shift($rows);
        $headers    = array_map(fn($h) => strtolower(trim((string) $h)), $rawHeaders);

        $lineNumber = 1;
        foreach ($rows as $rawRow) {
            $lineNumber++;
            $rawRow = array_map(fn($v) => (string) ($v ?? ''), $rawRow);

            if (count(array_filter($rawRow)) === 0) {
                continue;
            }

            $row = array_combine($headers, array_pad($rawRow, count($headers), ''));

            try {
                $this->processRow($row);
            } catch (\Exception $e) {
                $this->errors[] = "Ligne {$lineNumber} : " . $e->getMessage();
            }
        }
    }

    // -------------------------------------------------------------------------
    // Traitement d'une ligne (commun CSV + Excel)
    // -------------------------------------------------------------------------

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
        $price         = floatval(str_replace([' ', ','], ['', '.'], $row['price'] ?? $row['prix'] ?? '0'));

        if ($packageNameFr === '' || $price <= 0) {
            return; // Ignorer les lignes sans nom ou prix valide
        }

        $packageCode = trim($row['package_code'] ?? $row['code'] ?? '')
            ?: ('PKG-' . Str::slug($packageNameFr) . '-' . rand(1000, 9999));

        EventPackage::create([
            'event_id'                => $event->id,
            'package_name_fr'         => $packageNameFr,
            'package_name_en'         => trim($row['package_name_en'] ?? $row['name_en'] ?? '') ?: $packageNameFr,
            'package_code'            => $packageCode,
            'description_fr'          => trim($row['description_fr'] ?? $row['description'] ?? '') ?: null,
            'description_included_fr' => trim($row['included'] ?? $row['description_included_fr'] ?? $row['inclus'] ?? '') ?: null,
            'price'                   => $price,
            'currency'                => strtoupper(trim($row['currency'] ?? $row['devise'] ?? 'XOF')),
            'available_quantity'      => max(1, intval($row['available_quantity'] ?? $row['quantity'] ?? $row['quantite'] ?? 100)),
            'max_per_order'           => max(1, intval($row['max_per_order'] ?? $row['max_order'] ?? 10)),
            'is_active'               => true,
            'sort_order'              => intval($row['sort_order'] ?? $row['order'] ?? 1),
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

