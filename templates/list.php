<div class="table-responsive">
	<table id="small-group-listing" class="">
	<tr>
		<th>Group Leaders</th>
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

		if ( 0 < count( $args['small_groups'] ) ) :
			foreach ( $args['small_groups'] as $small_group ) : ?>
				<?php
				//Returns Array of Term Names for "group_area"
				$group_area = wp_get_post_terms( $small_group->ID, 'group_area', array( 'fields' => 'names' ) );
				if ( empty( $filter_array ) ) :
					$filter_array[] = $group_area[0];
				else :
					if ( ! in_array( $group_area[0], $filter_array ) ) {
						$filter_array[] = $group_area[0];
					}
				endif;

				$age_group = get_post_meta( $small_group->ID, SG_PREFIX . 'age_group', true );
				$life_phase = get_post_meta( $small_group->ID, SG_PREFIX . 'life_phase', true );
				$gender = get_post_meta( $small_group->ID, SG_PREFIX . 'gender', true );
				$day = get_post_meta( $small_group->ID, SG_PREFIX . 'day', true );
				?>
				<tr class="single-item" data-area="<?php echo $group_area[0]; ?>">
					<td><?php echo $small_group->post_title; ?></td>
					<td><?php echo $group_area[0]; ?></td>
					<td><?php echo ( $age_group ) ? $age_group : 'All ages'; ?></td>
					<td><?php echo ( $life_phase ) ? $life_phase : 'Any'; ?></td>
					<td><?php echo ( $gender ) ? $gender : 'Any'; ?></td>
					<td><?php echo ( $day ) ? $day : 'Various'; ?></td>
					<td>
						<a href="<?php echo add_query_arg( 'sg_id', $small_group->ID );?>" class="button">Enquire</a>
					</td>
				</tr>
			<?php endforeach;?>

		<?php else : ?>
			<tr class="single-item">
				<td colspan="7">There are currently no small groups listed.</td>
			</tr>
		<?php endif;?>
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
				jQuery('#small-group-listing tr.single-item').show();
				jQuery('tr').removeClass('even');
				jQuery('tr').removeClass('odd');
			} else {
				jQuery('#small-group-listing tr.single-item').hide();
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
