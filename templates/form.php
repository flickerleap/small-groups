<form method="post" class="small-groups">
	<div class="input-wrap">
		<label for="first_name">First Name *</label>
		<input type="text" id="first_name" name="first_name" value="<?php echo ( isset( $_POST['first_name'] ) ) ? $_POST['first_name'] : '';?>" />
		<?php sg_validate_input( 'first_name', 'Please, enter your first name.' );?>
	</div>
	<div class="input-wrap">
		<label for="last_name">Last Name *</label>
		<input type="text" id="last_name" name="last_name" value="<?php echo ( isset( $_POST['last_name'] ) ) ? $_POST['last_name'] : '';?>" />
		<?php sg_validate_input( 'last_name', 'Please, enter your last name.' );?>
	</div>
	<div class="input-wrap">
		<label for="email">Email *</label>
		<input type="email" id="email" name="email" value="<?php echo ( isset( $_POST['email'] ) ) ? $_POST['email'] : '';?>" />
		<?php sg_validate_input( 'email', 'Please, enter a valid email.' );?>
	</div>
	<div class="input-wrap">
		<label for="contact_number">Contact Number</label>
		<input type="tel" id="contact_number" name="contact_number" value="<?php echo ( isset( $_POST['contact_number'] ) ) ? $_POST['contact_number'] : '';?>" />
	</div>
	<?php wp_nonce_field( 'small_group_enquire', 'small_group_enquire_nonce' );?>
	<input type="hidden" name="action" value="small_group_enquire" />
	<input type="submit" value="Submit" />
</form>
