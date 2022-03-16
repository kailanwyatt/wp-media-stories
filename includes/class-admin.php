<?php
/**
 * WP Media Stories Admin.
 *
 * @since   0.1
 * @package WP_Media_Stories
 */

/**
 * WP Media Stories Admin.
 *
 * @since 0.1
 */
class WP_Media_Stories_Admin {
	/**
	 * Parent plugin class.
	 *
	 * @since 0.1
	 *
	 * @var   WP_Media_Stories
	 */
	protected $plugin = null;

	/**
	 * Slug of the plugin screen.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_screen_hook_suffix = null;

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
		add_action( 'init', array( $this, 'init' ), 1 );
		//add_action( 'admin_menu', array( $this, 'register_admin_menu' ) );
		add_action( 'add_meta_boxes', array( $this, 'register_metabox' ) );
		add_action( 'save_post', array( $this, 'save_metabox' ), 10, 2 ); 
		// Load admin style sheet and JavaScript.
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );

		add_action( 'wpms_metabox_settings', array( $this, 'wpms_metabox_settings' ), 10, 1 );
		add_action( 'wpms_save_meta', array( $this, 'wpms_save_meta' ), 10, 1 );
		
		//add_action( 'init', array( $this, 'create_book_taxonomies' ), 1000 );
	}

	// create two taxonomies, genres and writers for the post type "book"
public function create_book_taxonomies() {
	// Add new taxonomy, make it hierarchical (like categories)
	$labels = array(
		'name'              => _x( 'Genres', 'taxonomy general name', 'textdomain' ),
		'singular_name'     => _x( 'Genre', 'taxonomy singular name', 'textdomain' ),
		'search_items'      => __( 'Search Genres', 'textdomain' ),
		'all_items'         => __( 'All Genres', 'textdomain' ),
		'parent_item'       => __( 'Parent Genre', 'textdomain' ),
		'parent_item_colon' => __( 'Parent Genre:', 'textdomain' ),
		'edit_item'         => __( 'Edit Genre', 'textdomain' ),
		'update_item'       => __( 'Update Genre', 'textdomain' ),
		'add_new_item'      => __( 'Add New Genre', 'textdomain' ),
		'new_item_name'     => __( 'New Genre Name', 'textdomain' ),
		'menu_name'         => __( 'Genre', 'textdomain' ),
	);

	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'genre' ),
	);

	register_taxonomy( 'genre', array( $this->plugin->post_type ), $args );

	// Add new taxonomy, NOT hierarchical (like tags)
	$labels = array(
		'name'                       => _x( 'Writers', 'taxonomy general name', 'textdomain' ),
		'singular_name'              => _x( 'Writer', 'taxonomy singular name', 'textdomain' ),
		'search_items'               => __( 'Search Writers', 'textdomain' ),
		'popular_items'              => __( 'Popular Writers', 'textdomain' ),
		'all_items'                  => __( 'All Writers', 'textdomain' ),
		'parent_item'                => null,
		'parent_item_colon'          => null,
		'edit_item'                  => __( 'Edit Writer', 'textdomain' ),
		'update_item'                => __( 'Update Writer', 'textdomain' ),
		'add_new_item'               => __( 'Add New Writer', 'textdomain' ),
		'new_item_name'              => __( 'New Writer Name', 'textdomain' ),
		'separate_items_with_commas' => __( 'Separate writers with commas', 'textdomain' ),
		'add_or_remove_items'        => __( 'Add or remove writers', 'textdomain' ),
		'choose_from_most_used'      => __( 'Choose from the most used writers', 'textdomain' ),
		'not_found'                  => __( 'No writers found.', 'textdomain' ),
		'menu_name'                  => __( 'Writers', 'textdomain' ),
	);

	$args = array(
		'hierarchical'          => false,
		'labels'                => $labels,
		'show_ui'               => true,
		'show_admin_column'     => true,
		'update_count_callback' => '_update_post_term_count',
		'query_var'             => true,
		'rewrite'               => array( 'slug' => 'writer' ),
	);

	register_taxonomy( 'writer', $this->plugin->post_type, $args );
}

	public function init() {
		$labels = array(
			'name'               => _x( 'Gallery', 'post type general name', 'wp-media-stories' ),
			'singular_name'      => _x( 'Gallery', 'post type singular name', 'wp-media-stories' ),
			'menu_name'          => _x( 'Gallery', 'admin menu', 'wp-media-stories' ),
			'name_admin_bar'     => _x( 'Gallery', 'add new on admin bar', 'wp-media-stories' ),
			'add_new'            => _x( 'Add New', 'gallery', 'wp-media-stories' ),
			'add_new_item'       => __( 'Add New Gallery', 'wp-media-stories' ),
			'new_item'           => __( 'New Gallery', 'wp-media-stories' ),
			'edit_item'          => __( 'Edit Gallery', 'wp-media-stories' ),
			'view_item'          => __( 'View Gallery', 'wp-media-stories' ),
			'all_items'          => __( 'All Galleries', 'wp-media-stories' ),
			'search_items'       => __( 'Search Galleries', 'wp-media-stories' ),
			'parent_item_colon'  => __( 'Parent Galleries:', 'wp-media-stories' ),
			'not_found'          => __( 'No galleries found.', 'wp-media-stories' ),
			'not_found_in_trash' => __( 'No galleries found in Trash.', 'wp-media-stories' )
		);

		$args = array(
			'labels'             => $labels,
			'description'        => __( 'Media Stories.', 'wp-media-stories' ),
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => true,
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => null,
			'menu_icon'          => 'dashicons-format-gallery',
			'supports'           => array( 'title', 'thumbnail' )
		);

		register_post_type( $this->plugin->post_type, $args );

		$labels = array(
			'name'              => _x( 'Categories', 'taxonomy general name', 'wp-media-stories' ),
			'singular_name'     => _x( 'Category', 'taxonomy singular name', 'wp-media-stories' ),
			'search_items'      => __( 'Search Categories', 'wp-media-stories' ),
			'all_items'         => __( 'All Categories', 'wp-media-stories' ),
			'parent_item'       => __( 'Parent Category', 'wp-media-stories' ),
			'parent_item_colon' => __( 'Parent Category:', 'wp-media-stories' ),
			'edit_item'         => __( 'Edit Category', 'wp-media-stories' ),
			'update_item'       => __( 'Update Category', 'wp-media-stories' ),
			'add_new_item'      => __( 'Add New Category', 'wp-media-stories' ),
			'new_item_name'     => __( 'New Category Name', 'wp-media-stories' ),
			'menu_name'         => __( 'Category', 'wp-media-stories' ),
		);

		$args = array(
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array( 'slug' => 'media_story_category' ),
		);

		register_taxonomy( $this->plugin->category, array( $this->plugin->post_type ), $args );

		$labels = array(
			'name'                       => _x( 'Tags', 'taxonomy general name', 'wp-media-stories' ),
			'singular_name'              => _x( 'Tag', 'taxonomy singular name', 'wp-media-stories' ),
			'search_items'               => __( 'Search Tags', 'wp-media-stories' ),
			'popular_items'              => __( 'Popular Tags', 'wp-media-stories' ),
			'all_items'                  => __( 'All Tags', 'wp-media-stories' ),
			'parent_item'                => null,
			'parent_item_colon'          => null,
			'edit_item'                  => __( 'Edit Tag', 'wp-media-stories' ),
			'update_item'                => __( 'Update Tag', 'wp-media-stories' ),
			'add_new_item'               => __( 'Add New Tag', 'wp-media-stories' ),
			'new_item_name'              => __( 'New Tag Name', 'wp-media-stories' ),
			'separate_items_with_commas' => __( 'Separate tags with commas', 'wp-media-stories' ),
			'add_or_remove_items'        => __( 'Add or remove tags', 'wp-media-stories' ),
			'choose_from_most_used'      => __( 'Choose from the most used tags', 'wp-media-stories' ),
			'not_found'                  => __( 'No tags found.', 'wp-media-stories' ),
			'menu_name'                  => __( 'Tags', 'wp-media-stories' ),
		);

		$args = array(
			'hierarchical'          => false,
			'labels'                => $labels,
			'show_ui'               => true,
			'show_admin_column'     => true,
			'update_count_callback' => '_update_post_term_count',
			'query_var'             => true,
			'rewrite'               => array( 'slug' => 'media_story_tag' ),
		);

		register_taxonomy( $this->plugin->tag, $this->plugin->post_type, $args );
	}

	public function register_admin_menu() {
		add_submenu_page(
			'edit.php?post_type=' . $this->plugin->post_type,
			__( 'Settings', 'wp-media-stories' ),
			__( 'Settings', 'wp-media-stories' ),
			'manage_options',
			'epc-settings',
			array( $this, 'setup_settings_page' )
		);
	}

	public function setup_settings_page() {
		$this->include_admin_template( 'admin/templates/settings' );
	}

	public function register_metabox() {
		add_meta_box( 'wpms-media-meta-box', __( 'Media', 'wp-media-stories' ), array( $this, 'media_metabox_output' ), $this->plugin->post_type );
		add_meta_box( 'wpms-media-meta-box-side', __( 'Settings', 'wp-media-stories' ), array( $this, 'media_settings_metabox_output' ), $this->plugin->post_type, 'side', 'default' );
	}

	public function media_metabox_output( $post = array() ) {
		$photos  = get_post_meta( $post->ID, 'wp_media_stories', true );
		$post_id = $post->ID;
		if ( empty( $photos ) ) {
			$photos = array();
		}
		$count   = count( $photos );
		include_once( wp_media_stories()->dir() . 'admin/templates/metabox.php' );
	}

	public function metabox_shortcode_output( $post = array() ) {
		include_once( wp_media_stories()->dir() . 'admin/templates/metabox-shortcode.php' );
	}
	public function media_settings_metabox_output( $post = array() ) {
		include_once( wp_media_stories()->dir() . 'admin/templates/metabox-settings.php' );
	}

	public function include_admin_template(  $filename ) {
		$file = wp_media_stories()->dir( $filename . '.php' );
		if ( file_exists( $file ) ) {
			include_once( $file );
			return;
		}
		return false;
	}

	/**
	 * Metabox Setting.
	 * 
	 * @param  integer $post_id Post ID.
	 *
	 * @since 0.1
	 */
	public function wpms_metabox_settings( $post_id = 0 ) {
		$hide_title   = get_post_meta( $post_id, '_wp_media_stories_hide_title', true );
		$hide_desc    = get_post_meta( $post_id, '_wp_media_stories_hide_description', true );
		$media_size   = get_post_meta( $post_id, '_wp_media_stories_size', true );
		$hide_counter = get_post_meta( $post_id, '_wp_media_stories_hide_counter', true );
		$sizes        = get_intermediate_image_sizes();
	?>
		<p>
			<label for="wpms_hide_title"><input type="checkbox" name="wpms_hide_title" value="1" <?php checked( $hide_title, 1 ); ?>><?php esc_html_e( 'Hide Title', 'wp-media-stories' ); ?></label>
		</p>
		<p>
			<label for="wpms_hide_description"><input type="checkbox" name="wpms_hide_description" value="1" <?php checked( $hide_desc, 1 ); ?>><?php esc_html_e( 'Hide Description', 'wp-media-stories' ); ?></label>
		</p>
		<p>
			<label for="wpms_hide_counter"><input type="checkbox" name="wpms_hide_counter" value="1" <?php checked( $hide_counter, 1 ); ?>><?php esc_html_e( 'Hide Counter', 'wp-media-stories' ); ?></label>
		</p>
		<p>
			<label for="wpms_stories_size"><?php esc_html_e( 'Thumbnail Size', 'wp-media-stories' ); ?></label>
			<select name="wpms_stories_size" id="wpms_stories_size" class="postbox">
				<option value="full" <?php selected( $media_size, 'full' ); ?>><?php esc_html_e( 'Full (Default)', 'wp-media-stories' ); ?></option>
				<?php if ( ! empty( $sizes ) ) : ?>
					<?php foreach ( $sizes as $size ) : ?>
					<option value="<?php echo esc_attr( $size ); ?>" <?php selected( $media_size, $size ); ?>><?php echo esc_html( $size ); ?></option>
					<?php endforeach; ?>
				<?php endif; ?>
			</select>
		</p>
	<?php
	}

	/**
	 * Register and enqueue admin-specific style sheet.
	 *
	 * @since     1.0.0
	 *
	 * @return    null    Return early if no settings page is registered.
	 */
	public function enqueue_admin_styles() {

		$screen = get_current_screen();
		if ( $screen->id == $this->plugin->post_type ) {
			wp_enqueue_style( $this->plugin->plugin_slug .'-admin-styles', wp_media_stories()->url( 'admin/assets/css/wp-media-stories.css' ), array(), WP_Media_Stories::VERSION );
		}
	}

	/**
	 * Register and enqueue admin-specific Javascript.
	 *
	 * @since     1.0.0
	 *
	 * @return    null    Return early if no settings page is registered.
	 */
	public function enqueue_admin_scripts() {

		$screen = get_current_screen();

		if ( $screen->id != $this->plugin->post_type ) {
			return;
		}
		wp_enqueue_script( 'wp_enqueue_media'  );
		
		add_thickbox();
		wp_enqueue_script( 'iris' );
		
		wp_register_script( 'wpms-tinymce-js', includes_url( 'js/tinymce/' ) . 'wp-tinymce.php', array( 'jquery' ), false, true );
		wp_register_script( $this->plugin->plugin_slug . '-admin-script',  wp_media_stories()->url( 'admin/assets/js/wp-media-stories.js' ), array( 'jquery','jquery-ui-core','jquery-ui-sortable', 'jquery-ui-draggable', 'wpms-tinymce-js' ), WP_Media_Stories::VERSION );
		wp_localize_script(
			$this->plugin->plugin_slug . '-admin-script', 
			'myAjax', 
			array(
				'ajaxurl'   => admin_url( 'admin-ajax.php'),
				'loader'    => wp_media_stories()->url( 'admin/assets/images/loading.gif' ),
				'mcebutton' => wp_media_stories()->url( 'admin/assets/images/tinymce_button.png' )
			)
		);
		wp_enqueue_script( 'tinymce_js', includes_url( 'js/tinymce/' ) . 'wp-tinymce.php', array( 'jquery' ), false, true );
		wp_enqueue_script( $this->plugin->plugin_slug . '-admin-script');
	}

	public function save_metabox( $post_id = 0, $post = array() ) {	

		// Dont' save meta boxes for revisions or autosaves.
		if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || is_int( wp_is_post_revision( $post ) ) || is_int( wp_is_post_autosave( $post ) ) ) {
			return;
		}

		// Check the nonce.
		if ( empty( $_POST['wp_media_verification_nonce'] ) || ! wp_verify_nonce( $_POST['wp_media_verification_nonce'], 'wp_media_verification' ) ) {
			return;
		}

		// Check the post being saved == the $post_id to prevent triggering this call for other save_post events.
		if ( empty( $_POST['post_ID'] ) || $_POST['post_ID'] != $post_id ) {
			return;
		}

		// Check user has permission to edit.
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		// Check if the POST is set.
		if ( isset( $_POST['wp_media_stories'] ) ) {
			// Secondly we need to check if the user intended to change this value.
			$meta_value = isset( $_POST['wp_media_stories'] ) ? 
							wp_unslash( $_POST['wp_media_stories'] ) :
							array();
			update_post_meta( $post_id, 'wp_media_stories', $meta_value );
		} else {
			delete_post_meta( $post_id, 'wp_media_stories' );
		}

		/**
		 * After Save Meta
		 *
		 * @var  $post_id Post ID.
		 */
		do_action( 'wpms_save_meta', $post_id );
	}

	public function wpms_save_meta( $post_id = 0 ) {
		if ( isset( $_POST['wpms_hide_title'] ) ) {
			$meta_value = isset( $_POST['wpms_hide_title'] ) ? 
							absint( $_POST['wpms_hide_title'] ) :
							'';
			update_post_meta( $post_id, '_wp_media_stories_hide_title', $meta_value );
		} else {
			delete_post_meta( $post_id, '_wp_media_stories_hide_title' );
		}

		if ( isset( $_POST['wpms_hide_description'] ) ) {
			$meta_value = isset( $_POST['wpms_hide_description'] ) ? 
							absint( $_POST['wpms_hide_description'] ) :
							'';
			update_post_meta( $post_id, '_wp_media_stories_hide_description', $meta_value );
		} else {
			delete_post_meta( $post_id, '_wp_media_stories_hide_description' );
		}

		if ( isset( $_POST['wpms_hide_counter'] ) ) {
			$meta_value = isset( $_POST['wpms_hide_counter'] ) ? 
							absint( $_POST['wpms_hide_counter'] ) :
							'';
			update_post_meta( $post_id, '_wp_media_stories_hide_counter', $meta_value );
		} else {
			delete_post_meta( $post_id, '_wp_media_stories_hide_counter' );
		}

		if ( isset( $_POST['wpms_stories_size'] ) ) {
			$meta_value = isset( $_POST['wpms_stories_size'] ) ? 
							sanitize_text_field( $_POST['wpms_stories_size'] ) :
							'';
			update_post_meta( $post_id, '_wp_media_stories_size', $meta_value );
		} else {
			delete_post_meta( $post_id, '_wp_media_stories_size' );
		}

		
	}
	public function get_meta_field( $field = array(), $post_id = '' ) {
		$field['value'] = '';
		if ( $post_id ) {
			$value = get_post_meta( $post_id, $field['name'], true );
			$field['value'] = $value;
		}
		?>
		<tr>
			<th><label><?php echo esc_html( $field['title'] ); ?></label></th>
			<?php
			switch ( $field['type'] ) {
				case 'checkbox':
				?>
				<td><input type="checkbox" name="<?php echo esc_attr( $field['name'] ); ?>" value=""></td>
				<?php
				break;
				case 'text':
				?>
				<td><input type="text" id="<?php echo esc_attr( $field['id'] ); ?>" name="<?php echo esc_attr( $field['name'] ); ?>" value="<?php echo esc_attr( $field['value'] ); ?>" /></td>
				<?php
				break;
				case 'text':
				case 'editor':
				?>
				<td>
					<textarea id="<?php echo esc_attr( $field['id'] ); ?>" name="<?php echo esc_attr( $field['name'] ); ?>"><?php echo esc_html( $field['value'] ); ?></textarea>
				<?php
				break;
				default:
			}
			?>
		</tr>
		<?php
	}
}
