<?php
/**
 * Small groups for WordPress
 *
 * @link              https://leapsandbounds.io/
 * @since             1.0.0
 * @package           Small Groups
 *
 * Plugin Name: Small Groups
 * Plugin URI:  https://leapsandbounds.io
 * Description: Basic small group display
 * Version:     1.0
 * Author:      Leaps + Bounds
 * Author URI:  https://leapsandbounds.io/
 * License:     GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: small-groups
 **/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class SmallGroups {

	/** Refers to a single instance of this class. */
	private static $instance = null;

	public $prefix = 'connect_group';

	/**
	 * Creates or returns an instance of this class.
	 *
	 * @return  SmallGroups A single instance of this class.
	 */
	public static function instance() {

		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;

	} // end get_instance;

	/**
	 * Initializes the plugin by setting localization, filters, and administration functions.
	 */
	private function __construct() {

		$this->define_constants();
		$this->includes();

	} // end constructor

	public function define_constants() {
		define( 'SG_PREFIX', $this->prefix );
	}

	public function includes() {

		include_once( 'includes/sg-class-post-types.php' );
		include_once( 'includes/sg-class-frontend.php' );

	}

} // end class

SmallGroups::instance();
