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

		add_shortcode( 'connect-groups', array( $this, 'show_connect_groups' ) );

	}

	public function show_connect_groups( $atts, $content = null ) {
		global $location;

		$args = array(
			'posts_per_page' => -1,
		    'orderby' => 'rand',
		    'post_type' => 'connect_groups',
		);

		$connect_groups = new WP_Query( $args );

		ob_start();
		?>

		<div class="table-responsive">
			<table id="connect-group-listing" class="">
			<tr>
				<th>Connect Group Leaders</th>
				<th>Suburb</th>
				<th>Age</th>
				<th>Life Phase</th>
				<th>Gender</th>
				<th>Day</th>
				<th>
					<select id="filter-area">
						<option value="">Filter by Area</option>
					</select>
				</th>
			</tr>
				<?php
				$filter_array = array();
				// Print our Connect Groups posts
				if ( $connect_groups->have_posts() ) :
					while ( $connect_groups->have_posts() ) : $connect_groups->the_post(); ?>
						<?php
						//Returns Array of Term Names for "group_area"
						$group_area = wp_get_post_terms( $post->ID, 'group_area', array( 'fields' => 'names' ) );
						if ( empty( $filter_array ) ) {
							$filter_array[] = $group_area[0];
						} else {
							if ( ! in_array( $group_area[0], $filter_array ) ) {
								$filter_array[] = $group_area[0];
							}
						}
						$emails = implode( ',', array_map( 'trim', explode( ',', $post_meta_data['connect_group_email'][0] ) ) );
						?>
						<tr class="single-item" data-area="<?php echo $group_area[0]; ?>">
							<td><?php the_title(); ?></td>
							<td><?php echo $group_area[0]; ?></td>
							<td><?php echo $post_meta_data['connect_group_age_group'][0]; ?></td>
							<td><?php echo $post_meta_data['connect_group_life_phase'][0]; ?></td>
							<td><?php echo $post_meta_data['connect_group_gender'][0]; ?></td>
							<td><?php echo $post_meta_data['connect_group_day'][0]; ?></td>
							<td>
								<a href="<?php bloginfo( 'url' ); ?>/connect-group-enquiry/?group_email=<?php echo urlencode( $emails );?>&amp;group_name=<?php echo get_the_title();?>" class="white-button">Enquire</a>
							</td>
						</tr>
					<?php endwhile; ?>
			<?php endif; ?>
			</table>
		</div>

		<?php sort( $filter_array ); ?>

		<script>
			jQuery(function(){
				// Fill our selectbox with all the areas
				var options = jQuery("#filter-area");
				var listOfAreas = <?php echo json_encode( $filter_array ); ?>;

				jQuery.each(listOfAreas, function(item) {
			        options.append(jQuery("<option />").val(listOfAreas[item]).text(listOfAreas[item]));
			    });

				// Handle the onchange event to filter by selected area
				jQuery("#filter-area").on('change', function(){
					if (jQuery(this).val() == '') {
						jQuery('#connect-group-listing tr.single-item').show();
						jQuery('tr').removeClass('even');
						jQuery('tr').removeClass('odd');
					} else {
						jQuery('#connect-group-listing tr.single-item').hide();
						var foundTRs = jQuery("tr[data-area='" + jQuery(this).val() + "']").show();
						jQuery('tr').removeClass('even');
						jQuery('tr').removeClass('odd');
						jQuery.each(foundTRs, function(index, item){
							if ((index % 2) == 0) {
								jQuery(item).addClass('even');
							} else {
								jQuery(item).addClass('odd');
							}
						});
					}
				});
			});
		</script>

		<?php

		$return = ob_get_contents( );

		ob_end_clean();

		return $return;

	}

}

new SG_Frontend();
