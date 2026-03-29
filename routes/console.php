<?php

use App\Services\PdfCatalogEventImportService;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('catalog:import-pdf-events {--force : Supprime les anciennes reservations et remplace tous les evenements}', function () {
    if (!$this->option('force') && !$this->confirm('Cette commande va supprimer les reservations evenementielles existantes et reimporter tout le catalogue PDF. Continuer ?')) {
        $this->warn('Import annule.');

        return self::SUCCESS;
    }

    $summary = app(PdfCatalogEventImportService::class)->import();

    $this->info('Catalogue PDF importe avec succes.');
    $this->line('Evenements: ' . $summary['events']);
    $this->line('Packages: ' . $summary['packages']);
    $this->line('Options: ' . $summary['options']);

    return self::SUCCESS;
})->purpose('Reimporte le catalogue evenementiel depuis le jeu de donnees PDF');
