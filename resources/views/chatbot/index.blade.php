@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-8 px-4">
        <div class="max-w-4xl mx-auto">
            <!-- En-tête -->
            <div class="bg-white rounded-t-2xl shadow-lg p-6 border-b border-gray-200">
                <div class="flex items-center space-x-4">
                    <div
                        class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">Assistant Virtuel</h1>
                        <p class="text-sm text-gray-500">En ligne - Répond en quelques secondes</p>
                    </div>
                </div>
            </div>

            <!-- Zone de conversation -->
            <div id="chatContainer" class="bg-white shadow-lg h-96 overflow-y-auto p-6 space-y-4">
                <!-- Message de bienvenue -->
                <div class="flex items-start space-x-3 animate-fadeIn">
                    <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                            </path>
                        </svg>
                    </div>
                    <div class="bg-blue-50 rounded-lg p-4 max-w-md">
                        <p class="text-gray-800">Bonjour ! 👋 Je suis votre assistant virtuel. Je peux vous aider à :</p>
                        <ul class="mt-2 space-y-1 text-sm text-gray-700">
                            <li>✈️ Rechercher et réserver des vols</li>
                            <li>🎫 Réserver des billets pour des événements</li>
                            <li>🌍 Découvrir nos packages touristiques</li>
                            <li>💬 Vous mettre en contact avec un conseiller</li>
                        </ul>
                        <p class="mt-2 text-gray-800">Comment puis-je vous aider aujourd'hui ?</p>
                    </div>
                </div>
            </div>

            <!-- Zone de saisie -->
            <div class="bg-white rounded-b-2xl shadow-lg p-4 border-t border-gray-200">
                <form id="chatForm" class="flex space-x-3">
                    <input type="text" id="messageInput" placeholder="Écrivez votre message ici..."
                        class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        autocomplete="off">
                    <button type="submit" id="sendButton"
                        class="px-6 py-3 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-lg font-medium hover:from-blue-600 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 flex items-center space-x-2">
                        <span>Envoyer</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                        </svg>
                    </button>
                </form>

                <!-- Suggestions rapides -->
                <div id="quickSuggestions" class="mt-3 flex flex-wrap gap-2">
                    <button
                        class="suggestion-btn px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-full text-sm text-gray-700 transition-colors"
                        data-message="Je veux réserver un vol">
                        ✈️ Réserver un vol
                    </button>
                    <button
                        class="suggestion-btn px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-full text-sm text-gray-700 transition-colors"
                        data-message="Je cherche un événement">
                        🎫 Événements
                    </button>
                    <button
                        class="suggestion-btn px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-full text-sm text-gray-700 transition-colors"
                        data-message="Parler à un conseiller">
                        💬 Conseiller
                    </button>
                </div>
            </div>

            <!-- Indicateur de frappe -->
            <div id="typingIndicator" class="hidden mt-4 flex items-center space-x-2 text-gray-500">
                <div class="flex space-x-1">
                    <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce"></div>
                    <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.1s"></div>
                    <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                </div>
                <span class="text-sm">L'assistant écrit...</span>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            @keyframes fadeIn {
                from {
                    opacity: 0;
                    transform: translateY(10px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .animate-fadeIn {
                animation: fadeIn 0.3s ease-out;
            }

            #chatContainer::-webkit-scrollbar {
                width: 8px;
            }

            #chatContainer::-webkit-scrollbar-track {
                background: #f1f1f1;
                border-radius: 10px;
            }

            #chatContainer::-webkit-scrollbar-thumb {
                background: #888;
                border-radius: 10px;
            }

            #chatContainer::-webkit-scrollbar-thumb:hover {
                background: #555;
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const chatForm = document.getElementById('chatForm');
                const messageInput = document.getElementById('messageInput');
                const chatContainer = document.getElementById('chatContainer');
                const sendButton = document.getElementById('sendButton');
                const typingIndicator = document.getElementById('typingIndicator');
                const suggestionButtons = document.querySelectorAll('.suggestion-btn');

                let conversationHistory = [];

                // Gestion des suggestions rapides
                suggestionButtons.forEach(btn => {
                    btn.addEventListener('click', function () {
                        const message = this.getAttribute('data-message');
                        messageInput.value = message;
                        chatForm.dispatchEvent(new Event('submit'));
                    });
                });

                // Soumission du formulaire
                chatForm.addEventListener('submit', async function (e) {
                    e.preventDefault();

                    const message = messageInput.value.trim();
                    if (!message) return;

                    // Ajouter le message de l'utilisateur
                    addUserMessage(message);
                    messageInput.value = '';
                    sendButton.disabled = true;

                    // Afficher l'indicateur de frappe
                    typingIndicator.classList.remove('hidden');

                    try {
                        const response = await fetch('{{ route("chatbot.process") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                message: message,
                                conversation_history: conversationHistory
                            })
                        });

                        const data = await response.json();

                        // Masquer l'indicateur de frappe
                        typingIndicator.classList.add('hidden');

                        // Ajouter la réponse du bot
                        addBotMessage(data);

                        // Gérer les actions spécifiques
                        handleBotAction(data);

                        // Ajouter à l'historique
                        conversationHistory.push({
                            user: message,
                            bot: data.message,
                            type: data.type
                        });

                    } catch (error) {
                        console.error('Erreur:', error);
                        typingIndicator.classList.add('hidden');
                        addBotMessage({
                            message: 'Désolé, une erreur s\'est produite. Veuillez réessayer.',
                            type: 'error'
                        });
                    } finally {
                        sendButton.disabled = false;
                        messageInput.focus();
                    }
                });

                // Ajouter un message utilisateur
                function addUserMessage(message) {
                    const messageDiv = document.createElement('div');
                    messageDiv.className = 'flex items-start justify-end space-x-3 animate-fadeIn';
                    messageDiv.innerHTML = `
                    <div class="bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-lg p-4 max-w-md">
                        <p>${escapeHtml(message)}</p>
                    </div>
                    <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                `;
                    chatContainer.appendChild(messageDiv);
                    scrollToBottom();
                }

                // Ajouter un message du bot
                function addBotMessage(data) {
                    const messageDiv = document.createElement('div');
                    messageDiv.className = 'flex items-start space-x-3 animate-fadeIn';

                    let content = `<p>${data.message.replace(/\n/g, '<br>')}</p>`;

                    // Ajouter du contenu spécifique selon le type
                    if (data.type === 'event_list' && data.data) {
                        content += '<div class="mt-3 space-y-2">';
                        data.data.forEach(event => {
                            content += `
                            <div class="border border-gray-200 rounded p-3 hover:bg-gray-50 cursor-pointer" onclick="selectEvent(${event.id})">
                                <p class="font-medium text-gray-800">${event.title}</p>
                                <p class="text-sm text-gray-600">${event.date} - ${event.venue}</p>
                                <p class="text-sm text-blue-600 font-medium">À partir de ${event.price} €</p>
                            </div>
                        `;
                        });
                        content += '</div>';
                    }

                    if (data.type === 'package_list' && data.data) {
                        content += '<div class="mt-3 space-y-2">';
                        data.data.forEach(pkg => {
                            content += `
                            <div class="border border-gray-200 rounded p-3 hover:bg-gray-50 cursor-pointer">
                                <p class="font-medium text-gray-800">${pkg.title}</p>
                                <p class="text-sm text-gray-600">${pkg.destination} - ${pkg.duration}</p>
                                <p class="text-sm text-blue-600 font-medium">${pkg.price} €</p>
                            </div>
                        `;
                        });
                        content += '</div>';
                    }

                    messageDiv.innerHTML = `
                    <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div class="bg-blue-50 rounded-lg p-4 max-w-md">
                        ${content}
                    </div>
                `;
                    chatContainer.appendChild(messageDiv);
                    scrollToBottom();
                }

                // Gérer les actions du bot
                function handleBotAction(data) {
                    if (data.action === 'redirect_to_search' && data.data && data.data.search_url) {
                        setTimeout(() => {
                            window.location.href = data.data.search_url;
                        }, 2000);
                    }

                    if (data.action === 'connect_to_agent' && data.data) {
                        // Afficher les options de contact
                        console.log('Contact support:', data.data);
                    }
                }

                // Faire défiler vers le bas
                function scrollToBottom() {
                    chatContainer.scrollTop = chatContainer.scrollHeight;
                }

                // Échapper le HTML
                function escapeHtml(text) {
                    const div = document.createElement('div');
                    div.textContent = text;
                    return div.innerHTML;
                }

                // Fonction globale pour sélectionner un événement
                window.selectEvent = function (eventId) {
                    messageInput.value = `Je veux réserver l'événement ${eventId}`;
                    chatForm.dispatchEvent(new Event('submit'));
                };
            });
        </script>
    @endpush
@endsection