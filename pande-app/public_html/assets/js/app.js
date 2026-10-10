// ============================================================================
// PANDE COMPUTERS - INTERACTIVE FRONTEND APPLICATION JS
// High-Trust E-Commerce Shopping Experience & Resilient Session Cart
// ============================================================================

document.addEventListener('DOMContentLoaded', function() {
    initSessionSync();
    initAddToCart();
    initCartToastContainer();
    initEmptyCartAutoRestore();
    initCartLinkInterceptors();
});

/**
 * Synchronize session ID across meta tags, localStorage, and query strings
 */
function initSessionSync() {
    const urlParams = new URLSearchParams(window.location.search);
    const urlSid = urlParams.get('sid') || urlParams.get('_session_id');
    const metaSid = document.querySelector('meta[name="session-id"]')?.getAttribute('content');
    
    // Priority: URL sid > Meta sid > stored sid
    if (urlSid && urlSid.length >= 16) {
        localStorage.setItem('pande_session_id', urlSid);
    } else if (metaSid && metaSid.length >= 16) {
        const stored = localStorage.getItem('pande_session_id');
        if (!stored) {
            localStorage.setItem('pande_session_id', metaSid);
        }
    }

    updateCartLinksWithSession();
}

/**
 * Ensure all Cart and Checkout navigation links retain active session ID
 */
function updateCartLinksWithSession() {
    const sid = localStorage.getItem('pande_session_id');
    if (!sid) return;

    document.querySelectorAll('a[href*="/cart"], a[href*="/checkout"]').forEach(a => {
        try {
            const rawHref = a.getAttribute('href');
            if (!rawHref || rawHref.startsWith('#') || rawHref.startsWith('javascript:')) return;

            const url = new URL(rawHref, window.location.origin);
            if ((url.pathname.endsWith('/cart') || url.pathname.endsWith('/checkout')) && !url.searchParams.has('sid')) {
                url.searchParams.set('sid', sid);
                a.setAttribute('href', url.pathname + url.search);
            }
        } catch (_) {}
    });
}

/**
 * Intercept clicks on cart links as extra safety net
 */
function initCartLinkInterceptors() {
    document.addEventListener('click', function(e) {
        const link = e.target.closest('a[href*="/cart"], a[href*="/checkout"]');
        if (!link) return;

        const sid = localStorage.getItem('pande_session_id');
        if (!sid) return;

        try {
            const href = link.getAttribute('href');
            if (href && !href.includes('sid=')) {
                const url = new URL(href, window.location.origin);
                url.searchParams.set('sid', sid);
                link.setAttribute('href', url.pathname + url.search);
            }
        } catch (_) {}
    }, true);
}

/**
 * If customer visits /cart and finds an empty cart, auto-restore using session ID or local backup
 */
function initEmptyCartAutoRestore() {
    const isCartPage = window.location.pathname.endsWith('/cart');
    if (!isCartPage) return;

    const emptyContainer = document.getElementById('empty-cart-container');
    if (!emptyContainer) return;

    const sid = localStorage.getItem('pande_session_id');
    const urlParams = new URLSearchParams(window.location.search);
    const currentUrlSid = urlParams.get('sid');

    // Case 1: Page loaded without ?sid= parameter, but we have a valid stored session ID
    if (sid && (!currentUrlSid || currentUrlSid !== sid)) {
        urlParams.set('sid', sid);
        window.location.replace(window.location.pathname + '?' + urlParams.toString());
        return;
    }

    // Case 2: Session empty on server, but localStorage has cached cart items
    const rawLocalItems = localStorage.getItem('pande_cart_items');
    if (rawLocalItems) {
        try {
            const cachedItems = JSON.parse(rawLocalItems);
            const itemList = Object.values(cachedItems);
            if (itemList.length > 0) {
                syncLocalCartToServer(itemList);
            }
        } catch (_) {}
    }
}

/**
 * Synchronize local cart backup to server if session was reset
 */
function syncLocalCartToServer(items) {
    const sid = localStorage.getItem('pande_session_id');
    const metaCsrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    
    const formData = new FormData();
    formData.set('items', JSON.stringify(items));
    if (metaCsrf) formData.set('csrf_token', metaCsrf);
    if (sid) formData.set('_session_id', sid);

    fetch('/cart/sync' + (sid ? ('?sid=' + encodeURIComponent(sid)) : ''), {
        method: 'POST',
        body: formData,
        credentials: 'include',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            ...(sid ? { 'X-Session-ID': sid } : {}),
            ...(metaCsrf ? { 'X-CSRF-Token': metaCsrf } : {})
        }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success && data.count > 0) {
            const targetUrl = '/cart' + (sid ? ('?sid=' + encodeURIComponent(sid)) : '');
            window.location.replace(targetUrl);
        }
    })
    .catch(() => {});
}

// Toast notification container element
let toastContainer = null;

function initCartToastContainer() {
    if (!document.querySelector('.cart-toast-container')) {
        toastContainer = document.createElement('div');
        toastContainer.className = 'cart-toast-container';
        toastContainer.setAttribute('aria-live', 'polite');
        document.body.appendChild(toastContainer);
    } else {
        toastContainer = document.querySelector('.cart-toast-container');
    }
}

/**
 * Display interactive floating toast notification when product is added
 */
function showCartToast(data) {
    if (!toastContainer) {
        initCartToastContainer();
    }

    const toast = document.createElement('div');
    toast.className = 'cart-toast shadow-lg';
    
    const productName = data.product_name || 'Selected Item';
    const productPrice = data.product_price || '';
    const productImage = data.product_image || '/assets/images/products/laptop.svg';
    const subtotal = data.subtotal || '';
    const count = data.count || 1;
    const sid = localStorage.getItem('pande_session_id');
    const cartUrl = '/cart' + (sid ? ('?sid=' + encodeURIComponent(sid)) : '');

    toast.innerHTML = `
        <div class="d-flex align-items-start gap-3">
            <div class="border rounded p-1 bg-light text-center flex-shrink-0" style="width: 52px; height: 52px; display: flex; align-items: center; justify-content: center;">
                <img src="${productImage}" alt="${productName}" style="max-width: 44px; max-height: 44px; object-fit: contain;">
            </div>
            <div class="flex-grow-1" style="min-width: 0;">
                <div class="d-flex align-items-center justify-content-between mb-1">
                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-0.5" style="font-size: 0.72rem;">
                        <i class="bi bi-check2-circle me-1"></i> Added to Cart
                    </span>
                    <button type="button" class="btn-close btn-close-sm p-1" style="font-size: 0.65rem;" aria-label="Close"></button>
                </div>
                <div class="fw-bold text-dark small text-truncate mb-1" title="${productName}">
                    ${productName}
                </div>
                <div class="small text-muted" style="font-size: 0.75rem;">
                    ${productPrice ? `<span class="fw-semibold text-dark">${productPrice}</span> &bull; ` : ''}
                    Cart: <strong>${count} ${count === 1 ? 'item' : 'items'}</strong> (${subtotal})
                </div>
            </div>
        </div>
        <div class="d-flex gap-2 mt-3 pt-2 border-top">
            <a href="${cartUrl}" class="btn btn-primary btn-sm fw-bold flex-grow-1 text-center py-1.5" style="font-size: 0.8rem;">
                <i class="bi bi-bag-check me-1"></i> View Cart &amp; Checkout &rarr;
            </a>
            <button type="button" class="btn btn-outline-secondary btn-sm toast-dismiss-btn px-2.5 py-1.5" style="font-size: 0.8rem;">
                Continue
            </button>
        </div>
    `;

    const dismissToast = () => {
        toast.classList.add('toast-hiding');
        setTimeout(() => toast.remove(), 300);
    };

    toast.querySelector('.btn-close').addEventListener('click', dismissToast);
    toast.querySelector('.toast-dismiss-btn').addEventListener('click', dismissToast);

    const autoTimer = setTimeout(dismissToast, 5000);
    toast.addEventListener('mouseenter', () => clearTimeout(autoTimer));

    toastContainer.appendChild(toast);
}

/**
 * Display error toast if cart operation fails
 */
function showErrorToast(message) {
    if (!toastContainer) {
        initCartToastContainer();
    }

    const toast = document.createElement('div');
    toast.className = 'cart-toast border-danger-subtle shadow-lg';
    toast.innerHTML = `
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-exclamation-circle-fill text-danger fs-5"></i>
            <div class="flex-grow-1 small fw-semibold text-dark">
                ${message || 'Unable to update cart. Please try again.'}
            </div>
            <button type="button" class="btn-close btn-close-sm" aria-label="Close"></button>
        </div>
    `;

    const dismissToast = () => {
        toast.classList.add('toast-hiding');
        setTimeout(() => toast.remove(), 300);
    };

    toast.querySelector('.btn-close').addEventListener('click', dismissToast);
    setTimeout(dismissToast, 4500);
    toastContainer.appendChild(toast);
}

/**
 * Handle Add to Cart submission dynamically across all product cards and views
 */
function initAddToCart() {
    document.addEventListener('submit', function(e) {
        const form = e.target;
        if (!form || !form.classList.contains('add-to-cart-form')) {
            return;
        }

        e.preventDefault();

        const btn = form.querySelector('button[type="submit"]');
        if (!btn || btn.disabled) {
            return;
        }

        const originalHtml = btn.innerHTML;
        const originalWidth = btn.offsetWidth ? `${btn.offsetWidth}px` : 'auto';

        btn.style.minWidth = originalWidth;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status"></span> Adding...';
        btn.disabled = true;

        const formData = new FormData(form);
        formData.set('ajax', '1');

        const rawAction = form.getAttribute('action') || form.action || '/cart/add';
        let actionUrl = rawAction;
        try {
            if (rawAction.startsWith('http://') || rawAction.startsWith('https://')) {
                const parsed = new URL(rawAction);
                actionUrl = parsed.pathname + parsed.search;
            }
        } catch (_) {}

        const storedSessionId = localStorage.getItem('pande_session_id');
        if (storedSessionId && !formData.has('_session_id')) {
            formData.set('_session_id', storedSessionId);
        }

        // Attach sid query param to actionUrl as well
        if (storedSessionId && !actionUrl.includes('sid=')) {
            const sep = actionUrl.includes('?') ? '&' : '?';
            actionUrl += sep + 'sid=' + encodeURIComponent(storedSessionId);
        }

        const headers = {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        };
        if (storedSessionId) {
            headers['X-Session-ID'] = storedSessionId;
        }

        const metaCsrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        const formCsrf = form.querySelector('input[name="csrf_token"]')?.value;
        const csrfToken = formCsrf || metaCsrf;
        if (csrfToken) {
            headers['X-CSRF-Token'] = csrfToken;
            if (!formData.has('csrf_token')) {
                formData.set('csrf_token', csrfToken);
            }
        }

        fetch(actionUrl, {
            method: 'POST',
            body: formData,
            credentials: 'include',
            headers: headers
        })
        .then(res => {
            const sid = res.headers.get('X-Session-ID');
            if (sid) {
                localStorage.setItem('pande_session_id', sid);
            }
            return res.json().then(data => ({
                status: res.status,
                ok: res.ok,
                data: data
            })).catch(() => ({
                status: res.status,
                ok: res.ok,
                data: null
            }));
        })
        .then(result => {
            if (result.ok && result.data && result.data.success) {
                const data = result.data;
                const newSid = data.sessionId || localStorage.getItem('pande_session_id');
                if (newSid) {
                    localStorage.setItem('pande_session_id', newSid);
                }

                // Cache cart locally for resilient fallback
                try {
                    const cached = JSON.parse(localStorage.getItem('pande_cart_items') || '{}');
                    const pid = String(formData.get('product_id') || 'item');
                    const qty = parseInt(String(formData.get('quantity') || '1'), 10);
                    cached[pid] = {
                        product_id: parseInt(pid, 10),
                        quantity: (cached[pid] ? cached[pid].quantity : 0) + qty,
                        name: data.product_name,
                        price: data.product_price,
                        image: data.product_image
                    };
                    localStorage.setItem('pande_cart_items', JSON.stringify(cached));
                } catch (_) {}

                // Button visual feedback: green checkmark
                btn.innerHTML = '<i class="bi bi-check2 me-1"></i> Added!';
                btn.classList.remove('btn-primary', 'btn-outline-primary');
                btn.classList.add('btn-success');

                // 1. Update all header cart count badges
                document.querySelectorAll('.cart-count-badge').forEach(badge => {
                    badge.textContent = data.count;
                    badge.classList.remove('cart-bump');
                    void badge.offsetWidth;
                    badge.classList.add('cart-bump');
                });

                // 2. Update header cart subtotal
                if (data.subtotal) {
                    document.querySelectorAll('.header-cart-subtotal').forEach(el => {
                        el.textContent = data.subtotal;
                    });
                }

                // 3. Header cart icon wiggle
                document.querySelectorAll('.header-cart-icon').forEach(icon => {
                    icon.classList.remove('cart-bump');
                    void icon.offsetWidth;
                    icon.classList.add('cart-bump');
                });

                // 4. Update all cart links on the page with session ID
                updateCartLinksWithSession();

                // 5. Show prominent floating cart confirmation toast
                showCartToast(data);

                // Reset button state smoothly after 2 seconds
                setTimeout(() => {
                    btn.innerHTML = originalHtml;
                    btn.classList.remove('btn-success');
                    btn.classList.add('btn-primary');
                    btn.disabled = false;
                }, 2000);
            } else {
                const errorMsg = (result.data && result.data.message) ? result.data.message : 'Item could not be added.';
                showErrorToast(errorMsg);
                btn.innerHTML = originalHtml;
                btn.disabled = false;
            }
        })
        .catch(err => {
            console.error('[Add to Cart Error]', err);
            btn.innerHTML = originalHtml;
            btn.disabled = false;
            // Native fallback submit only if fetch is blocked
            form.submit();
        });
    });
}
