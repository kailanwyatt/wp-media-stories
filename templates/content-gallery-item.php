<div class="wpms--gallery-item">
	<?php if ( $show_image ) { ?>
	<div class="wpms--gallery-item-image">
		<a href="<?php the_permalink(); ?>" title="<?php esc_attr( get_the_title() ); ?>"><?php the_post_thumbnail( $image_size ); ?></a>
	</div>
	<?php } ?>
	<?php if ( $show_title ) { ?>
	<div class="wpms--gallery-item-title">
		<a href="<?php the_permalink(); ?>" title="<?php esc_attr( get_the_title() ); ?>"><?php the_title(); ?></a>
	</div>
	<?php } ?>
</div>