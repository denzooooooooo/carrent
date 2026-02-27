# Todo List - Correction des grilles tarifaires et filtres d'événements

## Objectif
Corriger l'affichage des forfaits (EventPackages) et améliorer les filtres sur la page événements.

## Tâches à effectuer

### 1. Mettre à jour EventController pour charger les packages
- [x] Modifier la méthode `show()` pour charger les EventPackages en plus des seatZones

### 2. Corriger l'affichage des grilles tarifaires dans event-details.blade.php
- [x] Afficher les EventPackages (forfaits) sur la page détails
- [x] Afficher les seat zones en complément
- [x] Utiliser les deux sources de tarification

### 3. Améliorer les filtres dans events.blade.php
- [x] Changer le filtre lieu pour utiliser LIKE (recherche partielle)
- [x] Ajouter des filtres dynamiques pour ville et pays
- [x] Ajouter un filtre par plage de prix
- [x] Dynamiser la liste des lieux depuis la base de données
- [x] Ajouter les traductions pour les nouveaux filtres

### 4. Améliorer le panneau admin
- [ ] Ajouter la gestion des packages dans le formulaire des événements

## Notes
- EventPackages = grilles tarifaires/forfaits (VIP, Standard, etc.)
- EventSeatZones = zones de places (Fosse, Tribune, etc.)

