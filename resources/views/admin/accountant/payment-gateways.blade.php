@extends('admin.layouts.app')

@section('title', 'Gestion des Passerelles de Paiement - Comptable')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800 font-weight-bold">
                <i class="fas fa-credit-card text-primary mr-2"></i>
                Gestion des Passerelles de Paiement
            </h1>
            <p class="text-muted mt-1">Configurez et gérez vos méthodes de paiement</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.accountant.dashboard') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left mr-2"></i>Retour au Dashboard
            </a>
        </div>
    </div>

    <!-- Payment Gateways Cards -->
    <div class="row">
        <!-- Stripe -->
        <div class="col-lg-6 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Stripe
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <i class="fab fa-stripe text-primary mr-2"></i>
                                Passerelle de Paiement
                            </div>
                            <div class="mt-3">
                                <span class="badge badge-success">Activé</span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fab fa-stripe fa-2x text-primary"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <button class="btn btn-primary btn-sm mr-2" data-toggle="modal" data-target="#stripeModal">
                            <i class="fas fa-cog mr-1"></i>Configurer
                        </button>
                        <button class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-eye mr-1"></i>Voir les Transactions
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- PayPal -->
        <div class="col-lg-6 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                PayPal
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <i class="fab fa-paypal text-info mr-2"></i>
                                Passerelle de Paiement
                            </div>
                            <div class="mt-3">
                                <span class="badge badge-warning">Configuration Requise</span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fab fa-paypal fa-2x text-info"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <button class="btn btn-info btn-sm mr-2" data-toggle="modal" data-target="#paypalModal">
                            <i class="fas fa-cog mr-1"></i>Configurer
                        </button>
                        <button class="btn btn-outline-secondary btn-sm" disabled>
                            <i class="fas fa-eye mr-1"></i>Voir les Transactions
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bank Transfer -->
        <div class="col-lg-6 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Virement Bancaire
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <i class="fas fa-university text-success mr-2"></i>
                                Paiement Manuel
                            </div>
                            <div class="mt-3">
                                <span class="badge badge-success">Activé</span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-university fa-2x text-success"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <button class="btn btn-success btn-sm mr-2" data-toggle="modal" data-target="#bankModal">
                            <i class="fas fa-cog mr-1"></i>Configurer
                        </button>
                        <button class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-eye mr-1"></i>Voir les Transactions
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cash on Delivery -->
        <div class="col-lg-6 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Paiement à la Livraison
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <i class="fas fa-money-bill-wave text-warning mr-2"></i>
                                Paiement sur Place
                            </div>
                            <div class="mt-3">
                                <span class="badge badge-success">Activé</span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-money-bill-wave fa-2x text-warning"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <button class="btn btn-warning btn-sm mr-2" data-toggle="modal" data-target="#codModal">
                            <i class="fas fa-cog mr-1"></i>Configurer
                        </button>
                        <button class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-eye mr-1"></i>Voir les Transactions
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Transaction Summary -->
    <div class="row">
        <div class="col-lg-12 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-line mr-2"></i>
                        Résumé des Transactions
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Total Transactions
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">1,234</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-credit-card fa-2x text-primary"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                Transactions Réussies
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">1,189</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-check-circle fa-2x text-success"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6 mb-4">
                            <div class="card border-left-warning shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                Transactions En Attente
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">23</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-clock fa-2x text-warning"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6 mb-4">
                            <div class="card border-left-danger shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                                Transactions Échouées
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">22</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-times-circle fa-2x text-danger"></i>
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

<!-- Stripe Configuration Modal -->
<div class="modal fade" id="stripeModal" tabindex="-1" role="dialog" aria-labelledby="stripeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="stripeModalLabel">
                    <i class="fab fa-stripe text-primary mr-2"></i>
                    Configuration Stripe
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="stripePublishableKey">Clé Publique</label>
                                <input type="text" class="form-control" id="stripePublishableKey" placeholder="pk_test_...">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="stripeSecretKey">Clé Secrète</label>
                                <input type="password" class="form-control" id="stripeSecretKey" placeholder="sk_test_...">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="stripeWebhookSecret">Secret Webhook</label>
                        <input type="password" class="form-control" id="stripeWebhookSecret" placeholder="whsec_...">
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="stripeTestMode" checked>
                        <label class="form-check-label" for="stripeTestMode">
                            Mode Test
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Sauvegarder</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- PayPal Configuration Modal -->
<div class="modal fade" id="paypalModal" tabindex="-1" role="dialog" aria-labelledby="paypalModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paypalModalLabel">
                    <i class="fab fa-paypal text-info mr-2"></i>
                    Configuration PayPal
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="paypalClientId">Client ID</label>
                                <input type="text" class="form-control" id="paypalClientId" placeholder="AZ...">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="paypalClientSecret">Client Secret</label>
                                <input type="password" class="form-control" id="paypalClientSecret" placeholder="EP...">
                            </div>
                        </div>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="paypalSandbox" checked>
                        <label class="form-check-label" for="paypalSandbox">
                            Mode Sandbox
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-info">Sauvegarder</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Bank Transfer Configuration Modal -->
<div class="modal fade" id="bankModal" tabindex="-1" role="dialog" aria-labelledby="bankModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bankModalLabel">
                    <i class="fas fa-university text-success mr-2"></i>
                    Configuration Virement Bancaire
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="bankName">Nom de la Banque</label>
                        <input type="text" class="form-control" id="bankName" placeholder="Banque Nationale">
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="accountName">Nom du Compte</label>
                                <input type="text" class="form-control" id="accountName" placeholder="Carré Premium SARL">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="accountNumber">Numéro de Compte</label>
                                <input type="text" class="form-control" id="accountNumber" placeholder="FR76...">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="iban">IBAN</label>
                        <input type="text" class="form-control" id="iban" placeholder="FR76...">
                    </div>
                    <div class="form-group">
                        <label for="swift">Code SWIFT/BIC</label>
                        <input type="text" class="form-control" id="swift" placeholder="BNPAFRPP">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-success">Sauvegarder</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Cash on Delivery Configuration Modal -->
<div class="modal fade" id="codModal" tabindex="-1" role="dialog" aria-labelledby="codModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="codModalLabel">
                    <i class="fas fa-money-bill-wave text-warning mr-2"></i>
                    Configuration Paiement à la Livraison
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="codInstructions">Instructions pour le Client</label>
                        <textarea class="form-control" id="codInstructions" rows="4" placeholder="Veuillez préparer le montant exact..."></textarea>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="codEnabled" checked>
                        <label class="form-check-label" for="codEnabled">
                            Activer le paiement à la livraison
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-warning">Sauvegarder</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Initialize tooltips
    $('[data-toggle="tooltip"]').tooltip();

    // Handle form submissions
    $('form').on('submit', function(e) {
        e.preventDefault();

        // Show loading state
        const submitBtn = $(this).find('button[type="submit"]');
        const originalText = submitBtn.html();
        submitBtn.html('<i class="fas fa-spinner fa-spin mr-2"></i>Sauvegarde...').prop('disabled', true);

        // Simulate save operation
        setTimeout(() => {
            submitBtn.html('<i class="fas fa-check mr-2"></i>Sauvegardé!').removeClass('btn-primary btn-info btn-success btn-warning').addClass('btn-success');

            setTimeout(() => {
                submitBtn.html(originalText).prop('disabled', false).removeClass('btn-success').addClass(originalText.includes('primary') ? 'btn-primary' : originalText.includes('info') ? 'btn-info' : originalText.includes('success') ? 'btn-success' : 'btn-warning');
                $(this).closest('.modal').modal('hide');
            }, 1500);
        }, 2000);
    });
});
</script>
@endsection
