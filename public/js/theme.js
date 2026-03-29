/**
 * ============================================
 * SYSTÈME DE GESTION DU THÈME CLAIR/SOMBRE
 * ============================================
 * 
 * Fonctionnalités:
 * - Mode clair par défaut
 * - Basculement entre clair et sombre
 * - Sauvegarde dans localStorage
 * - Détection du thème système
 * - Fonctionne sur toutes les pages
 * - Support desktop et mobile
 */

(function() {
    'use strict';

    // Éléments DOM
    const toggle = document.getElementById('theme-toggle');
    const html = document.documentElement;

    // Icônes pour le bouton
    const ICONS = {
        light: '🌙',
        dark: '☀️'
    };

    // Textes pour le bouton
    const TEXTS = {
        light: 'Mode sombre',
        dark: 'Mode clair'
    };

    /**
     * Obtenir le thème actuel
     */
    function getCurrentTheme() {
        return localStorage.getItem('theme') || 'light';
    }

    /**
     * Définir le thème
     */
    function setTheme(theme) {
        if (theme === 'dark') {
            html.setAttribute('data-theme', 'dark');
            html.classList.add('dark');
            localStorage.setItem('theme', 'dark');
            updateToggleButton('dark');
        } else {
            html.setAttribute('data-theme', 'light');
            html.classList.remove('dark');
            localStorage.setItem('theme', 'light');
            updateToggleButton('light');
        }
    }

    /**
     * Mettre à jour les boutons de toggle - ICÔNE SEULEMENT
     */
    function updateToggleButton(theme) {
        const icon = ICONS[theme];
        const text = TEXTS[theme];
        
        // Mettre à jour le bouton desktop
        if (toggle) {
            toggle.innerHTML = icon;
            toggle.setAttribute('aria-label', text);
            toggle.setAttribute('title', text);
        }

        // Mettre à jour le bouton mobile
        const toggleMobile = document.getElementById('theme-toggle-mobile');
        if (toggleMobile) {
            const iconSpan = toggleMobile.querySelector('span:last-child');
            if (iconSpan) {
                iconSpan.textContent = icon;
            }
        }
    }

    /**
     * Basculer entre les thèmes
     */
    function toggleTheme() {
        const currentTheme = getCurrentTheme();
        const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
        setTheme(newTheme);

        // Animation de feedback sur le bouton desktop
        if (toggle) {
            toggle.style.transform = 'scale(0.95)';
            setTimeout(() => {
                toggle.style.transform = 'scale(1)';
            }, 100);
        }
    }

    /**
     * Détecter la préférence système
     */
    function detectSystemTheme() {
        if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
            return 'dark';
        }
        return 'light';
    }

    /**
     * Initialiser le thème au chargement
     */
    function initTheme() {
        let theme = getCurrentTheme();

        // Si aucun thème n'est sauvegardé, utiliser la préférence système
        if (!localStorage.getItem('theme')) {
            theme = detectSystemTheme();
            localStorage.setItem('theme', theme);
        }

        setTheme(theme);
    }

    /**
     * Écouter les changements de préférence système
     */
    function watchSystemTheme() {
        if (window.matchMedia) {
            const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
            
            mediaQuery.addEventListener('change', (e) => {
                // Ne changer que si l'utilisateur n'a pas de préférence sauvegardée
                if (!localStorage.getItem('theme')) {
                    const newTheme = e.matches ? 'dark' : 'light';
                    setTheme(newTheme);
                }
            });
        }
    }

    /**
     * Initialisation au chargement du DOM
     */
    function init() {
        // Initialiser le thème immédiatement
        initTheme();

        // Attacher l'événement au bouton desktop
        if (toggle) {
            toggle.addEventListener('click', toggleTheme);
        }

        // Attacher l'événement au bouton mobile
        const toggleMobile = document.getElementById('theme-toggle-mobile');
        if (toggleMobile) {
            toggleMobile.addEventListener('click', toggleTheme);
        }

        // Surveiller les changements système
        watchSystemTheme();

        // Exposer la fonction toggle globalement (optionnel)
        window.toggleTheme = toggleTheme;
    }

    // Lancer l'initialisation
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

})();
