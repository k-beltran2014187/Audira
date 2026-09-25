<?php
/**
 * Theme support, assets, favicon, structured data and pattern category.
 *
 * @package Audira
 */

defined( 'ABSPATH' ) || exit;

/**
 * URL of a theme image.
 */
function audira_img( $file ) {
	return esc_url( get_theme_file_uri( 'assets/images/' . $file ) );
}

function audira_setup() {
	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'editor-styles' );
	add_editor_style( 'assets/css/styles.css' );
}
add_action( 'after_setup_theme', 'audira_setup' );

function audira_asset_version( $relative ) {
	$path = get_theme_file_path( $relative );
	return file_exists( $path ) ? (string) filemtime( $path ) : AUDIRA_VERSION;
}

function audira_enqueue_assets() {
	wp_enqueue_style( 'audira-styles', get_theme_file_uri( 'assets/css/styles.css' ), array(), audira_asset_version( 'assets/css/styles.css' ) );

	wp_enqueue_script(
		'audira-script',
		get_theme_file_uri( 'assets/js/audira.js' ),
		array(),
		audira_asset_version( 'assets/js/audira.js' ),
		array(
			'in_footer' => true,
			'strategy'  => 'defer',
		)
	);
}
add_action( 'wp_enqueue_scripts', 'audira_enqueue_assets' );

/**
 * Lets CSS know JavaScript is available before first paint, so the
 * scroll-reveal animation never hides content when scripts are blocked.
 */
function audira_js_class() {
	echo "<script>document.documentElement.classList.add('aud-js');</script>\n";
}
add_action( 'wp_head', 'audira_js_class', 1 );

/**
 * Preload the two fonts used above the fold.
 */
function audira_preload_fonts() {
	foreach ( array( 'fraunces-var.woff2', 'inter-var.woff2' ) as $font ) {
		printf(
			'<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin>' . "\n",
			esc_url( get_theme_file_uri( 'assets/fonts/' . $font ) )
		);
	}
}
add_action( 'wp_head', 'audira_preload_fonts', 2 );

/**
 * Favicon and theme color until a Site Icon is set in the Site Editor.
 */
function audira_favicon() {
	echo '<meta name="theme-color" content="#0E1E25">' . "\n";
	if ( has_site_icon() ) {
		return;
	}
	printf( '<link rel="icon" href="%s" type="image/svg+xml">' . "\n", audira_img( 'logo-mark.svg' ) );
}
add_action( 'wp_head', 'audira_favicon', 3 );

/**
 * FAQPage structured data on the landing page (rich results in Google).
 */
function audira_faq_schema() {
	$landing = (int) get_option( 'audira_landing_page_id' );
	if ( ! ( is_front_page() || ( $landing && is_page( $landing ) ) ) ) {
		return;
	}

	$items = array();
	foreach ( audira_catalog_faqs() as $faq ) {
		$items[] = array(
			'@type'          => 'Question',
			'name'           => $faq[0],
			'acceptedAnswer' => array(
				'@type' => 'Answer',
				'text'  => $faq[1],
			),
		);
	}

	$data = array(
		'@context'   => 'https://schema.org',
		'@type'      => 'FAQPage',
		'mainEntity' => $items,
	);

	echo '<script type="application/ld+json">' . wp_json_encode( $data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . "</script>\n";
}
add_action( 'wp_head', 'audira_faq_schema' );

/* -------------------------------------------------------------------------
 * Performance: drop emoji scripts the theme does not need
 * ---------------------------------------------------------------------- */
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );
remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
remove_action( 'admin_print_styles', 'print_emoji_styles' );

function audira_pattern_categories() {
	register_block_pattern_category( 'audira', array( 'label' => __( 'Audira — Landing', 'audira' ) ) );
}
add_action( 'init', 'audira_pattern_categories' );
