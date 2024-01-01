/**
 * Media Stories
 * https://www.wpmediastories.com
 *
 * Licensed under the GPLv2+ license.
 */

window.WPMediaStories = window.WPMediaStories || {};

( function( window, document, $, plugin ) {
	var $c = {};

	plugin.gallery_id = '';
	plugin.current_id = '';
	plugin.title      = '';
	plugin.init = function() {
		plugin.cache();
		plugin.bindEvents();
	};

	plugin.cache = function() {
		$c.window = $( window );
		$c.body = $( document.body );
	};

	plugin.bindEvents = function() {
		jQuery( document ).on( 'click', '.wpms--inline-image-link', plugin.openGallery );
		jQuery( document ).on( 'click', '#um-gallery-modal .mfp-close', function( e ) {
			e.preventDefault();
		} );
	};

	plugin.openGallery = function( event ) {
		event.preventDefault();
		var id       = jQuery(this).data('media_id');

		plugin.gallery_id = id;
		plugin.title    = jQuery( this ).data('title');
		var image    = jQuery('#um-gallery-item-' + id).attr('href');
		var source   = document.getElementById("um_gallery_media").innerHTML;
		var template = Handlebars.compile(source);
		html         = template();
		window.WPMediaStoriesModal.open({
			src: '<div id="um-gallery-modal" class="um-gallery-popup" data-id="' + id + '">Loading icon</div>'
		});
		//jQuery('.um-user-gallery-image-wrap').css('background-image',  'url(' + image + ')');
		plugin._um_load_image( null );

		jQuery('body').addClass('gallery-open');
	};

	plugin._init_slider = function() {
		jQuery('.slides').slick({
			asNavFor: '.slide-content-rows'
		});
		jQuery( '.slide-content-rows' ).slick({
			arrows: false,
			appendDots: false,
			fade: true
		});
	};

	/**
	 * Load Image
	 *
	 * @param  {int} id
	 * @return {void}
	 */
	plugin._um_load_image = function( media_id ){
		if ( ! media_id || media_id === 'undefined' ) {
			//return false;
		}
		
		var gallery    = wp_media_stories[ plugin.gallery_id ];
		var id;
		if ( media_id === 'undefined' || media_id === null ) {
			media_id         = gallery.default_id;
		} else {
			media_id         = media_id;
		}

		var media      = gallery.photos[media_id];
		//caption.replace("\n", "<br />");
		var current
		var media_id   = media.media_id;
		var title      = media.title;
		var caption    = media.caption;
		var title      = media.title;
		var full_url   = media.full_url;
		var thumbnail  = media.thumbnail;
		var position   = media.position;
		var media_frame = '';


		// Get the HTML tmpl.
		var source   = document.getElementById("um_gallery_media").innerHTML;
		
		plugin.current_id = media_id;

		var type 	= media.type;
		var image 	= jQuery( '#um-gallery-item-' + id ).attr('data-source-url');
		if ( 'youtube' == type || 'vimeo' == type || 'hudl' === type  ) {
			var vid = plugin.um_gallery_get_video_type( image );
			if ( 'youtube' == type ) {
				video_id = vid.id;
				media_frame = '<iframe class="mfp-iframe" width="100%" src="//www.youtube.com/embed/' + video_id + '" frameborder="0" allowfullscreen></iframe>';
			} else if( 'vimeo' == type ) {
				video_id = vid.id;
				media_frame = '<iframe src="//player.vimeo.com/video/' + video_id + '" width="100%" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
			} else if( 'hudl' === type ) {
				video_id = vid.id;
				media_frame = '<iframe src="//www.hudl.com/embed/video/' + video_id + '" width="100%" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
			}
		}

		var data = {
			'album_title': plugin.title,
			'media_id': plugin.gallery_id,
			'caption': caption,
			'title': title,
			'type': type,
			'full_url': full_url,
			'thumbnail': thumbnail,
			'media_frame': media_frame,
			'position': position,
			'image': image,
			'media_items': gallery.photos,
			'total_media': gallery.total_media
		};
		var template = Handlebars.compile(source);

		html    = template( data );

		if ( jQuery( '#um-gallery-modal').length ) {
			jQuery( '#um-gallery-modal' ).replaceWith( html );
			plugin._init_slider();
		} else {
			window.WPMediaStoriesModal.open({
				src: html
			});
			plugin._init_slider();
		}
	}

	$( plugin.init );
}( window, document, jQuery, window.WPMediaStories ) );
