@extends('layouts.admin')

@section('title', 'Détails de l\'utilisateur - ' . $user->name)

@section('content')
<div class="container-fluid">
    <!-- Header avec informations principales -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="card-title mb-0">
                                <i class="fas fa-user mr-2"></i>Détails de l'utilisateur
                            </h3>
                            <small class="text-light">ID: {{ $user->id }}</small>
                        </div>
                        <div>
                            <a href="{{ route('admin.users.index') }}" class="btn btn-light btn-sm">
                                <i class="fas fa-arrow-left"></i> Retour à la liste
                            </a>
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i> Modifier
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Avatar et statut -->
                        <div class="col-lg-4 text-center">
                            <div class="mb-4">
                                @if($user->avatar)
                                    <img src="{{ $user->getFirstMediaUrl('avatar', 'normal') }}" alt="Avatar" class="rounded-circle shadow" style="width: 120px; height: 120px; object-fit: cover; border: 4px solid #fff;">
                                @else
                                    <div class="bg-gradient-primary rounded-circle d-inline-flex align-items-center justify-content-center text-white shadow" style="width: 120px; height: 120px; font-size: 3rem;">
                                        <i class="fas fa-user"></i>
                                    </div>
                                @endif
                            </div>
                            <h4 class="font-weight-bold text-dark mb-1">{{ $user->name }}</h4>
                            <p class="text-muted mb-3">{{ $user->email }}</p>
                            <div class="d-flex justify-content-center gap-2">
                                <span class="badge badge-lg {{ $user->is_active ? 'badge-success' : 'badge-danger' }} px-3 py-2">
                                    <i class="fas fa-circle mr-1"></i>{{ $user->is_active ? 'Actif' : 'Inactif' }}
                                </span>
                                @if($user->email_verified_at)
                                    <span class="badge badge-info px-3 py-2">
                                        <i class="fas fa-check-circle mr-1"></i>Email vérifié
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Informations détaillées -->
                        <div class="col-lg-8">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="info-box bg-light rounded p-3 mb-3">
                                        <div class="d-flex align-items-center">
                                            <div class="info-box-icon bg-primary text-white rounded mr-3">
                                                <i class="fas fa-user"></i>
                                            </div>
                                            <div>
                                                <span class="info-box-text text-muted">Nom complet</span>
                                                <span class="info-box-number font-weight-bold">{{ $user->name }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-box bg-light rounded p-3 mb-3">
                                        <div class="d-flex align-items-center">
                                            <div class="info-box-icon bg-info text-white rounded mr-3">
                                                <i class="fas fa-envelope"></i>
                                            </div>
                                            <div>
                                                <span class="info-box-text text-muted">Email</span>
                                                <span class="info-box-number font-weight-bold">{{ $user->email }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-box bg-light rounded p-3 mb-3">
                                        <div class="d-flex align-items-center">
                                            <div class="info-box-icon bg-success text-white rounded mr-3">
                                                <i class="fas fa-phone"></i>
                                            </div>
                                            <div>
                                                <span class="info-box-text text-muted">Téléphone</span>
                                                <span class="info-box-number font-weight-bold">{{ $user->phone ?? 'Non défini' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-box bg-light rounded p-3 mb-3">
                                        <div class="d-flex align-items-center">
                                            <div class="info-box-icon bg-warning text-white rounded mr-3">
                                                <i class="fas fa-calendar"></i>
                                            </div>
                                            <div>
                                                <span class="info-box-text text-muted">Date d'inscription</span>
                                                <span class="info-box-number font-weight-bold">{{ $user->created_at->format('d/m/Y') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-box bg-light rounded p-3 mb-3">
                                        <div class="d-flex align-items-center">
                                            <div class="info-box-icon bg-secondary text-white rounded mr-3">
                                                <i class="fas fa-map-marker-alt"></i>
                                            </div>
                                            <div>
                                                <span class="info-box-text text-muted">Adresse</span>
                                                <span class="info-box-number font-weight-bold">{{ $user->address ?? 'Non définie' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-box bg-light rounded p-3 mb-3">
                                        <div class="d-flex align-items-center">
                                            <div class="info-box-icon bg-dark text-white rounded mr-3">
                                                <i class="fas fa-city"></i>
                                            </div>
                                            <div>
                                                <span class="info-box-text text-muted">Ville</span>
                                                <span class="info-box-number font-weight-bold">{{ $user->city ?? 'Non définie' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="row mt-4">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $user->bookings->count() }}</h3>
                    <p>Réservations totales</p>
                </div>
                <div class="icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <a href="#bookings" class="small-box-footer">Voir détails <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $user->bookings->where('status', 'confirmed')->count() }}</h3>
                    <p>Réservations confirmées</p>
                </div>
                <div class="icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <a href="#bookings" class="small-box-footer">Voir détails <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $user->reviews->count() }}</h3>
                    <p>Avis postés</p>
                </div>
                <div class="icon">
                    <i class="fas fa-star"></i>
                </div>
                <a href="#reviews" class="small-box-footer">Voir détails <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $user->bookings->sum('total_price') ? \App\Helpers\CurrencyHelper::format($user->bookings->sum('total_price')) : '0 FCFA' }}</h3>
                    <p>Total dépensé</p>
                </div>
                <div class="icon">
                    <i class="fas fa-euro-sign"></i>
                </div>
                <div class="small-box-footer">&nbsp;</div>
            </div>
        </div>
    </div>

    <!-- Réservations -->
    <div class="row mt-4" id="bookings">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-gradient-primary">
                    <h3 class="card-title text-white mb-0">
                        <i class="fas fa-shopping-cart mr-2"></i>Réservations ({{ $user->bookings->count() }})
                    </h3>
                </div>
                <div class="card-body table-responsive p-0">
                    @if($user->bookings->count() > 0)
                        <table class="table table-hover text-nowrap mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="border-0">ID</th>
                                    <th class="border-0">Type</th>
                                    <th class="border-0">Service</th>
                                    <th class="border-0">Date</th>
                                    <th class="border-0">Statut</th>
                                    <th class="border-0">Prix</th>
                                    <th class="border-0">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($user->bookings as $booking)
                                    <tr>
                                        <td>
                                            <span class="badge badge-light border">#{{ $booking->id }}</span>
                                        </td>
                                        <td>
                                            <span class="badge badge-info">{{ ucfirst($booking->booking_type) }}</span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($booking->booking_type === 'package')
                                                    <div class="text-truncate" style="max-width: 200px;">
                                                        <strong>{{ $booking->package->title ?? 'Package supprimé' }}</strong>
                                                    </div>
                                                @elseif($booking->booking_type === 'flight')
                                                    <div>
                                                        <i class="fas fa-plane text-primary mr-2"></i>
                                                        {{ $booking->flight->departure_city ?? 'Vol supprimé' }} → {{ $booking->flight->arrival_city ?? '' }}
                                                    </div>
                                                @elseif($booking->booking_type === 'event')
                                                    <div>
                                                        <i class="fas fa-calendar-alt text-success mr-2"></i>
                                                        {{ $booking->event->title ?? 'Événement supprimé' }}
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <small class="text-muted">{{ $booking->created_at->format('d/m/Y') }}</small>
                                            <br>
                                            <small class="text-muted">{{ $booking->created_at->format('H:i') }}</small>
                                        </td>
                                        <td>
                                            <span class="badge badge-{{ $booking->status === 'confirmed' ? 'success' : ($booking->status === 'pending' ? 'warning' : 'danger') }} px-2 py-1">
                                                <i class="fas fa-{{ $booking->status === 'confirmed' ? 'check' : ($booking->status === 'pending' ? 'clock' : 'times') }} mr-1"></i>
                                                {{ ucfirst($booking->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <strong class="text-primary">{{ \App\Helpers\CurrencyHelper::format($booking->total_price) }}</strong>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary" title="Voir détails">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Aucune réservation trouvée</h5>
                            <p class="text-muted">Cet utilisateur n'a pas encore effectué de réservation.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Avis -->
    <div class="row mt-4" id="reviews">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-gradient-success">
                    <h3 class="card-title text-white mb-0">
                        <i class="fas fa-star mr-2"></i>Avis ({{ $user->reviews->count() }})
                    </h3>
                </div>
                <div class="card-body">
                    @if($user->reviews->count() > 0)
                        <div class="row">
                            @foreach($user->reviews as $review)
                                <div class="col-lg-6 mb-4">
                                    <div class="card border-left-primary shadow-sm h-100">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start mb-3">
                                                <div class="flex-grow-1">
                                                    <h6 class="font-weight-bold text-primary mb-2">
                                                        @if($review->reviewable_type === 'App\\Models\\Package')
                                                            <i class="fas fa-suitcase text-primary mr-2"></i>Package: {{ $review->reviewable->title ?? 'Supprimé' }}
                                                        @elseif($review->reviewable_type === 'App\\Models\\Event')
                                                            <i class="fas fa-calendar-alt text-success mr-2"></i>Événement: {{ $review->reviewable->title ?? 'Supprimé' }}
                                                        @else
                                                            <i class="fas fa-concierge-bell text-info mr-2"></i>Service
                                                        @endif
                                                    </h6>
                                                    <div class="rating mb-2">
                                                        @for($i = 1; $i <= 5; $i++)
                                                            <i class="fas fa-star {{ $i <= $review->rating ? 'text-warning' : 'text-muted' }}"></i>
                                                        @endfor
                                                        <small class="text-muted ml-2">({{ $review->rating }}/5)</small>
                                                    </div>
                                                </div>
                                                <small class="text-muted">{{ $review->created_at->format('d/m/Y') }}</small>
                                            </div>
                                            <p class="card-text text-dark mb-0">{{ $review->comment }}</p>
                                        </div>
                                        <div class="card-footer bg-light">
                                            <small class="text-muted">
                                                <i class="fas fa-clock mr-1"></i>Posté le {{ $review->created_at->format('d/m/Y à H:i') }}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-star-half-alt fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Aucun avis trouvé</h5>
                            <p class="text-muted">Cet utilisateur n'a pas encore laissé d'avis.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.bg-gradient-primary {
    background: linear-gradient(45deg, #007bff, #0056b3);
}
.bg-gradient-success {
    background: linear-gradient(45deg, #28a745, #1e7e34);
}
.info-box {
    box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
    transition: all 0.3s ease;
}
.info-box:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,.15);
}
.rating .fas {
    font-size: 0.9rem;
}
.small-box {
    transition: all 0.3s ease;
}
.small-box:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 12px rgba(0,0,0,.15);
}
</style>
@endsection
