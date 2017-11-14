<?php
/**
 * Small groups email
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

class SG_Email {

	public function __construct() {

		add_action( 'template_redirect', array( $this, 'maybe_send_email' ) );
		add_filter( 'wp_mail_content_type', array( $this, 'html_email' ) );
		add_action( 'wp_mail_failed', array( $this, 'log_mailer_errors' ) );

	}

	public function maybe_send_email() {

		if ( isset( $_GET['sg_id'] ) && isset( $_POST['action'] ) && 'small_group_enquire' == $_POST['action'] ) :

			if ( empty( $_POST['first_name'] ) || empty( $_POST['last_name'] ) || empty( $_POST['email'] ) ) :
				return;
			endif;

			$email = get_post_meta( $_GET['sg_id'], SG_PREFIX . 'email', true );
			$subject = 'Group Enquiry';
			$small_group_name = get_the_title( $_GET['sg_id'] );

			$enquire_first_name = $_POST['first_name'];
			$enquire_last_name = $_POST['last_name'];
			$enquire_email = $_POST['email'];
			$enquire_contact_number = $_POST['contact_number'];

			$message = '<p>Hi!</p>';
			$message .= "<p>{$enquire_first_name} has enquired about your group ($small_group_name). You can reply directly to this email to respond to them.</p>";
			$message .= "<p>First name: {$enquire_first_name}<br />";
			$message .= "Last name: {$enquire_last_name}<br />";
			$message .= "Email: {$enquire_email}<br />";
			$message .= "Contact number: {$enquire_contact_number}</p>";
			$message .= "<p>Regards,<br />Rivers Connect Groups</p>";

			$headers = apply_filters( 'small_group_email_headers', array( "Reply-To: {$enquire_first_name} {$enquire_last_name} <{$enquire_email}>" ) );

			$this->send( $email, $subject, $message, $headers );

			wp_safe_redirect( remove_query_arg( 'sg_id' ) );

			die();

		endif;

	}

	public function send( $to, $subject, $message, $headers ) {

		wp_mail( $to, $subject, $message, $headers );

	}

	public function html_email() {
		return 'text/html';
	}

	public function log_mailer_errors( $error ) {
		error_log( $error->get_error_message() );
	}

}

new SG_Email();
