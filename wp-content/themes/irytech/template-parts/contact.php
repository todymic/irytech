<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<section class="contact section-band" id="contact">
	<span class="contact__glow" aria-hidden="true"></span>

	<div class="container contact__inner">
		<div class="contact__intro reveal" data-reveal>
			<p class="section-eyebrow">Contact</p>
			<h2 class="section-title">Discutons de votre projet.</h2>
			<a href="mailto:gtody.rabekoto@gmail.com" class="contact__email">gtody.rabekoto@gmail.com</a>

			<a href="https://wa.me/261385080588" target="_blank" rel="noopener" class="whatsapp-btn">
				<svg viewBox="0 0 24 24" fill="#ffffff" aria-hidden="true">
					<path d="M12.02 2c-5.5 0-9.96 4.46-9.96 9.96 0 1.76.46 3.48 1.33 5l-1.42 5.18 5.31-1.39a9.93 9.93 0 0 0 4.74 1.21h.01c5.5 0 9.96-4.46 9.96-9.96S17.52 2 12.02 2Zm5.83 14.16c-.25.7-1.24 1.28-2.02 1.44-.54.11-1.24.2-3.6-.77-2.98-1.23-4.9-4.26-5.05-4.46-.15-.2-1.21-1.61-1.21-3.07 0-1.46.76-2.18 1.03-2.48.27-.3.59-.37.78-.37h.56c.18 0 .43-.07.67.51.25.6.85 2.06.92 2.21.07.15.12.33.02.53-.1.2-.15.32-.3.5-.15.18-.32.4-.45.53-.15.15-.31.32-.13.62.18.3.8 1.32 1.72 2.14 1.18 1.05 2.18 1.38 2.48 1.53.3.15.48.13.65-.08.18-.2.76-.89.97-1.19.2-.3.4-.25.68-.15.28.1 1.75.83 2.05 .98.3.15.5.23.57.35.08.13.08.72-.17 1.42Z"/>
				</svg>
				<span>WhatsApp — +261 38 508 05 88</span>
			</a>
		</div>

		<form class="contact-form reveal" id="contact-form" data-reveal data-reveal-delay="150" novalidate>
			<div class="contact-form__row">
				<label for="cf-name">Nom</label>
				<input type="text" id="cf-name" name="name" required autocomplete="name">
			</div>

			<div class="contact-form__row">
				<label for="cf-email">E-mail</label>
				<input type="email" id="cf-email" name="email" required autocomplete="email">
			</div>

			<div class="contact-form__row">
				<label for="cf-message">Message</label>
				<textarea id="cf-message" name="message" rows="4" required></textarea>
			</div>

			<!-- Honeypot anti-spam -->
			<div class="contact-form__honeypot" aria-hidden="true">
				<label for="cf-website">Site web</label>
				<input type="text" id="cf-website" name="website" tabindex="-1" autocomplete="off">
			</div>

			<div class="contact-form__row">
				<div class="g-recaptcha" data-sitekey="<?php echo esc_attr( IRYTECH_RECAPTCHA_SITE_KEY ); ?>" data-theme="dark"></div>
			</div>

			<button type="submit" class="btn btn--primary contact-form__submit">
				<span class="contact-form__submit-label">Envoyer le message</span>
			</button>

			<p class="contact-form__feedback" id="contact-form-feedback" role="status" aria-live="polite"></p>
		</form>
	</div>
</section>
