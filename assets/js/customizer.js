/**
 * Live preview del Customizer — postMessage handlers.
 * Cargado solo dentro del iframe del Customizer.
 */
(function (wp) {
    'use strict';

    /* Esquema de color del nav */
    wp.customize('zz_nav_style', function (setting) {
        setting.bind(function (value) {
            var nav = document.querySelector('.site-header__nav');
            if (!nav) return;
            if (value === 'light') {
                nav.classList.add('site-header__nav--light');
                nav.setAttribute('data-nav-style', 'light');
            } else {
                nav.classList.remove('site-header__nav--light');
                nav.setAttribute('data-nav-style', 'dark');
            }
        });
    });

    /* Color primario — actualiza la variable CSS en vivo */
    wp.customize('zz_color_primary', function (setting) {
        setting.bind(function (value) {
            document.documentElement.style.setProperty('--zz-primary', value);
        });
    });

    /* Color oscuro — actualiza la variable CSS en vivo */
    wp.customize('zz_color_dark', function (setting) {
        setting.bind(function (value) {
            document.documentElement.style.setProperty('--zz-dark', value);
        });
    });

}(wp));
