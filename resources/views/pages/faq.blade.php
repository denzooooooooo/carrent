@extends('layouts.app')

@section('title', 'FAQ - Carré Premium')

@section('content')
<div class="min-h-screen bg-white">
  {{-- Hero --}}
  <section class="relative h-[40vh] bg-gradient-to-r from-purple-600 to-amber-600 overflow-hidden">
    <div class="absolute inset-0 bg-black/20"></div>
    <div class="relative z-10 container mx-auto h-full flex flex-col justify-center px-4">
      <h1 class="text-5xl font-black text-white mb-4">Questions Fréquentes</h1>
      <p class="text-xl text-white/90">Trouvez rapidement les réponses à vos questions</p>
    </div>
  </section>

  {{-- Search --}}
  <section class="py-8">
    <div class="container mx-auto">
      <div class="max-w-2xl mx-auto -mt-8 relative z-20">
          <div class="bg-white rounded-2xl shadow-2xl p-4">
          <div class="relative">
            <input
              type="text"
              placeholder="Rechercher une question..."
              class="w-full px-6 py-4 pl-12 rounded-xl border-2 border-gray-200 bg-white focus:border-purple-600 focus:outline-none"
            />
            <svg class="w-6 h-6 absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
          </div>
        </div>
      </div>
    </div>
  </section>

  {{-- FAQ Categories --}}
  <section class="py-12">
    <div class="container mx-auto">
      <div class="max-w-4xl mx-auto space-y-8">
        @php
          $faqs = [
            [
              'category' => 'Réservations',
              'questions' => [
                [
                  'q' => 'Comment réserver un vol sur Carré Premium ?',
                  'a' => 'Pour réserver un vol, rendez-vous sur notre page "Vols", entrez vos critères de recherche (départ, arrivée, dates), sélectionnez le vol qui vous convient, remplissez les informations des passagers et procédez au paiement sécurisé.'
                ],
                [
                  'q' => 'Puis-je modifier ma réservation après confirmation ?',
                  'a' => 'Oui, vous pouvez modifier votre réservation selon les conditions tarifaires de votre billet. Connectez-vous à votre compte, accédez à "Mes Réservations" et cliquez sur "Modifier". Des frais peuvent s\'appliquer.'
                ],
                [
                  'q' => 'Comment annuler ma réservation ?',
                  'a' => 'Pour annuler, connectez-vous à votre compte, allez dans "Mes Réservations", sélectionnez la réservation concernée et cliquez sur "Annuler". Les conditions d\'annulation et frais dépendent du type de billet acheté.'
                ],
                [
                  'q' => 'Puis-je réserver pour quelqu\'un d\'autre ?',
                  'a' => 'Oui, vous pouvez réserver pour d\'autres personnes. Il suffit d\'entrer leurs informations lors de la réservation. Assurez-vous que les noms correspondent exactement à ceux des documents d\'identité.'
                ]
              ]
            ],
            [
              'category' => 'Paiements',
              'questions' => [
                [
                  'q' => 'Quels moyens de paiement acceptez-vous ?',
                  'a' => 'Nous acceptons les cartes bancaires (Visa, Mastercard), Mobile Money (Orange Money, MTN Money, Moov Money), virements bancaires et PayPal. Tous les paiements sont sécurisés.'
                ],
                [
                  'q' => 'Mon paiement est-il sécurisé ?',
                  'a' => 'Absolument ! Nous utilisons le cryptage SSL et travaillons avec des partenaires de paiement certifiés PCI-DSS. Vos informations bancaires ne sont jamais stockées sur nos serveurs.'
                ],
                [
                  'q' => 'Quand serai-je débité ?',
                  'a' => 'Vous êtes débité immédiatement après la confirmation de votre réservation. Vous recevrez un email de confirmation avec tous les détails de votre achat.'
                ],
                [
                  'q' => 'Puis-je payer en plusieurs fois ?',
                  'a' => 'Pour certains packages et vols, le paiement en plusieurs fois est disponible. Cette option vous sera proposée lors du processus de paiement si elle est applicable.'
                ]
              ]
            ],
            [
              'category' => 'Billets & Documents',
              'questions' => [
                [
                  'q' => 'Comment recevoir mon billet ?',
                  'a' => 'Votre e-ticket est envoyé par email immédiatement après confirmation du paiement. Vous pouvez également le télécharger depuis votre compte dans la section "Mes Réservations".'
                ],
                [
                  'q' => 'Dois-je imprimer mon billet ?',
                  'a' => 'Non, un e-ticket sur votre smartphone suffit. Cependant, nous recommandons d\'avoir une copie imprimée en cas de problème technique.'
                ],
                [
                  'q' => 'Quels documents dois-je présenter à l\'aéroport ?',
                  'a' => 'Vous devez présenter votre e-ticket, une pièce d\'identité valide (passeport pour les vols internationaux) et tout visa requis pour votre destination.'
                ],
                [
                  'q' => 'Mon passeport expire bientôt, puis-je voyager ?',
                  'a' => 'Votre passeport doit être valide au moins 6 mois après votre date de retour. Vérifiez également les exigences spécifiques de votre pays de destination.'
                ]
              ]
            ],
            [
              'category' => 'Bagages',
              'questions' => [
                [
                  'q' => 'Quelle est la franchise bagage ?',
                  'a' => 'La franchise bagage dépend de votre classe de voyage et de la compagnie aérienne. En général : Économique (1x23kg), Affaires (2x32kg), Première (3x32kg). Vérifiez les détails sur votre e-ticket.'
                ],
                [
                  'q' => 'Puis-je ajouter des bagages supplémentaires ?',
                  'a' => 'Oui, vous pouvez ajouter des bagages lors de la réservation ou ultérieurement via votre compte. Il est moins cher de les ajouter en ligne qu\'à l\'aéroport.'
                ],
                [
                  'q' => 'Que puis-je emporter en cabine ?',
                  'a' => 'En cabine, vous pouvez généralement emporter un bagage de 8kg maximum (dimensions : 55x40x20cm) plus un accessoire personnel (sac à main, ordinateur portable).'
                ],
                [
                  'q' => 'Que faire si mes bagages sont perdus ?',
                  'a' => 'Signalez immédiatement la perte au comptoir bagages de l\'aéroport. Conservez votre récépissé et contactez-nous pour vous assister dans les démarches avec la compagnie aérienne.'
                ]
              ]
            ],
            [
              'category' => 'Événements & Packages',
              'questions' => [
                [
                  'q' => 'Comment réserver des billets pour un événement ?',
                  'a' => 'Parcourez notre section "Événements", sélectionnez l\'événement souhaité, choisissez votre zone de siège et le nombre de billets, puis procédez au paiement.'
                ],
                [
                  'q' => 'Les billets d\'événements sont-ils remboursables ?',
                  'a' => 'Cela dépend de l\'événement et des conditions de vente. Consultez les conditions spécifiques lors de votre réservation. En cas d\'annulation de l\'événement, vous serez remboursé intégralement.'
                ],
                [
                  'q' => 'Que comprend un package touristique ?',
                  'a' => 'Nos packages incluent généralement le transport, l\'hébergement, certains repas et activités. Les détails exacts sont spécifiés sur chaque page de package.'
                ],
                [
                  'q' => 'Puis-je personnaliser un package ?',
                  'a' => 'Oui ! Contactez notre service client pour discuter de vos besoins spécifiques. Nous pouvons adapter nos packages selon vos préférences.'
                ]
              ]
            ],
            [
              'category' => 'Compte & Sécurité',
              'questions' => [
                [
                  'q' => 'Comment créer un compte ?',
                  'a' => 'Cliquez sur "S\'inscrire" en haut de la page, remplissez le formulaire avec vos informations et validez votre email. Vous pouvez aussi vous inscrire lors de votre première réservation.'
                ],
                [
                  'q' => 'J\'ai oublié mon mot de passe, que faire ?',
                  'a' => 'Cliquez sur "Mot de passe oublié" sur la page de connexion, entrez votre email et suivez les instructions pour réinitialiser votre mot de passe.'
                ],
                [
                  'q' => 'Mes données personnelles sont-elles protégées ?',
                  'a' => 'Oui, nous prenons la protection de vos données très au sérieux. Consultez notre Politique de Confidentialité pour plus de détails sur la gestion de vos informations.'
                ],
                [
                  'q' => 'Comment supprimer mon compte ?',
                  'a' => 'Pour supprimer votre compte, contactez notre service client. Notez que cette action est irréversible et toutes vos données seront supprimées.'
                ]
              ]
            ]
          ];
        @endphp

        @foreach($faqs as $catIndex => $category)
          <div class="bg-white rounded-3xl p-8 shadow-xl">
            <h2 class="text-3xl font-black mb-6 flex items-center">
              <span class="w-12 h-12 bg-gradient-to-r from-purple-600 to-amber-600 rounded-full flex items-center justify-center text-white mr-4">
                {{ $catIndex + 1 }}
              </span>
              {{ $category['category'] }}
            </h2>

            <div class="space-y-4">
              @foreach($category['questions'] as $qIndex => $faq)
                <div class="border-2 border-gray-200 rounded-xl overflow-hidden">
                  <button
                    x-on:click="openIndex = openIndex === '{{ $catIndex }}-{{ $qIndex }}' ? null : '{{ $catIndex }}-{{ $qIndex }}'"
                    class="w-full p-6 text-left flex items-center justify-between hover:bg-gray-50 transition-colors"
                  >
                    <span class="font-bold text-lg pr-4">{{ $faq['q'] }}</span>
                    <svg
                      class="w-6 h-6 flex-shrink-0 transform transition-transform"
                      :class="openIndex === '{{ $catIndex }}-{{ $qIndex }}' ? 'rotate-180' : ''"
                      fill="none"
                      stroke="currentColor"
                      viewBox="0 0 24 24"
                    >
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                  </button>

                  <div
                    x-show="openIndex === '{{ $catIndex }}-{{ $qIndex }}'"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 max-h-0"
                    x-transition:enter-end="opacity-100 max-h-screen"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 max-h-screen"
                    x-transition:leave-end="opacity-0 max-h-0"
                    class="px-6 pb-6 text-gray-600 leading-relaxed overflow-hidden"
                    style="display: none;"
                  >
                    {{ $faq['a'] }}
                  </div>
                </div>
              @endforeach
            </div>
          </div>
        @endforeach
      </div>
    </div>
  </section>

  {{-- Contact CTA --}}
  <section class="py-16 bg-gradient-to-r from-purple-600 to-amber-600">
    <div class="container mx-auto text-center">
      <h2 class="text-4xl font-black text-white mb-4">Vous ne trouvez pas votre réponse ?</h2>
      <p class="text-xl text-white/90 mb-8">Notre équipe est là pour vous aider 24h/24, 7j/7</p>
      <div class="flex flex-wrap gap-4 justify-center">
        <a href="{{ route('contact') }}" class="px-8 py-4 bg-white text-purple-600 font-bold rounded-xl hover:shadow-2xl transition-all">
          Nous Contacter
        </a>
        <a href="tel:+225XXXXXXXXX" class="px-8 py-4 bg-transparent border-2 border-white text-white font-bold rounded-xl hover:bg-white hover:text-purple-600 transition-all">
          Appeler Maintenant
        </a>
      </div>
    </div>
  </section>
</div>

<script>
document.addEventListener('alpine:init', () => {
  Alpine.data('faq', () => ({
    openIndex: null
  }));
});
</script>
@endsection
