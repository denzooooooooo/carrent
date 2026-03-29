@extends('layouts.app')

@section('title', 'Confirmation réservation location - ' . $booking->booking_number . ' - Carré Premium')

@section('content')
@include('pages.partials.booking-confirmation', [
    'booking' => $booking,
    'serviceLabel' => 'Location',
    'heroTitle' => 'Votre réservation location est bien enregistrée.',
    'heroCopy' => 'Le créneau de location et les coordonnées du client sont maintenant liés au dossier. La confirmation finale dépend du statut de paiement et de validation.',
    'primaryActionUrl' => route('location.show', $booking->location?->id ?? 0),
    'primaryActionLabel' => 'Retour à la location',
    'secondaryActionUrl' => route('location'),
    'secondaryActionLabel' => 'Voir toutes les locations',
    'nextSteps' => [
        ['title' => 'Contrôle du règlement', 'body' => 'Le paiement ou la preuve de virement est rattaché au dossier location.'],
        ['title' => 'Validation logistique', 'body' => 'Les dates, la durée et les modalités de remise sont confirmées par l’équipe.'],
        ['title' => 'Suivi client', 'body' => 'Le support reste disponible pour toute modification avant la prise en charge.'],
    ],
])
@endsection
