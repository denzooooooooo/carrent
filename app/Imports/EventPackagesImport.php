<?php

namespace App\Imports;

use App\Models\Event;
use App\Models\EventPackage;
use Illuminate\Support\Str;

/**
 * Import de packages événements depuis un fichier CSV ou Excel (.xlsx).
 * - CSV  : PHP natif (fgetcsv) — zéro dépendance externe
 * - XLSX : ZipArchive + SimpleXML (extensions PHP core, toujours disponibles)
 *
 * Supporte deux formats :
 *  1. Format "Grille Tarifaire" : Ligne 1 = titre événement, Ligne 3 = en-têtes, Ligne 4+ = données
 *  2. Format générique : Ligne 1 = en-têtes (event_title, package_name_fr, price...), Ligne 2+ = données
 */
class EventPackagesImport
{
    protected int $rowCount = 0;
    protected array $errors = [];
    protected ?int $eventId = null;

    /**
     * Définir l'événement cible (depuis le formulaire).
     */
    public function setEventId(?int $id): void
    {
        $this->eventId = $id;
    }

    /**
     * Point d'entrée : détecte le format et délègue au bon lecteur.
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
            throw new \Exception("Format non supporté : .{$ext}. Utilisez .csv, .xlsx ou .xls.");
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
    // Détection du format et dispatch
    // -------------------------------------------------------------------------

    /**
     * Traite un tableau de lignes (commun CSV + XLSX après lecture).
     * Détecte automatiquement le format : Grille Tarifaire ou Générique.
     */
    protected function processRows(array $rows): void
    {
        if (empty($rows)) {
            return;
        }

        // Compter les cellules non-vides dans la première ligne
        $firstRowValues  = array_values($rows[0]);
        $nonEmptyCells   = array_filter($firstRowValues, fn($v) => trim((string) $v) !== '');

        // Format Grille Tarifaire : 1ère ligne = titre événement (1 seule cellule non-vide)
        if (count($nonEmptyCells) <= 1) {
            $this->processGrilleTarifaireRows($rows);
        } else {
            // Format générique : 1ère ligne = en-têtes
            $this->processGenericRows($rows);
        }
    }

    // -------------------------------------------------------------------------
    // Format "Grille Tarifaire" (Coupe du Monde, Roland Garros, etc.)
    // Ligne 0 = titre événement, Ligne 2 = en-têtes, Ligne 3+ = données
    // -------------------------------------------------------------------------

    protected function processGrilleTarifaireRows(array $rows): void
    {
        // Ligne 0 : titre de l'événement
        $eventTitle = trim((string) (array_values($rows[0])[0] ?? ''));

        // Trouver l'événement
        $event = $this->findEvent($eventTitle);

        if (!$event) {
            $this->errors[] = "Événement introuvable pour : \"{$eventTitle}\". Sélectionnez-le manuellement dans le menu déroulant.";
            return;
        }

        // Ligne 2 (index 2) : en-têtes de colonnes
        $rawHeaders = isset($rows[2]) ? array_values($rows[2]) : [];
        $headers    = array_map(fn($h) => strtolower(trim((string) $h)), $rawHeaders);

        // Lignes 3+ : données
        $lineNumber = 3;
        foreach (array_slice($rows, 3) as $rawRow) {
            $lineNumber++;
            $rawRow = array_map(fn($v) => trim((string) ($v ?? '')), array_values($rawRow));

            if (count(array_filter($rawRow)) === 0) {
                continue; // Ignorer les lignes vides
            }

            $row = array_combine($headers, array_pad($rawRow, count($headers), ''));

            // Mapping colonnes Grille Tarifaire → standard
            $packageNameFr = $row['forfait'] ?? $row['package'] ?? $row['package_name_fr'] ?? '';
            $price         = $row['tarif cp catalogue'] ?? $row['tarif'] ?? $row['prix'] ?? $row['price'] ?? '0';
            $descFr        = $row['description'] ?? '';
            // La 2ème colonne "Description" (avec espace) devient description_included_fr
            $descIncluded  = '';
            foreach ($headers as $idx => $h) {
                if (rtrim($h) === 'description' && $idx > 0 && isset($rawRow[$idx])) {
                    $descIncluded = $rawRow[$idx];
                    break;
                }
            }
            $packageCode = $row['ref'] ?? $row['code'] ?? '';

            $this->createPackage($event->id, $packageNameFr, $price, $packageCode, $descFr, $descIncluded, $lineNumber);
        }
    }

    // -------------------------------------------------------------------------
    // Format générique (en-têtes en ligne 1)
    // -------------------------------------------------------------------------

    protected function processGenericRows(array $rows): void
    {
        $rawHeaders = array_values(array_shift($rows));
        $headers    = array_map(fn($h) => strtolower(trim((string) $h)), $rawHeaders);

        $lineNumber = 1;
        foreach ($rows as $rawRow) {
            $lineNumber++;
            $rawRow = array_map(fn($v) => trim((string) ($v ?? '')), array_values($rawRow));

            if (count(array_filter($rawRow)) === 0) {
                continue;
            }

            $row   = array_combine($headers, array_pad($rawRow, count($headers), ''));
            $event = $this->findEvent($row['event_title'] ?? $row['event'] ?? '', $row['city'] ?? '');

            if (!$event) {
                $this->errors[] = "Ligne {$lineNumber} : événement introuvable.";
                continue;
            }

            $packageNameFr = $row['package_name_fr'] ?? $row['package_name'] ?? $row['name'] ?? '';
            $price         = $row['price'] ?? $row['prix'] ?? '0';
            $packageCode   = $row['package_code'] ?? $row['code'] ?? '';
            $descFr        = $row['description_fr'] ?? $row['description'] ?? '';
            $descIncluded  = $row['included'] ?? $row['description_included_fr'] ?? $row['inclus'] ?? '';

            $this->createPackage($event->id, $packageNameFr, $price, $packageCode, $descFr, $descIncluded, $lineNumber);
        }
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    /**
     * Trouver l'événement : d'abord par event_id fourni, sinon par titre/ville.
     */
    protected function findEvent(string $title = '', string $city = ''): ?Event
    {
        // Priorité 1 : event_id fourni via le formulaire
        if ($this->eventId) {
            return Event::find($this->eventId);
        }

        // Priorité 2 : recherche par titre
        if ($title !== '') {
            $event = Event::where('title_fr', 'like', '%' . substr($title, 0, 40) . '%')
                ->orWhere('title_en', 'like', '%' . substr($title, 0, 40) . '%')
                ->orWhere('slug', Str::slug(substr($title, 0, 40)))
                ->first();
            if ($event) return $event;

            // Recherche par mots-clés de ville dans le titre
            $knownCities = [
                'San Francisco', 'Los Angeles', 'Seattle', 'Atlanta', 'Boston', 'Miami',
                'New York', 'Dallas', 'Kansas City', 'Philadelphia', 'Vancouver', 'Toronto',
                'Guadalajara', 'Mexico', 'Paris', 'London', 'Madrid', 'Barcelona',
                'Roland Garros', 'Stade de France',
            ];
            foreach ($knownCities as $knownCity) {
                if (stripos($title, $knownCity) !== false) {
                    $event = Event::where('city', 'like', '%' . $knownCity . '%')
                        ->orWhere('venue_name', 'like', '%' . $knownCity . '%')
                        ->first();
                    if ($event) return $event;
                }
            }
        }

        // Priorité 3 : recherche par ville
        if ($city !== '') {
            return Event::where('city', 'like', '%' . $city . '%')->first();
        }

        return null;
    }

    /**
     * Créer un EventPackage après validation des données.
     */
    protected function createPackage(
        int $eventId,
        string $packageNameFr,
        string $rawPrice,
        string $packageCode,
        string $descFr,
        string $descIncluded,
        int $lineNumber
    ): void {
        $packageNameFr = trim($packageNameFr);

    public function getRowCount(): int
    {
        return $this->rowCount;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}

