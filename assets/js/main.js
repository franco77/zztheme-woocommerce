/**
 * ZZTheme — main.js
 * JavaScript puro, sin dependencias externas.
 */

(function () {
    'use strict';

    /* ── Menú mobile ──────────────────────────────────────────────────────── */
    function initMobileMenu() {
        var toggle = document.querySelector('.menu-toggle');
        var nav    = document.getElementById('site-navigation');
        if (!toggle || !nav) return;

        var overlay = document.querySelector('.site-overlay');

        function openMenu() {
            nav.classList.add('is-open');
            toggle.setAttribute('aria-expanded', 'true');
            document.body.style.overflow = 'hidden';
            if (overlay) overlay.classList.add('is-visible');
        }

        function closeMenu() {
            nav.classList.remove('is-open');
            toggle.setAttribute('aria-expanded', 'false');
            document.body.style.overflow = '';
            if (overlay) overlay.classList.remove('is-visible');
        }

        toggle.addEventListener('click', function () {
            nav.classList.contains('is-open') ? closeMenu() : openMenu();
        });

        if (overlay) {
            overlay.addEventListener('click', function () {
                closeMenu();
                closeSidebar();
            });
        }

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') closeMenu();
        });

        window.addEventListener('resize', function () {
            if (window.innerWidth > 768) closeMenu();
        });
    }

    /* ── Modo oscuro ──────────────────────────────────────────────────────── */
    function initDarkMode() {
        var btn  = document.getElementById('dark-mode-toggle');
        var html = document.documentElement;
        if (!btn) return;

        function applyMode(mode) {
            html.classList.remove('dark-mode', 'light-mode');
            if (mode === 'dark')  html.classList.add('dark-mode');
            if (mode === 'light') html.classList.add('light-mode');
            btn.setAttribute('aria-pressed', mode === 'dark' ? 'true' : 'false');
            updateIcon(mode);
        }

        function updateIcon(mode) {
            var icon = btn.querySelector('.dm-icon');
            if (!icon) return;
            icon.textContent = mode === 'dark' ? '☀' : '◑';
        }

        var stored = localStorage.getItem('zztheme-dark-mode');
        if (stored) applyMode(stored);

        btn.addEventListener('click', function () {
            var isDark = html.classList.contains('dark-mode');
            var next   = isDark ? 'light' : 'dark';
            localStorage.setItem('zztheme-dark-mode', next);
            applyMode(next);
        });
    }

    /* ── Sticky header ────────────────────────────────────────────────────── */
    function initStickyHeader() {
        var sentinel = document.getElementById('header-sentinel');
        var header   = document.getElementById('masthead');
        if (!sentinel || !header) return;

        if (!('IntersectionObserver' in window)) return;

        var observer = new IntersectionObserver(function (entries) {
            header.classList.toggle('is-sticky', !entries[0].isIntersecting);
        });

        observer.observe(sentinel);
    }

    /* ── Toggles de submenú ───────────────────────────────────────────────── */
    function initSubmenus() {
        var toggles = document.querySelectorAll('.submenu-toggle');
        toggles.forEach(function (btn) {
            btn.addEventListener('click', function (e) {
                e.stopPropagation();
                var li      = btn.closest('li');
                var submenu = li ? li.querySelector('.sub-menu') : null;
                if (!submenu) return;
                var isOpen = submenu.classList.contains('is-open');
                /* Cerrar todos los hermanos */
                var siblings = li.parentElement ? li.parentElement.querySelectorAll(':scope > li > .sub-menu.is-open') : [];
                siblings.forEach(function (s) {
                    s.classList.remove('is-open');
                    var sibBtn = s.previousElementSibling;
                    if (sibBtn && sibBtn.classList.contains('submenu-toggle')) {
                        sibBtn.textContent = '+';
                        sibBtn.setAttribute('aria-expanded', 'false');
                    }
                });
                submenu.classList.toggle('is-open', !isOpen);
                btn.textContent = isOpen ? '+' : '−';
                btn.setAttribute('aria-expanded', isOpen ? 'false' : 'true');
            });
        });
    }

    /* ── Shop sidebar (mobile) ────────────────────────────────────────────── */
    var sidebarOpen = false;

    function closeSidebar() {
        var sidebar = document.querySelector('.wc-sidebar');
        var overlay = document.querySelector('.site-overlay');
        if (!sidebar) return;
        sidebar.classList.remove('is-open');
        document.body.style.overflow = '';
        if (overlay) overlay.classList.remove('is-visible');
        sidebarOpen = false;
    }

    function initShopSidebar() {
        var toggle  = document.querySelector('.wc-sidebar-toggle');
        var sidebar = document.querySelector('.wc-sidebar');
        var overlay = document.querySelector('.site-overlay');
        if (!toggle || !sidebar) return;

        toggle.addEventListener('click', function () {
            sidebarOpen = !sidebarOpen;
            sidebar.classList.toggle('is-open', sidebarOpen);
            document.body.style.overflow = sidebarOpen ? 'hidden' : '';
            if (overlay) overlay.classList.toggle('is-visible', sidebarOpen);
        });

        window.addEventListener('resize', function () {
            if (window.innerWidth > 768) closeSidebar();
        });
    }

    /* ── Acordeones genéricos ─────────────────────────────────────────────── */
    function initAccordions() {
        var triggers = document.querySelectorAll('.accordion__trigger');
        triggers.forEach(function (trigger) {
            trigger.addEventListener('click', function () {
                var panel   = trigger.nextElementSibling;
                var isOpen  = trigger.getAttribute('aria-expanded') === 'true';
                /* Cerrar otros del mismo acordeón padre */
                var parent  = trigger.closest('.accordion-group');
                if (parent) {
                    parent.querySelectorAll('.accordion__trigger[aria-expanded="true"]').forEach(function (t) {
                        if (t !== trigger) {
                            t.setAttribute('aria-expanded', 'false');
                            var p = t.nextElementSibling;
                            if (p) p.classList.remove('is-open');
                        }
                    });
                }
                trigger.setAttribute('aria-expanded', isOpen ? 'false' : 'true');
                if (panel) panel.classList.toggle('is-open', !isOpen);
            });
        });
    }

    /* ── Lazy loading de imágenes ─────────────────────────────────────────── */
    function initLazyLoad() {
        var images = document.querySelectorAll('img[data-src]');
        if (!images.length) return;

        if (!('IntersectionObserver' in window)) {
            images.forEach(function (img) {
                img.src = img.dataset.src;
                img.removeAttribute('data-src');
            });
            return;
        }

        var observer = new IntersectionObserver(function (entries, obs) {
            entries.forEach(function (entry) {
                if (!entry.isIntersecting) return;
                var img = entry.target;
                img.src = img.dataset.src;
                img.removeAttribute('data-src');
                img.classList.add('loaded');
                obs.unobserve(img);
            });
        }, { rootMargin: '100px' });

        images.forEach(function (img) { observer.observe(img); });
    }

    /* ── Formulario de contacto AJAX ──────────────────────────────────────── */
    function initContactForm() {
        var form = document.getElementById('zztheme-contact-form');
        if (!form) return;

        var successMsg = form.querySelector('.contact-success');
        var errorMsg   = form.querySelector('.contact-error');
        var submitBtn  = form.querySelector('.contact-form__submit');

        form.addEventListener('submit', function (e) {
            e.preventDefault();
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.textContent = submitBtn.getAttribute('data-loading') || 'Enviando…';
            }

            var data = new FormData(form);
            var url  = (window.ZZTHEME && window.ZZTHEME.ajaxUrl) ? window.ZZTHEME.ajaxUrl : '/wp-admin/admin-ajax.php';

            fetch(url, { method: 'POST', body: data })
                .then(function (r) { return r.json(); })
                .then(function (res) {
                    if (res.success) {
                        form.style.display = 'none';
                        if (successMsg) successMsg.hidden = false;
                    } else {
                        if (errorMsg) errorMsg.hidden = false;
                    }
                })
                .catch(function () {
                    if (errorMsg) errorMsg.hidden = false;
                })
                .finally(function () {
                    if (submitBtn) {
                        submitBtn.disabled = false;
                        submitBtn.textContent = 'Enviar mensaje';
                    }
                });
        });
    }

    /* ── Toggle cupón en checkout ─────────────────────────────────────────── */
    function initCouponToggle() {
        var toggleBtn  = document.getElementById('coupon-toggle');
        var couponField = document.getElementById('coupon-field');
        if (!toggleBtn || !couponField) return;

        toggleBtn.addEventListener('click', function () {
            couponField.hidden = !couponField.hidden;
        });

        var applyBtn = document.getElementById('checkout_coupon_apply');
        if (!applyBtn) return;

        applyBtn.addEventListener('click', function () {
            var code = document.getElementById('checkout_coupon_code');
            if (!code || !code.value.trim()) return;

            var data = new FormData();
            data.append('action', 'woocommerce_apply_coupon');
            data.append('security', (window.wc_checkout_params && window.wc_checkout_params.apply_coupon_nonce) || '');
            data.append('coupon_code', code.value.trim());

            fetch((window.wc_checkout_params && window.wc_checkout_params.ajax_url) || '/wp-admin/admin-ajax.php', {
                method: 'POST',
                body: data,
            }).then(function () {
                window.location.reload();
            });
        });
    }

    /* ── Mini-cart: actualizar contador al añadir producto ────────────────── */
    function initMiniCart() {
        document.addEventListener('wc_fragments_refreshed', function () {
            var countEl = document.querySelector('.header-action--cart .header-action__count');
            if (!countEl) return;
            var cartCount = document.querySelector('.woocommerce-cart-form') ? null : null;
            /* WC actualiza sus propios fragments — sólo refrescamos si WC no lo hizo */
            if (window.wc_cart_fragments_params) return;
        });
    }

    /* ── Acordeón de subcategorías en sidebar de tienda ──────────────────── */
    function initSidebarCategories() {
        /* Auto-expandir si hay una subcategoria activa */
        document.querySelectorAll('.sidebar-categories .cat-item.is-active').forEach(function (li) {
            var parentUl = li.closest('ul.children');
            if (!parentUl) return;
            var parentLi = parentUl.closest('.cat-item');
            if (!parentLi) return;
            parentLi.classList.add('is-expanded');
            var btn = parentLi.querySelector('.cat-expand');
            if (btn) {
                btn.textContent = '−';
                btn.setAttribute('aria-expanded', 'true');
            }
        });

        /* Toggle al hacer clic en + / − */
        document.querySelectorAll('.cat-expand').forEach(function (btn) {
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
                var li     = btn.closest('.cat-item');
                var isOpen = li.classList.contains('is-expanded');
                li.classList.toggle('is-expanded', !isOpen);
                btn.textContent = isOpen ? '+' : '−';
                btn.setAttribute('aria-expanded', isOpen ? 'false' : 'true');
            });
        });
    }

    /* ── Botones +/− de cantidad en producto individual ──────────────────── */
    function initQuantityButtons() {
        document.querySelectorAll('.sp__add-to-cart .quantity').forEach(function (wrapper) {
            var input = wrapper.querySelector('input.qty');
            if (!input || wrapper.querySelector('.sp__qty-btn')) return;

            var btnMinus = document.createElement('button');
            btnMinus.type = 'button';
            btnMinus.className = 'sp__qty-btn sp__qty-btn--minus';
            btnMinus.innerHTML = '&minus;';
            btnMinus.setAttribute('aria-label', 'Disminuir cantidad');

            var btnPlus = document.createElement('button');
            btnPlus.type = 'button';
            btnPlus.className = 'sp__qty-btn sp__qty-btn--plus';
            btnPlus.textContent = '+';
            btnPlus.setAttribute('aria-label', 'Aumentar cantidad');

            wrapper.insertBefore(btnMinus, input);
            wrapper.appendChild(btnPlus);

            btnMinus.addEventListener('click', function () {
                var val = parseInt(input.value, 10) || 1;
                var min = parseInt(input.getAttribute('min'), 10) || 1;
                if (val > min) {
                    input.value = val - 1;
                    input.dispatchEvent(new Event('change', { bubbles: true }));
                }
            });

            btnPlus.addEventListener('click', function () {
                var val = parseInt(input.value, 10) || 1;
                var maxAttr = input.getAttribute('max');
                var max = maxAttr ? parseInt(maxAttr, 10) : Infinity;
                if (val < max) {
                    input.value = val + 1;
                    input.dispatchEvent(new Event('change', { bubbles: true }));
                }
            });
        });
    }

    /* ── Toast de notificaciones ─────────────────────────────────────────── */
    function showToast(msg, type) {
        var el = document.createElement('div');
        el.className = 'site-toast site-toast--' + (type || 'success');
        el.setAttribute('role', 'status');
        el.setAttribute('aria-live', 'polite');

        var icons = {
            success : '<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>',
            wishlist: '<svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>',
        };

        el.innerHTML = (icons[type] || icons.success) + '<span class="site-toast__msg">' + msg + '</span>';
        document.body.appendChild(el);

        requestAnimationFrame(function () {
            requestAnimationFrame(function () { el.classList.add('is-visible'); });
        });

        setTimeout(function () {
            el.classList.remove('is-visible');
            setTimeout(function () { if (el.parentNode) el.remove(); }, 350);
        }, 3200);
    }

    /* ── Lista de deseos (Wishlist) ──────────────────────────────────────── */
    function initWishlist() {
        var STORAGE_KEY = 'zz-wishlist';
        var ZZ          = window.ZZTHEME || {};
        var ajaxUrl     = ZZ.ajaxUrl       || '/wp-admin/admin-ajax.php';
        var shopUrl     = ZZ.shopUrl        || '/';
        var lblAdd      = ZZ.wishlistAdd    || 'Agregar a lista de deseos';
        var lblRemove   = ZZ.wishlistRemove || 'Quitar de lista de deseos';
        var lblEmpty    = ZZ.wishlistEmpty  || 'Tu lista de deseos está vacía.';
        var lblShop     = ZZ.wishlistShop   || 'Explorar productos';

        /* ── Almacenamiento — guarda objetos {id, added} ── */
        function getEntries() {
            try {
                var raw = JSON.parse(localStorage.getItem(STORAGE_KEY) || '[]');
                return raw.map(function (item) {
                    if (typeof item === 'string' || typeof item === 'number') {
                        return { id: String(item), added: new Date().toISOString() };
                    }
                    return { id: String(item.id), added: item.added || new Date().toISOString() };
                });
            } catch (e) { return []; }
        }

        function saveEntries(entries) {
            try { localStorage.setItem(STORAGE_KEY, JSON.stringify(entries)); } catch (e) {}
        }

        function getIds() {
            return getEntries().map(function (e) { return e.id; });
        }

        function toggle(pid) {
            var entries = getEntries();
            var found   = false;
            var next    = [];
            for (var i = 0; i < entries.length; i++) {
                if (String(entries[i].id) === String(pid)) { found = true; }
                else { next.push(entries[i]); }
            }
            if (!found) { next.push({ id: String(pid), added: new Date().toISOString() }); }
            saveEntries(next);
            return next.map(function (e) { return e.id; });
        }

        function removeEntry(pid) {
            var next = getEntries().filter(function (e) { return String(e.id) !== String(pid); });
            saveEntries(next);
            return next.map(function (e) { return e.id; });
        }

        function formatDate(iso) {
            try {
                var d = new Date(iso);
                return d.toLocaleDateString('es-ES', { year: 'numeric', month: 'long', day: 'numeric' });
            } catch (e) { return ''; }
        }

        function esc(str) {
            var d = document.createElement('div');
            d.appendChild(document.createTextNode(String(str)));
            return d.innerHTML;
        }

        /* ── Actualizar botones de corazón en toda la página ── */
        function updateButtons(ids) {
            document.querySelectorAll('.wishlist-btn[data-product-id]').forEach(function (btn) {
                var active = ids.indexOf(String(btn.dataset.productId)) !== -1;
                btn.classList.toggle('is-active', active);
                btn.setAttribute('aria-label', active ? lblRemove : lblAdd);
                var tx = btn.querySelector('.wishlist-btn__text');
                if (tx) tx.textContent = active ? lblRemove : lblAdd;
            });
        }

        /* ── Actualizar badge del header ── */
        function updateCount(ids) {
            var el = document.querySelector('.header-action__count--wishlist');
            if (el) el.textContent = ids.length;
        }

        /* ── Estado inicial ── */
        var ids = getIds();
        updateButtons(ids);
        updateCount(ids);

        /* ── Estado vacío en la página wishlist ── */
        function renderEmpty() {
            var cont = document.getElementById('wishlist-container');
            if (!cont) return;
            cont.innerHTML =
                '<div class="wishlist-empty">' +
                '<svg viewBox="0 0 24 24" width="56" height="56" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">' +
                '<path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>' +
                '</svg>' +
                '<p>' + esc(lblEmpty) + '</p>' +
                '<a href="' + esc(shopUrl) + '" class="button">' + esc(lblShop) + '</a>' +
                '</div>';
        }

        /* ── Construir tabla de productos ── */
        function buildTable(products) {
            var entries = getEntries();

            var html = '<div class="wishlist-table-wrap"><table class="wishlist-table">';
            html += '<thead><tr>';
            html += '<th>Producto</th><th>Precio</th><th>Fecha añadida</th>';
            html += '<th>Stock</th><th>Añadir al carrito</th><th></th>';
            html += '</tr></thead><tbody>';

            products.forEach(function (p) {
                var entry   = null;
                for (var i = 0; i < entries.length; i++) {
                    if (String(entries[i].id) === String(p.id)) { entry = entries[i]; break; }
                }
                var dateStr = entry ? formatDate(entry.added) : '';

                html += '<tr class="wishlist-row" data-product-id="' + esc(p.id) + '">';

                /* Producto */
                html += '<td class="wishlist-col-product">';
                html += '<a href="' + esc(p.url) + '" class="wishlist-product-link">';
                html += '<img src="' + esc(p.thumb) + '" alt="' + esc(p.title) + '" class="wishlist-thumb" width="70" height="70" loading="lazy">';
                html += '<span class="wishlist-product-name">' + esc(p.title) + '</span>';
                html += '</a></td>';

                /* Precio */
                html += '<td class="wishlist-col-price">' + p.price_html + '</td>';

                /* Fecha */
                html += '<td class="wishlist-col-date">' + esc(dateStr) + '</td>';

                /* Stock */
                var sClass = p.in_stock ? 'wishlist-stock--in' : 'wishlist-stock--out';
                html += '<td class="wishlist-col-stock"><span class="wishlist-stock ' + sClass + '">' + esc(p.stock_text) + '</span></td>';

                /* Carrito */
                html += '<td class="wishlist-col-cart">';
                if (p.in_stock) {
                    if (p.is_variable) {
                        html += '<a href="' + esc(p.url) + '" class="button wishlist-cart-btn">Ver opciones</a>';
                    } else {
                        html += '<a href="' + esc(p.cart_url) + '" data-product_id="' + esc(p.id) + '" rel="nofollow" ' +
                                'class="button ajax_add_to_cart add_to_cart_button wishlist-cart-btn">Añadir al carrito</a>';
                    }
                } else {
                    html += '<span class="wishlist-unavailable">No disponible</span>';
                }
                html += '</td>';

                /* Eliminar */
                html += '<td class="wishlist-col-remove">';
                html += '<button class="wishlist-remove-row" data-product-id="' + esc(p.id) + '" aria-label="Quitar de la lista">';
                html += '<svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">';
                html += '<line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>';
                html += '</svg></button></td>';

                html += '</tr>';
            });

            html += '</tbody></table></div>';
            return html;
        }

        /* ── Manejador global de clics ── */
        document.addEventListener('click', function (e) {

            /* Botón × de la tabla (siempre elimina) */
            var removeBtn = e.target.closest('.wishlist-remove-row[data-product-id]');
            if (removeBtn) {
                e.preventDefault();
                var pid    = removeBtn.dataset.productId;
                var newIds = removeEntry(pid);
                updateButtons(newIds);
                updateCount(newIds);
                var row = removeBtn.closest('.wishlist-row');
                if (row) {
                    row.style.transition = 'opacity 0.3s, transform 0.3s';
                    row.style.opacity    = '0';
                    row.style.transform  = 'scale(0.97)';
                    setTimeout(function () {
                        row.remove();
                        if (!document.querySelector('#wishlist-container .wishlist-row')) renderEmpty();
                    }, 300);
                }
                return;
            }

            /* Botón corazón (toggle) */
            var btn = e.target.closest('.wishlist-btn[data-product-id]');
            if (!btn) return;
            e.preventDefault();
            e.stopPropagation();

            var pid    = btn.dataset.productId;
            var newIds = toggle(pid);
            updateButtons(newIds);
            updateCount(newIds);

            if (newIds.indexOf(String(pid)) !== -1) {
                showToast('Agregado a lista de deseos', 'wishlist');
            }

            btn.classList.add('is-animating');
            btn.addEventListener('animationend', function () {
                btn.classList.remove('is-animating');
            }, { once: true });
        });

        /* ── Cargar productos en la página wishlist ── */
        var container = document.getElementById('wishlist-container');
        if (!container) return;

        var pageIds   = getIds();
        var pageNonce = container.dataset.nonce   || (ZZ.wishlistNonce || '');
        var pageAjax  = container.dataset.ajaxurl || ajaxUrl;

        if (!pageIds.length) { renderEmpty(); return; }

        var fd = new FormData();
        fd.append('action', 'zz_get_wishlist');
        fd.append('nonce',  pageNonce);
        fd.append('ids',    pageIds.join(','));

        fetch(pageAjax, { method: 'POST', body: fd })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                if (data && data.success && data.data.products && data.data.products.length) {
                    container.innerHTML = buildTable(data.data.products);
                    updateButtons(getIds());
                } else {
                    renderEmpty();
                }
            })
            .catch(function () { renderEmpty(); });
    }

    /* ── Búsqueda en tiempo real ─────────────────────────────────────────── */
    function initLiveSearch() {
        var input   = document.querySelector('.header-search__input');
        var wrapper = document.querySelector('.header-search-wrapper');
        if (!input || !wrapper) return;

        var ZZ          = window.ZZTHEME || {};
        var ajaxUrl     = ZZ.ajaxUrl     || '/wp-admin/admin-ajax.php';
        var nonce       = ZZ.searchNonce || '';
        var shopUrl     = ZZ.shopUrl     || '/';
        var searchLabel = ZZ.searchLabel || 'Ver todos los resultados';
        var emptyLabel  = ZZ.emptyLabel  || 'No se encontraron productos';
        var loadingLabel= ZZ.loadingLabel|| 'Buscando...';

        var timer       = null;
        var dropdown    = null;
        var lastQuery   = '';
        var controller  = null;

        function escHtml(str) {
            var d = document.createElement('div');
            d.appendChild(document.createTextNode(String(str)));
            return d.innerHTML;
        }

        function getDropdown() {
            if (!dropdown) {
                dropdown = document.createElement('div');
                dropdown.className = 'live-search-dropdown';
                dropdown.setAttribute('role', 'listbox');
                wrapper.appendChild(dropdown);
            }
            return dropdown;
        }

        function hide() {
            if (dropdown) dropdown.classList.remove('is-open');
        }

        function showLoading() {
            var dd = getDropdown();
            dd.innerHTML =
                '<div class="live-search-loading">' +
                '<span class="live-search-spinner"></span>' +
                escHtml(loadingLabel) +
                '</div>';
            dd.classList.add('is-open');
        }

        function showResults(results, q) {
            var dd = getDropdown();

            if (!results.length) {
                dd.innerHTML =
                    '<div class="live-search-empty">' +
                    escHtml(emptyLabel) + ' para "<strong>' + escHtml(q) + '</strong>"' +
                    '</div>';
                dd.classList.add('is-open');
                return;
            }

            var html = '<ul class="live-search-list" role="listbox">';
            results.forEach(function (item) {
                html +=
                    '<li class="live-search-item" role="option">' +
                    '<a href="' + escHtml(item.url) + '" class="live-search-link">' +
                    '<img src="' + escHtml(item.thumb) + '" alt="" class="live-search-thumb" width="44" height="44" loading="lazy">' +
                    '<div class="live-search-info">' +
                    '<span class="live-search-name">' + escHtml(item.title) + '</span>' +
                    (item.cat ? '<span class="live-search-cat">' + escHtml(item.cat) + '</span>' : '') +
                    '</div>' +
                    '<span class="live-search-price">' + item.price + '</span>' +
                    '</a></li>';
            });
            html += '</ul>';
            html +=
                '<a href="' + escHtml(shopUrl) + '?s=' + encodeURIComponent(q) + '&post_type=product" class="live-search-all">' +
                escHtml(searchLabel) + ' →' +
                '</a>';

            dd.innerHTML = html;
            dd.classList.add('is-open');
        }

        function doSearch(q) {
            if (q === lastQuery) return;
            lastQuery = q;

            if (controller) controller.abort();
            controller = ('AbortController' in window) ? new AbortController() : null;

            showLoading();

            var url = ajaxUrl + '?action=zz_live_search&nonce=' + encodeURIComponent(nonce) + '&q=' + encodeURIComponent(q);

            fetch(url, controller ? { signal: controller.signal } : {})
                .then(function (r) { return r.json(); })
                .then(function (data) {
                    if (data && data.success && data.data) {
                        showResults(data.data.results, q);
                    }
                })
                .catch(function (err) {
                    if (err && err.name === 'AbortError') return;
                    hide();
                });
        }

        input.addEventListener('input', function () {
            var q = input.value.trim();
            clearTimeout(timer);
            if (q.length < 2) {
                hide();
                lastQuery = '';
                if (controller) { controller.abort(); controller = null; }
                return;
            }
            timer = setTimeout(function () { doSearch(q); }, 300);
        });

        input.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') { hide(); input.blur(); }
        });

        input.addEventListener('focus', function () {
            if (input.value.trim().length >= 2 && dropdown && dropdown.querySelector('.live-search-list')) {
                dropdown.classList.add('is-open');
            }
        });

        document.addEventListener('click', function (e) {
            if (!wrapper.contains(e.target)) hide();
        });
    }

    /* ── Carrusel de productos ───────────────────────────────────────────── */
    function initCarousels() {
        document.querySelectorAll('.carousel-track').forEach(function (track) {
            var section = track.closest('.home-section--carousel');
            if (!section) return;

            var btnPrev = section.querySelector('.carousel-btn--prev');
            var btnNext = section.querySelector('.carousel-btn--next');

            function getSlideWidth() {
                var slide = track.querySelector('.carousel-slide');
                if (!slide) return 0;
                var gap = parseInt(getComputedStyle(track).gap, 10) || 20;
                return slide.offsetWidth + gap;
            }

            function updateButtons() {
                if (!btnPrev || !btnNext) return;
                btnPrev.disabled = track.scrollLeft <= 4;
                btnNext.disabled = track.scrollLeft >= track.scrollWidth - track.clientWidth - 4;
            }

            if (btnPrev) {
                btnPrev.addEventListener('click', function () {
                    track.scrollBy({ left: -getSlideWidth() * 2, behavior: 'smooth' });
                });
            }

            if (btnNext) {
                btnNext.addEventListener('click', function () {
                    track.scrollBy({ left: getSlideWidth() * 2, behavior: 'smooth' });
                });
            }

            track.addEventListener('scroll', updateButtons, { passive: true });
            updateButtons();
        });
    }

    /* ── Feedback al añadir al carrito ──────────────────────────────────── */
    function initCartFeedback() {
        if (!window.jQuery) return;

        window.jQuery(document.body).on('added_to_cart', function (e, fragments, hash, $btn) {
            var name = '';

            if ($btn && $btn.length) {
                var $li = $btn.closest('li.product');
                if ($li.length) {
                    name = $li.find('.product-card__title-link, .woocommerce-loop-product__name').first().text().trim();
                } else {
                    var titleEl = document.querySelector('.sp__title');
                    if (titleEl) name = titleEl.textContent.trim();
                }
            }

            if (name.length > 50) name = name.substring(0, 50) + '…';

            var safeD = document.createElement('div');
            safeD.appendChild(document.createTextNode(name));
            var safeName = safeD.innerHTML;

            var msg = name
                ? '<strong>' + safeName + '</strong> añadido al carrito'
                : 'Producto añadido al carrito';

            showToast(msg, 'success');
        });
    }

    /* ── Slider del hero ─────────────────────────────────────────────────── */
    function initHeroSlider() {
        var slider = document.querySelector('.hero-slider');
        if (!slider) return;

        var slides  = slider.querySelectorAll('.hero-slide');
        var dots    = slider.querySelectorAll('.hero-slider__dot');
        var btnPrev = slider.querySelector('.hero-slider__arrow--prev');
        var btnNext = slider.querySelector('.hero-slider__arrow--next');
        var total   = slides.length;

        if (total <= 1) return;

        var current  = 0;
        var timer    = null;
        var INTERVAL = parseInt(slider.dataset.autoplay, 10) || 5000;

        function goTo(n) {
            slides[current].classList.remove('is-active');
            slides[current].setAttribute('aria-hidden', 'true');
            if (dots[current]) {
                dots[current].classList.remove('is-active');
                dots[current].setAttribute('aria-current', 'false');
            }
            current = (n + total) % total;
            slides[current].classList.add('is-active');
            slides[current].setAttribute('aria-hidden', 'false');
            if (dots[current]) {
                dots[current].classList.add('is-active');
                dots[current].setAttribute('aria-current', 'true');
            }
        }

        function next() { goTo(current + 1); }
        function prev() { goTo(current - 1); }

        function startAuto() {
            stopAuto();
            timer = setInterval(next, INTERVAL);
        }
        function stopAuto() {
            if (timer) { clearInterval(timer); timer = null; }
        }

        if (btnNext) btnNext.addEventListener('click', function () { next(); startAuto(); });
        if (btnPrev) btnPrev.addEventListener('click', function () { prev(); startAuto(); });

        dots.forEach(function (dot, i) {
            dot.addEventListener('click', function () { goTo(i); startAuto(); });
        });

        slider.addEventListener('mouseenter', stopAuto);
        slider.addEventListener('focusin',    stopAuto);
        slider.addEventListener('mouseleave', startAuto);
        slider.addEventListener('focusout',   startAuto);

        slider.addEventListener('keydown', function (e) {
            if (e.key === 'ArrowLeft')  { prev(); startAuto(); }
            if (e.key === 'ArrowRight') { next(); startAuto(); }
        });

        var touchStartX = 0;
        slider.addEventListener('touchstart', function (e) {
            touchStartX = e.changedTouches[0].screenX;
        }, { passive: true });
        slider.addEventListener('touchend', function (e) {
            var dx = e.changedTouches[0].screenX - touchStartX;
            if (Math.abs(dx) > 50) { dx < 0 ? next() : prev(); startAuto(); }
        }, { passive: true });

        startAuto();
    }

    /* ── Dropdown de categorías en el header ─────────────────────────────── */
    function initCategoriesDropdown() {
        var btn   = document.querySelector('.header-categories-btn');
        var panel = document.querySelector('.header-cats__panel');
        if (!btn || !panel) return;

        function open() {
            btn.setAttribute('aria-expanded', 'true');
            panel.classList.add('is-open');
        }

        function close() {
            btn.setAttribute('aria-expanded', 'false');
            panel.classList.remove('is-open');
        }

        btn.addEventListener('click', function () {
            btn.getAttribute('aria-expanded') === 'true' ? close() : open();
        });

        document.addEventListener('click', function (e) {
            if (!e.target.closest('.header-cats')) close();
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') { close(); btn.focus(); }
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        initMobileMenu();
        initDarkMode();
        initStickyHeader();
        initSubmenus();
        initShopSidebar();
        initAccordions();
        initLazyLoad();
        initContactForm();
        initCouponToggle();
        initMiniCart();
        initQuantityButtons();
        initSidebarCategories();
        initCarousels();
        initWishlist();
        initLiveSearch();
        initCartFeedback();
        initCategoriesDropdown();
        initHeroSlider();
    });

})();
