<?php
class WP_Media_Stories_Editor_Gutenberg {

	public function __construct() {
		$this->hooks();
	}
	/**
	 * Initiate our hooks.
	 *
	 * @since  0.1
	 */
	public function hooks() {
		add_action( 'init', array( $this, 'column_block_cgb_editor_assets' ) );
	}

	public function column_block_cgb_editor_assets(){

		if ( ! function_exists( 'register_block_type' ) ) {
			// Gutenberg is not active.
			return;
		}
	    // Scripts.
	    wp_register_script(
	        'wp-media-stories-block-js', // Handle.
	        wp_media_stories()->url( 'admin/assets/js/wp-media-stories-block.js' ),
	        array('wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor')
	    );

	    // Styles.
	    wp_register_style(
	        'wp-media-stories-block-css', // Handle.
	        wp_media_stories()->url( 'admin/assets/css/wp-media-stories-block.css' ),
	        array('wp-edit-blocks')
	    );

	    wp_register_script(
	        'gutenberg-boilerplate-es5-step01',
	        plugins_url( 'step-01/block.js', __FILE__ ),
	        array( 'wp-blocks', 'wp-element' )
	    );

	    register_block_type( 'wp-media-stories/gallery-block', array(
	        'editor_script' => 'wp-media-stories-block-js',
	        'editor_style'  => 'wp-media-stories-block-css',
	    ) );
	} // End function column_block_cgb_editor_assets().
}

new WP_Media_Stories_Editor_Gutenberg();