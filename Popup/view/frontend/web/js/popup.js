/**
 * Opens the CMS block of popup.phtml in a modal, once per visitor, and again after showAgainAfterDays.
 *
 * The time the popup was shown is kept in localStorage. It is browser-only state, so the server-rendered page stays
 * the same for every visitor and can be served from the full page cache.
 */
define([
    'jquery',
    'Magento_Ui/js/modal/modal'
], function ($, modal) {
    'use strict';

    var DAY_MS = 24 * 60 * 60 * 1000;

    /**
     * localStorage throws in some private modes and when storage is blocked; the popup is then simply not remembered.
     */
    function readShownAt(key) {
        try {
            return parseInt(window.localStorage.getItem(key), 10) || 0;
        } catch (e) {
            return 0;
        }
    }

    function saveShownAt(key) {
        try {
            window.localStorage.setItem(key, String(Date.now()));
        } catch (e) {
            // not remembered, see readShownAt()
        }
    }

    return function (config, element) {
        var shownAt = readShownAt(config.storageKey);

        if (shownAt && Date.now() - shownAt < config.showAgainAfterDays * DAY_MS) {
            return;
        }

        modal({
            type: 'popup',
            responsive: true,
            innerScroll: true,
            buttons: [],
            modalClass: 'm2-popup-modal'
        }, $(element));

        $(element).removeClass('no-display').modal('openModal');
        saveShownAt(config.storageKey);
    };
});
