<?php
function wpms_setup_photo_data( $photo_data = array() ) {
	global $wpms_photo;
	$wpms_photo = $photo_data;
}

/**
 * Get the photo title
 *
 * @since  1.0.0
 *
 * @return string
 */
function wpms_get_ID() {
	global $wpms_photo;
	if ( ! empty( $wpms_photo['id'] ) ) {
		return absint( $wpms_photo['id'] );
	}
	return;
}

/**
 * Display the photo title
 * 
 * @since 1.0.0
 */
function wpms_the_title() {
	global $wpms_photo;
	echo wpms_get_title();
}

/**
 * Get the photo title
 *
 * @since  1.0.0
 *
 * @return string
 */
function wpms_get_title() {
	global $wpms_photo;
	if ( ! empty( $wpms_photo['title'] ) ) {
		return esc_html( $wpms_photo['title'] );
	}
	return;
}

/**
 * Display the photo title
 * 
 * @since 1.0.0
 */
function wpms_the_caption() {
	global $wpms_photo;
	echo wpms_get_caption();
}

/**
 * Get the photo title
 *
 * @since  1.0.0
 *
 * @return string
 */
function wpms_get_caption() {
	global $wpms_photo;
	if ( ! empty( $wpms_photo['caption'] ) ) {
		return wp_kses_post( $wpms_photo['caption'] );
	}
	return;
}

/**
 * Display the photo title
 * 
 * @since 1.0.0
 */
function wpms_the_counter( $index = 0, $total = 0 ) {
	echo wpms_get_counter( $index, $total );
}

/**
 * Get the photo title
 *
 * @since  1.0.0
 *
 * @return string
 */
function wpms_get_counter( $index = 0, $total = 0 ) {
	$counter =  sprintf( '%1d of %2d', $index, $total );
	return apply_filters( 'wpms_get_counter', $counter, $index, $total );
}

/**
 * Display the photo title
 * 
 * @since 1.0.0
 */
function wpms_the_image( $size = 'full' ) {
	global $wpms_photo;
	$image = sprintf( '<img src="%s" alt="%s" />', esc_url( wpms_get_image_url( $size ) ), esc_attr( wpms_get_title() ) );
	$image = apply_filters( 'wpms_the_image', $image, $wpms_photo );
	echo wp_kses_post( $image );
}

/**
 * Get the photo title
 *
 * @since  1.0.0
 *
 * @return string
 */
function wpms_get_image_url( $size = 'full' ) {
	global $wpms_photo;
	$image = '';
	if ( ! empty( $wpms_photo['id'] ) ) {
		$image_attributes = wp_get_attachment_image_src( $wpms_photo['id'], $size );
		if ( ! empty( $image_attributes ) ) {
			$image = $image_attributes[0];
		}
		return $image;
	}
	return;
}