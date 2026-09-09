<?php
/**
 * Traitement autonome du formulaire de contact (sans WordPress).
 * Envoie un e-mail à l'adresse de la société via PHP mail().
 */

header( 'Content-Type: application/json; charset=utf-8' );

define( 'IRYTECH_CONTACT_EMAIL', 'gtody.rabekoto@gmail.com' );

// Clés de test officielles Google (valident toujours) — à remplacer par vos vraies clés avant la mise en production.
// https://developers.google.com/recaptcha/docs/faq#id-like-to-run-automated-tests-with-recaptcha-v2-what-should-i-do
define( 'IRYTECH_RECAPTCHA_SECRET_KEY', '6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe' );

function irytech_json_response( $success, $message ) {
	echo json_encode( array( 'success' => $success, 'message' => $message ) );
	exit;
}

function irytech_verify_recaptcha( $token ) {
	$ch = curl_init( 'https://www.google.com/recaptcha/api/siteverify' );
	curl_setopt_array(
		$ch,
		array(
			CURLOPT_POST           => true,
			CURLOPT_POSTFIELDS     => http_build_query(
				array(
					'secret'   => IRYTECH_RECAPTCHA_SECRET_KEY,
					'response' => $token,
					'remoteip' => $_SERVER['REMOTE_ADDR'] ?? '',
				)
			),
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_TIMEOUT        => 10,
		)
	);
	$body = curl_exec( $ch );
	curl_close( $ch );

	if ( ! $body ) {
		return false;
	}

	$data = json_decode( $body, true );
	return ! empty( $data['success'] );
}

if ( $_SERVER['REQUEST_METHOD'] !== 'POST' ) {
	irytech_json_response( false, 'Méthode non autorisée.' );
}

// Honeypot anti-spam.
if ( ! empty( $_POST['website'] ) ) {
	irytech_json_response( true, 'Votre message a bien été envoyé, merci !' );
}

$recaptcha_token = trim( $_POST['g-recaptcha-response'] ?? '' );

if ( empty( $recaptcha_token ) || ! irytech_verify_recaptcha( $recaptcha_token ) ) {
	irytech_json_response( false, "Merci de valider le reCAPTCHA avant d'envoyer votre message." );
}

$name    = trim( strip_tags( $_POST['name'] ?? '' ) );
$email   = filter_var( trim( $_POST['email'] ?? '' ), FILTER_VALIDATE_EMAIL );
$message = trim( strip_tags( $_POST['message'] ?? '' ) );

if ( empty( $name ) || ! $email || empty( $message ) ) {
	irytech_json_response( false, 'Merci de remplir tous les champs avec une adresse e-mail valide.' );
}

$subject = sprintf( '[Site Irytech] Nouveau message de %s', $name );
$body    = sprintf( "Nom : %s\nE-mail : %s\n\nMessage :\n%s", $name, $email, $message );
$headers = sprintf( "Reply-To: %s <%s>\r\nContent-Type: text/plain; charset=utf-8", $name, $email );

$sent = mail( IRYTECH_CONTACT_EMAIL, $subject, $body, $headers );

if ( $sent ) {
	irytech_json_response( true, 'Votre message a bien été envoyé, merci !' );
}

irytech_json_response( false, "L'envoi a échoué, merci de réessayer plus tard." );
