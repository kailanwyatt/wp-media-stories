<?php
/**
 * WP Media Stories Template.
 *
 * @since   0.1
 * @package WP_Media_Stories
 */

/**
 * WP Media Stories Template.
 *
 * @since 0.1
 */
class WP_Media_Stories_Template {
	/**
	 * Parent plugin class.
	 *
	 * @since 0.1
	 *
	 * @var   WP_Media_Stories
	 */
	protected $plugin = null;

	/**
	 * Constructor.
	 *
	 * @since  0.1
	 *
	 * @param  WP_Media_Stories $plugin Main plugin object.
	 */
	public function __construct( $plugin ) {
		$this->plugin = $plugin;
		$this->hooks();
	}

	/**
	 * Initiate our hooks.
	 *
	 * @since  0.1
	 */
	public function hooks() {
		add_filter( 'the_content', array( $this, 'single_album_view' ), 12, 1 );
		
		// Load public-facing style sheet and JavaScript.
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
	}

	/**
	 * Setup Single View for an Album.
	 * 
	 * @param  string $content Post Content
	 *
	 * @since  1.0.0
	 *
	 * @return string
	 */
	public function single_album_view( $content ) {
		if ( is_singular( $this->plugin->post_type ) ) {
			ob_start();
			$photos        = get_post_meta( get_the_ID(), 'wp_media_stories', true );
			$hide_title    = get_post_meta( get_the_ID(), '_wp_media_stories_hide_title', true );
			$hide_desc     = get_post_meta( get_the_ID(), '_wp_media_stories_hide_description', true );
			$hide_counter  = get_post_meta( get_the_ID(), '_wp_media_stories_hide_counter', true );
			$media_size    = get_post_meta( get_the_ID(), '_wp_media_stories_size', true );

			
			$args = array(
				'show_caption'   => $hide_desc ?  : true,
				'show_title'     => $hide_title ? false : true,
				'show_counter'   => $hide_counter ? false : true,
				'show_copyright' => true,
				'media_size'     => $media_size ? $media_size : 'full',
				'photos'         => $photos,
				'total_items'    => count( $photos ),
				'index'          => 1,
			);

			/**
			 * Before Album View.
			 *
			 * @param  $args
			 */
			do_action( 'wpms_before_album_view', $args );
			/**
			 * Main Album Content.
			 */
			$this->get_template( 'content-single-album.php', $args );
			/**
			 * After Album View.
			 *
			 * @param  $args
			 */
			do_action( 'wpms_after_album_view', $args );
			$content = ob_get_clean();
		}
		return $content;
	}

	/**
	 * Register and enqueue public-facing style sheet.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';
		wp_enqueue_style( $this->plugin->plugin_slug . '-plugin-styles', wp_media_stories()->url( 'assets/css/media-stories' . $suffix . '.css' ), array(), WP_Media_Stories::VERSION );
	}

	/**
	 * Register and enqueues public-facing JavaScript files.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';
		$suffix = '';
		wp_enqueue_script( 'jquery-masonry','jquery' );
		wp_enqueue_script( $this->plugin->plugin_slug . '-plugin-script', wp_media_stories()->url( 'assets/js/media-stories' . $suffix . '.js' ) , array( 'jquery' ), WP_Media_Stories::VERSION );
	}
	
	function hex2rgb($hex) {
		$hex = str_replace("#", "", $hex);
	
		if( strlen($hex) == 3) {
		  $r = hexdec(substr($hex,0,1).substr($hex,0,1));
		  $g = hexdec(substr($hex,1,1).substr($hex,1,1));
		  $b = hexdec(substr($hex,2,1).substr($hex,2,1));
	   } else {
		  $r = hexdec(substr($hex,0,2));
		  $g = hexdec(substr($hex,2,2));
		  $b = hexdec(substr($hex,4,2));
	   }
	   $rgb = array($r, $g, $b);
	   //return implode(",", $rgb); // returns the rgb values separated by commas
	   return $rgb; // returns an array with the rgb values
	}

	/**
	 * Gets and includes template files.
	 *
	 * @since 1.0.0
	 * @param mixed  $template_name
	 * @param array  $args (default: array())
	 * @param string $template_path (default: '')
	 * @param string $default_path (default: '')
	 */
	function get_template( $template_name, $args = array(), $template_path = 'wp_media_stories', $default_path = '' ) {
		if ( $args && is_array( $args ) ) {
			extract( $args );
		}
		include( $this->locate_template( $template_name, $template_path, $default_path ) );
	}

	/**
	 * Locates a template and return the path for inclusion.
	 *
	 * This is the load order:
	 *
	 *		yourtheme		/	$template_path	/	$template_name
	 *		yourtheme		/	$template_name
	 *		$default_path	/	$template_name
	 *
	 * @since 1.0.0
	 * @param string      $template_name
	 * @param string      $template_path (default: 'job_manager')
	 * @param string|bool $default_path (default: '') False to not load a default
	 * @return string
	 */
	function locate_template( $template_name, $template_path = 'wp_media_stories', $default_path = '' ) {
		// Look within passed path within the theme - this is priority
		$template = locate_template(
			array(
				trailingslashit( $template_path ) . $template_name,
				$template_name
			)
		);

		// Get default template
		if ( ! $template && $default_path !== false ) {
			$default_path = $default_path ? $default_path : wp_media_stories()->dir() . '/templates/';
			if ( file_exists( trailingslashit( $default_path ) . $template_name ) ) {
				$template = trailingslashit( $default_path ) . $template_name;
			}
		}

		// Return what we found
		return apply_filters( 'WP_Media_Stories_locate_template', $template, $template_name, $template_path );
	}
	/**
	 *  Load the template files from within tutorpress/templates/ or the the theme if overrided within the theme.
	 *
	 * @since 0.0.1
	 * @param string $slug slug.
	 * @param string $name default: ''.
	 *
	 * @return void
	 */
	public static function get_part( $slug, $name = '' ) {

		$template = '';
		$plugin_template_url = wp_media_stories()->url;
		$plugin_template_path = wp_media_stories()->dir() . 'templates/';

		// Look in yourtheme/slug-name.php and yourtheme/um_classifieds/slug-name.php.
		if ( $name ) {

			$template = locate_template( array( "{$slug}-{$name}.php", "{$plugin_template_url}{$slug}-{$name}.php" ) );

		}

		// Get default slug-name.php.
		if ( ! $template && $name && file_exists( $plugin_template_path . "{$slug}-{$name}.php" ) ) {

			$template = $plugin_template_path . "{$slug}-{$name}.php";

		}

		if ( ! $template && file_exists( $plugin_template_path . "{$slug}.php" ) ) {
			$template = $plugin_template_path . "{$slug}.php";
		}
		// If template file doesn't exist, look in yourtheme/slug.php and yourtheme/um_classifieds/slug.php.
		if ( ! $template ) {

			$template = locate_template( array( "{$slug}.php", "{$plugin_template_url}{$slug}.php" ) );

		}

		if ( $template ) {
			include( $template );
		}
	} // end get part
}
