/**
 * Epic Content Gallery
 * https://www.b4ucode.com
 *
 * Licensed under the GPLv2+ license.
 */

window.WPMediaStories = window.WPMediaStories || {};



( function( window, document, $, plugin ) {
	var $c = {};

	var n = $("#wp-media-stories-list-item li").length;
	var count = 0;
	var custom_file_frame;


	String.prototype.replaceAll = function(searchStr, replaceStr) {
		var str = this;

	    // no match exists in string?
	    if(str.indexOf(searchStr) === -1) {
	        // return string
	        return str;
	    }

	    // replace and remove first match, and do another recursirve search/replace
	    return (str.replace(searchStr, replaceStr)).replaceAll(searchStr, replaceStr);
	}

	plugin.init = function() {
		plugin.cache();
		plugin.bindEvents();
		plugin.init_gallery();
	};

	plugin.init_gallery = function() {
		tinymce.init({ selector: '.fgCaptionBox' });
		jQuery( '.wpms-item-count .wpms-item-count-counter').text( jQuery('.wp-media-stories-list-item').length );
	};

	plugin.cache = function() {
		$c.window = $( window );
		$c.body = $( document.body );
	};

	plugin.bindEvents = function() {
		$(document).on('click', '.wpms-remove', plugin.removeMedia );
		jQuery(document).on('click', '.ls_test_media', plugin.addMedia );
		$("#wp-media-stories-list").sortable({
			placeholder: "portlet-placeholder",
			handle: ".wpms-holder",
			change: function(event, ui) {
		      ui.placeholder.css({visibility: 'visible', border : '1px solid #e5e5e5', height: '100px'});
		    },
			start: function (event, ui) {
				$("#wp-media-stories-list-item li").css("opacity", "0.6");
				$(ui.item[0]).css("opacity", "1");
				$(ui.item[0]).addClass("highlight");
			},
			stop: function(event, ui) {
				var data = "";
				$("#wp-media-stories-list-item li").css("opacity", "1");                                
				$("#wp-media-stories-list-item li").removeClass("highlight");                                
				$("#wp-media-stories-list-item li.fg-sorter").each(function(i, el){
					var p = $(this).find("input[name='fg_pic_tem']").val();
					var cap = $(this).find("input[name='fg_caption']").val();
					data += p + "+caption=" + cap + ",";
				});
				data = data.substring(0, data.length - 1);
			}
		});
	};

	plugin.removeMedia = function( event ) {
		event.preventDefault();											
		$(this).closest('.wp-media-stories-list-item').slideUp( 600, function() {
			$(this).remove();
			plugin.init_gallery();
		});
	};

	plugin.addMedia = function( event ) {
		count = 0;
		event.preventDefault();
		//If the frame already exists, reopen it
		if (typeof(custom_file_frame)!=="undefined") {
			custom_file_frame.close();
		}

		//Create WP media frame.
		custom_file_frame = wp.media.frames.customHeader = wp.media({
			//Title of media manager frame
			title: "Add Media to the Gallery",
			library: {
				type: 'image'
			},
			button: {
				//Button text
				text: "Add Media(s)"
			},
			//Do not allow multiple files, if you want multiple, set true
			multiple: true
		});

		//callback for selected image
		custom_file_frame.on('select', function() {
			var selection = custom_file_frame.state().get('selection');
			var delIcon ='';
			selection.map(function(attachment) {
				attachment = attachment.toJSON();
				var n = jQuery("#wp-media-stories-list li").length;
				if(n == '0') {
					count = 0;
				}else{
					count = n + 1;			 
				}
				var content = jQuery('#wpms-tmpl').html();
				content = content.replaceAll( '{{id}}', attachment.id );
				content = content.replace( "{{title}}", attachment.title );
				content = content.replace( "{{caption}}", attachment.caption );
				content = content.replace( "{{image_url}}", attachment.sizes.thumbnail.url );
				jQuery( '#wp-media-stories-list' ).append( content ).slideDown('fast');
				plugin.init_gallery();
			});
		});
		//Open modal
		custom_file_frame.open();
	};

	$( plugin.init );
}( window, document, jQuery, window.WPMediaStories ) );