<?php wp_nonce_field( 'wp_media_verification', 'wp_media_verification_nonce' ); ?>
<div class="wpms-actions">
	<span class="wpms-status">
		<span class="wpms-item-count">
			<?php printf( _n( '<span class="wpms-item-count-counter">%d</span> item', '<span class="wpms-item-count-counter">%d</span> items', $count, 'wp-media-stories' ), number_format_i18n( $count ) ); ?>
		</span>
	</span>
	<span class="wpms-action-button">
		<?php esc_html_e( 'Add the following shortcode to posts to embed gallery', 'wp-media-stories' ); ?>
		<span class="wpms-shortcode-embed">[wp_media_story_inline id='<?php echo $post->ID; ?>']</span>
		<input type="button" class="ls_test_media button button-primary button-large"  value="<?php esc_attr_e( 'Add Media','wp-media-stories' ); ?>" />
	</span>
</div>
<div class="egc-tabs">
	<!-- photos tab -->
	<div class="egc-tab" id="photos">
		<ul id="wp-media-stories-list" class="wp-media-stories-list">
		<?php
		if ( ! empty( $photos ) ) :
			$i = 0;
			foreach ( $photos as $id => $photo ) {
			$image_attributes = wp_get_attachment_image_src( $id,'thumbnail' );
			$thumb = $image_attributes[0];
		?>
		<li class="wp-media-stories-list-item">
			<div class="wp-media-stories-list-content wp-media-stories-list-content-row">
				<div class="wp-media-stories-list-actions">
					<div class="wp-media-stories-options">
						<a href="javascript:;" class="wpms-remove">
							<span class="dashicons dashicons-trash"></span>
						</a>
						<span class="dashicons dashicons-move wpms-holder"></span>
					</div>
				</div>
				<div class="wp-media-stories-list-image">
					<img src="<?php echo esc_attr( $thumb ); ?>" class="thumb-src" />
				</div>
				<div class="wp-media-stories-list-details">
					<div class="wp-media-stories-list-field">
						<div class="wp-media-stories-list-field-input wp-media-stories-list-field-title">
							<input type="text" name="wp_media_stories[<?php echo absint( $id ); ?>][title]" class="fgTitle" value="<?php echo esc_attr( $photo['title'] ); ?>" placeholder="<?php esc_attr_e( 'Title', 'wp-media-stories' ); ?>" />
						</div>
					</div>
					<div class="wp-media-stories-list-field">
						<div class="wp-media-stories-list-field-input wpms-caption-field">
						<textarea name="wp_media_stories[<?php echo absint( $id ); ?>][caption]" class="fgCaptionBox"><?php echo esc_html( $photo['caption'] ); ?></textarea>
						</div>
					</div>
					<input type="hidden" name="wp_media_stories[<?php echo absint( $id ); ?>][id]" value="<?php echo esc_attr( $id ); ?>" />
				</div>
			</div>
		</li>
			<?php
			$i++;
			}
			endif;
			?>
		</ul>
	</div>
	<!-- end photos tab -->
</div>
<script type="text/html" id="wpms-tmpl">
	<li class="wp-media-stories-list-item">
		<div class="wp-media-stories-list-content">
			<div class="wp-media-stories-list-actions">
				<div class="wp-media-stories-options">
					<a href="javascript:;" class="wpms-remove">
						<span class="dashicons dashicons-trash"></span>
					</a>
					<span class="dashicons dashicons-move wpms-holder"></span>
				</div>
			</div>
			<div class="wp-media-stories-list-image">
				<img src="{{image_url}}" class="thumb-src" />
			</div>
			<div class="wp-media-stories-list-details">
				<div class="wp-media-stories-list-field">
					<div class="wp-media-stories-list-field-input wp-media-stories-list-field-title">
						<input type="text" name="wp_media_stories[{{id}}][title]" class="fgTitle" value="{{title}}" placeholder="<?php esc_attr_e( 'Title', 'wp-media-stories' ); ?>" />
					</div>
				</div>
				<div class="wp-media-stories-list-field">
					<div class="wp-media-stories-list-field-input wpms-caption-field">
					<textarea name="wp_media_stories[{{id}}][caption]" class="fgCaptionBox">{{caption}}</textarea>
					</div>
				</div>
				<input type="hidden" name="wp_media_stories[{{id}}][id]" value="{{id}}" />
			</div>
		</div>
	</li>
</script>