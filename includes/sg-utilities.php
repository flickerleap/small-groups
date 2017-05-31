<?php
/**
 * Get other templates passing attributes and including the file.
 *
 * @access public
 * @param string $template_name
 * @param array $args (default: array())
 * @param string $template_path (default: '')
 * @param string $default_path (default: '')
 * @return void
 */
function sg_get_template( $template_name, $args = array(), $template_path = '', $default_path = '' ) {

	$located = sg_locate_template( $template_name, $template_path, $default_path );

	if ( ! file_exists( $located ) ) {
		_doing_it_wrong( __FUNCTION__, sprintf( '<code>%s</code> does not exist.', $located ), '2.1' );
		return;
	}

	include( $located );

}

/**
 * Like wc_get_template, but returns the HTML instead of outputting.
 * @see wc_get_template
 * @since 2.5.0
 * @param string $template_name
 */
function sg_get_template_html( $template_name, $args = array(), $template_path = '', $default_path = '' ) {
	ob_start();
	sg_get_template( $template_name, $args, $template_path, $default_path );
	return ob_get_clean();
}

/**
 * Locate a template and return the path for inclusion.
 *
 * This is the load order:
 *
 *		yourtheme		/	$template_path	/	$template_name
 *		yourtheme		/	$template_name
 *		$default_path	/	$template_name
 *
 * @access public
 * @param string $template_name
 * @param string $template_path (default: '')
 * @param string $default_path (default: '')
 * @return string
 */
function sg_locate_template( $template_name, $template_path = '', $default_path = '' ) {
	if ( ! $template_path ) {
		$template_path = sg()->template_path();
	}

	if ( ! $default_path ) {
		$default_path = sg()->plugin_path() . '/templates/';
	}

	// Look within passed path within the theme - this is priority
	$template = locate_template(
		array(
			trailingslashit( $template_path ) . $template_name,
			$template_name,
		)
	);

	// Get default template
	if ( ! $template ) {
		$template = $default_path . $template_name;
	}

	// Return what we found
	return apply_filters( 'small_groups_locate_template', $template, $template_name, $template_path );
}

/**
 * Retrieve page permalink
 *
 * @param string $page
 * @return string
 */
function sg_get_page_permalink( $page ) {
	$permalink = get_permalink( get_page_by_path( $page ) );

	return apply_filters( 'small_groups_get_' . $page . '_page_permalink', $permalink );
}

if ( ! function_exists( 'write_log' ) ) {
	function write_log( $log ) {
		if ( WP_DEBUG && WP_DEBUG_LOG ) :
			if ( is_array( $log ) || is_object( $log ) ) :
				error_log( print_r( $log, true ) );
			else :
				error_log( $log );
			endif;
		endif;
	}
}

function sg_validate_input( $key, $invalid_message, $type = 'text' ) {
	if (  isset( $_POST[ $key ] ) ) :

		if ( 'email' == $type ) :
			if ( is_email( $_POST[ $key ] ) ) :
				echo '<span class="invalid-field">' . $invalid_message . '</span>';
			endif;
		else :
			if ( empty( $_POST[ $key ] ) ) :
				echo '<span class="invalid-field">' . $invalid_message . '</span>';
			endif;
		endif;

	endif;
}
