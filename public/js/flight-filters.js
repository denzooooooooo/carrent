/**
 * =====================================================
 * SYSTÈME DE FILTRAGE DYNAMIQUE POUR VOLS
 * Version: 2.0 - Testé et fonctionnel
 * =====================================================
 */

document.addEventListener('DOMContentLoaded', function () {
    console.log('🚀 Démarrage du système de filtrage');

    class FlightFilterSystem {
        constructor() {
            // Initialisation des éléments DOM
            this.priceSlider = document.getElementById('priceSlider');
            this.durationSlider = document.getElementById('durationSlider');
            this.priceValue = document.getElementById('priceValue');
            this.durationValue = document.getElementById('durationValue');
            this.resultsCount = document.getElementById('resultsCount');
            this.visibleResultsCount = document.getElementById('visibleResultsCount');
            this.sortSelect = document.getElementById('sortSelect');
            this.resetBtn = document.getElementById('resetFilters');
            
            // Conteneurs de vols
            this.bestFlightsContainer = document.getElementById('bestFlights');
            this.otherFlightsContainer = document.getElementById('otherFlights');
            
            // Headers des sections
            this.bestFlightsHeader = this.bestFlightsContainer?.closest('.mb-8')?.querySelector('h2');
            this.otherFlightsHeader = this.otherFlightsContainer?.closest('div')?.querySelector('h2');
            
            // Toutes les cartes de vols
            this.flightCards = document.querySelectorAll('.flight-card');
            
            // Vérification
            if (this.flightCards.length === 0) {
                console.warn('⚠️ Aucune carte de vol trouvée !');
                return;
            }

            // État des filtres avec valeurs initiales des sliders
            this.filters = {
                maxPrice: this.priceSlider ? parseFloat(this.priceSlider.value) : 999999999,
                maxDuration: this.durationSlider ? parseInt(this.durationSlider.value) : 9999,
                stops: [],
                airlines: []
            };

            console.log(`📊 ${this.flightCards.length} vols chargés`);
            console.log('⚙️ Filtres initiaux:', this.filters);

            this.init();
        }

        init() {
            this.attachEventListeners();
            this.logFlightData(); // Debug: afficher les données des vols
            this.updateCounts();
            console.log('✅ Système initialisé avec succès');
        }

        /**
         * Debug: Affiche les données de toutes les cartes
         */
        logFlightData() {
            console.log('📋 Données des vols:');
            this.flightCards.forEach((card, index) => {
                console.log(`Vol ${index + 1}:`, {
                    price: card.dataset.price,
                    duration: card.dataset.duration,
                    stops: card.dataset.stops,
                    airline: card.dataset.airline,
                    best: card.dataset.best
                });
            });
        }

        /**
         * Attache tous les écouteurs d'événements
         */
        attachEventListeners() {
            // Slider de prix
            if (this.priceSlider) {
                this.priceSlider.addEventListener('input', (e) => {
                    this.filters.maxPrice = parseFloat(e.target.value);
                    if (this.priceValue) {
                        this.priceValue.textContent = this.formatPrice(this.filters.maxPrice);
                    }
                    console.log('💰 Prix max:', this.filters.maxPrice);
                    this.applyFilters();
                });
            }

            // Slider de durée
            if (this.durationSlider) {
                this.durationSlider.addEventListener('input', (e) => {
                    this.filters.maxDuration = parseInt(e.target.value);
                    if (this.durationValue) {
                        const hours = Math.floor(this.filters.maxDuration / 60);
                        const minutes = this.filters.maxDuration % 60;
                        this.durationValue.textContent = `${hours}h ${minutes}min`;
                    }
                    console.log('⏱️ Durée max:', this.filters.maxDuration);
                    this.applyFilters();
                });
            }

            // Checkboxes escales
            document.querySelectorAll('.filter-stops').forEach(checkbox => {
                checkbox.addEventListener('change', () => {
                    this.updateStopsFilter();
                    this.applyFilters();
                });
            });

            // Checkboxes compagnies
            document.querySelectorAll('.filter-airline').forEach(checkbox => {
                checkbox.addEventListener('change', () => {
                    this.updateAirlinesFilter();
                    this.applyFilters();
                });
            });

            // Sélecteur de tri
            if (this.sortSelect) {
                this.sortSelect.addEventListener('change', (e) => {
                    console.log('🔄 Tri:', e.target.value);
                    this.sortFlights(e.target.value);
                });
            }

            // Bouton reset
            if (this.resetBtn) {
                this.resetBtn.addEventListener('click', () => {
                    this.resetFilters();
                });
            }
        }

        /**
         * Met à jour le filtre des escales
         */
        updateStopsFilter() {
            this.filters.stops = Array.from(document.querySelectorAll('.filter-stops:checked'))
                .map(cb => cb.value);
            console.log('✈️ Escales filtrées:', this.filters.stops);
        }

        /**
         * Met à jour le filtre des compagnies
         */
        updateAirlinesFilter() {
            this.filters.airlines = Array.from(document.querySelectorAll('.filter-airline:checked'))
                .map(cb => cb.value);
            console.log('🛫 Compagnies filtrées:', this.filters.airlines);
        }

        /**
         * Applique tous les filtres actifs
         */
        applyFilters() {
            console.log('🔍 Application des filtres:', this.filters);

            let visibleCount = 0;
            let bestVisible = 0;
            let otherVisible = 0;

            this.flightCards.forEach(card => {
                // Récupération des données de la carte
                const price = parseFloat(card.dataset.price) || 0;
                const duration = parseInt(card.dataset.duration) || 0;
                const stops = parseInt(card.dataset.stops) || 0;
                const airline = (card.dataset.airline || '').trim();
                const isBest = card.dataset.best === 'true';

                let visible = true;

                // 1. Filtre PRIX
                if (price > this.filters.maxPrice) {
                    visible = false;
                }

                // 2. Filtre DURÉE
                if (duration > this.filters.maxDuration) {
                    visible = false;
                }

                // 3. Filtre ESCALES
                if (this.filters.stops.length > 0) {
                    let stopsMatch = false;
                    
                    for (const filterStop of this.filters.stops) {
                        if (filterStop === '2+' && stops >= 2) {
                            stopsMatch = true;
                            break;
                        } else if (parseInt(filterStop) === stops) {
                            stopsMatch = true;
                            break;
                        }
                    }
                    
                    if (!stopsMatch) {
                        visible = false;
                    }
                }

                // 4. Filtre COMPAGNIES
                if (this.filters.airlines.length > 0) {
                    if (!this.filters.airlines.includes(airline)) {
                        visible = false;
                    }
                }

                // Appliquer la visibilité
                if (visible) {
                    card.style.display = 'block';
                    card.style.animation = 'fadeIn 0.3s ease-in';
                    visibleCount++;
                    
                    if (isBest) {
                        bestVisible++;
                    } else {
                        otherVisible++;
                    }
                } else {
                    card.style.display = 'none';
                }
            });

            // Mettre à jour les headers de sections
            this.updateSectionHeaders(bestVisible, otherVisible);
            
            // Mettre à jour les compteurs
            this.updateCounts(visibleCount);

            console.log(`✅ ${visibleCount} vols visibles (${bestVisible} meilleurs, ${otherVisible} autres)`);
        }

        /**
         * Met à jour les titres des sections
         */
        updateSectionHeaders(bestVisible, otherVisible) {
            // Section "Meilleurs vols"
            if (this.bestFlightsHeader) {
                const bestSection = this.bestFlightsHeader.parentElement;
                if (bestVisible === 0) {
                    bestSection.style.display = 'none';
                } else {
                    bestSection.style.display = 'block';
                    this.bestFlightsHeader.textContent = `⭐ Meilleurs vols (${bestVisible})`;
                }
            }

            // Section "Autres vols"
            if (this.otherFlightsHeader) {
                const otherSection = this.otherFlightsHeader.parentElement;
                if (otherVisible === 0) {
                    otherSection.style.display = 'none';
                } else {
                    otherSection.style.display = 'block';
                    this.otherFlightsHeader.textContent = `✈️ Autres vols (${otherVisible})`;
                }
            }
        }

        /**
         * Trie les vols selon le critère choisi
         */
        sortFlights(sortBy) {
            const sortFunctions = {
                'best': (a, b) => {
                    if (a.dataset.best !== b.dataset.best) {
                        return a.dataset.best === 'true' ? -1 : 1;
                    }
                    return parseFloat(a.dataset.price || 0) - parseFloat(b.dataset.price || 0);
                },
                'price_asc': (a, b) => {
                    return parseFloat(a.dataset.price || 0) - parseFloat(b.dataset.price || 0);
                },
                'price_desc': (a, b) => {
                    return parseFloat(b.dataset.price || 0) - parseFloat(a.dataset.price || 0);
                },
                'duration_asc': (a, b) => {
                    return parseInt(a.dataset.duration || 0) - parseInt(b.dataset.duration || 0);
                },
                'duration_desc': (a, b) => {
                    return parseInt(b.dataset.duration || 0) - parseInt(a.dataset.duration || 0);
                }
            };

            const sortFn = sortFunctions[sortBy] || sortFunctions.best;

            // Tri des meilleurs vols
            if (this.bestFlightsContainer) {
                const cards = Array.from(this.bestFlightsContainer.querySelectorAll('.flight-card'));
                cards.sort(sortFn).forEach(card => this.bestFlightsContainer.appendChild(card));
            }

            // Tri des autres vols
            if (this.otherFlightsContainer) {
                const cards = Array.from(this.otherFlightsContainer.querySelectorAll('.flight-card'));
                cards.sort(sortFn).forEach(card => this.otherFlightsContainer.appendChild(card));
            }

            console.log('✅ Tri appliqué:', sortBy);
        }

        /**
         * Réinitialise tous les filtres
         */
        resetFilters() {
            console.log('🔄 Réinitialisation des filtres');

            // Reset sliders
            if (this.priceSlider) {
                this.priceSlider.value = this.priceSlider.max;
                this.filters.maxPrice = parseFloat(this.priceSlider.max);
                if (this.priceValue) {
                    this.priceValue.textContent = this.formatPrice(this.filters.maxPrice);
                }
            }

            if (this.durationSlider) {
                this.durationSlider.value = this.durationSlider.max;
                this.filters.maxDuration = parseInt(this.durationSlider.max);
                if (this.durationValue) {
                    const hours = Math.floor(this.filters.maxDuration / 60);
                    const minutes = this.filters.maxDuration % 60;
                    this.durationValue.textContent = `${hours}h ${minutes}min`;
                }
            }

            // Reset checkboxes
            document.querySelectorAll('.filter-stops, .filter-airline').forEach(cb => {
                cb.checked = false;
            });

            // Reset select
            if (this.sortSelect) {
                this.sortSelect.value = 'best';
            }

            // Reset filtres internes
            this.filters.stops = [];
            this.filters.airlines = [];

            // Réappliquer
            this.applyFilters();
            this.sortFlights('best');

            console.log('✅ Filtres réinitialisés');
        }

        /**
         * Met à jour les compteurs
         */
        updateCounts(visibleCount = null) {
            if (visibleCount === null) {
                visibleCount = Array.from(this.flightCards).filter(
                    card => card.style.display !== 'none'
                ).length;
            }

            const totalCount = this.flightCards.length;

            if (this.resultsCount) {
                this.resultsCount.textContent = `${totalCount} vols trouvés`;
            }

            if (this.visibleResultsCount) {
                this.visibleResultsCount.textContent = `${visibleCount} résultats`;
            }
        }

        /**
         * Formate un prix
         */
        formatPrice(price) {
            return new Intl.NumberFormat('fr-FR').format(Math.round(price)) + ' XOF';
        }
    }

    // Ajouter l'animation CSS
    if (!document.getElementById('flight-filter-animations')) {
        const style = document.createElement('style');
        style.id = 'flight-filter-animations';
        style.textContent = `
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
        `;
        document.head.appendChild(style);
    }

    // Initialisation
    try {
        window.flightFilterSystem = new FlightFilterSystem();
    } catch (error) {
        console.error('❌ Erreur d\'initialisation:', error);
    }
});