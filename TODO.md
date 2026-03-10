# TODO - Fix création d'événement (table event_packages manquante)

## Étapes

- [x] 1. Vérifier l’état des migrations pour `event_packages`
- [ ] 2. Exécuter les migrations manquantes (si nécessaire)
- [x] 3. Ajouter un garde-fou dans `Admin/EventController` si la table `event_packages` n'existe pas
- [ ] 4. Valider la syntaxe PHP des fichiers modifiés
- [ ] 5. Re-tester la création d’un événement avec package
