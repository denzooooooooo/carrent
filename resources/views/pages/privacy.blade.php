@extends('layouts.app')

@section('title', __('Privacy Policy') . ' - Carré Premium')

@section('content')
<div class="min-h-screen bg-white">
  {{-- Hero --}}
  <section class="relative h-[30vh] bg-gradient-to-r from-purple-600 to-amber-600 overflow-hidden">
    <div class="absolute inset-0 bg-black/20"></div>
    <div class="relative z-10 container-custom h-full flex flex-col justify-center px-4">
      <h1 class="text-5xl font-black text-white mb-2">{{ __('Privacy Policy') }}</h1>
      <p class="text-white/90">{{ __('Last updated: January 10, 2025') }}</p>
    </div>
  </section>

  {{-- Content --}}
  <section class="py-12">
    <div class="container-custom">
      <div class="max-w-4xl mx-auto bg-white rounded-3xl p-8 md:p-12 shadow-xl">

        <div class="prose prose-lg max-w-none">
          <div class="mb-8 p-6 bg-blue-50 rounded-2xl border-2 border-blue-200">
            <p class="text-sm mb-0">
              {{ __('At Carré Premium, we take the protection of your personal data very seriously. This policy explains how we collect, use, share and protect your information.') }}
            </p>
          </div>

          <h2 class="text-3xl font-black mb-4">{{ __('1. Information We Collect') }}</h2>

          <h3 class="text-2xl font-bold mb-3">1.1 Informations Fournies Directement</h3>
          <p class="mb-4">Lorsque vous utilisez nos services, nous collectons les informations que vous nous fournissez :</p>
          <ul class="list-disc pl-6 mb-6 space-y-2">
            <li><strong>Informations de compte :</strong> nom, prénom, email, téléphone, mot de passe</li>
            <li><strong>Informations de voyage :</strong> numéro de passeport, date de naissance, nationalité</li>
            <li><strong>Informations de paiement :</strong> détails de carte bancaire, historique des transactions</li>
            <li><strong>Préférences :</strong> destinations favorites, préférences de voyage</li>
          </ul>

          <h3 class="text-2xl font-bold mb-3">1.2 Informations Collectées Automatiquement</h3>
          <p class="mb-4">Lors de votre navigation sur notre site, nous collectons automatiquement :</p>
          <ul class="list-disc pl-6 mb-6 space-y-2">
            <li><strong>Données de navigation :</strong> adresse IP, type de navigateur, pages visitées</li>
            <li><strong>Cookies :</strong> identifiants de session, préférences utilisateur</li>
            <li><strong>Données d'appareil :</strong> type d'appareil, système d'exploitation</li>
            <li><strong>Données de localisation :</strong> localisation approximative basée sur l'IP</li>
          </ul>

          <h2 class="text-3xl font-black mb-4 mt-8">{{ __('2. Use of Your Data') }}</h2>
          <p class="mb-4">Nous utilisons vos données personnelles pour :</p>

          <h3 class="text-2xl font-bold mb-3">2.1 Fourniture des Services</h3>
          <ul class="list-disc pl-6 mb-4 space-y-2">
            <li>Traiter vos réservations et paiements</li>
            <li>Vous envoyer des confirmations et e-tickets</li>
            <li>Gérer votre compte utilisateur</li>
            <li>Fournir un support client</li>
          </ul>

          <h3 class="text-2xl font-bold mb-3">2.2 Amélioration des Services</h3>
          <ul class="list-disc pl-6 mb-4 space-y-2">
            <li>Analyser l'utilisation du site pour l'améliorer</li>
            <li>Personnaliser votre expérience</li>
            <li>Développer de nouvelles fonctionnalités</li>
          </ul>

          <h3 class="text-2xl font-bold mb-3">2.3 Communication</h3>
          <ul class="list-disc pl-6 mb-6 space-y-2">
            <li>Vous envoyer des mises à jour sur vos réservations</li>
            <li>Vous informer de nos offres et promotions (avec votre consentement)</li>
            <li>Répondre à vos questions et demandes</li>
          </ul>

          <h2 class="text-3xl font-black mb-4 mt-8">{{ __('3. Sharing Your Data') }}</h2>
          <p class="mb-4">Nous ne vendons jamais vos données personnelles. Nous les partageons uniquement dans les cas suivants :</p>

          <h3 class="text-2xl font-bold mb-3">3.1 Prestataires de Services</h3>
          <p class="mb-4">Nous partageons vos données avec :</p>
          <ul class="list-disc pl-6 mb-4 space-y-2">
            <li><strong>Compagnies aériennes :</strong> pour émettre vos billets</li>
            <li><strong>Organisateurs d'événements :</strong> pour vos réservations</li>
            <li><strong>Processeurs de paiement :</strong> pour traiter vos transactions</li>
            <li><strong>Services d'hébergement :</strong> pour stocker vos données de manière sécurisée</li>
          </ul>

          <h3 class="text-2xl font-bold mb-3">3.2 Obligations Légales</h3>
          <p class="mb-6">
            Nous pouvons divulguer vos informations si la loi l'exige ou pour protéger nos droits, votre sécurité ou celle d'autrui.
          </p>

          <h2 class="text-3xl font-black mb-4 mt-8">{{ __('4. Data Security') }}</h2>
          <p class="mb-4">Nous mettons en œuvre des mesures de sécurité robustes :</p>
          <ul class="list-disc pl-6 mb-6 space-y-2">
            <li><strong>Cryptage SSL/TLS :</strong> toutes les données sensibles sont cryptées</li>
            <li><strong>Serveurs sécurisés :</strong> hébergement dans des centres de données certifiés</li>
            <li><strong>Accès restreint :</strong> seul le personnel autorisé peut accéder aux données</li>
            <li><strong>Audits réguliers :</strong> tests de sécurité et mises à jour fréquentes</li>
            <li><strong>Conformité PCI-DSS :</strong> pour les paiements par carte</li>
          </ul>

          <h2 className="text-3xl font-black mb-4 mt-8">{{ __('5. Your Rights') }}</h2>
          <p class="mb-4">Conformément au RGPD et aux lois locales, vous avez le droit de :</p>

          <h3 class="text-2xl font-bold mb-3">5.1 Accès et Rectification</h3>
          <p class="mb-4">
            Vous pouvez accéder à vos données personnelles et les corriger à tout moment depuis votre compte ou en nous contactant.
          </p>

          <h3 class="text-2xl font-bold mb-3">5.2 Suppression</h3>
          <p class="mb-4">
            Vous pouvez demander la suppression de vos données, sauf si nous devons les conserver pour des raisons légales ou contractuelles.
          </p>

          <h3 class="text-2xl font-bold mb-3">5.3 Opposition et Limitation</h3>
          <p class="mb-4">
            Vous pouvez vous opposer au traitement de vos données à des fins marketing ou demander une limitation du traitement.
          </p>

          <h3 class="text-2xl font-bold mb-3">5.4 Portabilité</h3>
          <p class="mb-6">
            Vous pouvez demander une copie de vos données dans un format structuré et couramment utilisé.
          </p>

          <h2 class="text-3xl font-black mb-4 mt-8">{{ __('6. Cookies and Similar Technologies') }}</h2>
          <p class="mb-4">Nous utilisons des cookies pour :</p>
          <ul class="list-disc pl-6 mb-4 space-y-2">
            <li><strong>Cookies essentiels :</strong> nécessaires au fonctionnement du site</li>
            <li><strong>Cookies de performance :</strong> pour analyser l'utilisation du site</li>
            <li><strong>Cookies de fonctionnalité :</strong> pour mémoriser vos préférences</li>
            <li><strong>Cookies marketing :</strong> pour personnaliser les publicités (avec votre consentement)</li>
          </ul>
          <p class="mb-6">
            Vous pouvez gérer vos préférences de cookies via les paramètres de votre navigateur ou notre bannière de cookies.
          </p>

          <h2 class="text-3xl font-black mb-4 mt-8">{{ __('7. Data Retention') }}</h2>
          <p class="mb-6">
            Nous conservons vos données personnelles aussi longtemps que nécessaire pour fournir nos services et respecter nos obligations légales. Les données de réservation sont conservées pendant 10 ans conformément aux obligations comptables.
          </p>

          <h2 class="text-3xl font-black mb-4 mt-8">{{ __('8. International Transfers') }}</h2>
          <p class="mb-6">
            Vos données peuvent être transférées et traitées dans des pays autres que votre pays de résidence. Nous nous assurons que ces transferts respectent les normes de protection des données applicables.
          </p>

          <h2 class="text-3xl font-black mb-4 mt-8">{{ __('9. Protection of Minors') }}</h2>
          <p class="mb-6">
            Nos services ne sont pas destinés aux personnes de moins de 18 ans. Nous ne collectons pas sciemment de données personnelles auprès de mineurs sans le consentement parental.
          </p>

          <h2 class="text-3xl font-black mb-4 mt-8">{{ __('10. Changes to This Policy') }}</h2>
          <p class="mb-6">
            Nous pouvons mettre à jour cette politique de confidentialité périodiquement. Nous vous informerons de tout changement significatif par email ou via une notification sur notre site.
          </p>

          <h2 class="text-3xl font-black mb-4 mt-8">{{ __('11. Contact') }}</h2>
          <p class="mb-2">Pour toute question concernant cette politique ou vos données personnelles :</p>
          <ul class="list-none space-y-2 mb-6">
            <li><strong>Délégué à la Protection des Données :</strong> dpo@carrepremium.com</li>
            <li><strong>Email :</strong> privacy@carrepremium.com</li>
            <li><strong>Téléphone :</strong> +225 27 XX XX XX XX</li>
            <li><strong>Adresse :</strong> Abidjan, Plateau, Côte d'Ivoire</li>
          </ul>

          <h2 class="text-3xl font-black mb-4 mt-8">{{ __('12. Supervisory Authority') }}</h2>
          <p class="mb-6">
            Si vous estimez que vos droits ne sont pas respectés, vous pouvez déposer une plainte auprès de l'autorité de protection des données compétente en Côte d'Ivoire.
          </p>

          <div class="mt-12 p-6 bg-green-50 rounded-2xl border-2 border-green-200">
            <h3 class="text-xl font-bold mb-3">🔒 {{ __('Our Commitment') }}</h3>
            <p class="text-sm text-gray-600 mb-0">
              {{ __('Carré Premium is committed to protecting your privacy and processing your personal data in a transparent, fair and compliant manner. Your trust is our priority.') }}
            </p>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
@endsection
