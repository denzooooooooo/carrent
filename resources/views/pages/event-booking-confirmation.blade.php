@extends('layouts.app')

@section('title', 'Confirmation réservation événement - ' . $booking->booking_number . ' - Carré Premium')

@section('content')
@include('pages.partials.booking-confirmation', [
    'booking' => $booking,
    'serviceLabel' => 'Événement',
    'heroTitle' => 'Votre réservation événement est bien enregistrée.',
    'heroCopy' => 'Le dossier est créé et relié à votre événement, votre zone ou votre formule. La suite dépend du statut de paiement indiqué ci-dessous.',
    'primaryActionUrl' => route('events.show', $booking->event?->slug ?? ''),
    'primaryActionLabel' => 'Retour à l’événement',
    'secondaryActionUrl' => route('events'),
    'secondaryActionLabel' => 'Voir tous les événements',
    'nextSteps' => [
        ['title' => 'Vérification paiement', 'body' => 'Le paiement ou la preuve de virement est contrôlé avant l’émission finale des documents.'],
        ['title' => 'Émission des accès', 'body' => 'Billets, QR codes ou documents utiles sont générés une fois la réservation validée.'],
        ['title' => 'Assistance événement', 'body' => 'En cas de changement ou de question, l’équipe suit le dossier avec la référence affichée.'],
    ],
])
@endsection
