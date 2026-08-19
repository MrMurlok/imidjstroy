document.addEventListener('DOMContentLoaded', function () {

    const menuButton = document.querySelector(
        '.js-mobile-menu-toggle'
    );

    const mobileNav = document.querySelector(
        '.js-mobile-nav'
    );

    if (menuButton && mobileNav) {

        menuButton.addEventListener('click', function () {

            const isOpen = mobileNav.classList.toggle(
                'is-open'
            );

            menuButton.classList.toggle(
                'is-open',
                isOpen
            );

            menuButton.setAttribute(
                'aria-expanded',
                isOpen ? 'true' : 'false'
            );
        });

        mobileNav.querySelectorAll('a').forEach(function (link) {

            link.addEventListener('click', function () {

                mobileNav.classList.remove('is-open');
                menuButton.classList.remove('is-open');

                menuButton.setAttribute(
                    'aria-expanded',
                    'false'
                );
            });
        });
    }
});

/* =========================================================
   FOOTER / MAP MODAL
========================================================= */

document.addEventListener('DOMContentLoaded', function () {
    const modal = document.querySelector('.js-map-modal');
    const openButtons = document.querySelectorAll('.js-map-open');
    const closeButtons = document.querySelectorAll('.js-map-close');

    if (!modal || !openButtons.length) {
        return;
    }

    function openMapModal() {
        modal.classList.add('is-open');
        modal.setAttribute('aria-hidden', 'false');
        document.body.classList.add('map-modal-open');
    }

    function closeMapModal() {
        modal.classList.remove('is-open');
        modal.setAttribute('aria-hidden', 'true');
        document.body.classList.remove('map-modal-open');
    }

    openButtons.forEach(function (button) {
        button.addEventListener('click', openMapModal);
    });

    closeButtons.forEach(function (button) {
        button.addEventListener('click', closeMapModal);
    });

    document.addEventListener('keydown', function (event) {
        if (
            event.key === 'Escape' &&
            modal.classList.contains('is-open')
        ) {
            closeMapModal();
        }
    });
});

/* =========================================================
   HOME CATEGORIES CAROUSEL
========================================================= */

document.addEventListener('DOMContentLoaded', function () {
    const viewport = document.querySelector('.js-categories-viewport');
    const previous = document.querySelector('.js-categories-prev');
    const next = document.querySelector('.js-categories-next');

    if (!viewport || !previous || !next) {
        return;
    }

    function getStep() {
        const firstItem = viewport.querySelector('.home-categories__item');

        if (!firstItem) {
            return viewport.clientWidth;
        }

        return firstItem.getBoundingClientRect().width;
    }

    function updateButtons() {
        const maxScroll = viewport.scrollWidth - viewport.clientWidth;
        const position = viewport.scrollLeft;

        previous.disabled = position <= 2;
        next.disabled = position >= maxScroll - 2;
    }

    previous.addEventListener('click', function () {
        viewport.scrollBy({
            left: -getStep(),
            behavior: 'smooth'
        });
    });

    next.addEventListener('click', function () {
        const maxScroll = viewport.scrollWidth - viewport.clientWidth;

        if (viewport.scrollLeft >= maxScroll - 2) {
            viewport.scrollTo({
                left: 0,
                behavior: 'smooth'
            });
            return;
        }

        viewport.scrollBy({
            left: getStep(),
            behavior: 'smooth'
        });
    });

    viewport.addEventListener('scroll', updateButtons, { passive: true });
    window.addEventListener('resize', updateButtons);

    updateButtons();
});

/* =========================================================
   CONTACT FORM
========================================================= */

document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('.js-contact-form');
    const submit = document.querySelector('.js-contact-submit');
    const text = document.querySelector('.js-contact-submit-text');

    if (!form || !submit || !text) {
        return;
    }

    form.addEventListener('submit', function () {
        submit.disabled = true;
        text.textContent = 'Отправка...';
    });
});

/* =========================================================
   LEAF CATEGORY FILTERS
========================================================= */

document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('.js-category-products-filters');

    if (!form) {
        return;
    }

    const autosubmitFields = form.querySelectorAll('.js-category-filter-submit');

    autosubmitFields.forEach(function (field) {
        field.addEventListener('change', function () {
            form.requestSubmit();
        });
    });

    const range = form.querySelector('.js-category-price-range');

    if (!range) {
        return;
    }

    const minInput = range.querySelector('.js-category-range-min');
    const maxInput = range.querySelector('.js-category-range-max');
    const minHidden = range.querySelector('.js-category-price-min-hidden');
    const maxHidden = range.querySelector('.js-category-price-max-hidden');
    const fill = range.querySelector('.js-category-range-fill');
    const label = form.querySelector('.js-category-price-label');

    if (!minInput || !maxInput || !minHidden || !maxHidden || !fill || !label) {
        return;
    }

    const boundMin = Number(range.dataset.boundMin || 0);
    const boundMax = Number(range.dataset.boundMax || 0);

    function formatNumber(value) {
        return new Intl.NumberFormat('ru-RU', {
            maximumFractionDigits: 0
        }).format(value);
    }

    function updateRange(changedInput) {
        let minValue = Number(minInput.value);
        let maxValue = Number(maxInput.value);

        if (minValue > maxValue) {
            if (changedInput === minInput) {
                minValue = maxValue;
                minInput.value = String(minValue);
            } else {
                maxValue = minValue;
                maxInput.value = String(maxValue);
            }
        }

        minHidden.value = String(minValue);
        maxHidden.value = String(maxValue);

        const span = Math.max(1, boundMax - boundMin);
        const left = ((minValue - boundMin) / span) * 100;
        const right = 100 - ((maxValue - boundMin) / span) * 100;

        fill.style.left = left + '%';
        fill.style.right = right + '%';

        label.textContent = formatNumber(minValue) + ' – ' + formatNumber(maxValue) + ' ₽';
    }

    minInput.addEventListener('input', function () {
        updateRange(minInput);
    });

    maxInput.addEventListener('input', function () {
        updateRange(maxInput);
    });

    minInput.addEventListener('change', function () {
        form.requestSubmit();
    });

    maxInput.addEventListener('change', function () {
        form.requestSubmit();
    });

    updateRange(null);
});


/* =========================================================
   SINGLE PRODUCT PAGE
========================================================= */

document.addEventListener('DOMContentLoaded', function () {
    const backButton = document.querySelector('.js-product-back');

    if (!backButton) {
        return;
    }

    backButton.addEventListener('click', function () {
        const fallback = backButton.dataset.fallback || '/shop/';

        if (window.history.length > 1 && document.referrer) {
            window.history.back();
            return;
        }

        window.location.href = fallback;
    });
});

/* =========================================================
   CART
========================================================= */

document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('.imidjstroy-cart__form');

    if (!form) {
        return;
    }

    const updateButton = form.querySelector('.js-cart-update');
    let updateTimer = null;

    function submitCartUpdate() {
        if (!updateButton) {
            return;
        }

        window.clearTimeout(updateTimer);

        updateTimer = window.setTimeout(function () {
            updateButton.disabled = false;

            if (typeof form.requestSubmit === 'function') {
                form.requestSubmit(updateButton);
                return;
            }

            updateButton.click();
        }, 220);
    }

    form.querySelectorAll('.js-cart-quantity').forEach(function (control) {
        const input = control.querySelector('input.qty');
        const minus = control.querySelector('.js-cart-qty-minus');
        const plus = control.querySelector('.js-cart-qty-plus');

        if (!input) {
            return;
        }

        function getNumber(value, fallback) {
            const number = Number(value);
            return Number.isFinite(number) ? number : fallback;
        }

        function getStep() {
            return Math.max(getNumber(input.step, 1), 0.000001);
        }

        function setQuantity(nextValue) {
            const min = input.min === '' ? 0 : getNumber(input.min, 0);
            const max = input.max === '' ? Infinity : getNumber(input.max, Infinity);
            const step = getStep();

            let value = Math.max(min, Math.min(max, nextValue));
            value = Math.round(value / step) * step;

            input.value = String(value);
            input.dispatchEvent(new Event('change', { bubbles: true }));
            submitCartUpdate();
        }

        if (minus) {
            minus.addEventListener('click', function () {
                setQuantity(getNumber(input.value, 1) - getStep());
            });
        }

        if (plus) {
            plus.addEventListener('click', function () {
                setQuantity(getNumber(input.value, 0) + getStep());
            });
        }

        input.addEventListener('change', function () {
            submitCartUpdate();
        });
    });
});


/* =========================================================
   AJAX ADD TO CART UI
   ========================================================= */

document.addEventListener('DOMContentLoaded', function () {
    function normalizeAddedToCartLink(scope) {
        const root = scope && scope.querySelectorAll ? scope : document;

        root.querySelectorAll(
            '.product-card__actions .added_to_cart.wc-forward, ' +
            '.popular-product-card__actions .added_to_cart.wc-forward, ' +
            '.category-product-card__actions .added_to_cart.wc-forward'
        ).forEach(function (link) {
            link.textContent = 'В корзину';
            link.setAttribute('aria-label', 'Перейти в корзину');
        });
    }

    normalizeAddedToCartLink(document);

    /* WooCommerce uses jQuery events for AJAX add-to-cart. */
    if (window.jQuery) {
        window.jQuery(document.body).on(
            'added_to_cart',
            function (event, fragments, cartHash, $button) {
                if (!$button || !$button.length) {
                    normalizeAddedToCartLink(document);
                    return;
                }

                const button = $button.get(0);

                if (
                    !button.classList.contains('product-card__action--cart') &&
                    !button.classList.contains('popular-product-card__action--cart') &&
                    !button.classList.contains('category-product-card__button--cart')
                ) {
                    return;
                }

                button.setAttribute('aria-label', 'Товар добавлен в корзину');

                const link = button.nextElementSibling;

                if (
                    link &&
                    link.classList.contains('added_to_cart') &&
                    link.classList.contains('wc-forward')
                ) {
                    link.textContent = 'В корзину';
                    link.setAttribute('aria-label', 'Перейти в корзину');
                } else {
                    normalizeAddedToCartLink(button.parentElement || document);
                }
            }
        );
    }
});
