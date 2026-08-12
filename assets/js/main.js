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
