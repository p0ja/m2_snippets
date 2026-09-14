/**
 * Claims a code with an AJAX POST when the visitor clicks the widget button.
 * Initialised by data-mage-init in widget/codes.phtml, which passes the controller URL.
 */
define([
    'jquery',
    'mage/translate',
    'mage/cookies'
], function ($, $t) {
    'use strict';

    return function (config, element) {
        var $widget = $(element),
            $button = $widget.find('[data-role="claim"]'),
            $message = $widget.find('[data-role="message"]');

        function showMessage(text) {
            $message.text(text).prop('hidden', !text);
        }

        $button.on('click', function () {
            $button.prop('disabled', true);
            showMessage('');

            $.ajax({
                url: config.url,
                type: 'POST',
                dataType: 'json',
                // The page itself may come from the full page cache, so the form key is read from its cookie.
                data: {'form_key': $.mage.cookies.get('form_key')}
            }).done(function (response) {
                if (!response.code) {
                    showMessage(response.message);

                    return;
                }

                // text(), not html(): the code is data, never markup.
                $widget.find('[data-role="code"]').text(response.code);
                $widget.find('[data-role="result"]').prop('hidden', false);
                $button.remove();
            }).fail(function () {
                showMessage($t('Something went wrong. Please try again later.'));
            }).always(function () {
                $button.prop('disabled', false);
            });
        });
    };
});
