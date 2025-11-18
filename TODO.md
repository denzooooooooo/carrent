# TODO: Intégration des réservations d'événements dans l'espace admin

## Tâches à accomplir

- [ ] Modifier EventController pour créer un enregistrement Booking lors de la réservation d'événement
- [ ] Ajouter la relation eventBooking dans le modèle Booking
- [ ] Mettre à jour BookingController admin pour charger les détails des réservations d'événements
- [ ] Ajouter la fonctionnalité de renvoi du reçu dans la vue admin bookings show
- [ ] Tester le processus de réservation et la visibilité admin

## Détails techniques

### 1. Modification d'EventController
- Dans la méthode `book()`, après création d'EventBooking, créer un Booking avec type 'event'
- Lier le Booking à l'EventBooking via un champ event_booking_id

### 2. Mise à jour du modèle Booking
- Ajouter la relation belongsTo vers EventBooking
- S'assurer que les champs nécessaires sont présents (event_id, etc.)

### 3. Mise à jour de BookingController admin
- Dans la méthode `show()`, charger les détails d'EventBooking si booking_type === 'event'
- Passer les données supplémentaires à la vue

### 4. Ajout du bouton renvoi reçu
- Dans resources/views/admin/bookings/show.blade.php, ajouter un bouton pour renvoyer l'email de confirmation
- Créer une nouvelle méthode dans BookingController pour gérer le renvoi

### 5. Tests
- Créer une réservation d'événement
- Vérifier qu'elle apparaît dans l'admin bookings
- Tester le renvoi du reçu
