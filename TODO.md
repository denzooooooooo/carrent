# TODO - Packages Touristiques Sportifs (Catalogues PDF)

## Étapes

- [ ] 1. Migration : ajouter `currency`, `event_date_start`, `event_date_end` à `tour_packages`
- [ ] 2. Modèle `TourPackage` : ajouter nouveaux champs à `$fillable` et `$casts`
- [ ] 3. Admin `PackageController` : ajouter types `sport_event`, `motorsport`, `football` + validation
- [ ] 4. Seeder `TourPackageCatalogueSeeder` : 30 packages des 3 PDFs + 3 nouvelles catégories
- [ ] 5. Vue admin `_form.blade.php` : currency, dates événement, nouveaux types
- [ ] 6. Vue admin `index.blade.php` : filtre par type, affichage currency
- [ ] 7. Vues `create.blade.php` et `edit.blade.php` : passer nouveaux packageTypes
- [ ] 8. Exécuter migration + seeder
