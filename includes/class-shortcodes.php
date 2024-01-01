<?php
/**
 * WP Media Stories Shortcodes.
 *
 * @since   0.1
 * @package WP_Media_Stories
 */

/**
 * WP Media Stories Shortcodes.
 *
 * @since 0.1
 */
class WP_Media_Stories_Shortcodes {
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
		add_shortcode( 'wp_media_story_inline', array( $this, 'wpms_inline_handler' ) );
		add_shortcode( 'wp_media_story_galleries', array( $this, 'wp_media_story_galleries_handler' ) );
	}

	public function wpms_inline_handler( $atts = array() ) {

		 $a = shortcode_atts( array(
			'id'         => '',
		), $atts );

		$media_id = $a['id'];

		// Bail if no media ID set.
		if ( ! $media_id ) {
			return;
		}

		// Get the photos.
		$photo_data = get_post_meta( $media_id, 'wp_media_stories', true );

		// Bail if not photos found.
		if ( empty( $photo_data ) ) {
			return;
		}

		// Photos array.
		$photos = array();

		$default_id = 0;
		$index      = 1;
		foreach ( $photo_data as $sub_media_id => $media ) {
			// Set the default ID.
			if ( $index == 1 ) {
				$default_id = 'media_' . $sub_media_id;
			}
			$image_full_url = wp_get_attachment_image_src( $sub_media_id, 'large' );
			$image_thumb_url = wp_get_attachment_image_src( $sub_media_id, 'thumbnail' );
			$photos[ 'media_' . $sub_media_id ] = array(
				'media_id'    => $sub_media_id,
				'title'       => ! empty( $media['title'] ) ? $media['title'] : '',
				'caption'     => ! empty( $media['caption'] ) ? $media['caption'] : '',
				'full_url'    => $image_full_url[0],
				'thumbnail'   => $image_thumb_url[0],
				'position'    => $index,
				'total_media' => count( $photo_data )
			);
			//
			$index++;
		}
		// Output buffering
		ob_start();

		$featured_image = '';
		if ( has_post_thumbnail( $media_id ) ) {
			$featured_image = wp_get_attachment_url( get_post_thumbnail_id( $media_id ) );
		}
		$args = array(
			'media_id'       => $media_id,
			'main_image'     => $featured_image,
			'default_id'     => $default_id,
			'title'          => get_the_title( $media_id ),
			'caption'        => '',
			'show_caption'   => true,
			'show_title'     => false,
			'show_copyright' => true,
			'photos'         => $photos,
			'total_media'    => count( $photos )
		);
		$this->plugin->template->get_template( 'inline-gallery.php', $args );
		$content = ob_get_clean();
		return $content;
	}

	public function wp_media_story_galleries_handler( $atts = array() ) {
		$atts = shortcode_atts( array(
			'category'         => '',
			'exclude_category' => '',
			'tags'             => '',
			'exclude_tags'     => '',
			'author'           => false,
			'relation'         => 'OR',
			'number'           => 9,
			'excerpt'          => 'yes',
			'full_content'     => 'no',
			'columns'          => 3,
			'image_size'       => 'medium',
			'show_title'       => true,
			'show_image'       => true,
			'show_description' => true,
			'orderby'          => 'post_date',
			'order'            => 'DESC',
			'ids'              => '',
			'class'            => '',
			'pagination'       => 'true',
			'layout'           => 'grid',
		), $atts, 'wp_media_story_galleries' );

		$args = array(
			'pagination' => $atts['pagination'],
			'image_size' => $atts['image_size'],
		);

		if ( filter_var( $atts['show_title'], FILTER_VALIDATE_BOOLEAN ) ) {
			$args['show_title'] = true;
		} else {
			$args['show_title'] = false;
		}

		if ( filter_var( $atts['show_image'], FILTER_VALIDATE_BOOLEAN ) ) {
			$args['show_image'] = true;
		} else {
			$args['show_image'] = false;
		}

		if ( filter_var( $atts['show_description'], FILTER_VALIDATE_BOOLEAN ) ) {
			$args['show_description'] = true;
		} else {
			$args['show_description'] = false;
		}
		$query = array(
			'post_type'      => $this->plugin->post_type,
			'orderby'        => $atts['orderby'],
			'order'          => $atts['order']
		);

		if ( filter_var( $atts['pagination'], FILTER_VALIDATE_BOOLEAN ) || ( ! filter_var( $atts['pagination'], FILTER_VALIDATE_BOOLEAN ) && $atts[ 'number' ] ) ) {

			$query['posts_per_page'] = (int) $atts['number'];

			if ( $query['posts_per_page'] < 0 ) {
				$query['posts_per_page'] = abs( $query['posts_per_page'] );
			}
		} else {
			$query['nopaging'] = true;
		}

		if( 'random' == $atts['orderby'] ) {
			$atts['pagination'] = false;
		}

		switch ( $atts['orderby'] ) {

			case 'title':
				$query['orderby'] = 'title';
			break;

			case 'id':
				$query['orderby'] = 'ID';
			break;

			case 'random':
				$query['orderby'] = 'rand';
			break;

			case 'post__in':
				$query['orderby'] = 'post__in';
			break;

			default:
				$query['orderby'] = 'post_date';
			break;
		}

		if ( $atts['tags'] || $atts['category'] || $atts['exclude_category'] || $atts['exclude_tags'] ) {

			$query['tax_query'] = array(
				'relation' => $atts['relation']
			);

			if ( $atts['tags'] ) {

				$tag_list = explode( ',', $atts['tags'] );

				foreach( $tag_list as $tag ) {

					$t_id  = (int) $tag;
					$is_id = is_int( $t_id ) && ! empty( $t_id );

					if( $is_id ) {

						$term_id = $tag;

					} else {

						$term = get_term_by( 'slug', $tag, $this->plugin->tag );

						if( ! $term ) {
							continue;
						}

						$term_id = $term->term_id;
					}

					$query['tax_query'][] = array(
						'taxonomy' => $this->plugin->tag,
						'field'    => 'term_id',
						'terms'    => $term_id
					);
				}

			}

			if ( $atts['category'] ) {

				$categories = explode( ',', $atts['category'] );

				foreach( $categories as $category ) {

					$t_id  = (int) $category;
					$is_id = is_int( $t_id ) && ! empty( $t_id );

					if( $is_id ) {

						$term_id = $category;

					} else {

						$term = get_term_by( 'slug', $category, $this->plugin->category );

						if( ! $term ) {
							continue;
						}

						$term_id = $term->term_id;

					}

					$query['tax_query'][] = array(
						'taxonomy' => $this->plugin->category,
						'field'    => 'term_id',
						'terms'    => $term_id,
					);

				}

			}

			if ( $atts['exclude_category'] ) {

				$categories = explode( ',', $atts['exclude_category'] );

				foreach( $categories as $category ) {

					$t_id  = (int) $category;
					$is_id = is_int( $t_id ) && ! empty( $t_id );

					if( $is_id ) {

						$term_id = $category;

					} else {

						$term = get_term_by( 'slug', $category, $this->plugin->category );

						if( ! $term ) {
							continue;
						}

						$term_id = $term->term_id;
					}

					$query['tax_query'][] = array(
						'taxonomy' => $this->plugin->category,
						'field'    => 'term_id',
						'terms'    => $term_id,
						'operator' => 'NOT IN'
					);
				}

			}

			if ( $atts['exclude_tags'] ) {

				$tag_list = explode( ',', $atts['exclude_tags'] );

				foreach( $tag_list as $tag ) {

					$t_id  = (int) $tag;
					$is_id = is_int( $t_id ) && ! empty( $t_id );

					if( $is_id ) {

						$term_id = $tag;

					} else {

						$term = get_term_by( 'slug', $tag, $this->plugin->tag );

						if( ! $term ) {
							continue;
						}

						$term_id = $term->term_id;
					}

					$query['tax_query'][] = array(
						'taxonomy' => $this->plugin->tag,
						'field'    => 'term_id',
						'terms'    => $term_id,
						'operator' => 'NOT IN'
					);

				}

			}
		}

		if ( $atts['exclude_tags'] || $atts['exclude_category'] ) {
			$query['tax_query']['relation'] = 'AND';
		}

		if ( $atts['author'] ) {
			$authors = explode( ',', $atts['author'] );
			if ( ! empty( $authors ) ) {
				$author_ids = array();
				$author_names = array();

				foreach ( $authors as $author ) {
					if ( is_numeric( $author ) ) {
						$author_ids[] = $author;
					} else {
						$user = get_user_by( 'login', $author );
						if ( $user ) {
							$author_ids[] = $user->ID;
						}
					}
				}

				if ( ! empty( $author_ids ) ) {
					$author_ids      = array_unique( array_map( 'absint', $author_ids ) );
					$query['author'] = implode( ',', $author_ids );
				}
			}
		}

		if ( ! empty( $atts['ids'] ) ) {
			$query['post__in'] = explode( ',', $atts['ids'] );
		}

		if ( get_query_var( 'paged' ) ) {
			$query['paged'] = get_query_var('paged');
		} else if ( get_query_var( 'page' ) ) {
			$query['paged'] = get_query_var( 'page' );
		} else {
			$query['paged'] = 1;
		}


		// Allow the query to be manipulated by other plugins
		$query = apply_filters( 'wpms_galleries_query', $query, $atts );
		
		$galleries = new WP_Query( $query );

		do_action( 'wpms_galleries_list_before', $atts );
		
		ob_start();

		if ( $galleries->have_posts() ) :
			$i = 1;
			$columns_class   = array( 'wpms_gallery_columns_' . $atts['columns'] );
			$custom_classes  = array_filter( explode( ',', $atts['class'] ) );
			$wrapper_classes = array_unique( array_merge( $columns_class, $custom_classes ) );
			$wrapper_classes = implode( ' ', $wrapper_classes );
		?>

			<div class="wpms_galleries_list wpms_gallery_list_<?php echo esc_attr( $atts['layout'] ); ?> <?php echo apply_filters( 'wpms_galleries_list_wrapper_class', $wrapper_classes, $atts ); ?>">

				<?php do_action( 'wpms_galleries_list_top', $atts, $galleries ); ?>

				<?php while ( $galleries->have_posts() ) : $galleries->the_post(); ?>
					<?php
						$featured_image = '';
						if ( has_post_thumbnail( $media_id ) ) {
							$featured_image = wp_get_attachment_url( get_post_thumbnail_id( $media_id ) );
						}
						$args['main_image'] = $featured_image;
					?>
					<?php $this->plugin->template->get_template( 'content-gallery-item.php', $args ); //do_action( 'wpms_galleries_shortcode_item', $atts, $i ); ?>
				<?php $i++; endwhile; ?>

				<?php wp_reset_postdata(); ?>

				<?php do_action( 'wpms_galleries_list_bottom', $atts ); ?>

			</div>

			<?php
			
		else:
			echo __( 'No galleries found', 'wp-media-stories' );
		endif;

		do_action( 'wpms_galleries_list_after', $atts, $galleries, $query );

		$display = ob_get_clean();

		return apply_filters( 'wp_media_story_galleries', $display, $atts, $query );

		$wp_query_args = apply_filters( 'wp_media_story_galleries_query_args', $wp_query_args );
		$the_query     = new WP_Query( $wp_query_args );

		$args = array(
			'show_pagination' => $a['show_pagination'],
		);
		ob_start();
		$this->plugin->template->get_template( 'galleries.php', $args );
		$content = ob_get_clean();
		return $content;
	}
}
