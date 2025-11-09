@extends('layouts.admin')

@section('title', 'Détails de l\'utilisateur - ' . $user->name)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Détails de l'utilisateur</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Retour à la liste
                        </a>
                        <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-edit"></i> Modifier
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="text-center">
                                @if($user->avatar)
                                    <img src="{{ $user->getFirstMediaUrl('avatar', 'normal') }}" alt="Avatar" class="img-circle elevation-2" style="width: 150px; height: 150px; object-fit: cover;">
                                @else
                                    <div class="bg-light rounded-circle d-inline-block" style="width: 150px; height: 150px; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-user fa-3x text-secondary"></i>
                                    </div>
                                @endif
                                <h4 class="mt-3">{{ $user->name }}</h4>
                                <p class="text-muted">{{ $user->email }}</p>
                                <span class="badge badge-{{ $user->is_active ? 'success' : 'danger' }}">
                                    {{ $user->is_active ? 'Actif' : 'Inactif' }}
                                </span>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-6">
                                    <strong><i class="fas fa-user mr-1"></i> Nom complet</strong>
                                    <p class="text-muted">{{ $user->name }}</p>
                                    <hr>
                                </div>
                                <div class="col-md-6">
                                    <strong><i class="fas fa-envelope mr-1"></i> Email</strong>
                                    <p class="text-muted">{{ $user->email }}</p>
                                    <hr>
                                </div>
                                <div class="col-md-6">
                                    <strong><i class="fas fa-phone mr-1"></i> Téléphone</strong>
                                    <p class="text-muted">{{ $user->phone ?? 'Non défini' }}</p>
                                    <hr>
                                </div>
                                <div class="col-md-6">
                                    <strong><i class="fas fa-calendar mr-1"></i> Date d'inscription</strong>
                                    <p class="text-muted">{{ $user->created_at->format('d/m/Y H:i') }}</p>
                                    <hr>
                                </div>
                                <div class="col-md-6">
                                    <strong><i class="fas fa-map-marker-alt mr-1"></i> Adresse</strong>
                                    <p class="text-muted">{{ $user->address ?? 'Non définie' }}</p>
                                    <hr>
                                </div>
                                <div class="col-md-6">
                                    <strong><i class="fas fa-city mr-1"></i> Ville</strong>
                                    <p class="text-muted">{{ $user->city ?? 'Non définie' }}</p>
                                    <hr>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Réservations -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Réservations ({{ $user->bookings->count() }})</h3>
                </div>
                <div class="card-body table-responsive p-0">
                    @if($user->bookings->count() > 0)
                        <table class="table table-hover text-nowrap">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Type</th>
                                    <th>Service</th>
                                    <th>Date</th>
                                    <th>Statut</th>
                                    <th>Prix</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($user->bookings as $booking)
                                    <tr>
                                        <td>{{ $booking->id }}</td>
                                        <td>
                                            <span class="badge badge-info">{{ ucfirst($booking->booking_type) }}</span>
                                        </td>
                                        <td>
                                            @if($booking->booking_type === 'package')
                                                {{ $booking->package->title ?? 'Package supprimé' }}
                                            @elseif($booking->booking_type === 'flight')
                                                {{ $booking->flight->departure_city ?? 'Vol supprimé' }} → {{ $booking->flight->arrival_city ?? '' }}
                                            @elseif($booking->booking_type === 'event')
                                                {{ $booking->event->title ?? 'Événement supprimé' }}
                                            @endif
                                        </td>
                                        <td>{{ $booking->created_at->format('d/m/Y') }}</td>
                                        <td>
                                            <span class="badge badge-{{ $booking->status === 'confirmed' ? 'success' : ($booking->status === 'pending' ? 'warning' : 'danger') }}">
                                                {{ ucfirst($booking->status) }}
                                            </span>
                                        </td>
                                        <td>{{ \App\Helpers\CurrencyHelper::format($booking->total_price) }}</td>
                                        <td>
                                            <a href="#" class="btn btn-info btn-sm">
                                                <i class="fas fa-eye"></i> Voir
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="text-center py-4">
                            <p class="text-muted">Aucune réservation trouvée</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Avis -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Avis ({{ $user->reviews->count() }})</h3>
                </div>
                <div class="card-body">
                    @if($user->reviews->count() > 0)
                        <div class="row">
                            @foreach($user->reviews as $review)
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between">
                                                <h6 class="card-title">
                                                    @if($review->reviewable_type === 'App\\Models\\Package')
                                                        Package: {{ $review->reviewable->title ?? 'Supprimé' }}
                                                    @elseif($review->reviewable_type === 'App\\Models\\Event')
                                                        Événement: {{ $review->reviewable->title ?? 'Supprimé' }}
                                                    @else
                                                        Service
                                                    @endif
                                                </h6>
                                                <div class="rating">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <i class="fas fa-star {{ $i <= $review->rating ? 'text-warning' : 'text-muted' }}"></i>
                                                    @endfor
                                                </div>
                                            </div>
                                            <p class="card-text">{{ $review->comment }}</p>
                                            <small class="text-muted">{{ $review->created_at->format('d/m/Y H:i') }}</small>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <p class="text-muted">Aucun avis trouvé</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
