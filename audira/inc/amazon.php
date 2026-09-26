<?php
/**
 * Amazon Associates: settings screen, link builders and tag enforcement.
 *
 * @package Audira
 */

defined( 'ABSPATH' ) || exit;

/* -------------------------------------------------------------------------
 * Defaults. You can also hard-code them in wp-config.php:
 *   define( 'AUDIRA_AMAZON_TAG', 'yourstore-20' );
 * The value saved in Settings → Audira Affiliate always wins.
 * ---------------------------------------------------------------------- */
if ( ! defined( 'AUDIRA_AMAZON_DOMAIN' ) ) {
	define( 'AUDIRA_AMAZON_DOMAIN', 'www.amazon.com' );
}
if ( ! defined( 'AUDIRA_AMAZON_TAG' ) ) {
	define( 'AUDIRA_AMAZON_TAG', 'yourtag-20' );
}

/**
 * Marketplaces offered in the settings screen.
 */
function audira_amazon_marketplaces() {
	return array(
		'www.amazon.com'    => 'United States — amazon.com',
		'www.amazon.ca'     => 'Canada — amazon.ca',
		'www.amazon.co.uk'  => 'United Kingdom — amazon.co.uk',
		'www.amazon.com.mx' => 'Mexico — amazon.com.mx',
		'www.amazon.es'     => 'Spain — amazon.es',
	);
}

/**
 * Saved settings merged with defaults.
 */
function audira_amazon_settings() {
	$saved = get_option( 'audira_amazon', array() );
	$saved = is_array( $saved ) ? $saved : array();

	$settings = wp_parse_args(
		array_filter( $saved ),
		array(
			'tag'    => AUDIRA_AMAZON_TAG,
			'domain' => AUDIRA_AMAZON_DOMAIN,
		)
	);

	if ( ! array_key_exists( $settings['domain'], audira_amazon_marketplaces() ) ) {
		$settings['domain'] = 'www.amazon.com';
	}

	return $settings;
}

function audira_amazon_tag() {
	return audira_amazon_settings()['tag'];
}

function audira_amazon_domain() {
	return audira_amazon_settings()['domain'];
}

/**
 * Product detail page with the affiliate tag.
 */
function audira_amazon_product_url( $asin ) {
	return sprintf( 'https://%s/dp/%s?tag=%s', audira_amazon_domain(), rawurlencode( $asin ), rawurlencode( audira_amazon_tag() ) );
}

/**
 * Adds a product and quantity straight to the visitor's Amazon cart.
 */
function audira_amazon_cart_url( $asin, $qty = 1 ) {
	return sprintf(
		'https://%s/gp/aws/cart/add.html?ASIN.1=%s&Quantity.1=%d&AssociateTag=%s',
		audira_amazon_domain(),
		rawurlencode( $asin ),
		max( 1, absint( $qty ) ),
		rawurlencode( audira_amazon_tag() )
	);
}

/**
 * Amazon search results with the affiliate tag.
 */
function audira_amazon_search_url( $query = 'otc hearing aids for seniors' ) {
	return sprintf( 'https://%s/s?k=%s&tag=%s', audira_amazon_domain(), rawurlencode( $query ), rawurlencode( audira_amazon_tag() ) );
}

/* -------------------------------------------------------------------------
 * Tag enforcement
 * Every Amazon link rendered on the site gets the current tag, including
 * links typed by hand in the editor and links saved before the tag changed.
 * ---------------------------------------------------------------------- */
function audira_tag_amazon_url( $url ) {
	$parts = wp_parse_url( html_entity_decode( $url ) );
	if ( empty( $parts['host'] ) || ! preg_match( '/(^|\.)amazon\.(com|ca|co\.uk|com\.mx|es)$/i', $parts['host'] ) ) {
		return $url;
	}

	$query = array();
	if ( ! empty( $parts['query'] ) ) {
		parse_str( $parts['query'], $query );
	}

	$is_cart = isset( $parts['path'] ) && false !== strpos( $parts['path'], '/gp/aws/cart/add' );
	if ( $is_cart ) {
		$query['AssociateTag'] = audira_amazon_tag();
	} else {
		$query['tag'] = audira_amazon_tag();
	}

	$rebuilt = sprintf( 'https://%s%s', $parts['host'], isset( $parts['path'] ) ? $parts['path'] : '/' );

	return $rebuilt . '?' . http_build_query( $query, '', '&', PHP_QUERY_RFC3986 );
}

function audira_filter_amazon_links( $html ) {
	if ( false === stripos( $html, 'amazon.' ) ) {
		return $html;
	}

	return preg_replace_callback(
		'#href=(["\'])(https?://(?:[a-z0-9-]+\.)*amazon\.[a-z.]+/[^"\']*)\1#i',
		static function ( $m ) {
			return 'href=' . $m[1] . esc_url( audira_tag_amazon_url( $m[2] ) ) . $m[1];
		},
		$html
	);
}

function audira_render_block_amazon( $content ) {
	return audira_filter_amazon_links( $content );
}
add_filter( 'render_block', 'audira_render_block_amazon', 20 );

/* -------------------------------------------------------------------------
 * Settings → Audira Affiliate
 * ---------------------------------------------------------------------- */
function audira_register_settings() {
	register_setting(
		'audira_amazon',
		'audira_amazon',
		array(
			'type'              => 'array',
			'sanitize_callback' => 'audira_sanitize_settings',
			'default'           => array(),
		)
	);
}
add_action( 'admin_init', 'audira_register_settings' );

function audira_sanitize_settings( $input ) {
	$input  = is_array( $input ) ? $input : array();
	$tag    = isset( $input['tag'] ) ? preg_replace( '/[^A-Za-z0-9_-]/', '', $input['tag'] ) : '';
	$domain = isset( $input['domain'] ) ? sanitize_text_field( $input['domain'] ) : 'www.amazon.com';

	if ( ! array_key_exists( $domain, audira_amazon_marketplaces() ) ) {
		$domain = 'www.amazon.com';
	}

	return array(
		'tag'    => $tag,
		'domain' => $domain,
	);
}

function audira_settings_menu() {
	add_options_page(
		__( 'Audira Affiliate', 'audira' ),
		__( 'Audira Affiliate', 'audira' ),
		'manage_options',
		'audira-affiliate',
		'audira_settings_page'
	);
}
add_action( 'admin_menu', 'audira_settings_menu' );

function audira_settings_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$s = audira_amazon_settings();
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Audira — Amazon Associates', 'audira' ); ?></h1>
		<p><?php esc_html_e( 'Every Amazon link on the site (buttons, cart links and links you add in the editor) is rewritten with the tag below when the page is displayed.', 'audira' ); ?></p>
		<form method="post" action="options.php">
			<?php settings_fields( 'audira_amazon' ); ?>
			<table class="form-table" role="presentation">
				<tr>
					<th scope="row"><label for="audira-tag"><?php esc_html_e( 'Associate tag (Tracking ID)', 'audira' ); ?></label></th>
					<td>
						<input name="audira_amazon[tag]" id="audira-tag" type="text" class="regular-text" value="<?php echo esc_attr( $s['tag'] ); ?>" placeholder="yourstore-20">
						<p class="description"><?php esc_html_e( 'Find it in Amazon Associates Central → Account Settings → Manage Your Tracking IDs. U.S. tags end in -20.', 'audira' ); ?></p>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="audira-domain"><?php esc_html_e( 'Marketplace', 'audira' ); ?></label></th>
					<td>
						<select name="audira_amazon[domain]" id="audira-domain">
							<?php foreach ( audira_amazon_marketplaces() as $domain => $label ) : ?>
								<option value="<?php echo esc_attr( $domain ); ?>" <?php selected( $s['domain'], $domain ); ?>><?php echo esc_html( $label ); ?></option>
							<?php endforeach; ?>
						</select>
					</td>
				</tr>
			</table>
			<?php audira_assistant_settings_section(); ?>
			<?php audira_products_settings_section(); ?>
			<?php submit_button(); ?>
		</form>
	</div>
	<?php
}

/**
 * Reminder until a real tag is saved.
 */
function audira_missing_tag_notice() {
	if ( ! current_user_can( 'manage_options' ) || AUDIRA_AMAZON_TAG !== audira_amazon_tag() ) {
		return;
	}
	printf(
		'<div class="notice notice-warning"><p>%s <a href="%s">%s</a></p></div>',
		esc_html__( 'Audira: your Amazon Associate tag is not set yet, so purchases will not be credited to you.', 'audira' ),
		esc_url( admin_url( 'options-general.php?page=audira-affiliate' ) ),
		esc_html__( 'Add your tag', 'audira' )
	);
}
add_action( 'admin_notices', 'audira_missing_tag_notice' );
