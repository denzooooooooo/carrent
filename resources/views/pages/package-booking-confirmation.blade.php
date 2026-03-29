@extends('layouts.app')

@section('title', 'Confirmation réservation package - ' . $booking->booking_number . ' - Carré Premium')

@section('content')
@include('pages.partials.booking-confirmation', [
    'booking' => $booking,
    'serviceLabel' => 'Package',
    'heroTitle' => 'Votre réservation package est bien enregistrée.',
    'heroCopy' => 'Le dossier voyage est créé avec les coordonnées et participants renseignés. La validation finale dépend du règlement et des éléments opérationnels.',
    'primaryActionUrl' => route('packages.show', $booking->package?->slug ?? ''),
    'primaryActionLabel' => 'Retour au package',
    'secondaryActionUrl' => route('packages'),
    'secondaryActionLabel' => 'Voir tous les packages',
    'nextSteps' => [
        ['title' => 'Validation du dossier', 'body' => 'Les disponibilités et éléments de voyage sont vérifiés avec votre réservation.'],
        ['title' => 'Documents de séjour', 'body' => 'L’itinéraire, les vouchers ou confirmations sont préparés selon le statut du dossier.'],
        ['title' => 'Accompagnement', 'body' => 'L’équipe peut reprendre contact pour préciser les passagers ou les demandes spéciales.'],
    ],
])
@endsection
