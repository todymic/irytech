<?php
/**
 * Données des réalisations affichées en page d'accueil.
 * Contenu de premier jet à valider — textes, chiffres et visuels à remplacer par les vrais.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function irytech_portfolio_items() {
	return array(
		array(
			'slug'        => 'ticketevent',
			'name'        => 'Ticketevent',
			'tagline'     => 'Billetterie événementielle en ligne',
			'description' => "Plateforme de vente de billets pour concerts, festivals et spectacles à Madagascar : recherche d'événements, paiement en ligne et scan de billets.",
			'url'         => 'https://ticketevent.mg/',
			'accent'      => '#e8542e',
			'accent_2'    => '#ff8a5c',
			'logo'        => 'ticketevent/logo.png',
			'tech'        => array( 'Angular', 'PHP', 'MySQL' ),
			'gallery'     => array(
				'front' => array(
					array( 'label' => "Page d'accueil", 'image' => 'ticketevent/poster.jpg' ),
					array( 'label' => 'Fiche événement', 'image' => '' ),
				),
				'bo'    => array(
					array( 'label' => 'Connexion organisateur', 'image' => '' ),
					array( 'label' => 'Tableau de bord', 'image' => '' ),
				),
			),
		),
		array(
			'slug'        => 'tapakila',
			'name'        => 'Tapakila',
			'tagline'     => 'Solution de billetterie & gestion d\'accès',
			'description' => "Plateforme de billetterie avec back-office dédié aux organisateurs pour créer leurs événements, suivre les ventes et gérer les accès en temps réel.",
			'url'         => 'https://tapakila-bo.irytech.net/',
			'accent'      => '#3f6df0',
			'accent_2'    => '#7aa2ff',
			'logo'        => 'tapakila/logo.png',
			'tech'        => array( 'React', 'Symfony', 'PostgreSQL' ),
			'gallery'     => array(
				'front' => array(
					array( 'label' => "Page d'accueil", 'image' => '' ),
				),
				'bo'    => array(
					array( 'label' => 'Connexion back-office', 'image' => 'tapakila/logo.png' ),
					array( 'label' => 'Gestion des événements', 'image' => '' ),
				),
			),
		),
		array(
			'slug'        => 'hetsika',
			'name'        => 'Hetsika',
			'tagline'     => "Billetterie et découverte d'événements",
			'description' => "Plateforme de découverte et de vente de billets pour des événements à Madagascar, avec mise en avant des événements phares et achat en ligne.",
			'url'         => '',
			'accent'      => '#e8547a',
			'accent_2'    => '#ff8fab',
			'logo'        => '',
			'tech'        => array( 'React', 'Node.js' ),
			'gallery'     => array(
				'front' => array(
					array( 'label' => "Page d'accueil", 'image' => 'hetsika/front-1.png' ),
				),
				'bo'    => array(
					array( 'label' => 'Back-office', 'image' => '' ),
				),
			),
		),
		array(
			'slug'        => 'mitoera',
			'name'        => 'Mitoera',
			'tagline'     => 'Billetterie & plans de salle interactifs',
			'description' => "Mitoera transforme n'importe quelle salle malgache — stade, théâtre, amphithéâtre — en plan interactif : vente de billets place par place, en quelques secondes.",
			'url'         => '',
			'accent'      => '#4fb98a',
			'accent_2'    => '#ff6b52',
			'logo'        => 'mitoera/logo.svg',
			'tech'        => array( 'Vue.js', 'Symfony' ),
			'gallery'     => array(
				'front' => array(
					array( 'label' => "Page d'accueil", 'image' => 'mitoera/front-1.png' ),
				),
				'bo'    => array(
					array( 'label' => 'Back-office', 'image' => '' ),
				),
			),
		),
	);
}
