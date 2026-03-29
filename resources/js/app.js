import './bootstrap';

const initHeader = () => {
    const root = document.querySelector('[data-header-root]');

    if (!root) {
        return;
    }

    const header = root.querySelector('header');
    const frame = root.querySelector('[data-header-frame]');
    const overlay = root.querySelector('[data-header-overlay]');
    const panels = Array.from(root.querySelectorAll('[data-header-panel]'));
    const toggles = Array.from(root.querySelectorAll('[data-header-toggle]'));
    const closeTriggers = Array.from(root.querySelectorAll('[data-header-close]'));
    const menuToggle = root.querySelector('[data-header-toggle="mobile-main-menu"]');
    const openIcon = menuToggle?.querySelector('[data-menu-icon="open"]');
    const closeIcon = menuToggle?.querySelector('[data-menu-icon="close"]');

    let openPanelId = null;

    const setHeaderHeight = () => {
        if (!header) {
            return;
        }

        document.documentElement.style.setProperty('--cp-header-height', `${header.offsetHeight}px`);
    };

    const syncScrolledState = () => {
        frame?.classList.toggle('is-scrolled', window.scrollY > 16);
    };

    const updateMenuButton = () => {
        const isOpen = openPanelId === 'mobile-main-menu';

        if (menuToggle) {
            menuToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        }

        openIcon?.classList.toggle('hidden', isOpen);
        closeIcon?.classList.toggle('hidden', !isOpen);
    };

    const updateBodyLock = () => {
        document.body.classList.toggle('overflow-hidden', openPanelId === 'mobile-main-menu');
    };

    const hideAllPanels = () => {
        panels.forEach((panel) => panel.classList.add('hidden'));
        toggles.forEach((button) => button.setAttribute('aria-expanded', 'false'));
        overlay?.classList.add('hidden');
        openPanelId = null;
        updateMenuButton();
        updateBodyLock();
    };

    const openPanel = (panelId) => {
        const targetPanel = root.querySelector(`#${panelId}`);

        if (!targetPanel) {
            return;
        }

        panels.forEach((panel) => {
            panel.classList.toggle('hidden', panel !== targetPanel);
        });

        toggles.forEach((button) => {
            const isTarget = button.getAttribute('data-header-toggle') === panelId;
            button.setAttribute('aria-expanded', isTarget ? 'true' : 'false');
        });

        overlay?.classList.toggle('hidden', panelId !== 'mobile-main-menu');
        openPanelId = panelId;
        updateMenuButton();
        updateBodyLock();
    };

    toggles.forEach((button) => {
        button.addEventListener('click', () => {
            const panelId = button.getAttribute('data-header-toggle');

            if (!panelId) {
                return;
            }

            if (openPanelId === panelId) {
                hideAllPanels();
                return;
            }

            openPanel(panelId);
        });
    });

    closeTriggers.forEach((trigger) => {
        trigger.addEventListener('click', () => {
            hideAllPanels();
        });
    });

    overlay?.addEventListener('click', () => {
        hideAllPanels();
    });

    document.addEventListener('click', (event) => {
        if (!openPanelId) {
            return;
        }

        const currentPanel = root.querySelector(`#${openPanelId}`);
        const currentToggle = root.querySelector(`[data-header-toggle="${openPanelId}"]`);

        if (currentPanel?.contains(event.target) || currentToggle?.contains(event.target)) {
            return;
        }

        hideAllPanels();
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && openPanelId) {
            hideAllPanels();
        }
    });

    window.addEventListener('scroll', syncScrolledState, { passive: true });
    window.addEventListener('resize', () => {
        setHeaderHeight();

        if (window.innerWidth >= 1024 && openPanelId === 'mobile-main-menu') {
            hideAllPanels();
        }
    });
    window.addEventListener('load', setHeaderHeight);

    syncScrolledState();
    setHeaderHeight();
    hideAllPanels();
};

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initHeader);
} else {
    initHeader();
}
