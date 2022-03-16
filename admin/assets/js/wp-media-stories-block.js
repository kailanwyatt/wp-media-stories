
/**
 * Hello World: Step 1
 *
 * Simple block, renders and saves the same content without interactivity.
 *
 * Using inline styles - no external stylesheet needed.  Not recommended
 * because all of these styles will appear in `post_content`.
 */
( function( blocks, editor, i18n, element, components, _ ) {
	var el = element.createElement;
	var RichText = editor.RichText;
	var MediaUpload = editor.MediaUpload;
	console.log( editor );
	var blockStyle = {
		backgroundColor: '#900',
		color: '#fff',
		padding: '20px',
	};

	blocks.registerBlockType( 'wp-media-stories/gallery-block', {
		title: i18n.__( 'WP Media Stories: Embed', 'wp-media-stories' ),
		icon: 'universal-access-alt',
		category: 'layout',
		attributes: {
			title: {
				type: 'array',
				source: 'children',
				selector: 'h2',
			},
			mediaID: {
				type: 'number',
			},
			mediaURL: {
				type: 'string',
				source: 'attribute',
				selector: 'img',
				attribute: 'src',
			},
			galleriesPicker: {
				type: 'array',
				source: 'children',
				selector: '.galleries',
			},
			instructions: {
				type: 'array',
				source: 'children',
				selector: '.steps',
			},
		},
		edit: function( props ) {
			var attributes = props.attributes;

			var onSelectImage = function( media ) {
				return props.setAttributes( {
					mediaURL: media.url,
					mediaID: media.id,
				} );
			};

			return (
				el( 'div', { className: props.className },
					el( RichText, {
						tagName: 'h2',
						inline: true,
						placeholder: i18n.__( 'Write Recipe title…', 'gutenberg-examples' ),
						value: attributes.title,
						onChange: function( value ) {
							props.setAttributes( { title: value } );
						},
					} ),
					el( 'div', { className: 'recipe-image-2' },
						el( components.SelectControl, {
							className: 'galleries'
							/*options: [
						        { value: 'small', label: i18n.__( 'Small' ) },
						        { value: 'normal', label: i18n.__( 'Normal' ) },
						        { value: 'medium', label: i18n.__( 'Medium' ) },
						        { value: 'large', label: i18n.__( 'Large' ) },
						    ]*/
						})
					)
				)
			);
		},
		save: function() {
			return el(
				'p',
				{ style: blockStyle },
				'Hello World, step 1 (from the frontend).'
			);
		},
	} );
}(
	window.wp.blocks,
	window.wp.editor,
	window.wp.i18n,
	window.wp.element,
	window.wp.components,
	window._,
) );
