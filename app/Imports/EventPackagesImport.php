<?php

namespace App\Imports;

use App\Models\Event;
use App\Models\EventPackage;
use Illuminate\Support\Str;
/**
 * Import de packages événements depuis un fichier CSV ou Excel (.xlsx).
 * - CSV  : PHP natif (fgetcsv) — zéro dépendance externe
 * - XLSX : ZipArchive + SimpleXML (extensions PHP core, toujours disponibles)
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
    // Lecteur XLSX via ZipArchive + SimpleXML (PHP core — zéro dépendance)
    // -------------------------------------------------------------------------

    protected function importSpreadsheet(string $filePath): void
    {
        if (!class_exists('ZipArchive')) {
            throw new \Exception("L'extension PHP ZipArchive est requise pour lire les fichiers XLSX.");
        }

        $zip = new \ZipArchive();
        if ($zip->open($filePath) !== true) {
            throw new \Exception("Impossible d'ouvrir le fichier XLSX (fichier corrompu ou format invalide).");
        }

        // 1. Lire les chaînes partagées (sharedStrings.xml)
        $sharedStrings    = [];
        $sharedStringsXml = $zip->getFromName('xl/sharedStrings.xml');
        if ($sharedStringsXml) {
            $ssXml = simplexml_load_string($sharedStringsXml);
            foreach ($ssXml->si as $si) {
                if (isset($si->t)) {
                    $sharedStrings[] = (string) $si->t;
                } else {
                    // Texte enrichi : concaténer tous les éléments r/t
                    $text = '';
                    foreach ($si->r as $r) {
                        $text .= (string) $r->t;
                    }
                    $sharedStrings[] = $text;
                }
            }
        }

        // 2. Lire la première feuille (sheet1.xml)
        $sheetXml = $zip->getFromName('xl/worksheets/sheet1.xml');
        $zip->close();

        if (!$sheetXml) {
            throw new \Exception("Impossible de lire les données de la feuille Excel.");
        }

        $xml    = simplexml_load_string($sheetXml);
        $rows   = [];
        $maxCol = 0;

        foreach ($xml->sheetData->row as $xmlRow) {
            $rowIndex = (int) $xmlRow['r'] - 1;
            $rowData  = [];

            foreach ($xmlRow->c as $cell) {
                $colIndex = $this->cellRefToColIndex((string) $cell['r']);
                $maxCol   = max($maxCol, $colIndex);

                $type  = (string) $cell['t'];
                $value = isset($cell->v) ? (string) $cell->v : '';

                if ($type === 's') {
                    // Référence vers sharedStrings
                    $value = $sharedStrings[(int) $value] ?? '';
                } elseif ($type === 'b') {
                    $value = $value ? '1' : '0';
                }

                $rowData[$colIndex] = $value;
            }

            // Remplir les colonnes manquantes (cellules vides non présentes dans le XML)
            for ($i = 0; $i <= $maxCol; $i++) {
                $rowData[$i] = $rowData[$i] ?? '';
            }
            ksort($rowData);
            $rows[$rowIndex] = array_values($rowData);
        }

        ksort($rows);
        $rows = array_values($rows);

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

    /**
     * Convertit une référence de cellule Excel (ex: "A1", "B2", "AA3")
     * en index de colonne 0-based.
     */
    protected function cellRefToColIndex(string $cellRef): int
    {
        preg_match('/^([A-Z]+)/', strtoupper($cellRef), $matches);
        $col   = $matches[1] ?? 'A';
        $index = 0;
        foreach (str_split($col) as $char) {
            $index = $index * 26 + (ord($char) - ord('A') + 1);
        }
        return $index - 1;
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

