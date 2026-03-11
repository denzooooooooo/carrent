# TODO - Correctifs en cours

## A) Événements: table event_packages absente
- [x] Mettre à jour `app/Http/Controllers/Admin/EventController.php` pour ne plus bloquer la création/mise à jour d'événement si `event_packages` est absente.
- [x] Ajouter un warning utilisateur quand les packages sont ignorés faute de migration.
- [x] Logger un warning technique pour faciliter le diagnostic en production.
- [x] Vérifier la syntaxe PHP du contrôleur.
- [ ] Fournir la commande serveur pour exécuter les migrations en production (`php artisan migrate --force`).

## B) Phase 1 CinetPay Location (priorité)
- [ ] Mettre à jour `PaymentController@cinetpayReturn` pour confirmer `locationBooking` et rediriger vers `location.booking.confirmation`.
- [ ] Mettre à jour `PaymentController@cinetpayNotify` pour confirmer `locationBooking`.
- [ ] Étendre `sendConfirmationEmail()` pour réservation `location` (email de confirmation/reçu).
- [ ] Vérifier la syntaxe PHP de `PaymentController`.
- [ ] Diagnostiquer et corriger l’erreur vendor/composer (`ConsoleOutput` introuvable) pour relancer `php artisan serve`.
- [ ] Corriger le blocage git `index.lock`.
