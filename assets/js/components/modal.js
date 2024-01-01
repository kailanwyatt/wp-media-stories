/**
 * Media Stories
 * https://www.wpmediastories.com
 *
 * Licensed under the GPLv2+ license.
 */

window.WPMediaStoriesModal = window.WPMediaStoriesModal || {};

( function( window, document, $, plugin ) {
    plugin.modalId = '#myModal';
    plugin.modal = jQuery('#myModal');
    plugin.init = function() {
        plugin.modal = jQuery('#myModal');
        plugin.closeButton = plugin.modal.find(".close");
        plugin.addEventListeners();
	};

    plugin.addEventListeners = function() {
        // Close when the close button is clicked
        jQuery(document).on('click', '#myModal .close', plugin.close);

        // Close when clicking outside the modal
        $(window).on('click', function(event) {
            if ($(event.target).is( jQuery( plugin.modalId ))) {
                plugin.close();
            }
        });
    };

    plugin.renderHtml = function() {
        jQuery('body').prepend(
            '<div id="myModal" class="modal">' +
            '<div class="modal-content">' +
              '<span class="close">&times;</span>' +
              '<div class="modal-content-inner"></div>'+
            '</div>' +
          '</div>'
        );
    };

    plugin.open = function(data) {
        plugin.renderHtml();
        var modal = jQuery(plugin.modalId);
        var src = data.src || '<span class="close">×</span>';
        modal.find('.modal-content-inner').html( data.src );
        modal.show();
    };

    plugin.close = function() {
        var modal = jQuery(plugin.modalId);
        modal.fadeOut().remove();
    };

    $( plugin.init );

}( window, document, jQuery, window.WPMediaStoriesModal ) );