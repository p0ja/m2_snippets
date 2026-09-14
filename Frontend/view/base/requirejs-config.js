/**
 * Changed: Magento merges the "config" variable of every requirejs-config.js into one file. The self-executing
 * function called require.config() itself and showed alert("Done") on every admin and storefront page (base
 * area), and its "helper_main" path pointed to a file that did not exist.
 * The map entry is an alias: any module can depend on "helper_main".
 */
var config = {
    map: {
        '*': {
            'helper_main': 'M2_Frontend/js/helper/main'
        }
    }

    /*
    paths: {
        'jquery.cookie': 'Package_Module/path/to/jquery.cookie.min'
    },
    shim: { // 'shim' makes sure jquery is completely loaded first, see http://requirejs.org/docs/api.html#config-shim
        'jquery.cookie': {
            'deps': ['jquery']
        }
    }
    */
};
