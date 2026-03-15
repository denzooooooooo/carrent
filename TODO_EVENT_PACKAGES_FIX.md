# Fix Event Packages Table Missing Error

## Statut: ✅ En cours

### Étape 1: Créer TODO.md [COMPLÉTÉ]
- [x] Créer ce fichier de suivi

### Étape 2: Modifier EventController.php pour gestion gracieuse
- [x] Ajouter vérification Schema::hasTable('event_packages')
- [x] Conditionnellement charger 'packages'
**COMPLÉTÉ**

### Étape 3: Modifier Event.php model
- [x] Ajouter Schema import et scopeHasPackages()/hasPackages accessor
- [x] Protéger EventController::book() method
**COMPLÉTÉ**

### Étape 4: Créer script migration safe pour prod
- [x] Créé deploy_fix_event_packages.sh
**COMPLÉTÉ**

### Étape 5: Test local
- [ ] php artisan migrate:rollback --step=3 puis migrate
- [ ] Tester page event details

### Étape 6: Déploiement prod
- [ ] Pusher code sur GitHub
- [ ] SSH serveur: php artisan migrate --force
- [ ] Tester https://carrepremium.com/events/finale-uefa-europa-league

### Étape 7: Optionnel - Seeder packages Excel
- [ ] Créer seeder depuis GRILLE TARIFAIRE files

**Prochaines étapes après complétion:**
```
ssh production-server
cd /path/to/carrent-collaborative
php artisan migrate --force
php artisan cache:clear
php artisan config:clear
```

