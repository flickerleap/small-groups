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
 * Version:     1.0.0
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

	private $version = '1.0.0';

	public $prefix = 'small_groups_';

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
		$this->init();

	} // end constructor

	public function define_constants() {

		$upload_dir = wp_upload_dir();

		$this->define( 'SG_PLUGIN_FILE', __FILE__ );
		$this->define( 'SG_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
		$this->define( 'SG_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
		$this->define( 'SG_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
		$this->define( 'SG_PREFIX', $this->prefix );
		$this->define( 'SG_VERSION', $this->version );

	}

	/**
	 * Define constant if not already set
	 * @param  string $name
	 * @param  string|bool $value
	 */
	private function define( $name, $value ) {
		if ( ! defined( $name ) ) {
			define( $name, $value );
		}
	}

	public function includes() {

		include_once( 'includes/sg-utilities.php' );

		include_once( 'includes/sg-class-post-types.php' );
		include_once( 'includes/sg-class-frontend.php' );
		include_once( 'includes/sg-class-email.php' );

	}

	public function init() {

		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ) );

	}

	public function enqueue_assets() {
		wp_enqueue_style( 'small-groups', SG_PLUGIN_URL . '/assets/css/small-groups.css', null, SG_VERSION );
	}

	/**
	 * Get the plugin url.
	 * @return string
	 */
	public function plugin_url() {
		return untrailingslashit( plugins_url( '/', __FILE__ ) );
	}

	/**
	 * Get the plugin path.
	 * @return string
	 */
	public function plugin_path() {
		return untrailingslashit( plugin_dir_path( __FILE__ ) );
	}

	/**
	 * Get the template path.
	 * @return string
	 */
	public function template_path() {
		return apply_filters( 'fitpress_template_path', 'fitpress/' );
	}

	/**
	 * Get Ajax URL.
	 * @return string
	 */
	public function ajax_url() {
		return admin_url( 'admin-ajax.php', 'relative' );
	}

} // end class

function sg() {
	return SmallGroups::instance();
}

$GLOBALS['small_groups'] = sg();
