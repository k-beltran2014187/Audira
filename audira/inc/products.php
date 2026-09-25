<?php
/**
 * Products editable from Settings → Audira Affiliate (no code needed).
 *
 * Saved values override the defaults in inc/catalog.php. Each product takes
 * any Amazon link: a full amazon.com URL or a SiteStripe short link
 * (amzn.to). Short links already carry your tag and are left untouched.
 *
 * @package Audira
 */

defined( 'ABSPATH' ) || exit;

/**
 * Saved product settings.
 */
function audira_saved_products() {
	$saved = get_option( 'audira_products', array() );
	return is_array( $saved ) ? $saved : array();
}

/**
 * Merge saved values (non-empty only) over a list of default items.
 */
function audira_apply_saved_products( $group, array $items ) {
	$saved = audira_saved_products();
	if ( empty( $saved[ $group ] ) || ! is_array( $saved[ $group ] ) ) {
		return $items;
	}
	foreach ( $items as $i => $item ) {
		if ( empty( $saved[ $group ][ $i ] ) || ! is_array( $saved[ $group ][ $i ] ) ) {
			continue;
		}
		foreach ( $saved[ $group ][ $i ] as $key => $value ) {
			if ( '' === $value || array() === $value ) {
				continue;
			}
			$items[ $i ][ $key ] = $value;
		}
	}
	return $items;
}

/**
 * Is this an Amazon (or amzn.to) link?
 */
function audira_is_amazon_url( $url ) {
	$host = wp_parse_url( $url, PHP_URL_HOST );
	return $host && preg_match( '/(^|\.)(amazon\.(com|ca|co\.uk|com\.mx|es)|amzn\.to)$/i', $host );
}

/**
 * Real ASIN of a product, or '' when only a short link / placeholder exists.
 */
function audira_product_asin( array $p ) {
	if ( ! empty( $p['url'] ) && preg_match( '#/(?:dp|gp/product|gp/aw/d)/([A-Z0-9]{10})#i', $p['url'], $m ) ) {
		return strtoupper( $m[1] );
	}
	if ( ! empty( $p['asin'] ) && false === strpos( $p['asin'], 'XXX' ) ) {
		return $p['asin'];
	}
	return '';
}

/**
 * Where the product's buttons point.
 */
function audira_product_url( array $p ) {
	if ( ! empty( $p['url'] ) ) {
		return $p['url'];
	}
	return audira_amazon_product_url( isset( $p['asin'] ) ? $p['asin'] : '' );
}

/**
 * Picture of a product: a Media Library / external URL, or a theme image.
 */
function audira_product_image( array $p ) {
	if ( ! empty( $p['image'] ) && preg_match( '#^https?://#', $p['image'] ) ) {
		return esc_url( $p['image'] );
	}
	return audira_img( $p['image'] );
}

/**
 * "More products we recommend": one "Product name | link" per line.
 * Lines without a name are kept in the settings but not shown on the site.
 */
function audira_more_products() {
	$saved = audira_saved_products();
	$text  = isset( $saved['more'] ) ? $saved['more'] : audira_catalog_more_default();

	$items = array();
	foreach ( preg_split( '/\r\n|\r|\n/', (string) $text ) as $line ) {
		$parts = array_map( 'trim', explode( '|', $line, 2 ) );
		if ( 2 !== count( $parts ) || '' === $parts[0] || ! audira_is_amazon_url( $parts[1] ) ) {
			continue;
		}
		$items[] = array(
			'title' => $parts[0],
			'url'   => $parts[1],
		);
	}
	return $items;
}

/* -------------------------------------------------------------------------
 * Settings
 * ---------------------------------------------------------------------- */
function audira_register_product_settings() {
	register_setting(
		'audira_amazon',
		'audira_products',
		array(
			'type'              => 'array',
			'sanitize_callback' => 'audira_sanitize_products',
			'default'           => array(),
		)
	);
}
add_action( 'admin_init', 'audira_register_product_settings' );

function audira_clean_url( $url ) {
	$url = esc_url_raw( trim( (string) $url ) );
	return ( $url && audira_is_amazon_url( $url ) ) ? $url : '';
}

function audira_sanitize_products( $input ) {
	$input = is_array( $input ) ? $input : array();
	$out   = array(
		'picks'  => array(),
		'styles' => array(),
		'more'   => '',
	);

	if ( ! empty( $input['picks'] ) && is_array( $input['picks'] ) ) {
		foreach ( $input['picks'] as $i => $p ) {
			$bullets = isset( $p['bullets'] ) ? $p['bullets'] : array();
			// WordPress may sanitize twice on first save, so accept the array form too.
			$bullets = is_array( $bullets ) ? $bullets : preg_split( '/\r\n|\r|\n/', (string) $bullets );
			$bullets = array_filter( array_map( 'sanitize_text_field', $bullets ) );
			$score   = isset( $p['score'] ) && is_numeric( $p['score'] ) ? number_format( min( 10, max( 0, (float) $p['score'] ) ), 1 ) : '';

			$out['picks'][ absint( $i ) ] = array(
				'url'      => audira_clean_url( isset( $p['url'] ) ? $p['url'] : '' ),
				'title'    => sanitize_text_field( isset( $p['title'] ) ? $p['title'] : '' ),
				'badge'    => sanitize_text_field( isset( $p['badge'] ) ? $p['badge'] : '' ),
				'best_for' => sanitize_text_field( isset( $p['best_for'] ) ? $p['best_for'] : '' ),
				'bullets'  => array_values( $bullets ),
				'score'    => $score,
				'image'    => esc_url_raw( isset( $p['image'] ) ? trim( $p['image'] ) : '' ),
			);
		}
	}

	if ( ! empty( $input['styles'] ) && is_array( $input['styles'] ) ) {
		foreach ( $input['styles'] as $i => $p ) {
			$out['styles'][ absint( $i ) ] = array(
				'url' => audira_clean_url( isset( $p['url'] ) ? $p['url'] : '' ),
			);
		}
	}

	if ( isset( $input['more'] ) ) {
		$lines = array();
		foreach ( preg_split( '/\r\n|\r|\n/', (string) $input['more'] ) as $line ) {
			$line = sanitize_text_field( $line );
			if ( '' !== $line ) {
				$lines[] = $line;
			}
		}
		$out['more'] = implode( "\n", $lines );
	}

	return $out;
}

function audira_products_admin_assets( $hook ) {
	if ( 'settings_page_audira-affiliate' !== $hook ) {
		return;
	}
	wp_enqueue_media();
	wp_add_inline_style(
		'common',
		'.aud-admin-card{background:#fff;border:1px solid #dcdcde;border-radius:8px;padding:16px 20px;margin:0 0 16px;max-width:980px}
		.aud-admin-card h3{margin:0 0 8px}.aud-admin-card .form-table th{width:170px;padding:10px 10px 10px 0}.aud-admin-card .form-table td{padding:8px 10px}
		.aud-admin-thumb{width:64px;height:48px;object-fit:cover;border-radius:6px;vertical-align:middle;margin-right:8px;background:#f0f0f1}'
	);
	wp_add_inline_script(
		'media-editor',
		"jQuery(function($){ $(document).on('click','.aud-pick-image',function(e){ e.preventDefault(); var input=$(this).siblings('input'), img=$(this).siblings('img'); var frame=wp.media({title:'Choose product image',multiple:false,library:{type:'image'}}); frame.on('select',function(){ var a=frame.state().get('selection').first().toJSON(); input.val(a.url); img.attr('src',a.url); }); frame.open(); }); });"
	);
}
add_action( 'admin_enqueue_scripts', 'audira_products_admin_assets' );

/**
 * Image field with a Media Library button.
 */
function audira_admin_image_field( $name, $value, $fallback ) {
	$src = $value ? $value : audira_img( $fallback );
	printf(
		'<img class="aud-admin-thumb" src="%1$s" alt=""><input type="url" class="regular-text" name="%2$s" value="%3$s" placeholder="%4$s"> <button type="button" class="button aud-pick-image">%5$s</button><p class="description">%6$s</p>',
		esc_url( $src ),
		esc_attr( $name ),
		esc_attr( $value ),
		esc_attr__( 'Leave empty to use the built-in illustration', 'audira' ),
		esc_html__( 'Choose image', 'audira' ),
		esc_html__( 'Optional. Upload a product photo to Media first (e.g. from Amazon SiteStripe → Image).', 'audira' )
	);
}

/**
 * Products section of the settings page (inside the same form).
 */
function audira_products_settings_section() {
	$saved  = audira_saved_products();
	$picks  = audira_catalog_picks();
	$styles = audira_catalog_styles();
	$base   = audira_catalog_picks_defaults();
	$more   = isset( $saved['more'] ) ? $saved['more'] : audira_catalog_more_default();
	?>
	<h2><?php esc_html_e( 'Products', 'audira' ); ?></h2>
	<?php if ( ! empty( $saved ) ) : ?>
		<p><a class="button" href="<?php echo esc_url( wp_nonce_url( admin_url( 'admin-post.php?action=audira_reset_products' ), 'audira_reset_products' ) ); ?>" onclick="return confirm('<?php echo esc_js( __( 'Replace your saved products with the theme’s recommended list?', 'audira' ) ); ?>');"><?php esc_html_e( 'Restore the theme’s recommended products', 'audira' ); ?></a></p>
	<?php endif; ?>
	<p><?php esc_html_e( 'Paste the Amazon link of each product (a full amazon.com link or a SiteStripe short link like https://amzn.to/…) and type the product name as it should appear on your site. Changes show on the home page as soon as you save.', 'audira' ); ?></p>

	<h3><?php esc_html_e( 'Editor’s top picks', 'audira' ); ?></h3>
	<?php foreach ( $picks as $i => $p ) : $n = 'audira_products[picks][' . $i . ']'; ?>
		<div class="aud-admin-card">
			<h3>#<?php echo esc_html( $i + 1 ); ?> · <?php echo esc_html( $p['badge'] ); ?></h3>
			<table class="form-table" role="presentation">
				<tr><th><?php esc_html_e( 'Amazon link', 'audira' ); ?></th><td><input type="url" class="large-text" name="<?php echo esc_attr( $n ); ?>[url]" value="<?php echo esc_attr( isset( $p['url'] ) ? $p['url'] : '' ); ?>" placeholder="https://amzn.to/…"></td></tr>
				<tr><th><?php esc_html_e( 'Product name', 'audira' ); ?></th><td><input type="text" class="large-text" name="<?php echo esc_attr( $n ); ?>[title]" value="<?php echo esc_attr( $p['title'] ); ?>"></td></tr>
				<tr><th><?php esc_html_e( 'Badge', 'audira' ); ?></th><td><input type="text" class="regular-text" name="<?php echo esc_attr( $n ); ?>[badge]" value="<?php echo esc_attr( $p['badge'] ); ?>"></td></tr>
				<tr><th><?php esc_html_e( 'Best for', 'audira' ); ?></th><td><input type="text" class="large-text" name="<?php echo esc_attr( $n ); ?>[best_for]" value="<?php echo esc_attr( $p['best_for'] ); ?>"></td></tr>
				<tr><th><?php esc_html_e( 'Highlights (one per line)', 'audira' ); ?></th><td><textarea class="large-text" rows="3" name="<?php echo esc_attr( $n ); ?>[bullets]"><?php echo esc_textarea( implode( "\n", $p['bullets'] ) ); ?></textarea></td></tr>
				<tr><th><?php esc_html_e( 'Your score (0–10)', 'audira' ); ?></th><td><input type="number" step="0.1" min="0" max="10" class="small-text" name="<?php echo esc_attr( $n ); ?>[score]" value="<?php echo esc_attr( $p['score'] ); ?>"></td></tr>
				<tr><th><?php esc_html_e( 'Image', 'audira' ); ?></th><td><?php audira_admin_image_field( $n . '[image]', preg_match( '#^https?://#', $p['image'] ) ? $p['image'] : '', $base[ $i ]['image'] ); ?></td></tr>
			</table>
		</div>
	<?php endforeach; ?>

	<h3><?php esc_html_e( 'Hearing aid styles (one product per style)', 'audira' ); ?></h3>
	<div class="aud-admin-card">
		<table class="form-table" role="presentation">
			<?php foreach ( $styles as $i => $p ) : $n = 'audira_products[styles][' . $i . ']'; ?>
				<tr><th><?php echo esc_html( $p['title'] ); ?></th><td><input type="url" class="large-text" name="<?php echo esc_attr( $n ); ?>[url]" value="<?php echo esc_attr( isset( $p['url'] ) ? $p['url'] : '' ); ?>" placeholder="https://amzn.to/…"></td></tr>
			<?php endforeach; ?>
		</table>
		<p class="description"><?php esc_html_e( 'With a full amazon.com/dp/… link the card shows "Add 1 / 2 / 3 to cart" buttons. With a short amzn.to link it shows one "See it on Amazon" button.', 'audira' ); ?></p>
	</div>

	<h3><?php esc_html_e( 'More products we recommend', 'audira' ); ?></h3>
	<div class="aud-admin-card">
		<p><?php echo wp_kses_post( __( 'One product per line, written as <code>Product name | Amazon link</code>. Lines that have only a link (no name) are saved but <strong>not shown</strong> until you add the name.', 'audira' ) ); ?></p>
		<textarea class="large-text code" rows="9" name="audira_products[more]"><?php echo esc_textarea( $more ); ?></textarea>
	</div>
	<?php
}

/**
 * "Restore the theme's recommended products" button.
 */
function audira_reset_products() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'Sorry, you are not allowed to do that.', 'audira' ) );
	}
	check_admin_referer( 'audira_reset_products' );
	delete_option( 'audira_products' );
	wp_safe_redirect( admin_url( 'options-general.php?page=audira-affiliate&settings-updated=true' ) );
	exit;
}
add_action( 'admin_post_audira_reset_products', 'audira_reset_products' );
