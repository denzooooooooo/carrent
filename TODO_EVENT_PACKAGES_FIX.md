# TODO: Fix Event Packages 500 Error (PROD)
État: ⏳ En cours

## Étape 1: ✅ Créer ce fichier TODO
## Étape 2: Vérifier & exécuter script de déploiement existant
- [ ] `cat deploy_fix_event_packages.sh`
- [ ] Exécuter sur prod server

## Étape 3: Ajouter sécurité dans la vue Blade
- [ ] Modifier `resources/views/pages/event-details.blade.php`
- [ ] Ajouter `Schema::hasTable('event_packages')` check

## Étape 4: Exécuter migrations sur PROD
```
ssh prod-server
cd /path/to/app
php artisan migrate --force
```

## Étape 5: Populer les données packages
- [ ] Vérifier `EventGrilleTarifaireSeeder.php`
- [ ] `php artisan db:seed --class=EventGrilleTarifaireSeeder`
- [ ] Ou importer via Admin /events/import

## Étape 6: Test
- [ ] https://carrepremium.com/events/finale-uefa-europa-league
- [ ] Vérifier pas d'erreur 500
- [ ] Packages s'affichent (même vides OK)

## Étape 7: Automatiser déploiement
- [ ] Modifier `.github/workflows/prod.yml`
- [ ] Ajouter `migrate --force`

## Étape 8: Créer PR GitHub
```
gh pr create --title "Fix: event_packages table"
```

**Prochaine étape:** Vérifier le script existant `deploy_fix_event_packages.sh`

