/**
 * Mixin for the mage/dropdown widget (registered in view/base/requirejs-config.js).
 *
 * Changed: removed alert(). The mixin is registered for the base area, so the alert blocked every admin and
 * storefront page that loads mage/dropdown; console.log() shows the same hook without interrupting the page.
 */
define(['jquery'], function ($) {
    'use strict';

    return function (originalWidget) {
        console.log('Our mixin is hooked up');

        // Redefine the named widget, using the original widget definition as the parent.
        $.widget('mage.dropdownDialog', $.mage.dropdownDialog, {
            /**
             * New code runs first, then the parent open() keeps the original behaviour.
             */
            open: function () {
                console.log('I opened a dropdown!');

                return this._super();
            }
        });

        return originalWidget;
    };
});
