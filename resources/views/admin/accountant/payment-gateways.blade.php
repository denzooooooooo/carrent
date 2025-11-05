@extends('admin.layouts.app')

@section('title', 'Gestion des Passerelles de Paiement - Comptable')

@section('content')
<div class="max-w-7xl mx-auto py-8">
    <!-- Page Header -->
    <div class="flex justify-between items-center mb-8 border-b pb-2">
        <div>
            <h1 class="text-3xl font-bold text-dark gradient-text">
                <i class="fas fa-credit-card mr-3"></i>
                Gestion des Passerelles de Paiement
            </h1>
            <p class="text-gray-600 mt-2">Configurez et gérez vos méthodes de paiement</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.accountant.dashboard') }}" class="py-2 px-4 rounded-lg text-white font-semibold bg-gray-600 hover:bg-gray-700 transition duration-300 shadow-md flex items-center">
                <i class="fas fa-arrow-left mr-2"></i> Retour au Dashboard
            </a>
        </div>
    </div>

    <!-- Payment Gateways Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
        <!-- Stripe -->
        <div class="bg-white rounded-xl shadow-xl p-6 border-l-4 border-primary hover:shadow-2xl transition-all duration-300">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <div class="text-sm font-bold text-primary uppercase mb-2">
                        Stripe
                    </div>
                    <div class="text-xl font-bold text-gray-800 mb-3">
                        <i class="fab fa-stripe text-primary mr-2"></i>
                        Passerelle de Paiement
                    </div>
                    <div class="mb-4">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                            <i class="fas fa-check-circle mr-1"></i>Activé
                        </span>
                    </div>
                </div>
                <div class="text-primary">
                    <i class="fab fa-stripe text-4xl"></i>
                </div>
            </div>
            <div class="flex space-x-3 mt-4">
                <button class="bg-primary hover:bg-primary/90 text-white px-4 py-2 rounded-lg font-medium transition duration-300 flex items-center" data-toggle="modal" data-target="#stripeModal">
                    <i class="fas fa-cog mr-2"></i>Configurer
                </button>
                <button class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg font-medium transition duration-300 flex items-center">
                    <i class="fas fa-eye mr-2"></i>Voir les Transactions
                </button>
            </div>
        </div>

        <!-- PayPal -->
        <div class="bg-white rounded-xl shadow-xl p-6 border-l-4 border-info hover:shadow-2xl transition-all duration-300">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <div class="text-sm font-bold text-info uppercase mb-2">
                        PayPal
                    </div>
                    <div class="text-xl font-bold text-gray-800 mb-3">
                        <i class="fab fa-paypal text-info mr-2"></i>
                        Passerelle de Paiement
                    </div>
                    <div class="mb-4">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                            <i class="fas fa-exclamation-triangle mr-1"></i>Configuration Requise
                        </span>
                    </div>
                </div>
                <div class="text-info">
                    <i class="fab fa-paypal text-4xl"></i>
                </div>
            </div>
            <div class="flex space-x-3 mt-4">
                <button class="bg-info hover:bg-info/90 text-white px-4 py-2 rounded-lg font-medium transition duration-300 flex items-center" data-toggle="modal" data-target="#paypalModal">
                    <i class="fas fa-cog mr-2"></i>Configurer
                </button>
                <button class="bg-gray-200 hover:bg-gray-300 text-gray-400 px-4 py-2 rounded-lg font-medium transition duration-300 flex items-center cursor-not-allowed" disabled>
                    <i class="fas fa-eye mr-2"></i>Voir les Transactions
                </button>
            </div>
        </div>

        <!-- Bank Transfer -->
        <div class="bg-white rounded-xl shadow-xl p-6 border-l-4 border-success hover:shadow-2xl transition-all duration-300">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <div class="text-sm font-bold text-success uppercase mb-2">
                        Virement Bancaire
                    </div>
                    <div class="text-xl font-bold text-gray-800 mb-3">
                        <i class="fas fa-university text-success mr-2"></i>
                        Paiement Manuel
                    </div>
                    <div class="mb-4">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                            <i class="fas fa-check-circle mr-1"></i>Activé
                        </span>
                    </div>
                </div>
                <div class="text-success">
                    <i class="fas fa-university text-4xl"></i>
                </div>
            </div>
            <div class="flex space-x-3 mt-4">
                <button class="bg-success hover:bg-success/90 text-white px-4 py-2 rounded-lg font-medium transition duration-300 flex items-center" data-toggle="modal" data-target="#bankModal">
                    <i class="fas fa-cog mr-2"></i>Configurer
                </button>
                <button class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg font-medium transition duration-300 flex items-center">
                    <i class="fas fa-eye mr-2"></i>Voir les Transactions
                </button>
            </div>
        </div>

        <!-- Cash on Delivery -->
        <div class="bg-white rounded-xl shadow-xl p-6 border-l-4 border-warning hover:shadow-2xl transition-all duration-300">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <div class="text-sm font-bold text-warning uppercase mb-2">
                        Paiement à la Livraison
                    </div>
                    <div class="text-xl font-bold text-gray-800 mb-3">
                        <i class="fas fa-money-bill-wave text-warning mr-2"></i>
                        Paiement sur Place
                    </div>
                    <div class="mb-4">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                            <i class="fas fa-check-circle mr-1"></i>Activé
                        </span>
                    </div>
                </div>
                <div class="text-warning">
                    <i class="fas fa-money-bill-wave text-4xl"></i>
                </div>
            </div>
            <div class="flex space-x-3 mt-4">
                <button class="bg-warning hover:bg-warning/90 text-white px-4 py-2 rounded-lg font-medium transition duration-300 flex items-center" data-toggle="modal" data-target="#codModal">
                    <i class="fas fa-cog mr-2"></i>Configurer
                </button>
                <button class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg font-medium transition duration-300 flex items-center">
                    <i class="fas fa-eye mr-2"></i>Voir les Transactions
                </button>
            </div>
        </div>
    </div>

    <!-- Transaction Summary -->
    <div class="bg-white rounded-xl shadow-xl p-8">
        <div class="flex items-center mb-6">
            <i class="fas fa-chart-line text-primary text-2xl mr-3"></i>
            <h2 class="text-2xl font-bold text-gray-800">Résumé des Transactions</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-gradient-to-r from-primary to-primary/80 rounded-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-primary/80 text-sm font-medium uppercase">Total Transactions</p>
                        <p class="text-3xl font-bold">1,234</p>
                    </div>
                    <i class="fas fa-credit-card text-3xl text-primary/60"></i>
                </div>
            </div>

            <div class="bg-gradient-to-r from-green-500 to-green-400 rounded-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm font-medium uppercase">Transactions Réussies</p>
                        <p class="text-3xl font-bold">1,189</p>
                    </div>
                    <i class="fas fa-check-circle text-3xl text-green-200"></i>
                </div>
            </div>

            <div class="bg-gradient-to-r from-yellow-500 to-yellow-400 rounded-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-yellow-100 text-sm font-medium uppercase">Transactions En Attente</p>
                        <p class="text-3xl font-bold">23</p>
                    </div>
                    <i class="fas fa-clock text-3xl text-yellow-200"></i>
                </div>
            </div>

            <div class="bg-gradient-to-r from-red-500 to-red-400 rounded-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-red-100 text-sm font-medium uppercase">Transactions Échouées</p>
                        <p class="text-3xl font-bold">22</p>
                    </div>
                    <i class="fas fa-times-circle text-3xl text-red-200"></i>
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
