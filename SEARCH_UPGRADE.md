Objectif

Préparer et activer une recherche performante : 1) FULLTEXT indexes (MySQL/MariaDB) pour des requêtes SQL rapides ; 2) Meilisearch via Laravel Scout pour autocomplétion, pertinence et scalabilité.

Actions réalisées par moi

- Migration ajoutée : database/migrations/2025_12_17_000001_add_fulltext_indexes_to_packages_and_events.php
  - Ajoute des indexes FULLTEXT sur `tour_packages(title_fr, description_fr)` et `events(title_fr, description_fr)`.
- SearchController mis à jour : essaye d'utiliser Scout/Meilisearch (si installé) et retombe sur LIKE si absent.
- Endpoint d'autocomplete AJAX déjà ajouté : GET /search/suggest
- Frontend autocomplete ajouté (header) : champ de recherche propose des suggestions via AJAX.

Étapes recommandées à exécuter localement (obligatoire pour finir l'intégration)

1) Sauvegarde

- Effectue une sauvegarde complète de la base avant de lancer la migration (surtout si production).

2) Exécuter la migration FULLTEXT

Dans ton terminal (workspace racine) :

```bash
php artisan migrate
```

Notes :
- Si ta base n'autorise pas FULLTEXT sur InnoDB (versions très anciennes), l'ALTER TABLE peut échouer. Le migration ignore les erreurs, mais vérifie le résultat.
- Vérifie ensuite dans MySQL :

```sql
SHOW INDEX FROM tour_packages;
SHOW INDEX FROM events;
```

3) Intégrer Meilisearch + Laravel Scout (optionnel mais recommandé)

- Installation (exécute localement) :

```bash
composer require laravel/scout
composer require meilisearch/meilisearch-php http-interop/http-factory-guzzle
```

- Configuration :
  - Publie config Scout :

```bash
php artisan vendor:publish --provider="Laravel\Scout\ScoutServiceProvider"
```

  - Dans `.env` :

```
SCOUT_DRIVER=meilisearch
MEILISEARCH_HOST=http://127.0.0.1:7700
MEILISEARCH_KEY=masterKeyIfAny
```

  - Démarre Meilisearch en local (par ex. via Docker) :

```bash
docker run -it --rm -p 7700:7700 getmeili/meilisearch:latest
```

- Préparer les modèles

  - Ajoute le trait `Laravel\Scout\Searchable` sur les modèles que tu veux indexer (exemples) :

```php
// app/Models/TourPackage.php
use Laravel\Scout\Searchable;

class TourPackage extends Model
{
    use Searchable;

    public function toSearchableArray()
    {
        return [
            'title' => $this->title_fr ?? $this->title_en,
            'description' => strip_tags($this->description_fr ?? $this->description_en ?? ''),
            'slug' => $this->slug,
        ];
    }
}
```

- Indexation initiale

Après avoir installé Scout et configuré Meilisearch, lance :

```bash
php artisan scout:import "App\\Models\\TourPackage"
php artisan scout:import "App\\Models\\Event"
php artisan scout:import "App\\Models\\Flight"
```

4) Validation

- Vérifie que les suggestions AJAX renvoient des résultats : tape dans l'input de recherche et observe la dropdown.
- Teste des recherches complètes via `/search?q=safari`.

5) Améliorations possibles

- Keyboard navigation (flèches haut/bas + Enter) pour la dropdown coté client.
- Caching côté serveur (Redis) pour suggestions fréquentes.
- Ajouter scoring/boosts dans Meilisearch (boost sur titre vs description).
- Nettoyage des erreurs du linter Tailwind (optionnel).

Si tu veux, je peux :
- Générer automatiquement les modifications de modèle (patches) pour ajouter `Searchable`, et préparer les imports.
- Générer une migration batch pour faire un flag DB indiquant que l'indexation est complète.
- Préparer les commandes `php artisan` d'indexation en séquence et un petit script d'aide.

Dis-moi si tu veux que je :
- Crée les modifications dans les modèles (TourPackage, Event, Flight) automatiquement ; ou
- Prépare uniquement les instructions (comme ci-dessus) et tu exécutes composer/migrate/index.

