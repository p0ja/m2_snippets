/**
 * Added: AMD helper module, moved from view/frontend/scripts/helper/main.js. Files outside web/ are never deployed
 * as static content. The original returned 0 instead of the object, so getMessage() was never available.
 */
define([], function () {
    'use strict';

    return {
        /**
         * @returns {String}
         */
        getMessage: function () {
            return 'helper script.js';
        }
    };
});
