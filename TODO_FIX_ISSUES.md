# TODO: Correction des problèmes de réservation de packages

## Problème 3: Erreur SQL `package_booking_id` + date fixe + redirection CinetPay

### Tâches:
- [x] Créer la migration pour ajouter `package_booking_id` à la table `bookings`
- [x] Corriger `PackageController::book()` pour gérer les dates fixes du package
- [x] Corriger le formulaire de réservation pour masquer le sélecteur de date si le package a une date fixe
- [x] Exécuter `php artisan migrate` ✅ DONE

---

# TODO: Correction des deux problèmes signalés (anciens)

## Problème 1: N/A dans le dashboard admin (admin/bookings/index.blade.php) - ✅ CORRIGÉ
- [x] Corriger la logique de fallback pour les réservations de guests (vol sans compte utilisateur)
- [x] Utiliser des parenthèses explicites pour gérer la précédence des opérateurs
- [x] Ajouter des vérifications supplémentaires pour passenger_details null/empty

## Problème 2: Sélection des packages (pages/event-details.blade.php) - ✅ CORRIGÉ

### Corrections effectuées:
1. **Migration créée**: `database/migrations/2025_12_20_000004_add_package_id_to_event_bookings_table.php`
   - Ajoute la colonne `package_id` à la table `event_bookings`
   - Rend `zone_id` nullable pour permettre les réservations de packages

2. **Modèle EventBooking mis à jour** (`app/Models/EventBooking.php`):
   - Ajoute `package_id` au tableau `$fillable`
   - Ajoute la relation `package()` vers `EventPackage`

### Tests à effectuer:
- Tester la réservation d'un package depuis la page event-details
- Vérifier que la réservation est créée correctement avec package_id
- Vérifier que le dashboard admin affiche correctement les informations

