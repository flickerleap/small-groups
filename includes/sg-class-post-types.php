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

class SG_Post_Types {

	public $fields;

	public function __construct() {

		$this->fields = $this->get_fields();

		add_action( 'init', array( $this, 'register_post_types' ) );
		add_action( 'init', array( $this, 'register_taxonomies' ) );

		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
		add_action( 'save_post', array( $this, 'save_meta' ) );

	}

	public function register_post_types() {
		$labels = array(
			'name'               => _x( 'Small Groups', 'post type general name' ),
			'singular_name'      => _x( 'Small Group', 'post type singular name' ),
			'add_new'            => _x( 'Add New', 'small_group' ),
			'add_new_item'       => __( 'Add New Small Group' ),
			'edit_item'          => __( 'Edit Small Group' ),
			'new_item'           => __( 'New Small Group' ),
			'all_items'          => __( 'All Small Groups' ),
			'view_item'          => __( 'View Small Group' ),
			'search_items'       => __( 'Search Small Groups' ),
			'not_found'          => __( 'No small groups found' ),
			'not_found_in_trash' => __( 'No small groups found in the Trash' ),
			'parent_item_colon'  => '',
			'menu_name'          => 'Small Groups',
		);

		$args = array(
			'labels'        => $labels,
			'description'   => 'List Small Groups',
			'public'        => true,
			'menu_position' => 5,
			'supports'      => array( 'title' ),
			'has_archive'   => true,
			'menu_icon' => 'dashicons-groups',
		);

		register_post_type( 'small_group', $args );

		do_action( 'small_groups_post_types_registered' );
	}

	public function register_taxonomies() {
		$labels = array(
			'name'              => _x( 'Group Areas', 'taxonomy general name' ),
			'singular_name'     => _x( 'Group Area', 'taxonomy singular name' ),
			'search_items'      => __( 'Search Group Areas' ),
			'all_items'         => __( 'All Group Areas' ),
			'parent_item'       => __( 'Parent Group Area' ),
			'parent_item_colon' => __( 'Parent Group Area:' ),
			'edit_item'         => __( 'Edit Group Area' ),
			'update_item'       => __( 'Update Group Area' ),
			'add_new_item'      => __( 'Add New Group Area' ),
			'new_item_name'     => __( 'New Group Area' ),
			'menu_name'         => __( 'Group Area' ),
		);

		$args = array(
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array( 'slug' => 'group-area' ),
		);

		register_taxonomy( 'group_area', 'small_group', $args );
	}

	public function add_meta_boxes() {
		add_meta_box(
			'small-group-information',
			'Connect Group Information',
			array( $this, 'small_group_info_meta_box' ),
			'small_group',
			'normal',
			'high'
		);
	}

	public function small_group_info_meta_box( $post ) {

		wp_nonce_field( 'small_group_info', 'small_group_info_nonce' );

		echo '<table class="form-table">';

		foreach ( $this->fields as $field ) {
			$value = get_post_meta( $post->ID, $field['id'], true );
				// begin a table row wit
			echo '<tr> 
				<th><label for="' . $field['id'] . '">' . $field['label'] . '</label></th> 
				<td>';
			switch ( $field['type'] ) {
				// text
				case 'text':
					echo '<input type="text" name="' . $field['id'] . '" id="' . $field['id'] . '" value="' . $value . '" size="30" />';
					echo '<br /><span class="description">' . $field['desc'] . '</span>';
				break;
			} //end switch
				echo '</td></tr>';
		} // end foreach

		echo '</table>'; // end table
	}

	public function save_meta( $post_id ) {

		// Check if our nonce is set.
		if ( ! isset( $_POST['small_group_info_nonce'] ) ) {
			return $post_id;
		}

		$nonce = $_POST['small_group_info_nonce'];

		// Verify that the nonce is valid.
		if ( ! wp_verify_nonce( $nonce, 'small_group_info' ) ) {
			return $post_id;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) :
			return $post_id;
		endif;

		if ( ! current_user_can( 'edit_post', $post_id ) ) :
			return $post_id;
		endif;

		foreach ( $this->fields as $field ) :
			$old_value = get_post_meta( $post_id, $field['id'], true );
			$new_value = $_POST[ $field['id'] ];
			if ( $new_value && $new_value != $old_value ) :
				update_post_meta( $post_id, $field['id'], $new_value );
			elseif ( '' == $new_value && $old_value ) :
				delete_post_meta( $post_id, $field['id'], $old_value );
			endif;
		endforeach;
	}

	public function get_fields() {

		$fields = array(
			array(
				'label' => 'Age Group',
				'desc'  => 'Enter Small Group age group. Ex: 15 - 22',
				'id'    => SG_PREFIX . 'age_group',
				'type'  => 'text',
			),
			array(
				'label' => 'Life Phase',
				'desc'  => 'Enter Small Group life phase. Ex: Mixed, Mostly Single',
				'id'    => SG_PREFIX . 'life_phase',
				'type'  => 'text',
			),
			array(
				'label' => 'Gender',
				'desc'  => 'Enter Small Group gender. Ex: Mixed, Female Only, Male Only',
				'id'    => SG_PREFIX . 'gender',
				'type'  => 'text',
			),
			array(
				'label' => 'Day',
				'desc'  => 'Enter Small Group meeting day. Ex: Thursday / Saturday',
				'id'    => SG_PREFIX . 'day',
				'type'  => 'text',
			),
			array(
				'label' => 'Contact Email',
				'desc'  => 'Enter Small Group contact email. Comma separted.',
				'id'    => SG_PREFIX . 'email',
				'type'  => 'text',
			),
			array(
				'label' => 'Contact Number',
				'desc'  => 'Enter Small Group contact number.  Comma separted.',
				'id'    => SG_PREFIX . 'number',
				'type'  => 'text',
			),
		);

		return $fields;

	}

}

new SG_Post_Types();
