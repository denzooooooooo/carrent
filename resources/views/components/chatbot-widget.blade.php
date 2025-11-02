<!-- Widget Chatbot Flottant -->
<div id="chatbotWidget" class="fixed bottom-6 right-6 z-50 hidden">
    <!-- Bouton d'ouverture -->
    <button id="chatbotToggle"
        class="w-16 h-16 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-full shadow-lg flex items-center justify-center text-white hover:shadow-xl transform hover:scale-110 transition-all duration-200 focus:outline-none focus:ring-4 focus:ring-blue-300"
        aria-label="Ouvrir le chat">
        <svg id="chatIcon" class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z">
            </path>
        </svg>
        <svg id="closeIcon" class="w-8 h-8 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
    </button>

    <!-- Badge de notification -->
    <span id="chatNotificationBadge"
        class="hidden absolute -top-1 -right-1 w-6 h-6 bg-red-500 rounded-full flex items-center justify-center text-xs text-white font-bold">
        1
    </span>

    <!-- Fenêtre de chat -->
    <div id="chatbotWindow"
        class="hidden absolute bottom-20 right-0 w-96 bg-white rounded-2xl shadow-2xl overflow-hidden"
        style="height: 600px;">
        <!-- En-tête du chat -->
        <div class="bg-gradient-to-r from-blue-500 to-indigo-600 text-white p-4 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                        </path>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-lg">Assistant</h3>
                    <p class="text-xs text-white/80">En ligne</p>
                </div>
            </div>
            <button id="minimizeChat" class="text-white hover:bg-white/20 p-2 rounded-lg transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>
        </div>

        <!-- Zone des messages -->
        <div id="widgetChatMessages" class="flex-1 overflow-y-auto p-4 space-y-4 bg-gray-50"
            style="height: calc(100% - 160px);">
            <!-- Message de bienvenue -->
            <div class="flex items-start space-x-2">
                <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                        </path>
                    </svg>
                </div>
                <div class="bg-white rounded-lg p-3 shadow-sm max-w-xs">
                    <p class="text-sm text-gray-800">Bonjour ! 👋 Comment puis-je vous aider ?</p>
                </div>
            </div>
        </div>

        <!-- Zone de saisie -->
        <div class="border-t border-gray-200 p-4 bg-white">
            <form id="widgetChatForm" class="flex space-x-2">
                <input type="text" id="widgetMessageInput" placeholder="Écrivez votre message..."
                    class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    autocomplete="off">
                <button type="submit"
                    class="px-4 py-2 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-lg hover:from-blue-600 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                    </svg>
                </button>
            </form>

            <!-- Suggestions -->
            <div class="mt-2 flex flex-wrap gap-1">
                <button
                    class="widget-suggestion text-xs px-3 py-1 bg-gray-100 hover:bg-gray-200 rounded-full text-gray-700"
                    data-message="Je veux réserver un vol">
                    ✈️ Vol
                </button>
                <button
                    class="widget-suggestion text-xs px-3 py-1 bg-gray-100 hover:bg-gray-200 rounded-full text-gray-700"
                    data-message="Je cherche un événement">
                    🎫 Événement
                </button>
                <button
                    class="widget-suggestion text-xs px-3 py-1 bg-gray-100 hover:bg-gray-200 rounded-full text-gray-700"
                    data-message="Parler à un conseiller">
                    💬 Aide
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    #widgetChatMessages::-webkit-scrollbar {
        width: 6px;
    }

    #widgetChatMessages::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    #widgetChatMessages::-webkit-scrollbar-thumb {
        background: #cbd5e0;
        border-radius: 3px;
    }

    #widgetChatMessages::-webkit-scrollbar-thumb:hover {
        background: #a0aec0;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .message-animate {
        animation: slideIn 0.3s ease-out;
    }
</style>

<script>
    // Attendre que le DOM soit complètement chargé
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initChatbot);
    } else {
        initChatbot();
    }

    function initChatbot() {
        console.log('Initialisation du chatbot...');

        const chatbotToggle = document.getElementById('chatbotToggle');
        const chatbotWindow = document.getElementById('chatbotWindow');
        const minimizeChat = document.getElementById('minimizeChat');
        const chatIcon = document.getElementById('chatIcon');
        const closeIcon = document.getElementById('closeIcon');
        const widgetChatForm = document.getElementById('widgetChatForm');
        const widgetMessageInput = document.getElementById('widgetMessageInput');
        const widgetChatMessages = document.getElementById('widgetChatMessages');
        const widgetSuggestions = document.querySelectorAll('.widget-suggestion');

        // Vérifier que tous les éléments existent
        if (!chatbotToggle || !chatbotWindow) {
            console.error('Éléments du chatbot non trouvés');
            return;
        }

        console.log('Chatbot initialisé avec succès');

        let isOpen = false;
        let widgetConversationHistory = [];

        // Toggle du chatbot
        chatbotToggle.addEventListener('click', function () {
            console.log('Toggle chatbot cliqué');
            isOpen = !isOpen;
            if (isOpen) {
                chatbotWindow.classList.remove('hidden');
                chatIcon.classList.add('hidden');
                closeIcon.classList.remove('hidden');
                widgetMessageInput.focus();
                console.log('Chatbot ouvert');
            } else {
                chatbotWindow.classList.add('hidden');
                chatIcon.classList.remove('hidden');
                closeIcon.classList.add('hidden');
                console.log('Chatbot fermé');
            }
        });

        minimizeChat.addEventListener('click', function () {
            console.log('Minimiser chatbot cliqué');
            chatbotWindow.classList.add('hidden');
            chatIcon.classList.remove('hidden');
            closeIcon.classList.add('hidden');
            isOpen = false;
        });

        // Suggestions
        widgetSuggestions.forEach(btn => {
            btn.addEventListener('click', function () {
                const message = this.getAttribute('data-message');
                console.log('Suggestion cliquée:', message);
                widgetMessageInput.value = message;
                widgetChatForm.dispatchEvent(new Event('submit'));
            });
        });

        // Soumission du formulaire
        widgetChatForm.addEventListener('submit', async function (e) {
            e.preventDefault();
            console.log('Formulaire soumis');

            const message = widgetMessageInput.value.trim();
            if (!message) {
                console.log('Message vide, ignoré');
                return;
            }

            console.log('Message envoyé:', message);

            // Ajouter le message de l'utilisateur
            addWidgetUserMessage(message);
            widgetMessageInput.value = '';

            try {
                const url = '{{ route("chatbot.process") }}';
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

                console.log('URL:', url);
                console.log('CSRF Token:', csrfToken);

                if (!csrfToken) {
                    console.error('CSRF Token non trouvé');
                    throw new Error('CSRF Token manquant');
                }

                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        message: message,
                        conversation_history: widgetConversationHistory
                    })
                });

                console.log('Response status:', response.status);

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const data = await response.json();
                console.log('Réponse reçue:', data);

                // Ajouter la réponse du bot
                addWidgetBotMessage(data);

                // Ajouter à l'historique
                widgetConversationHistory.push({
                    user: message,
                    bot: data.message,
                    type: data.type
                });

                // Gérer les actions
                if (data.action === 'redirect_to_search' && data.data?.search_url) {
                    setTimeout(() => {
                        window.location.href = data.data.search_url;
                    }, 2000);
                }

            } catch (error) {
                console.error('Erreur complète:', error);
                addWidgetBotMessage({
                    message: 'Désolé, une erreur s\'est produite. Veuillez vérifier votre connexion et réessayer.',
                    type: 'error'
                });
            }
        });

        function addWidgetUserMessage(message) {
            console.log('Ajout message utilisateur:', message);
            const messageDiv = document.createElement('div');
            messageDiv.className = 'flex items-start justify-end space-x-2 message-animate';
            messageDiv.innerHTML = `
                <div class="bg-blue-500 text-white rounded-lg p-3 shadow-sm max-w-xs">
                    <p class="text-sm">${escapeHtml(message)}</p>
                </div>
                <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
            `;
            widgetChatMessages.appendChild(messageDiv);
            widgetChatMessages.scrollTop = widgetChatMessages.scrollHeight;
        }

        function addWidgetBotMessage(data) {
            console.log('Ajout message bot:', data);
            const messageDiv = document.createElement('div');
            messageDiv.className = 'flex items-start space-x-2 message-animate';
            messageDiv.innerHTML = `
                <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div class="bg-white rounded-lg p-3 shadow-sm max-w-xs">
                    <p class="text-sm text-gray-800">${data.message.replace(/\n/g, '<br>')}</p>
                </div>
            `;
            widgetChatMessages.appendChild(messageDiv);
            widgetChatMessages.scrollTop = widgetChatMessages.scrollHeight;
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
    }
</script>