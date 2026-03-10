# TODO - Fix création package (package_type truncation)

## Étapes

- [x] 1. Vérifier migration `add_sport_types_to_tour_packages_table`
- [x] 2. Vérifier ENUM SQL réel de `tour_packages.package_type`
- [ ] 3. Durcir `Admin/PackageController` (normalisation + alias package_type)
- [ ] 4. Ajouter garde-fou ENUM DB côté contrôleur
- [ ] 5. Tester création package (motorsport, sport_event, football)
- [ ] 6. Commit & push sur `prod`
