@extends('admin.layouts.app')

@section('title', 'Gestion des Réservations - Comptable')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Gestion des Réservations</h1>
            <p class="text-muted">Suivi et gestion des paiements des réservations</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.accountant.dashboard') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left mr-2"></i>Retour au Dashboard
            </a>
            <a href="{{ route('admin.accountant.reports') }}" class="btn btn-outline-primary">
                <i class="fas fa-chart-line mr-2"></i>Rapports
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-3">
                    <label for="status" class="form-label">Statut</label>
                    <select name="status" id="status" class="form-control">
                        <option value="all" {{ request('status', 'all') === 'all' ? 'selected' : '' }}>Tous les statuts</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>En attente</option>
                        <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Confirmé</option>
                        <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Annulé</option>
                        <option value="refunded" {{ request('status') === 'refunded' ? 'selected' : '' }}>Remboursé</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="type" class="form-label">Type</label>
                    <select name="type" id="type" class="form-control">
                        <option value="all" {{ request('type', 'all') === 'all' ? 'selected' : '' }}>Tous les types</option>
                        <option value="package" {{ request('type') === 'package' ? 'selected' : '' }}>Packages</option>
                        <option value="event" {{ request('type') === 'event' ? 'selected' : '' }}>Événements</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="search" class="form-label">Recherche</label>
                    <input type="text" name="search" id="search" class="form-control"
                           value="{{ request('search') }}" placeholder="Nom du client, produit...">
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary mr-2">
                        <i class="fas fa-search mr-2"></i>Filtrer
                    </button>
                    <a href="{{ route('admin.accountant.bookings') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times mr-2"></i>Réinitialiser
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Bookings Table -->
    <div class="card shadow">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                Réservations ({{ $bookings->total() }})
            </h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Type</th>
                            <th>Produit</th>
                            <th>Client</th>
                            <th>Montant</th>
                            <th>Statut</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bookings as $booking)
                        <tr>
                            <td>#{{ $booking->id }}</td>
                            <td>
                                <span class="badge badge-{{ $booking->type === 'package' ? 'primary' : 'success' }}">
                                    {{ ucfirst($booking->type) }}
                                </span>
                            </td>
                            <td>
                                <div class="font-weight-bold">{{ $booking->title }}</div>
                                <small class="text-muted">
                                    @if($booking->type === 'package')
                                        Package touristique
                                    @else
                                        Événement
                                    @endif
                                </small>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle bg-primary text-white mr-2" style="width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 14px;">
                                        {{ substr($booking->user->name ?? 'N/A', 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="font-weight-bold">{{ $booking->user->name ?? 'N/A' }}</div>
                                        <small class="text-muted">{{ $booking->user->email ?? '' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="font-weight-bold text-success">
                                    {{ number_format($booking->total_amount, 0, ',', ' ') }} FCFA
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-{{ $booking->status === 'confirmed' ? 'success' : ($booking->status === 'pending' ? 'warning' : ($booking->status === 'cancelled' ? 'danger' : 'secondary')) }}">
                                    @switch($booking->status)
                                        @case('pending')
                                            En attente
                                            @break
                                        @case('confirmed')
                                            Confirmé
                                            @break
                                        @case('cancelled')
                                            Annulé
                                            @break
                                        @case('refunded')
                                            Remboursé
                                            @break
                                        @default
                                            {{ ucfirst($booking->status) }}
                                    @endswitch
                                </span>
                            </td>
                            <td>{{ $booking->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <!-- View Details -->
                                    <button type="button" class="btn btn-sm btn-outline-info" data-toggle="modal" data-target="#bookingModal{{ $booking->id }}">
                                        <i class="fas fa-eye"></i>
                                    </button>

                                    <!-- Update Status -->
                                    @if($booking->status !== 'refunded')
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" data-toggle="dropdown">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            @if($booking->status !== 'confirmed')
                                            <form method="POST" action="{{ route('admin.accountant.bookings.update-status', [$booking->type, $booking->id]) }}" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="confirmed">
                                                <button type="submit" class="dropdown-item">
                                                    <i class="fas fa-check text-success mr-2"></i>Confirmer
                                                </button>
                                            </form>
                                            @endif

                                            @if($booking->status !== 'cancelled')
                                            <form method="POST" action="{{ route('admin.accountant.bookings.update-status', [$booking->type, $booking->id]) }}" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="cancelled">
                                                <button type="submit" class="dropdown-item">
                                                    <i class="fas fa-times text-danger mr-2"></i>Annuler
                                                </button>
                                            </form>
                                            @endif

                                            @if($booking->status === 'confirmed')
                                            <form method="POST" action="{{ route('admin.accountant.bookings.update-status', [$booking->type, $booking->id]) }}" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="refunded">
                                                <button type="submit" class="dropdown-item">
                                                    <i class="fas fa-undo text-warning mr-2"></i>Rembourser
                                                </button>
                                            </form>
                                            @endif
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </td>
                        </tr>

                        <!-- Booking Details Modal -->
                        <div class="modal fade" id="bookingModal{{ $booking->id }}" tabindex="-1" role="dialog">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Détails de la Réservation #{{ $booking->id }}</h5>
                                        <button type="button" class="close" data-dismiss="modal">
                                            <span>&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h6>Informations Client</h6>
                                                <p><strong>Nom:</strong> {{ $booking->user->name ?? 'N/A' }}</p>
                                                <p><strong>Email:</strong> {{ $booking->user->email ?? 'N/A' }}</p>
                                                <p><strong>Téléphone:</strong> {{ $booking->user->phone ?? 'N/A' }}</p>
                                            </div>
                                            <div class="col-md-6">
                                                <h6>Détails de la Réservation</h6>
                                                <p><strong>Type:</strong> {{ ucfirst($booking->type) }}</p>
                                                <p><strong>Produit:</strong> {{ $booking->title }}</p>
                                                <p><strong>Montant:</strong> {{ number_format($booking->total_amount, 0, ',', ' ') }} FCFA</p>
                                                <p><strong>Statut:</strong>
                                                    <span class="badge badge-{{ $booking->status === 'confirmed' ? 'success' : ($booking->status === 'pending' ? 'warning' : 'danger') }}">
                                                        {{ ucfirst($booking->status) }}
                                                    </span>
                                                </p>
                                                <p><strong>Date:</strong> {{ $booking->created_at->format('d/m/Y H:i') }}</p>
                                            </div>
                                        </div>

                                        @if($booking->type === 'event' && isset($booking->booking))
                                        <div class="row mt-3">
                                            <div class="col-12">
                                                <h6>Détails Événement</h6>
                                                <div class="table-responsive">
                                                    <table class="table table-sm">
                                                        <thead>
                                                            <tr>
                                                                <th>Zone</th>
                                                                <th>Quantité</th>
                                                                <th>Prix Unit.</th>
                                                                <th>Total</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($booking->booking->eventBookingItems ?? [] as $item)
                                                            <tr>
                                                                <td>{{ $item->seatZone->zone_name_fr ?? 'N/A' }}</td>
                                                                <td>{{ $item->quantity }}</td>
                                                                <td>{{ number_format($item->price, 0, ',', ' ') }} FCFA</td>
                                                                <td>{{ number_format($item->total_price, 0, ',', ' ') }} FCFA</td>
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="fas fa-inbox fa-3x mb-3"></i>
                                    <p>Aucune réservation trouvée</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($bookings->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $bookings->appends(request()->query())->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Auto-submit form on filter change
    $('#status, #type').change(function() {
        $(this).closest('form').submit();
    });
});
</script>
@endpush
@endsection
