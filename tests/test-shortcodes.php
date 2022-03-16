<?php
/**
 * WP Media Stories Shortcodes Tests.
 *
 * @since   0.1
 * @package WP_Media_Stories
 */
class WP_Media_Stories_Shortcodes_Test extends WP_UnitTestCase {

	/**
	 * Test if our class exists.
	 *
	 * @since  0.1
	 */
	function test_class_exists() {
		$this->assertTrue( class_exists( 'WP_Media_Stories_Shortcodes' ) );
	}

	/**
	 * Test that we can access our class through our helper function.
	 *
	 * @since  0.1
	 */
	function test_class_access() {
		$this->assertInstanceOf( 'WP_Media_Stories_Shortcodes', wp_media_stories()->shortcodes );
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
