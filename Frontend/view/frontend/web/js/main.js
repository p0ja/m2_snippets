/**
 * Added: replaces view/frontend/scripts/main.js and scripts/require.js (a standalone RequireJS entry point).
 * Started with data-mage-init in content.phtml, it receives its config and element and loads the helper through
 * the "helper_main" alias from requirejs-config.js. The message is written with textContent (plain text, not HTML)
 * instead of alert().
 */
define(['helper_main'], function (helperMain) {
    'use strict';

    return function (config, element) {
        element.textContent = helperMain.getMessage();
    };
});
