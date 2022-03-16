<?php
/**
 * WP_Media_Stories.
 *
 * @since   0.1
 * @package WP_Media_Stories
 */
class WP_Media_Stories_Test extends WP_UnitTestCase {

	/**
	 * Test if our class exists.
	 *
	 * @since  0.1
	 */
	function test_class_exists() {
		$this->assertTrue( class_exists( 'WP_Media_Stories') );
	}

	/**
	 * Test that our main helper function is an instance of our class.
	 *
	 * @since  0.1
	 */
	function test_get_instance() {
		$this->assertInstanceOf(  'WP_Media_Stories', wp_media_stories() );
	}

	/**
	 * Replace this with some actual testing code.
	 *
	 * @since  0.1
	 */
	function test_sample() {
		$this->assertTrue( true );
	}
}
