/* ============================================================
   LOADING SYSTEM — Professional loading for SIAKAD
   ============================================================ */

(function() {
    'use strict';

    /* ============================================================
       STATE
       ============================================================ */
    var pageReady = false;
    var progressBar = document.getElementById('loading-progress');
    var progressInner = progressBar ? progressBar.querySelector('.bar') : null;
    var fullscreen = document.getElementById('loading-fullscreen');
    var loaderText = document.getElementById('loader-text');

    /* ============================================================
       HELPERS
       ============================================================ */
    function isDataTable(el) {
        return el && (el.classList.contains('dataTable') || el.id.match(/^table_/));
    }

    /* ============================================================
       1. PROGRESS BAR
       ============================================================ */
    function progressStart() {
        if (!progressBar || !progressInner) return;
        progressBar.classList.add('active');
        progressInner.style.width = '30%';
    }

    function progressComplete() {
        if (!progressBar || !progressInner) return;
        progressInner.style.width = '100%';
        setTimeout(function() {
            progressBar.classList.remove('active');
            progressInner.style.width = '0';
        }, 400);
    }

    /* ============================================================
       2. FULL SCREEN LOADER
       ============================================================ */
    function showFullscreen(text) {
        if (!fullscreen) return;
        if (text && loaderText) loaderText.textContent = text;
        else if (loaderText) loaderText.textContent = 'Memuat halaman...';
        fullscreen.classList.add('active');
    }

    function hideFullscreen() {
        if (!fullscreen) return;
        fullscreen.classList.remove('active');
    }

    /* ============================================================
       3. PAGE TRANSITION — Sidebar / menu clicks
       ============================================================ */
    function setupPageTransition() {
        var links = document.querySelectorAll(
            '.sidebar-menu a[href], ' +
            '.menu-link[href], ' +
            '.menu-submenu-link[href], ' +
            '.sidebar-nav a[href]:not([href="#"]), ' +
            'a.nav-link:not([href="#"]):not([data-bs-toggle]), ' +
            '.breadcrumb-item a[href], ' +
            '.btn[href]:not([data-bs-toggle]):not(.btn-loading)'
        );

        links.forEach(function(link) {
            link.addEventListener('click', function(e) {
                var href = this.getAttribute('href');
                if (!href || href === '#' || href === '' || href.startsWith('javascript')) return;
                if (e.ctrlKey || e.metaKey || e.shiftKey) return;
                if (this.getAttribute('target') === '_blank') return;
                if (this.hasAttribute('data-bs-toggle')) return;
                if (this.hasAttribute('data-bs-target')) return;

                var isSamePage = href.startsWith('#') ||
                    href === window.location.pathname ||
                    href === window.location.href;

                if (!isSamePage && !e.defaultPrevented) {
                    progressStart();
                    showFullscreen('Memuat halaman...');
                    sessionStorage.setItem('sia_navigating', 'true');
                }
            });
        });
    }

    /* ============================================================
       4. FORM SUBMISSION — Button loading state
       ============================================================ */
    function setupFormLoading() {
        document.addEventListener('submit', function(e) {
            var form = e.target;
            if (form.tagName !== 'FORM') return;

            var submitBtn = form.querySelector('[type="submit"]');
            if (!submitBtn) return;
            if (submitBtn.disabled) return;

            var action = (form.getAttribute('action') || '').toLowerCase();
            var method = (form.getAttribute('method') || 'get').toLowerCase();

            var loadingText = 'Menyimpan...';
            var showLoader = true;

            if (action.includes('hapus') || action.includes('destroy') || action.includes('delete')) {
                loadingText = 'Menghapus...';
            } else if (action.includes('update') || action.includes('edit') || method === 'put') {
                loadingText = 'Memperbarui data...';
            } else if (action.includes('import')) {
                loadingText = 'Mengimpor data...';
            } else if (action.includes('export') || action.includes('cetak') || action.includes('pdf')) {
                loadingText = 'Menyiapkan file...';
            } else if (action.includes('login') || action.includes('masuk')) {
                loadingText = 'Memverifikasi akun...';
            } else if (action.includes('arsip') || action.includes('kenaikan') || action.includes('sinkron')) {
                loadingText = 'Memproses...';
            }

            if (showLoader) {
                showFullscreen(loadingText);
            }
            setButtonLoading(submitBtn, loadingText);
        });
    }

    function setButtonLoading(btn, text) {
        if (!btn || btn.classList.contains('btn-loading')) return;

        var originalHtml = btn.innerHTML;
        btn.classList.add('btn-loading');
        btn.disabled = true;
        btn.dataset.originalHtml = originalHtml;

        var loader = document.createElement('span');
        loader.className = 'btn-loader';
        loader.innerHTML = '<span class="spinner"></span> <span>' + text + '</span>';
        btn.appendChild(loader);
    }

    function restoreButton(btn) {
        if (!btn || !btn.classList.contains('btn-loading')) return;

        btn.classList.remove('btn-loading');
        btn.disabled = false;

        var loader = btn.querySelector('.btn-loader');
        if (loader) loader.remove();
    }

    /* ============================================================
       5. RESTORE BUTTONS AFTER AJAX
       ============================================================ */
    function setupAjaxRestore() {
        var origAjax = window.XMLHttpRequest;
        if (!origAjax) return;

        var origSend = origAjax.prototype.send;
        origAjax.prototype.send = function() {
            this.addEventListener('loadend', function() {
                document.querySelectorAll('.btn-loading').forEach(restoreButton);
                hideFullscreen();
            });
            return origSend.apply(this, arguments);
        };
    }

    /* ============================================================
       6. DATATABLE LOADING OVERLAY
       ============================================================ */
    function setupDataTableLoading() {
        var tables = document.querySelectorAll('.table.display, .dataTable');
        if (!tables.length) return;

        var template = document.getElementById('dt-loading-template');
        if (!template) return;

        tables.forEach(function(table) {
            var wrapper = table.closest('.table-responsive') || table.parentElement;
            if (!wrapper) return;
            if (wrapper.querySelector('.dt-loading-overlay')) return;

            var clone = template.content.cloneNode(true);
            wrapper.style.position = 'relative';
            wrapper.appendChild(clone);
        });
    }

    function dtLoadingStart(tableId) {
        var selector = tableId ? '#' + tableId : '.table-responsive';
        var overlays = document.querySelectorAll(selector + ' .dt-loading-overlay');
        overlays.forEach(function(o) { o.classList.add('active'); });
    }

    function dtLoadingStop(tableId) {
        var selector = tableId ? '#' + tableId : '.table-responsive';
        var overlays = document.querySelectorAll(selector + ' .dt-loading-overlay');
        overlays.forEach(function(o) { o.classList.remove('active'); });
    }

    /* ============================================================
       7. LOGIN HANDLING
       ============================================================ */
    function setupLoginLoading() {
        var loginForm = document.querySelector('form[action*="login"]');
        if (!loginForm) return;

        loginForm.addEventListener('submit', function() {
            showFullscreen('');
            if (loaderText) loaderText.textContent = 'Memverifikasi akun...';
            var dots = fullscreen ? fullscreen.querySelector('.loader-dots') : null;
            if (dots) dots.style.display = 'none';

            var loginContent = document.createElement('div');
            loginContent.className = 'login-loading';
            loginContent.innerHTML =
                '<img src="../img/logo2.png" alt="Logo" class="login-logo" onerror="this.style.display=\'none\'">' +
                '<div class="login-verify-text">Memverifikasi akun...</div>' +
                '<div class="login-sub-text">Mohon tunggu sebentar</div>' +
                '<div class="login-progress"><div class="login-progress-bar"></div></div>';

            var inner = fullscreen ? fullscreen.querySelector('.loader-inner') : null;
            if (inner) {
                inner.innerHTML = '';
                inner.appendChild(loginContent);
            }
        });
    }

    /* ============================================================
       8. LOGOUT HANDLING
       ============================================================ */
    function setupLogoutLoading() {
        document.addEventListener('click', function(e) {
            var btn = e.target.closest('a[href*="logout"], button[formaction*="logout"], form[action*="logout"] [type="submit"]');
            if (!btn) return;

            if (btn.hasAttribute('onclick')) return;

            var form = btn.closest('form') || document.getElementById('logout-form');
            if (form) {
                e.preventDefault();
                showLogoutScreen(function() {
                    form.submit();
                });
            }
        });
    }

    function showLogoutScreen(callback) {
        progressStart();
        showFullscreen('');

        var inner = fullscreen ? fullscreen.querySelector('.loader-inner') : null;
        if (inner) {
            inner.innerHTML =
                '<div class="logout-loading">' +
                    '<div class="logout-icon"><i class="fas fa-door-open"></i></div>' +
                    '<div class="logout-text">Sampai jumpa kembali...</div>' +
                    '<div class="logout-sub">Terima kasih telah menggunakan Siakad</div>' +
                    '<div class="logout-dots">' +
                        '<span></span><span></span><span></span>' +
                    '</div>' +
                '</div>';
        }

        setTimeout(callback, 600);
    }

    /* ============================================================
       9. PAGE READY — Finalize loading
       ============================================================ */
    function onPageReady() {
        if (pageReady) return;
        pageReady = true;

        var isNavigating = sessionStorage.getItem('sia_navigating') === 'true';
        sessionStorage.removeItem('sia_navigating');

        if (isNavigating) {
            setTimeout(function() {
                hideFullscreen();
                progressComplete();
            }, 400);
        } else {
            setTimeout(function() {
                hideFullscreen();
                progressComplete();
            }, 200);
        }

        document.querySelectorAll('.btn-loading').forEach(restoreButton);
    }

    /* ============================================================
       11. AJAX GLOBAL — Form submit via AJAX (SweetAlert etc.)
       ============================================================ */
    function setupAjaxButtons() {
        document.addEventListener('click', function(e) {
            var btn = e.target.closest('[onclick*="$.ajax"], [onclick*="ajax"], .swal-button--confirm, .swal-button--danger');
            if (!btn) return;

            if (btn.classList.contains('swal-button--confirm') ||
                btn.classList.contains('swal-button--danger')) {

                var swalText = '';
                var modalBody = document.querySelector('.swal-modal') ||
                               document.querySelector('.swal-overlay');
                if (modalBody) {
                    var title = modalBody.querySelector('.swal-title');
                    if (title && title.textContent.toLowerCase().includes('hapus')) {
                        swalText = 'Menghapus...';
                    }
                }

                if (swalText) {
                    btn.classList.add('btn-loading');
                    btn.disabled = true;
                    btn.dataset.originalHtml = btn.innerHTML;
                    btn.innerHTML = '<span class="spinner" style="width:14px;height:14px;border:2px solid #fff;border-top-color:transparent;border-radius:50%;display:inline-block;animation:spinnerRotate .6s linear infinite;vertical-align:middle;margin-right:6px;"></span> ' + swalText;
                }
            }
        });
    }

    /* ============================================================
       INIT
       ============================================================ */
    function init() {
        var isNavigating = sessionStorage.getItem('sia_navigating') === 'true';

        if (isNavigating) {
            progressStart();
            showFullscreen('Memuat halaman...');
        }

        setupPageTransition();
        setupFormLoading();
        setupAjaxRestore();
        setupDataTableLoading();
        setupLoginLoading();
        setupLogoutLoading();
        setupAjaxButtons();

        if (document.readyState === 'complete') {
            onPageReady();
        } else {
            window.addEventListener('load', onPageReady);
            document.addEventListener('DOMContentLoaded', function() {
                progressStart();
            });
        }

        setTimeout(function() {
            if (!pageReady) {
                hideFullscreen();
                progressComplete();
            }
        }, 8000);

        if (typeof $ !== 'undefined' && $.fn.dataTable) {
            $(document).on('preXhr.dt', function(e, settings) {
                var tableId = settings.nTable.id;
                dtLoadingStart(tableId);
            });
            $(document).on('xhr.dt', function(e, settings) {
                var tableId = settings.nTable.id;
                dtLoadingStop(tableId);
            });
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
