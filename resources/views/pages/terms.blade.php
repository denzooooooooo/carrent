@extends('layouts.app')

@section('title', 'Conditions d\'Utilisation - Carré Premium')

@section('content')
<div class="min-h-screen bg-white">
  {{-- Hero --}}
  <section class="relative h-[30vh] bg-gradient-to-r from-purple-600 to-amber-600 overflow-hidden">
    <div class="absolute inset-0 bg-black/20"></div>
    <div class="relative z-10 container mx-auto h-full flex flex-col justify-center px-4">
      <h1 class="text-5xl font-black text-white mb-2">Conditions d'Utilisation</h1>
      <p class="text-white/90">Dernière mise à jour : 10 Janvier 2025</p>
    </div>
  </section>

  {{-- Content --}}
  <section class="py-12">
    <div class="container mx-auto">
      <div class="max-w-4xl mx-auto bg-white rounded-3xl p-8 md:p-12 shadow-xl">

        <div class="prose prose-lg max-w-none">

          <h2 class="text-3xl font-black mb-4">1. Acceptation des Conditions</h2>
          <p class="mb-6">
            En accédant et en utilisant le site web de Carré Premium (ci-après "le Site"), vous acceptez d'être lié par les présentes conditions d'utilisation. Si vous n'acceptez pas ces conditions, veuillez ne pas utiliser notre Site.
          </p>

          <h2 class="text-3xl font-black mb-4 mt-8">2. Services Proposés</h2>
          <p class="mb-4">Carré Premium propose les services suivants :</p>
          <ul class="list-disc pl-6 mb-6 space-y-2">
            <li>Réservation de billets d'avion</li>
            <li>Réservation de billets pour événements sportifs et culturels</li>
            <li>Packages touristiques (hélicoptère, jet privé, circuits)</li>
            <li>Services de conciergerie voyage</li>
          </ul>

          <h2 class="text-3xl font-black mb-4 mt-8">3. Inscription et Compte Utilisateur</h2>
          <h3 class="text-2xl font-bold mb-3">3.1 Création de Compte</h3>
          <p class="mb-4">
            Pour effectuer une réservation, vous devez créer un compte en fournissant des informations exactes et à jour. Vous êtes responsable de la confidentialité de vos identifiants de connexion.
          </p>

          <h3 class="text-2xl font-bold mb-3">3.2 Responsabilité du Compte</h3>
          <p class="mb-6">
            Vous êtes entièrement responsable de toutes les activités effectuées sous votre compte. En cas d'utilisation non autorisée, vous devez nous en informer immédiatement.
          </p>

          <h2 class="text-3xl font-black mb-4 mt-8">4. Réservations et Paiements</h2>
          <h3 class="text-2xl font-bold mb-3">4.1 Processus de Réservation</h3>
          <p class="mb-4">
            Toutes les réservations sont soumises à disponibilité. Une réservation n'est confirmée qu'après réception du paiement intégral et envoi d'un email de confirmation.
          </p>

          <h3 class="text-2xl font-bold mb-3">4.2 Prix et Paiement</h3>
          <ul class="list-disc pl-6 mb-4 space-y-2">
            <li>Les prix sont affichés en Francs CFA (XOF), Euros (EUR) ou Dollars US (USD)</li>
            <li>Les prix incluent toutes les taxes sauf mention contraire</li>
            <li>Le paiement doit être effectué au moment de la réservation</li>
            <li>Nous acceptons les cartes bancaires, Mobile Money et virements</li>
          </ul>

          <h3 class="text-2xl font-bold mb-3">4.3 Confirmation</h3>
          <p class="mb-6">
            Après paiement, vous recevrez un email de confirmation contenant votre e-ticket et les détails de votre réservation. Vérifiez attentivement toutes les informations.
          </p>

          <h2 class="text-3xl font-black mb-4 mt-8">5. Modifications et Annulations</h2>
          <h3 class="text-2xl font-bold mb-3">5.1 Modifications</h3>
          <p class="mb-4">
            Les modifications de réservation sont possibles selon les conditions tarifaires de votre billet. Des frais peuvent s'appliquer. Contactez notre service client pour toute modification.
          </p>

          <h3 class="text-2xl font-bold mb-3">5.2 Annulations</h3>
          <p class="mb-4">Les conditions d'annulation varient selon le type de réservation :</p>
          <ul class="list-disc pl-6 mb-6 space-y-2">
            <li><strong>Vols :</strong> Selon les conditions de la compagnie aérienne</li>
            <li><strong>Événements :</strong> Généralement non remboursables sauf annulation de l'événement</li>
            <li><strong>Packages :</strong> Annulation gratuite jusqu'à 30 jours avant le départ, puis frais dégressifs</li>
          </ul>

          <h2 class="text-3xl font-black mb-4 mt-8">6. Responsabilités</h2>
          <h3 class="text-2xl font-bold mb-3">6.1 Responsabilité de Carré Premium</h3>
          <p class="mb-4">
            Carré Premium agit en tant qu'intermédiaire entre vous et les prestataires de services (compagnies aériennes, organisateurs d'événements, etc.). Nous ne sommes pas responsables des retards, annulations ou modifications effectués par ces prestataires.
          </p>

          <h3 class="text-2xl font-bold mb-3">6.2 Responsabilité de l'Utilisateur</h3>
          <p class="mb-4">Vous êtes responsable de :</p>
          <ul class="list-disc pl-6 mb-6 space-y-2">
            <li>Vérifier la validité de vos documents de voyage (passeport, visa)</li>
            <li>Arriver à l'heure aux points de départ</li>
            <li>Respecter les règles des compagnies aériennes et organisateurs</li>
            <li>Fournir des informations exactes lors de la réservation</li>
          </ul>

          <h2 class="text-3xl font-black mb-4 mt-8">7. Propriété Intellectuelle</h2>
          <p class="mb-6">
            Tous les contenus du Site (textes, images, logos, vidéos) sont la propriété de Carré Premium ou de ses partenaires. Toute reproduction sans autorisation est interdite.
          </p>

          <h2 class="text-3xl font-black mb-4 mt-8">8. Protection des Données</h2>
          <p class="mb-6">
            Vos données personnelles sont traitées conformément à notre Politique de Confidentialité. Nous nous engageons à protéger vos informations et à ne les utiliser que dans le cadre de nos services.
          </p>

          <h2 class="text-3xl font-black mb-4 mt-8">9. Limitation de Responsabilité</h2>
          <p class="mb-6">
            Carré Premium ne peut être tenu responsable des dommages indirects, incidents ou consécutifs résultant de l'utilisation de nos services, sauf en cas de faute grave ou intentionnelle de notre part.
          </p>

          <h2 class="text-3xl font-black mb-4 mt-8">10. Force Majeure</h2>
          <p class="mb-6">
            Nous ne serons pas tenus responsables de tout manquement à nos obligations en cas de force majeure (catastrophes naturelles, guerres, pandémies, grèves, etc.).
          </p>

          <h2 class="text-3xl font-black mb-4 mt-8">11. Modifications des Conditions</h2>
          <p class="mb-6">
            Nous nous réservons le droit de modifier ces conditions à tout moment. Les modifications entreront en vigueur dès leur publication sur le Site. Votre utilisation continue du Site après modification constitue votre acceptation des nouvelles conditions.
          </p>

          <h2 class="text-3xl font-black mb-4 mt-8">12. Droit Applicable et Juridiction</h2>
          <p class="mb-6">
            Ces conditions sont régies par le droit ivoirien. Tout litige sera soumis à la juridiction exclusive des tribunaux d'Abidjan, Côte d'Ivoire.
          </p>

          <h2 class="text-3xl font-black mb-4 mt-8">13. Contact</h2>
          <p class="mb-2">Pour toute question concernant ces conditions, contactez-nous :</p>
          <ul class="list-none space-y-2 mb-6">
            <li><strong>Email :</strong> legal@carrepremium.com</li>
            <li><strong>Téléphone :</strong> +225 27 XX XX XX XX</li>
            <li><strong>Adresse :</strong> Abidjan, Plateau, Côte d'Ivoire</li>
          </ul>

          <div class="mt-12 p-6 bg-purple-50 rounded-2xl border-2 border-purple-200">
            <p class="text-sm text-gray-600">
              <strong>Note importante :</strong> En utilisant nos services, vous reconnaissez avoir lu, compris et accepté l'intégralité de ces conditions d'utilisation. Si vous avez des questions, n'hésitez pas à contacter notre service client avant d'effectuer une réservation.
            </p>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
@endsection
