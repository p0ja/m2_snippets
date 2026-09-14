/**
 * Changed: 'use strict', and the component is returned directly; the assignment to the undeclared
 * viewModelConstructor created a global variable (a ReferenceError in strict mode). The unused ko dependency
 * was removed.
 */
define([
    'uiElement'
], function (Element) {
    'use strict';

    return Element.extend({
        defaults: {
            template: 'M2_Knockout/m2_simple_template'
        }
    });
});
