<?php
/**
 * Small groups post types
 *
 * @link              https://leapsandbounds.io/
 * @since             1.0.0
 * @package           Small Groups
 *
 **/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class SG_Frontend {

	public function __construct() {

		add_shortcode( 'small-groups', array( $this, 'display_small_groups' ) );

	}

	public function display_small_groups( $atts, $content = null ) {
		global $location;

		if ( isset( $_GET['sg_id'] ) ) :

			return sg_get_template_html( 'form.php', array( 'sg_id' => $_GET['sg_id'] ) );

		else :

			$args = array(
				'posts_per_page' => -1,
				'orderby' => 'rand',
				'post_type' => 'small_group',
			);

			$small_groups_obj = new WP_Query( $args );

			$small_groups = array();

			if ( $small_groups_obj->have_posts() ) :

				$small_groups = $small_groups_obj->posts;

			endif;

			return sg_get_template_html( 'list.php', array( 'small_groups' => $small_groups ) );

		endif;

	}

}

new SG_Frontend();
