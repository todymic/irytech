<?php
/**
 * Traitement autonome du formulaire de contact (sans WordPress).
 * Vérifie reCAPTCHA v3 (score) puis envoie un e-mail via PHP mail().
 */

header( 'Content-Type: application/json; charset=utf-8' );

define( 'IRYTECH_CONTACT_EMAIL', 'contact@irytech.net' );

// Remplacez par votre vraie clé secrète reCAPTCHA v3 (obtenue sur
// https://www.google.com/recaptcha/admin/create, type "v3", même domaine que
// la clé de site utilisée dans index.html).
define( 'IRYTECH_RECAPTCHA_SECRET_KEY', 'RECAPTCHA_V3_SECRET_KEY' );

// Score minimal accepté (0.0 = probablement un bot, 1.0 = probablement humain).
define( 'IRYTECH_RECAPTCHA_MIN_SCORE', 0.5 );

function irytech_json_response( $success, $message ) {
	echo json_encode( array( 'success' => $success, 'message' => $message ) );
	exit;
}

function irytech_verify_recaptcha( $token ) {
	// Tant que la vraie clé secrète n'a pas été configurée, on n'appelle pas
	// l'API Google (elle rejetterait tout) : le formulaire reste utilisable,
	// protégé uniquement par le honeypot, en attendant la vraie clé.
	if ( IRYTECH_RECAPTCHA_SECRET_KEY === 'RECAPTCHA_V3_SECRET_KEY' ) {
		return true;
	}

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

	if ( empty( $data['success'] ) ) {
		return false;
	}

	// action doit correspondre à celle envoyée par grecaptcha.execute() côté JS.
	if ( isset( $data['action'] ) && $data['action'] !== 'contact' ) {
		return false;
	}

	$score = isset( $data['score'] ) ? (float) $data['score'] : 0.0;

	return $score >= IRYTECH_RECAPTCHA_MIN_SCORE;
}

if ( $_SERVER['REQUEST_METHOD'] !== 'POST' ) {
	irytech_json_response( false, 'Méthode non autorisée.' );
}

// Honeypot anti-spam.
if ( ! empty( $_POST['website'] ) ) {
	irytech_json_response( true, 'Votre message a bien été envoyé, merci !' );
}

$recaptcha_token = trim( $_POST['recaptcha_token'] ?? '' );

// Tant que la vraie clé secrète n'est pas configurée, aucun token n'est même
// envoyé par le JS (voir main.js) : on ne bloque pas l'envoi dans ce cas.
$recaptcha_configured = IRYTECH_RECAPTCHA_SECRET_KEY !== 'RECAPTCHA_V3_SECRET_KEY';

if ( $recaptcha_configured && ( empty( $recaptcha_token ) || ! irytech_verify_recaptcha( $recaptcha_token ) ) ) {
	irytech_json_response( false, "La vérification anti-spam a échoué. Merci de réessayer." );
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
