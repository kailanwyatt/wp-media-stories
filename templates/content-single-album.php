<?php if ( ! empty( $photos ) ) : ?>
	<div class="wpms--img-row">
	<?php foreach( $photos as $id => $photo ) : wpms_setup_photo_data( $photo ); ?>
		<div class="wpms--img-row">
			<?php if ( $show_counter ) { ?>
			<div class="wpms--counter"><?php wpms_the_counter( $index, $total_items ); ?></div>
			<?php } ?>
			<div class="wpms--image"><?php wpms_the_image( $media_size ); ?></div>
			<?php if ( $show_caption || $show_title ) : ?>
			<div class="wpms--photo-details">
				<?php if ( $show_title ) : ?>
				<div class="wpms--title"><h3><?php wpms_the_title(); ?></h3></div>
				<?php endif; ?>
				<?php if ( $show_caption ) : ?>
				<div class="wpms--caption"><?php wpms_the_caption(); ?></div>
				<?php endif; ?>
			</div>
			<?php endif; ?>
		</div>
	<?php $index++; ?>
	<?php endforeach; ?>
	</div>
<?php endif; ?>