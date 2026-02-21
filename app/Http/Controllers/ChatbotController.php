<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Airport;
use App\Models\Event;
use App\Models\TourPackage;
use Illuminate\Support\Facades\Log;

class ChatbotController extends Controller
{
    /**
     * Afficher l'interface du chatbot
     */
    public function index()
    {
        return view('chatbot.index');
    }

    /**
     * Traiter les messages du chatbot
     */
    public function processMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
            'conversation_history' => 'nullable|array'
        ]);

        $message = $request->input('message');
        $history = $request->input('conversation_history', []);

        try {
            // If OPENAI_API_KEY is set, use OpenAI Chat Completions to generate the response.
            $openaiKey = env('OPENAI_API_KEY') ?: config('services.openai.key');
            if ($openaiKey) {
                Log::info('Using OpenAI for chatbot response');

                $systemPrompt = "Vous êtes l'assistant virtuel de Carré Premium, un service de conciergerie et billetterie.\n" .
                    "Répondez en français, de manière professionnelle et concise.\n" .
                    "Vous devez renvoyer uniquement un objet JSON valide (aucun texte additionnel) avec les champs :\n" .
                    "- message: chaîne (le texte à afficher),\n" .
                    "- type: 'text'|'event_list'|'package_list'|'flight_results'|'greeting'|'error'|'info',\n" .
                    "- action: null ou action string (ex: 'redirect_to_search','connect_to_agent'),\n" .
                    "- data: null ou objet additionnel (par ex. { search_url: '...' })\n" .
                    "Si vous proposez des listes (events/packages), placez-les dans data en tableau.\n" .
                    "Si vous ne connaissez pas la réponse, proposez des options de clarification.\n" .
                    "Respectez le format JSON strict.";

                $messages = [
                    ['role' => 'system', 'content' => $systemPrompt],
                    ['role' => 'user', 'content' => $message]
                ];

                try {
                    $resp = Http::withToken($openaiKey)
                        ->timeout(30)
                        ->post('https://api.openai.com/v1/chat/completions', [
                            'model' => 'gpt-3.5-turbo',
                            'messages' => $messages,
                            'temperature' => 0.2,
                            'max_tokens' => 500,
                            'top_p' => 1,
                        ]);

                    if ($resp->successful()) {
                        $content = $resp->json('choices.0.message.content');

                        // Try to decode JSON the assistant returned
                        $decoded = null;
                        if ($content) {
                            // Clean BOM or stray markers
                            $contentTrim = trim($content);
                            // Attempt to find first '{' to mitigate extra text
                            $firstBrace = strpos($contentTrim, '{');
                            if ($firstBrace !== false) {
                                $possible = substr($contentTrim, $firstBrace);
                            } else {
                                $possible = $contentTrim;
                            }

                            $decoded = json_decode($possible, true);
                        }

                        if (is_array($decoded) && isset($decoded['message'])) {
                            // Ensure default fields
                            $decoded['success'] = $decoded['success'] ?? true;
                            $decoded['type'] = $decoded['type'] ?? 'text';
                            $decoded['action'] = $decoded['action'] ?? null;
                            $decoded['data'] = $decoded['data'] ?? null;

                            return response()->json($decoded);
                        }

                        // If not valid JSON, return the raw assistant text as message
                        return response()->json([
                            'success' => true,
                            'message' => $content ?? 'Désolé, je n’ai pas pu générer de réponse.',
                            'type' => 'text'
                        ]);
                    }

                    Log::error('OpenAI API error', ['status' => $resp->status(), 'body' => $resp->body()]);
                    // fallthrough to local handling on failure
                } catch (\Exception $e) {
                    Log::error('OpenAI request failed', ['error' => $e->getMessage()]);
                    // fallthrough to local handling
                }
            }

            // Analyser l'intention de l'utilisateur
            $analysis = $this->analyzeIntent($message, $history);

            Log::info('Chatbot Analysis', [
                'message' => $message,
                'analysis' => $analysis
            ]);

            // Router vers le bon gestionnaire selon l'intention
            switch ($analysis['intent']) {
                case 'flight_search':
                    return $this->handleFlightSearch($analysis['entities'], $message, $history);
                
                case 'event_booking':
                    return $this->handleEventBooking($analysis['entities'], $message);
                
                case 'package_booking':
                    return $this->handlePackageBooking($analysis['entities'], $message);
                
                case 'customer_support':
                    return $this->handleCustomerSupport($message);
                
                case 'greeting':
                    return $this->handleGreeting();
                
                default:
                    return $this->handleUnknown($message);
            }

        } catch (\Exception $e) {
            Log::error('Chatbot Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Désolé, une erreur s\'est produite. Pouvez-vous reformuler votre demande ?',
                'type' => 'error'
            ]);
        }
    }

    /**
     * Analyser l'intention du message avec IA
     */
    private function analyzeIntent($message, $history = [])
    {
        // Analyse d'intention améliorée : scoring par mots-clés + matching fuzzy.
        $text = mb_strtolower($message);

        // Mots-clés par intent
        $intentKeywords = [
            'flight_search' => ['vol', 'vols', 'avion', 'flight', 'départ', 'arrivée', 'billet', 'réserver un vol', 'aller', 'retour', 'iata', 'aéroport'],
            'event_booking' => ['concert', 'événement', 'event', 'spectacle', 'billet', 'ticket', 'réserver un événement', 'reservation', 'réserver'],
            'package_booking' => ['package', 'packages', 'tour', 'visite', 'safari', 'croisière', 'hélicoptère', 'jet', 'package touristique'],
            'customer_support' => ['aide', 'help', 'assistance', 'conseiller', 'contact', 'numéro', 'téléphone', 'support', 'problème', 'question', 'email'],
            'greeting' => ['bonjour', 'salut', 'hello', 'hi', 'bonsoir']
        ];

        // FAQ / intents simples override
        $faqKeywords = [
            'pricing' => ['prix', 'tarif', 'coût', 'combien', 'tarifs'],
            'hours' => ['horaire', 'horaires', 'ouvert', 'fermé', 'heure'],
            'contact' => ['contact', 'téléphone', 'numéro', 'email', 'mail']
        ];

        // Vérifier FAQ first (directe)
        foreach ($faqKeywords as $fIntent => $keys) {
            foreach ($keys as $k) {
                if (mb_stripos($text, $k) !== false) {
                    // map FAQ to intents
                    if ($fIntent === 'pricing') {
                        return ['intent' => 'package_booking', 'entities' => []];
                    }
                    if ($fIntent === 'contact' || $fIntent === 'hours') {
                        return ['intent' => 'customer_support', 'entities' => []];
                    }
                }
            }
        }

        // Score calculation
        $scores = [];
        $words = preg_split('/[\s,;.?!]+/u', $text, -1, PREG_SPLIT_NO_EMPTY);

        foreach ($intentKeywords as $intent => $keys) {
            $scores[$intent] = 0;
            foreach ($keys as $k) {
                if (mb_stripos($text, $k) !== false) {
                    $scores[$intent] += 2; // exact substring match
                    continue;
                }

                // fuzzy match against words (levenshtein when words length >=4)
                foreach ($words as $w) {
                    if (mb_strlen($w) >= 4 && mb_strlen($k) >= 4) {
                        $dist = levenshtein(mb_substr($w, 0, 50), mb_substr($k, 0, 50));
                        if ($dist <= 2) {
                            $scores[$intent] += 1;
                        }
                    }
                }
            }
        }

        // Choisir l'intent avec le meilleur score
        arsort($scores);
        $bestIntent = key($scores);

        // Seuil minimal pour considérer un match
        if ($scores[$bestIntent] >= 2) {
            switch ($bestIntent) {
                case 'flight_search':
                    return ['intent' => 'flight_search', 'entities' => $this->extractFlightEntities($message)];
                case 'event_booking':
                    return ['intent' => 'event_booking', 'entities' => $this->extractEventEntities($message)];
                case 'package_booking':
                    return ['intent' => 'package_booking', 'entities' => $this->extractPackageEntities($message)];
                case 'customer_support':
                    return ['intent' => 'customer_support', 'entities' => []];
                case 'greeting':
                    return ['intent' => 'greeting', 'entities' => []];
            }
        }

        // Fallback: essayer des heuristiques simples (dates, chiffres, presence d'IATA)
        if (preg_match('/\b([A-Z]{3})\b/', $message)) {
            return ['intent' => 'flight_search', 'entities' => $this->extractFlightEntities($message)];
        }

        // Aucun intent trouvé : demander une clarification
        return ['intent' => 'unknown', 'entities' => []];
    }

    /**
     * Extraire les entités d'un message de vol - VERSION AMÉLIORÉE
     */
    private function extractFlightEntities($message)
    {
        $entities = [
            'departure' => null,
            'arrival' => null,
            'date' => null,
            'return_date' => null,
            'passengers' => 1,
            'trip_type' => 'one_way' // aller simple par défaut
        ];

        Log::info('Extraction des entités de vol', ['message' => $message]);

        // Déterminer le type de vol
        if (preg_match('/\b(aller[-\s]retour|round[-\s]?trip|retour)\b/i', $message)) {
            $entities['trip_type'] = 'round_trip';
        } elseif (preg_match('/\b(aller[-\s]simple|one[-\s]?way|simple)\b/i', $message)) {
            $entities['trip_type'] = 'one_way';
        }

        // Extraction des villes/aéroports avec recherche flexible
        $airports = Airport::whereNotNull('iata_code')
            ->where('iata_code', '!=', '')
            ->get();

        $foundAirports = [];

        foreach ($airports as $airport) {
            // Recherche par nom de ville
            $cityPattern = '/\b' . preg_quote(mb_strtolower($airport->municipality), '/') . '\b/i';
            
            // Recherche par nom de pays
            $countryPattern = '/\b' . preg_quote(mb_strtolower($airport->country), '/') . '\b/i';
            
            // Recherche par code IATA
            $iataPattern = '/\b' . preg_quote($airport->iata_code, '/') . '\b/i';
            
            if (preg_match($cityPattern, $message) || 
                preg_match($countryPattern, $message) ||
                preg_match($iataPattern, $message)) {
                $foundAirports[] = [
                    'iata' => $airport->iata_code,
                    'name' => $airport->municipality,
                    'country' => $airport->country
                ];
            }
        }

        Log::info('Aéroports trouvés', ['airports' => $foundAirports]);

        // Déterminer départ et arrivée selon le contexte
        if (count($foundAirports) >= 2) {
            // Détecter l'ordre avec les mots-clés
            if (preg_match('/\b(de|depuis|from)\s+([a-zàéèêëïîôùû\s]+)\s+(à|vers|pour|to)\s+([a-zàéèêëïîôùû\s]+)/i', $message, $matches)) {
                // Ordre explicite: "de X à Y"
                $entities['departure'] = $foundAirports[0]['iata'];
                $entities['arrival'] = $foundAirports[1]['iata'];
            } elseif (preg_match('/\b(à|en|vers)\s+([a-zàéèêëïîôùû\s]+)\s+.*(de|depuis)\s+([a-zàéèêëïîôùû\s]+)/i', $message)) {
                // Ordre inversé: "à Y depuis X"
                $entities['departure'] = $foundAirports[1]['iata'];
                $entities['arrival'] = $foundAirports[0]['iata'];
            } else {
                // Ordre par apparition dans le message
                $entities['departure'] = $foundAirports[0]['iata'];
                $entities['arrival'] = $foundAirports[1]['iata'];
            }
        } elseif (count($foundAirports) == 1) {
            // Une seule ville trouvée, déterminer si c'est départ ou arrivée
            if (preg_match('/\b(à|vers|pour|en|to)\b/i', $message)) {
                $entities['arrival'] = $foundAirports[0]['iata'];
            } else {
                $entities['departure'] = $foundAirports[0]['iata'];
            }
        }

        // Extraction des dates - Formats multiples
        $datePatterns = [
            // Format: "12 novembre", "15 décembre"
            '/\b(\d{1,2})\s+(janvier|février|mars|avril|mai|juin|juillet|août|septembre|octobre|novembre|décembre)/i',
            // Format: "12/11/2024", "12-11-2024"
            '/\b(\d{1,2})[\/\-](\d{1,2})[\/\-](\d{2,4})\b/',
            // Format: "2024-11-12"
            '/\b(\d{4})[\/\-](\d{1,2})[\/\-](\d{1,2})\b/',
        ];

        foreach ($datePatterns as $pattern) {
            if (preg_match($pattern, $message, $matches)) {
                $entities['date'] = $this->parseDate($matches, $pattern);
                Log::info('Date extraite', ['date' => $entities['date'], 'matches' => $matches]);
                break;
            }
        }

        // Si pas de date trouvée, essayer des expressions relatives
        if (!$entities['date']) {
            if (preg_match('/\b(aujourd\'?hui|today)\b/i', $message)) {
                $entities['date'] = now()->format('Y-m-d');
            } elseif (preg_match('/\b(demain|tomorrow)\b/i', $message)) {
                $entities['date'] = now()->addDay()->format('Y-m-d');
            }
        }

        // Extraction du nombre de passagers
        if (preg_match('/\b(\d+)\s*(personne|passager|adulte|voyageur|passenger)/i', $message, $matches)) {
            $entities['passengers'] = (int)$matches[1];
        }

        Log::info('Entités extraites', ['entities' => $entities]);

        return $entities;
    }

    /**
     * Parser une date depuis les matches de regex
     */
    private function parseDate($matches, $pattern)
    {
        // Si format "12 novembre"
        if (count($matches) == 3 && !is_numeric($matches[2])) {
            $months = [
                'janvier' => '01', 'février' => '02', 'mars' => '03', 'avril' => '04',
                'mai' => '05', 'juin' => '06', 'juillet' => '07', 'août' => '08',
                'septembre' => '09', 'octobre' => '10', 'novembre' => '11', 'décembre' => '12'
            ];
            
            $day = str_pad($matches[1], 2, '0', STR_PAD_LEFT);
            $month = $months[mb_strtolower($matches[2])] ?? '01';
            $year = now()->year;
            
            // Si la date est dans le passé, utiliser l'année prochaine
            $date = "$year-$month-$day";
            if (strtotime($date) < time()) {
                $year++;
                $date = "$year-$month-$day";
            }
            
            return $date;
        }
        
        // Format JJ/MM/AAAA ou JJ-MM-AAAA
        if (count($matches) == 4 && strlen($matches[3]) >= 2) {
            $year = strlen($matches[3]) == 2 ? '20' . $matches[3] : $matches[3];
            $month = str_pad($matches[2], 2, '0', STR_PAD_LEFT);
            $day = str_pad($matches[1], 2, '0', STR_PAD_LEFT);
            return "$year-$month-$day";
        }
        
        // Format AAAA/MM/JJ
        if (count($matches) == 4 && strlen($matches[1]) == 4) {
            $year = $matches[1];
            $month = str_pad($matches[2], 2, '0', STR_PAD_LEFT);
            $day = str_pad($matches[3], 2, '0', STR_PAD_LEFT);
            return "$year-$month-$day";
        }
        
        return null;
    }

    /**
     * Extraire les entités d'un message d'événement
     */
    private function extractEventEntities($message)
    {
        $entities = [
            'event_name' => null,
            'date' => null,
            'city' => null,
            'tickets' => 1
        ];

        // Recherche d'événements par titre
        $events = Event::where('is_active', true)->get();
        
        foreach ($events as $event) {
            if (stripos($message, $event->title_fr) !== false || 
                stripos($message, $event->title_en) !== false) {
                $entities['event_name'] = $event->title_fr;
                $entities['event_id'] = $event->id;
            }
        }

        // Extraction du nombre de billets
        if (preg_match('/\b(\d+)\s*(billet|ticket|place)/i', $message, $matches)) {
            $entities['tickets'] = (int)$matches[1];
        }

        return $entities;
    }

    /**
     * Extraire les entités d'un message de package
     */
    private function extractPackageEntities($message)
    {
        $entities = [
            'package_type' => null,
            'destination' => null,
            'participants' => 1
        ];

        // Types de packages
        $packageTypes = [
            'hélicoptère' => 'helicopter',
            'helicopter' => 'helicopter',
            'jet' => 'private_jet',
            'croisière' => 'cruise',
            'safari' => 'safari',
            'visite' => 'city_tour',
        ];

        foreach ($packageTypes as $keyword => $type) {
            if (stripos($message, $keyword) !== false) {
                $entities['package_type'] = $type;
                break;
            }
        }

        return $entities;
    }

    /**
     * Gérer la recherche de vols - VERSION AMÉLIORÉE
     */
    private function handleFlightSearch($entities, $message, $history = [])
    {
        Log::info('handleFlightSearch appelé', ['entities' => $entities]);

        $missingInfo = [];

        if (!$entities['departure']) $missingInfo[] = 'ville de départ';
        if (!$entities['arrival']) $missingInfo[] = 'ville d\'arrivée';
        if (!$entities['date']) $missingInfo[] = 'date de départ';

        if (!empty($missingInfo)) {
            // Message plus détaillé pour guider l'utilisateur
            $missingText = implode(', ', $missingInfo);
            
            $message = "Pour rechercher votre vol, j'ai besoin des informations suivantes :\n\n";
            
            if (!$entities['departure']) {
                $message .= "• D'où partez-vous ? (ville de départ)\n";
            }
            if (!$entities['arrival']) {
                $message .= "• Où allez-vous ? (ville d'arrivée)\n";
            }
            if (!$entities['date']) {
                $message .= "• Quand souhaitez-vous partir ? (date de départ)\n";
            }
            
            $message .= "\nPar exemple : \"Je veux partir de Paris à New York le 15 décembre\"";

            return response()->json([
                'success' => true,
                'message' => $message,
                'type' => 'question',
                'missing_fields' => $missingInfo,
                'action' => 'collect_flight_info',
                'collected_data' => $entities
            ]);
        }

        // Toutes les informations sont présentes
        $departure = Airport::where('iata_code', $entities['departure'])->first();
        $arrival = Airport::where('iata_code', $entities['arrival'])->first();

        if (!$departure || !$arrival) {
            Log::error('Aéroports non trouvés', [
                'departure_code' => $entities['departure'],
                'arrival_code' => $entities['arrival']
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Désolé, je n\'ai pas trouvé ces aéroports dans notre base de données. Pouvez-vous préciser les villes ?',
                'type' => 'error'
            ]);
        }

        $tripTypeText = $entities['trip_type'] === 'round_trip' ? 'aller-retour' : 'aller simple';

        return response()->json([
            'success' => true,
            'message' => "Parfait ! 🎉\n\nJe recherche des vols {$tripTypeText} :\n• De : {$departure->municipality} ({$departure->iata_code})\n• Vers : {$arrival->municipality} ({$arrival->iata_code})\n• Date : {$entities['date']}\n• Passagers : {$entities['passengers']}\n\nRedirection vers les résultats...",
            'type' => 'flight_results',
            'action' => 'redirect_to_search',
            'data' => [
                'departure_id' => $entities['departure'],
                'arrival_id' => $entities['arrival'],
                'outbound_date' => $entities['date'],
                'adults' => $entities['passengers'],
                'search_url' => route('flights.search', [
                    'departure_id' => $entities['departure'],
                    'arrival_id' => $entities['arrival'],
                    'outbound_date' => $entities['date'],
                    'adults' => $entities['passengers'],
                ])
            ]
        ]);
    }

    /**
     * Gérer la réservation d'événements
     */
    private function handleEventBooking($entities, $message)
    {
        if (!isset($entities['event_id'])) {
            $events = Event::where('is_active', true)
                ->where('event_date', '>=', now())
                ->orderBy('event_date')
                ->limit(5)
                ->get();

            if ($events->isEmpty()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Désolé, nous n\'avons pas d\'événements disponibles pour le moment. Revenez bientôt !',
                    'type' => 'info'
                ]);
            }

            $eventsList = $events->map(function($event) {
                return [
                    'id' => $event->id,
                    'title' => $event->title_fr,
                    'date' => $event->event_date->format('d/m/Y'),
                    'venue' => $event->venue_name,
                    'price' => $event->min_price
                ];
            });

            return response()->json([
                'success' => true,
                'message' => 'Voici nos événements à venir. Lequel vous intéresse ?',
                'type' => 'event_list',
                'action' => 'show_events',
                'data' => $eventsList
            ]);
        }

        $event = Event::find($entities['event_id']);

        return response()->json([
            'success' => true,
            'message' => "Super ! Réservons des places pour {$event->title_fr}. Combien de billets souhaitez-vous ?",
            'type' => 'event_booking',
            'action' => 'show_event_details',
            'data' => [
                'event_id' => $event->id,
                'event_title' => $event->title_fr,
                'event_date' => $event->event_date->format('d/m/Y à H:i'),
                'venue' => $event->venue_name,
                'min_price' => $event->min_price,
                'available_seats' => $event->available_seats
            ]
        ]);
    }

    /**
     * Gérer la réservation de packages
     */
    private function handlePackageBooking($entities, $message)
    {
        $query = TourPackage::where('is_active', true);

        if ($entities['package_type']) {
            $query->where('package_type', $entities['package_type']);
        }

        $packages = $query->orderBy('is_featured', 'desc')
            ->limit(5)
            ->get();

        if ($packages->isEmpty()) {
            return response()->json([
                'success' => true,
                'message' => 'Désolé, nous n\'avons pas de packages disponibles pour le moment.',
                'type' => 'info'
            ]);
        }

        $packagesList = $packages->map(function($package) {
            return [
                'id' => $package->id,
                'title' => $package->title_fr,
                'destination' => $package->destination,
                'duration' => $package->duration_text_fr,
                'price' => $package->price
            ];
        });

        return response()->json([
            'success' => true,
            'message' => 'Voici nos packages disponibles :',
            'type' => 'package_list',
            'action' => 'show_packages',
            'data' => $packagesList
        ]);
    }

    /**
     * Gérer le support client
     */
    private function handleCustomerSupport($message)
    {
        return response()->json([
            'success' => true,
            'message' => 'Je vous mets en relation avec un conseiller client. Un instant s\'il vous plaît...',
            'type' => 'customer_support',
            'action' => 'connect_to_agent',
            'data' => [
                'support_url' => route('support.chat'),
                'phone' => '+225 XX XX XX XX XX',
                'email' => 'support@votresite.com'
            ]
        ]);
    }

    /**
     * Gérer les salutations
     */
    private function handleGreeting()
    {
        return response()->json([
            'success' => true,
            'message' => "Bonjour ! 👋 Je suis votre assistant virtuel.\n\nJe peux vous aider à :\n\n✈️ Rechercher et réserver des vols\n🎫 Réserver des billets pour des événements\n🌍 Découvrir nos packages touristiques\n💬 Vous mettre en contact avec un conseiller\n\nComment puis-je vous aider aujourd'hui ?",
            'type' => 'greeting',
            'action' => 'show_options'
        ]);
    }

    /**
     * Gérer les messages non compris
     */
    private function handleUnknown($message)
    {
        return response()->json([
            'success' => true,
            'message' => "Je ne suis pas sûr d'avoir bien compris. Pouvez-vous préciser si vous souhaitez :\n\n• Rechercher un vol ✈️\n• Réserver un événement 🎫\n• Découvrir nos packages 🌍\n• Parler à un conseiller 💬",
            'type' => 'clarification',
            'action' => 'show_options'
        ]);
    }
}