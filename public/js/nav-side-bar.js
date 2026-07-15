/**
 * Sidebar — Modern Admin Sidebar
 * Vanilla JS — no dependencies
 *
 * Features:
 * - Collapse / expand toggle
 * - Accordion submenu (one open at a time) — for classic sidebars
 * - Flyout submenu (panel to the right / bottom sheet on mobile) — for .sidebar--flyout
 * - Auto-highlight active parent on page load
 * - Mobile overlay
 * - Backward compatible with navbar .js-hamburger
 */

(function () {
    'use strict';

    const SIDEBAR_SEL = '#sidebar';
    const TOGGLER_SEL = '#sidebarToggler';
    const TOGGLE_CLASS = 'is-collapsed';
    const MOBILE_OPEN = 'is-mobile-open';
    const OVERLAY_CLASS = 'sidebar-overlay';
    const BODY_COLLAPSED_CLASS = 'sidebar-collapsed';
    const FLYOUT_OPEN_CLASS = 'is-flyout-open';
    const FLYOUT_BACKDROP_CLASS = 'flyout-backdrop';

    let sidebar = document.querySelector(SIDEBAR_SEL);
    const hasSidebar = !!sidebar;

    let isFlyout = false;
    if (sidebar) {
        isFlyout = sidebar.classList.contains('sidebar--flyout');
    }


    /* ============================================================
       STATE
       ============================================================ */

    let isCollapsed = false;
    let isMobile = window.innerWidth <= 768;
    let overlay = null;
    let flyoutBackdrop = null;
    let openFlyoutItem = null;   // currently open .menu-item (flyout mode)


    /* ============================================================
       HELPERS
       ============================================================ */

    function getOverlayParent() {
        return document.querySelector('.app-layout') || document.body;
    }

    function createOverlay() {
        overlay = document.createElement('div');
        overlay.className = OVERLAY_CLASS;
        getOverlayParent().appendChild(overlay);
        overlay.addEventListener('click', closeMobile);
    }

    function updateBodyClasses() {
        if (!hasSidebar) return;
        document.body.classList.toggle(BODY_COLLAPSED_CLASS, isCollapsed && !isMobile);
    }


    /* ============================================================
       TOGGLE — Desktop collapse / expand
       ============================================================ */

    function toggleSidebar() {
        if (!hasSidebar || isMobile) return;
        isCollapsed = !isCollapsed;
        sidebar.classList.toggle(TOGGLE_CLASS, isCollapsed);
        updateBodyClasses();
        closeAllFlyouts();
        try {
            localStorage.setItem('sidebar-collapsed', isCollapsed ? '1' : '0');
        } catch (e) { /* ignore */ }
    }

    function setSidebarState(collapsed) {
        if (!hasSidebar || isMobile) return;
        isCollapsed = collapsed;
        sidebar.classList.toggle(TOGGLE_CLASS, collapsed);
        updateBodyClasses();
    }


    /* ============================================================
       MOBILE — Open / close
       ============================================================ */

    function openMobile() {
        if (!hasSidebar) return;
        isMobile = true;
        sidebar.classList.add(MOBILE_OPEN);
        if (overlay) overlay.classList.add('is-visible');
        document.body.style.overflow = 'hidden';
    }

    function closeMobile() {
        if (!hasSidebar) return;
        isMobile = true;
        sidebar.classList.remove(MOBILE_OPEN);
        if (overlay) overlay.classList.remove('is-visible');
        document.body.style.overflow = '';
        closeAllFlyouts();
    }

    function toggleMobile() {
        if (!hasSidebar) return;
        if (sidebar.classList.contains(MOBILE_OPEN)) {
            closeMobile();
        } else {
            openMobile();
        }
    }


    /* ============================================================
       ACCORDION SUBMENU — classic sidebars (non-flyout)
       ============================================================ */

    function initSubmenus() {
        if (!hasSidebar || isFlyout) return; // flyout mode handles its own navigation

        const toggles = sidebar.querySelectorAll('.menu-toggle');
        toggles.forEach(function (toggle) {
            toggle.addEventListener('click', function (e) {
                e.preventDefault();

                if (sidebar.classList.contains(TOGGLE_CLASS)) {
                    if (isCollapsed) {
                        toggleSidebar();
                        setTimeout(function () {
                            toggleSubmenu(this);
                        }.bind(this), 300);
                        return;
                    }
                }
                toggleSubmenu(this);
            });
        });
    }

    function toggleSubmenu(toggleEl) {
        const parentItem = toggleEl.closest('.menu-item.has-submenu');
        if (!parentItem) return;

        const isOpen = parentItem.classList.contains('open');

        if (hasSidebar) {
            sidebar.querySelectorAll('.menu-item.has-submenu.open').forEach(function (el) {
                if (el !== parentItem) el.classList.remove('open');
            });
        }

        parentItem.classList.toggle('open', !isOpen);
    }


    /* ============================================================
       AUTO-OPEN — Expand active submenu on page load
       ============================================================ */

    function autoOpenActive() {
        if (!hasSidebar || isFlyout) return; // Blade already emits .has-active; flyout stays closed

        const currentPath = window.location.pathname;
        sidebar.querySelectorAll('.menu-submenu-link').forEach(function (link) {
            const href = link.getAttribute('href');
            if (!href || href === '#') return;
            if (currentPath.indexOf(href) !== -1 || currentPath.startsWith(href)) {
                const parentItem = link.closest('.menu-item.has-submenu');
                if (parentItem) parentItem.classList.add('open');
            }
        });
    }


    /* ============================================================
       FLYOUT SUBMENU — Enterprise flyout navigation
       Single open at a time, click-outside / ESC to close,
       slide + fade animation, bottom-sheet on mobile.
       ============================================================ */

    function ensureBackdrop() {
        if (flyoutBackdrop) return;
        flyoutBackdrop = document.createElement('div');
        flyoutBackdrop.className = FLYOUT_BACKDROP_CLASS;
        getOverlayParent().appendChild(flyoutBackdrop);
        flyoutBackdrop.addEventListener('click', closeAllFlyouts);
    }

    function positionFlyout(panel, triggerEl) {
        if (isMobile || !hasSidebar) return; // CSS handles bottom-sheet positioning
        if (triggerEl.closest('.menu-submenu-item.has-submenu')) return; // nested — CSS handles it

        const sbRect = sidebar.getBoundingClientRect();
        const trRect = triggerEl.getBoundingClientRect();
        const gap = 12;
        const panelHeight = panel.offsetHeight;
        const vh = window.innerHeight;

        let top = trRect.top;
        const maxTop = vh - panelHeight - 12;
        if (top > maxTop) top = Math.max(12, maxTop);
        if (top < 12) top = 12;

        panel.style.left = (sbRect.right + gap) + 'px';
        panel.style.top = top + 'px';
    }

    function openFlyout(parentItem) {
        if (!parentItem) return;
        const panel = parentItem.querySelector('.menu-submenu');
        if (!panel) return;

        // Close unrelated flyouts only (nested flyouts stay open)
        if (openFlyoutItem && openFlyoutItem !== parentItem && !openFlyoutItem.contains(parentItem)) {
            closeFlyout();
        }

        ensureBackdrop();
        positionFlyout(panel, parentItem.querySelector('.menu-toggle'));

        parentItem.classList.add(FLYOUT_OPEN_CLASS);
        const toggle = parentItem.querySelector('[data-flyout-toggle]');
        if (toggle) toggle.setAttribute('aria-expanded', 'true');

        if (isMobile && flyoutBackdrop && !sidebar.classList.contains(MOBILE_OPEN)) {
            flyoutBackdrop.classList.add('is-visible');
            document.body.style.overflow = 'hidden';
        }

        openFlyoutItem = parentItem;
    }

    function closeFlyout(targetItem) {
        // Close a specific flyout, or the last opened one (and its chain)
        var item = targetItem || openFlyoutItem;
        if (!item) return;

        // Close the item and all its descendant flyouts
        var itemsToClose = [item];
        // Find all descendant flyouts
        item.querySelectorAll('.menu-item.is-flyout-open, .menu-submenu-item.is-flyout-open').forEach(function (child) {
            itemsToClose.push(child);
        });

        itemsToClose.forEach(function (el) {
            el.classList.remove(FLYOUT_OPEN_CLASS);
            var t = el.querySelector('[data-flyout-toggle]');
            if (t) t.setAttribute('aria-expanded', 'false');
        });

        if (flyoutBackdrop) flyoutBackdrop.classList.remove('is-visible');
        if (isMobile && hasSidebar && !sidebar.classList.contains(MOBILE_OPEN)) {
            document.body.style.overflow = '';
        }

        // Reset off-screen position for the item
        var p = item.querySelector('.menu-submenu');
        if (p && !item.classList.contains('menu-submenu-item')) {
            p.style.left = '-99999px';
        }

        openFlyoutItem = null;
    }

    function closeAllFlyouts() {
        if (!hasSidebar) return;
        // Close ALL flyouts in the sidebar (parent + nested)
        sidebar.querySelectorAll('.menu-item.is-flyout-open, .menu-submenu-item.is-flyout-open').forEach(function (el) {
            el.classList.remove(FLYOUT_OPEN_CLASS);
            var t = el.querySelector('[data-flyout-toggle]');
            if (t) t.setAttribute('aria-expanded', 'false');
        });
        if (flyoutBackdrop) flyoutBackdrop.classList.remove('is-visible');
        if (isMobile && !sidebar.classList.contains(MOBILE_OPEN)) {
            document.body.style.overflow = '';
        }
        openFlyoutItem = null;
    }

    function toggleFlyout(parentItem) {
        if (!parentItem) return;
        const alreadyOpen = parentItem.classList.contains(FLYOUT_OPEN_CLASS);
        if (alreadyOpen) {
            closeAllFlyouts();
        } else {
            openFlyout(parentItem);
        }
    }

    function initFlyout() {
        if (!hasSidebar) return;
        // Event delegation: one listener for all flyout triggers
        sidebar.addEventListener('click', function (e) {
            const toggle = e.target.closest('[data-flyout-toggle]');
            if (!toggle) return;
            e.preventDefault();
            const parentItem = toggle.closest('.menu-item.has-submenu, .menu-submenu-item.has-submenu');
            if (parentItem) toggleFlyout(parentItem);
        });

        // Click outside closes all flyouts (but not inside context panel)
        document.addEventListener('click', function (e) {
            if (!openFlyoutItem) return;
            const withinSidebar = e.target.closest('#sidebar');
            const withinFlyout = e.target.closest('.menu-submenu');
            const withinContext = e.target.closest('.context-panel');
            if (!withinSidebar && !withinFlyout && !withinContext) closeAllFlyouts();
        });

        // ESC closes all
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && openFlyoutItem) closeAllFlyouts();
        });

        // Scroll / resize closes all
        let scrollTimer = null;
        function onScrollOrResize() {
            if (!openFlyoutItem) return;
            closeAllFlyouts();
        }
        window.addEventListener('scroll', onScrollOrResize, true);
        window.addEventListener('resize', onScrollOrResize);
    }


    /* ============================================================
       CONTEXT PANEL — Enterprise context selector
       Opens when clicking [data-context-panel] triggers
       ============================================================ */

    var contextPanel = null;
    var contextPanelInner = null;
    var contextPanelBody = null;
    var contextPanelTitle = null;
    var contextPanelClose = null;

    function initContextPanel() {
        contextPanel = document.getElementById('contextPanel');
        if (!contextPanel) return;

        contextPanelInner = contextPanel.querySelector('.context-panel-inner');
        contextPanelBody = document.getElementById('contextPanelBody');
        contextPanelTitle = document.getElementById('contextPanelTitle');
        contextPanelClose = document.getElementById('contextPanelClose');

        // Close button
        if (contextPanelClose) {
            contextPanelClose.addEventListener('click', closeContextPanel);
        }

        // Click backdrop to close
        var backdrop = document.getElementById('contextPanelBackdrop');
        if (backdrop) {
            backdrop.addEventListener('click', closeContextPanel);
        }

        // ESC to close
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && contextPanel && contextPanel.classList.contains('is-open')) {
                closeContextPanel();
            }
        });

        // Click on [data-context-panel] triggers
        document.addEventListener('click', function (e) {
            var trigger = e.target.closest('[data-context-panel]');
            if (!trigger) return;
            e.preventDefault();
            var panelName = trigger.getAttribute('data-context-panel');
            if (panelName) openContextPanel(panelName);
        });
    }

    function openContextPanel(panelName) {
        if (!contextPanel || !window.contextPanelData) return;

        var data = window.contextPanelData[panelName];
        if (!data) return;

        // Set title
        if (contextPanelTitle) contextPanelTitle.textContent = data.title || 'Panel';

        // Build simple link list (like flyout style)
        if (contextPanelBody && data.items) {
            contextPanelBody.innerHTML = '';
            data.items.forEach(function (item) {
                var link = document.createElement('a');
                link.href = item.route || '#';
                link.className = 'context-panel-link';
                link.textContent = item.title || '';
                contextPanelBody.appendChild(link);
            });
        }

        // Position panel vertically aligned with the Peserta item in Haflah flyout
        var trigger = document.querySelector('[data-context-panel="' + panelName + '"]');
        if (trigger && contextPanelInner) {
            var triggerRect = trigger.getBoundingClientRect();
            contextPanelInner.style.top = triggerRect.top + 'px';
        }

        // Show panel
        contextPanel.classList.add('is-open');
        document.body.style.overflow = 'hidden';

        // Store active trigger for highlight
        var activeTrigger = document.querySelector('[data-context-panel="' + panelName + '"]');
        if (activeTrigger) {
            activeTrigger.closest('.menu-submenu-item')?.classList.add('is-active');
        }
    }

    function closeContextPanel() {
        if (!contextPanel) return;
        contextPanel.classList.remove('is-open');
        if (contextPanelInner) contextPanelInner.style.top = '';
        document.body.style.overflow = '';

        // Remove active highlight from context triggers
        document.querySelectorAll('[data-context-panel]').forEach(function (el) {
            el.closest('.menu-submenu-item')?.classList.remove('is-active');
        });
    }


    /* ============================================================
       BACKWARD COMPAT — Handle navbar .js-hamburger
       ============================================================ */

    function initHamburgerCompat() {
        const hamburger = document.querySelector('.js-hamburger');
        if (hamburger) {
            hamburger.addEventListener('click', function (e) {
                e.preventDefault();
                if (!hasSidebar) {
                    // No sidebar (guru/siswa/bk) — toggle mobile dropdown
                    const checki = document.querySelector('#checki');
                    if (checki) checki.checked = !checki.checked;
                } else if (isMobile) {
                    toggleMobile();
                } else {
                    toggleSidebar();
                }
            });
        }
    }

    /* ============================================================
       CHECKBOX COMPAT — Toggle #checki via #btn (legacy)
       ============================================================ */

    function initCheckboxCompat() {
        const mobileBtn = document.querySelector('#btn');
        const checki = document.querySelector('#checki');
        if (mobileBtn && checki) {
            mobileBtn.addEventListener('click', function (e) {
                e.stopPropagation();
                checki.checked = !checki.checked;
            });
        }
    }


    /* ============================================================
       RESIZE — Handle responsive changes
       ============================================================ */

    function handleResize() {
        const wasMobile = isMobile;
        isMobile = window.innerWidth <= 768;

        if (isMobile !== wasMobile) {
            if (hasSidebar) closeAllFlyouts();
            if (isMobile) {
                if (hasSidebar) {
                    sidebar.classList.remove(TOGGLE_CLASS);
                    sidebar.classList.remove(MOBILE_OPEN);
                }
                if (overlay) overlay.classList.remove('is-visible');
                document.body.style.overflow = '';
                document.body.classList.remove(BODY_COLLAPSED_CLASS);
            } else {
                if (hasSidebar) closeMobile();
                let stored = false;
                try {
                    stored = localStorage.getItem('sidebar-collapsed') === '1';
                } catch (e) { /* ignore */ }
                setSidebarState(stored);
            }
        }
    }


    /* ============================================================
       INIT
       ============================================================ */

    function init() {
        if (hasSidebar) {
            createOverlay();

            isMobile = window.innerWidth <= 768;

            if (!isMobile) {
                let stored = false;
                try {
                    stored = localStorage.getItem('sidebar-collapsed') === '1';
                } catch (e) { /* ignore */ }
                setSidebarState(stored);
            } else {
                sidebar.classList.remove(TOGGLE_CLASS);
                document.body.classList.remove(BODY_COLLAPSED_CLASS);
            }

            // Toggler
            const toggler = document.querySelector(TOGGLER_SEL);
            if (toggler) {
                toggler.addEventListener('click', function (e) {
                    e.stopPropagation();
                    if (isMobile) {
                        toggleMobile();
                    } else {
                        toggleSidebar();
                    }
                });
            }

            // Navigation mode
            if (isFlyout) {
                initFlyout();
            } else {
                initSubmenus();
                autoOpenActive();
            }
            initContextPanel();

            // Touch to close overlay
            if (overlay) {
                overlay.addEventListener('touchstart', closeMobile);
            }
        }

        // Always run: hamburger + checkbox compat
        initHamburgerCompat();
        initCheckboxCompat();

        // Resize
        window.addEventListener('resize', handleResize);
    }

    // Wait for DOM
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

})();
