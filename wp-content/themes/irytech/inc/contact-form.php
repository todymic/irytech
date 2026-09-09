<?php
/**
 * Traitement du formulaire de contact (AJAX) vers l'adresse e-mail de la société.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'IRYTECH_CONTACT_EMAIL', 'gtody.rabekoto@gmail.com' );

function irytech_verify_recaptcha( $token ) {
	$response = wp_remote_post(
		'https://www.google.com/recaptcha/api/siteverify',
		array(
			'body' => array(
				'secret'   => IRYTECH_RECAPTCHA_SECRET_KEY,
				'response' => $token,
				'remoteip' => isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '',
			),
			'timeout' => 10,
		)
	);

	if ( is_wp_error( $response ) ) {
		return false;
	}

	$body = json_decode( wp_remote_retrieve_body( $response ), true );

	return ! empty( $body['success'] );
}

function irytech_handle_contact_form() {
	check_ajax_referer( 'irytech_contact', 'nonce' );

	// Honeypot anti-spam.
	if ( ! empty( $_POST['website'] ) ) {
		wp_send_json_success();
	}

	$recaptcha_token = isset( $_POST['g-recaptcha-response'] ) ? sanitize_text_field( wp_unslash( $_POST['g-recaptcha-response'] ) ) : '';

	if ( empty( $recaptcha_token ) || ! irytech_verify_recaptcha( $recaptcha_token ) ) {
		wp_send_json_error( array( 'message' => __( "Merci de valider le reCAPTCHA avant d'envoyer votre message.", 'irytech' ) ), 400 );
	}

	$name    = isset( $_POST['name'] ) ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : '';
	$email   = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
	$subject = isset( $_POST['subject'] ) ? sanitize_text_field( wp_unslash( $_POST['subject'] ) ) : '';
	$message = isset( $_POST['message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['message'] ) ) : '';

	if ( empty( $name ) || ! is_email( $email ) || empty( $message ) ) {
		wp_send_json_error( array( 'message' => __( 'Merci de remplir tous les champs avec une adresse e-mail valide.', 'irytech' ) ), 400 );
	}

	$mail_subject = sprintf( '[Site Irytech] %s', $subject ?: 'Nouveau message de contact' );
	$mail_body    = sprintf(
		"Nom : %s\nE-mail : %s\n\nMessage :\n%s",
		$name,
		$email,
		$message
	);
	$headers = array( 'Reply-To: ' . $name . ' <' . $email . '>' );

	$sent = wp_mail( IRYTECH_CONTACT_EMAIL, $mail_subject, $mail_body, $headers );

	if ( $sent ) {
		wp_send_json_success( array( 'message' => __( 'Votre message a bien été envoyé, merci !', 'irytech' ) ) );
	}

	wp_send_json_error( array( 'message' => __( "L'envoi a échoué, merci de réessayer plus tard.", 'irytech' ) ), 500 );
}
add_action( 'wp_ajax_irytech_contact', 'irytech_handle_contact_form' );
add_action( 'wp_ajax_nopriv_irytech_contact', 'irytech_handle_contact_form' );
