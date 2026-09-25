<?php
/**
 * Pages created when the theme is activated.
 *
 * - Home: every landing section, set as the front page.
 * - About, Affiliate Disclosure, Medical Disclaimer: needed for Amazon
 *   Associates approval and FTC compliance. Edit them freely.
 *
 * Pages are only created once; deleting one does not bring it back unless
 * you switch themes and re-activate Audira.
 *
 * @package Audira
 */

defined( 'ABSPATH' ) || exit;

function audira_landing_sections() {
	return array( 'hero', 'trust', 'picks', 'styles', 'compare', 'otc', 'method', 'process', 'faq', 'cta' );
}

function audira_paragraphs( array $paragraphs ) {
	$out = '';
	foreach ( $paragraphs as $p ) {
		if ( 0 === strpos( $p, '## ' ) ) {
			$out .= "<!-- wp:heading -->\n<h2 class=\"wp-block-heading\">" . esc_html( substr( $p, 3 ) ) . "</h2>\n<!-- /wp:heading -->\n\n";
			continue;
		}
		$out .= "<!-- wp:paragraph -->\n<p>" . wp_kses_post( $p ) . "</p>\n<!-- /wp:paragraph -->\n\n";
	}
	return $out;
}

function audira_default_pages() {
	$site = get_bloginfo( 'name' );

	return array(
		'about'                => array(
			'title'   => __( 'About', 'audira' ),
			'content' => audira_paragraphs(
				array(
					sprintf( '%s helps older adults — and the families who love them — choose hearing aids with confidence. We turn confusing specs into plain English and point you to products we would recommend to our own parents.', esc_html( $site ) ),
					'## How we choose',
					'We compare sound quality, comfort, ease of use for older hands and eyes, battery life, warranty and support, and the real experiences of long-term owners. No manufacturer can pay for a spot on our lists.',
					'## How we make money',
					'We are reader-supported. When you buy through our links we may earn a commission from Amazon, at no extra cost to you. It never changes our rankings.',
				)
			),
		),
		'affiliate-disclosure' => array(
			'title'   => __( 'Affiliate Disclosure', 'audira' ),
			'content' => audira_paragraphs(
				array(
					sprintf( '%s is a participant in the Amazon Services LLC Associates Program, an affiliate advertising program designed to provide a means for sites to earn advertising fees by advertising and linking to Amazon.com.', esc_html( $site ) ),
					'As an Amazon Associate we earn from qualifying purchases. When you click a link on this site and buy something on Amazon, we may receive a small commission. The price you pay is exactly the same.',
					'Our recommendations are editorially independent. Manufacturers cannot pay to be included or to change their position. Prices and availability are shown on Amazon and may change at any time.',
					'Amazon and the Amazon logo are trademarks of Amazon.com, Inc. or its affiliates.',
				)
			),
		),
		'medical-disclaimer'   => array(
			'title'   => __( 'Medical Disclaimer', 'audira' ),
			'content' => audira_paragraphs(
				array(
					sprintf( 'The content on %s is for general information only and is not medical advice. It is not a substitute for a professional diagnosis, hearing evaluation or treatment.', esc_html( $site ) ),
					'Over-the-counter (OTC) hearing aids are intended for adults 18 and older with perceived mild to moderate hearing loss. See a doctor or licensed hearing professional before buying if you have sudden hearing loss, hearing loss in only one ear, ear pain, drainage, ringing in one ear, or dizziness.',
					'Always read the manufacturer\'s instructions and warnings for any device you buy.',
				)
			),
		),
	);
}

function audira_create_pages() {
	// Landing page.
	$existing = (int) get_option( 'audira_landing_page_id' );
	if ( ! $existing || ! get_post( $existing ) ) {
		$content = '';
		foreach ( audira_landing_sections() as $slug ) {
			$content .= '<!-- wp:pattern {"slug":"audira/' . $slug . '"} /-->' . "\n";
		}

		$page_id = wp_insert_post(
			array(
				'post_type'    => 'page',
				'post_status'  => 'publish',
				'post_title'   => __( 'Home', 'audira' ),
				'post_name'    => 'home',
				'post_content' => $content,
			)
		);

		if ( $page_id && ! is_wp_error( $page_id ) ) {
			update_post_meta( $page_id, '_wp_page_template', 'page-landing' );
			update_option( 'audira_landing_page_id', $page_id );

			if ( 'posts' === get_option( 'show_on_front' ) ) {
				update_option( 'show_on_front', 'page' );
				update_option( 'page_on_front', $page_id );
			}
		}
	}

	// Legal and about pages.
	foreach ( audira_default_pages() as $slug => $page ) {
		if ( get_page_by_path( $slug ) ) {
			continue;
		}
		wp_insert_post(
			array(
				'post_type'    => 'page',
				'post_status'  => 'publish',
				'post_title'   => $page['title'],
				'post_name'    => $slug,
				'post_content' => $page['content'],
			)
		);
	}
}
add_action( 'after_switch_theme', 'audira_create_pages' );

/**
 * Link to a page by slug, falling back to a pretty URL.
 */
function audira_page_url( $slug ) {
	if ( 'privacy-policy' === $slug && get_privacy_policy_url() ) {
		return get_privacy_policy_url();
	}
	$page = get_page_by_path( $slug );
	return $page ? get_permalink( $page ) : home_url( '/' . $slug . '/' );
}
