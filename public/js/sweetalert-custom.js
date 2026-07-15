/* ============================================================
   SWEETALERT CUSTOM — Fully Custom Modern UI
   Replaces ALL SweetAlert popups with custom modern ones.
   No dependency on SweetAlert built-in popup.
   ============================================================ */

(function() {
    'use strict';

    var toastContainer = null;

    function getToastContainer() {
        if (!toastContainer) {
            toastContainer = document.getElementById('ms-toast-container');
            if (!toastContainer) {
                toastContainer = document.createElement('div');
                toastContainer.id = 'ms-toast-container';
                toastContainer.className = 'ms-toast-container';
                document.body.appendChild(toastContainer);
            }
        }
        return toastContainer;
    }

    function isToastCall(args) {
        if (args.length === 3) {
            var t = typeof args[0];
            var m = typeof args[1];
            var i = typeof args[2];
            if (t === 'string' && m === 'string' && i === 'string') return true;
            return false;
        }
        if (args.length === 1 && typeof args[0] === 'object') {
            var opts = args[0];
            if (opts.dangerMode) return false;
            if (opts.showCancelButton) return false;
            if (opts.showDenyButton) return false;
            if (Array.isArray(opts.buttons) && opts.buttons.length === 2 && opts.buttons[0] === true) return false;
            if (Array.isArray(opts.buttons) && opts.buttons.length === 2 && opts.buttons[0] === false) return true;
            if (typeof opts.buttons === 'string') return false;
            if (opts.timer !== undefined && opts.timer > 0) return false;
            return true;
        }
        if (args.length === 2) {
            return typeof args[0] === 'string' && typeof args[1] === 'string';
        }
        if (args.length === 1 && typeof args[0] === 'string') {
            return true;
        }
        return false;
    }

    function escapeHtml(text) {
        if (!text) return '';
        var d = document.createElement('div');
        d.textContent = text;
        return d.innerHTML;
    }

    /* -------------------------------------------------------
       TOAST NOTIFICATIONS
    ------------------------------------------------------- */

    function showToast(title, message, icon) {
        var container = getToastContainer();

        var iconMap = {
            success: 'check-circle',
            error: 'times-circle',
            warning: 'exclamation-triangle',
            info: 'info-circle',
            question: 'question-circle'
        };
        var iconName = iconMap[icon] || 'info-circle';

        var toast = document.createElement('div');
        toast.className = 'ms-toast ms-toast-' + (icon || 'info');

        var closeFn = function() {
            clearTimeout(autoClose);
            toast.classList.add('ms-toast-hiding');
            setTimeout(function() { if (toast.parentNode) toast.remove(); }, 300);
        };

        toast.innerHTML =
            '<div class="toast-icon"><i class="fas fa-' + iconName + '"></i></div>' +
            '<div class="toast-body">' +
                '<div class="toast-title">' + escapeHtml(title) + '</div>' +
                (message ? '<p class="toast-text">' + escapeHtml(message) + '</p>' : '') +
            '</div>' +
            '<button class="toast-close" type="button"><i class="fas fa-times"></i></button>' +
            '<div class="toast-progress"></div>';

        container.appendChild(toast);

        toast.querySelector('.toast-close').addEventListener('click', closeFn);

        var autoClose = setTimeout(function() {
            if (toast.parentNode) {
                toast.classList.add('ms-toast-hiding');
                setTimeout(function() { if (toast.parentNode) toast.remove(); }, 300);
            }
        }, 2000);

        return toast;
    }

    /* -------------------------------------------------------
       CONFIRM MODAL — Fully Custom (no SweetAlert dependency)
    ------------------------------------------------------- */

    function getModalRoot() {
        var el = document.getElementById('ms-modal-root');
        if (!el) {
            el = document.createElement('div');
            el.id = 'ms-modal-root';
            document.body.appendChild(el);
        }
        return el;
    }

    function parseButtons(opts) {
        var result = {
            cancelText: 'Batal',
            confirmText: 'Ya, Hapus',
            dangerMode: opts.dangerMode || false
        };

        if (opts.buttons) {
            var b = opts.buttons;
            if (typeof b === 'string') {
                result.confirmText = b;
                result.cancelText = null;
            } else if (Array.isArray(b)) {
                if (b.length >= 1) {
                    if (b[0] === true) {
                        result.cancelText = 'Batal';
                    } else if (typeof b[0] === 'string') {
                        result.cancelText = b[0];
                    } else {
                        result.cancelText = null;
                    }
                }
                if (b.length >= 2 && typeof b[1] === 'string') {
                    result.confirmText = b[1];
                }
            }
        }

        return result;
    }

    function buildConfirmModal(title, text, icon, btnInfo, onConfirm, onCancel) {
        var root = getModalRoot();

        var iconMap = {
            warning: 'exclamation-triangle',
            error: 'times-circle',
            success: 'check-circle',
            info: 'info-circle',
            question: 'question-circle'
        };
        var faIcon = iconMap[icon] || 'exclamation-triangle';

        var backdrop = document.createElement('div');
        backdrop.className = 'ms-modal-backdrop';
        backdrop.addEventListener('click', function(e) {
            if (e.target === backdrop) onCancel();
        });

        var modal = document.createElement('div');
        modal.className = 'ms-modal';

        var modalInner = document.createElement('div');
        modalInner.className = 'ms-modal-inner';

        var iconEl = document.createElement('div');
        iconEl.className = 'ms-modal-icon ms-modal-icon-' + (icon || 'warning');
        iconEl.innerHTML = '<i class="fas fa-' + faIcon + '"></i>';

        var titleEl = document.createElement('div');
        titleEl.className = 'ms-modal-title';
        titleEl.textContent = title;

        var textEl = document.createElement('div');
        textEl.className = 'ms-modal-text';
        textEl.textContent = text;

        var actionsEl = document.createElement('div');
        actionsEl.className = 'ms-modal-actions';

        if (btnInfo.cancelText) {
            var cancelBtn = document.createElement('button');
            cancelBtn.type = 'button';
            cancelBtn.className = 'ms-modal-btn ms-modal-btn-cancel';
            cancelBtn.textContent = btnInfo.cancelText;
            cancelBtn.addEventListener('click', function(e) {
                e.preventDefault();
                onCancel();
            });
            actionsEl.appendChild(cancelBtn);
        }

        var confirmBtn = document.createElement('button');
        confirmBtn.type = 'button';
        confirmBtn.className = 'ms-modal-btn ms-modal-btn-confirm' +
            (btnInfo.dangerMode ? ' ms-modal-btn-danger' : '');
        confirmBtn.textContent = btnInfo.confirmText;
        confirmBtn.addEventListener('click', function(e) {
            e.preventDefault();
            onConfirm();
        });
        actionsEl.appendChild(confirmBtn);

        modalInner.appendChild(iconEl);
        modalInner.appendChild(titleEl);
        modalInner.appendChild(textEl);
        modalInner.appendChild(actionsEl);
        modal.appendChild(modalInner);
        backdrop.appendChild(modal);
        root.appendChild(backdrop);

        // Trigger enter animation on next frame
        requestAnimationFrame(function() {
            backdrop.classList.add('ms-modal-visible');
            modal.classList.add('ms-modal-visible');
        });

        return {
            backdrop: backdrop,
            modal: modal,
            close: function() {
                modal.classList.remove('ms-modal-visible');
                backdrop.classList.remove('ms-modal-visible');
                setTimeout(function() {
                    if (backdrop.parentNode) backdrop.remove();
                }, 300);
            }
        };
    }

    function showConfirm(opts) {
        var title = opts.title || 'Konfirmasi';
        var text = opts.text || opts.message || '';
        var icon = opts.icon || 'warning';

        var btnInfo = parseButtons(opts);

        return {
            then: function(cb) {
                var instance = buildConfirmModal(
                    title,
                    text,
                    icon,
                    btnInfo,
                    function() {
                        instance.close();
                        if (typeof cb === 'function') cb(true);
                    },
                    function() {
                        instance.close();
                        if (typeof cb === 'function') cb(false);
                    }
                );
                return this;
            }
        };
    }

    /* -------------------------------------------------------
       MAIN INTERCEPT
    ------------------------------------------------------- */

    function msSwal() {
        var args = arguments;

        if (isToastCall(args)) {
            var title = '', message = '', icon = 'success';

            if (args.length === 1 && typeof args[0] === 'object') {
                var opts = args[0];
                title = opts.title || '';
                message = opts.text || opts.message || '';
                icon = opts.icon || 'success';
            } else if (args.length === 3) {
                title = args[0] || '';
                message = args[1] || '';
                icon = args[2] || 'success';
            } else if (args.length === 2) {
                title = args[0] || '';
                message = args[1] || '';
            } else if (args.length === 1 && typeof args[0] === 'string') {
                title = args[0];
            }

            showToast(title, message, icon);

            return {
                then: function(cb) {
                    setTimeout(function() {
                        if (typeof cb === 'function') cb(true);
                    }, 2200);
                    return this;
                }
            };
        }

        var opts = args.length === 1 && typeof args[0] === 'object'
            ? args[0]
            : { title: args[0], text: args[1], icon: args[2] };

        return showConfirm(opts);
    }

    // Override window.swal (handles inline swal() calls in Blade)
    window.swal = msSwal;

    // Save as __ms_swal so alert.blade.php can use it even if
    // sweetalert.all.js overwrites window.swal later
    window.__ms_swal = msSwal;

    // Also intercept Swal.fire() for SweetAlert2 callers
    // This handles both vendor/alert.blade.php and any Swal.fire() calls
    window.Swal = window.Swal || {};
    window.Swal.fire = msSwal;
})();
