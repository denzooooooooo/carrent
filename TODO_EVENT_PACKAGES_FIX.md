# TODO: Fix Event Packages 500 Error (PROD)
État: ⏳ En cours

## Étape 1: ✅ Créer ce fichier TODO
## Étape 2: Vérifier & exécuter script de déploiement existant
- [ ] `cat deploy_fix_event_packages.sh`
- [ ] Exécuter sur prod server

## Étape 3: ✅ Sécurité Blade ajoutée (2 edits)

## Étape 4: ❌ GitHub Actions FAIL - SSH timeout

**SSH secrets manquants/invalides:**
- SSH_USER_PROD
- SSH_HOST_PROD  
- SSH_PORT_PROD
- SSH_PRIVATE_KEY_PROD

**FIX MANUAL (recommandé):**
```
ssh votre-serveur-prod
cd /home/carrepremium.com/public_html  # Chemin dans workflow
php artisan migrate --force
php artisan db:seed --class=EventGrilleTarifaireSeeder --force
```

**OU** Modifier `deploy_fix_event_packages.sh`:
```
nano deploy_fix_event_packages.sh  # Remplacer /path/to/your/carrent-collaborative/current
scp deploy_fix_event_packages.sh user@serveur:/tmp/
ssh user@serveur "bash /tmp/deploy_fix_event_packages.sh"
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



