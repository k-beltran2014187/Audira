<?php
/**
 * Products menu (custom post type) — unlimited products managed from WordPress.
 *
 * Each product has a title, a description (the editor), an Amazon link,
 * several Amazon-hosted images with one "main" image, a score, highlights and
 * the attributes the Hearing Aid Finder uses (style, hearing loss, features,
 * price range). Products marked "Top pick #1–5" feed the home page ranking;
 * the rest appear under "More products we recommend" and in the catalog.
 *
 * @package Audira
 */

defined( 'ABSPATH' ) || exit;

const AUDIRA_PRODUCT = 'audira_product';

/* -------------------------------------------------------------------------
 * Vocabulary
 * ---------------------------------------------------------------------- */
function audira_product_styles() {
	return array(
		'ric'    => 'Receiver-in-Canal (RIC)',
		'bte'    => 'Behind-the-Ear (BTE)',
		'ite'    => 'In-the-Ear (ITE / ITC)',
		'iic'    => 'Invisible (IIC / CIC)',
		'earbud' => 'Earbud style',
		'other'  => 'Accessory / other',
	);
}

function audira_product_losses() {
	return array(
		'mild'     => 'Mild',
		'moderate' => 'Moderate',
	);
}

function audira_product_features() {
	return array(
		'rechargeable' => 'Rechargeable',
		'bluetooth'    => 'Bluetooth streaming',
		'app'          => 'Smartphone app',
		'noise'        => 'Speech in noise',
		'tinnitus'     => 'Tinnitus relief',
		'long_battery' => 'Long battery life',
		'waterproof'   => 'Water resistant',
		'discreet'     => 'Very discreet',
		'audiology'    => 'Professional support',
	);
}

function audira_product_tiers() {
	return array(
		'budget'  => 'Budget',
		'mid'     => 'Mid-range',
		'premium' => 'Premium',
	);
}

/* -------------------------------------------------------------------------
 * Post type
 * ---------------------------------------------------------------------- */
function audira_register_product_cpt() {
	register_post_type(
		AUDIRA_PRODUCT,
		array(
			'labels'       => array(
				'name'          => __( 'Products', 'audira' ),
				'singular_name' => __( 'Product', 'audira' ),
				'add_new'       => __( 'Add New Product', 'audira' ),
				'add_new_item'  => __( 'Add New Product', 'audira' ),
				'edit_item'     => __( 'Edit Product', 'audira' ),
				'all_items'     => __( 'All Products', 'audira' ),
				'search_items'  => __( 'Search Products', 'audira' ),
				'menu_name'     => __( 'Products', 'audira' ),
			),
			'public'       => true,
			'has_archive'  => 'products',
			'rewrite'      => array( 'slug' => 'products', 'with_front' => false ),
			'menu_icon'    => 'dashicons-cart',
			'menu_position' => 5,
			'supports'     => array( 'title', 'editor', 'excerpt', 'page-attributes' ),
			'show_in_rest' => true,
		)
	);
}
add_action( 'init', 'audira_register_product_cpt' );

/**
 * Flush permalinks once when the product URLs are introduced.
 */
function audira_maybe_flush_rewrites() {
	if ( get_option( 'audira_rewrite_version' ) !== '1' ) {
		flush_rewrite_rules( false );
		update_option( 'audira_rewrite_version', '1' );
	}
}
add_action( 'init', 'audira_maybe_flush_rewrites', 20 );

/* -------------------------------------------------------------------------
 * Reading product data
 * ---------------------------------------------------------------------- */
function audira_meta( $id, $key, $default = '' ) {
	$v = get_post_meta( $id, '_aud_' . $key, true );
	return ( '' === $v || null === $v ) ? $default : $v;
}

/**
 * Normalized product array (same keys the home page patterns use).
 */
function audira_product_data( $post ) {
	$post   = get_post( $post );
	$id     = $post->ID;
	$images = array_values( array_filter( (array) audira_meta( $id, 'images', array() ) ) );
	$main   = (int) audira_meta( $id, 'main', 0 );
	if ( $main > 0 && isset( $images[ $main ] ) ) {
		// Main image first.
		$first = $images[ $main ];
		unset( $images[ $main ] );
		array_unshift( $images, $first );
	}
	$style = audira_meta( $id, 'style', 'ric' );

	return array(
		'id'        => $id,
		'title'     => get_the_title( $post ),
		'permalink' => get_permalink( $post ),
		'url'       => audira_meta( $id, 'url' ),
		'asin'      => '',
		'badge'     => audira_meta( $id, 'badge' ),
		'score'     => audira_meta( $id, 'score' ),
		'best_for'  => audira_meta( $id, 'best_for' ),
		'bullets'   => array_values( array_filter( (array) audira_meta( $id, 'bullets', array() ) ) ),
		'images'    => $images,
		'image'     => $images ? $images[0] : audira_meta( $id, 'fallback', 'type-' . ( in_array( $style, array( 'bte', 'ric', 'ite', 'iic' ), true ) ? $style : 'ric' ) . '.svg' ),
		'rank'      => (int) audira_meta( $id, 'top_rank', 0 ),
		'featured'  => 1 === (int) audira_meta( $id, 'top_rank', 0 ),
		'style'     => $style,
		'loss'      => (array) audira_meta( $id, 'loss', array() ),
		'features'  => (array) audira_meta( $id, 'features', array() ),
		'tier'      => audira_meta( $id, 'tier', 'mid' ),
		'excerpt'   => has_excerpt( $post ) ? get_the_excerpt( $post ) : wp_trim_words( wp_strip_all_tags( $post->post_content ), 28 ),
	);
}

/**
 * All published products: top picks first (by rank), then by menu order / score.
 */
function audira_all_products() {
	static $cache = null;
	if ( null !== $cache ) {
		return $cache;
	}
	$posts = get_posts(
		array(
			'post_type'      => AUDIRA_PRODUCT,
			'post_status'    => 'publish',
			'posts_per_page' => 200,
			'orderby'        => array( 'menu_order' => 'ASC', 'date' => 'DESC' ),
		)
	);
	$items = array_map( 'audira_product_data', $posts );
	usort(
		$items,
		static function ( $a, $b ) {
			$ra = $a['rank'] ?: 99;
			$rb = $b['rank'] ?: 99;
			if ( $ra !== $rb ) {
				return $ra - $rb;
			}
			return (float) $b['score'] <=> (float) $a['score'];
		}
	);
	$cache = $items;
	return $items;
}

function audira_top_products() {
	return array_values( array_filter( audira_all_products(), static function ( $p ) {
		return $p['rank'] > 0;
	} ) );
}

function audira_other_products() {
	return array_values( array_filter( audira_all_products(), static function ( $p ) {
		return 0 === $p['rank'];
	} ) );
}

/* -------------------------------------------------------------------------
 * Admin: product editor meta box
 * ---------------------------------------------------------------------- */
function audira_product_meta_boxes() {
	add_meta_box( 'audira_product_details', __( 'Product details', 'audira' ), 'audira_product_meta_box', AUDIRA_PRODUCT, 'normal', 'high' );
}
add_action( 'add_meta_boxes', 'audira_product_meta_boxes' );

function audira_product_meta_box( $post ) {
	wp_nonce_field( 'audira_product_save', 'audira_product_nonce' );
	$id       = $post->ID;
	$images   = array_values( (array) audira_meta( $id, 'images', array() ) );
	$main     = (int) audira_meta( $id, 'main', 0 );
	$loss     = (array) audira_meta( $id, 'loss', array( 'mild', 'moderate' ) );
	$features = (array) audira_meta( $id, 'features', array() );
	if ( ! $images ) {
		$images = array( '' );
	}
	?>
	<style>
		.aud-mb th{width:190px;text-align:left;padding:12px 10px 12px 0;vertical-align:top}.aud-mb td{padding:8px 0}
		.aud-imgs{display:grid;gap:8px}.aud-img-row{display:flex;gap:8px;align-items:center;padding:8px;border:1px solid #dcdcde;border-radius:8px;background:#fff}
		.aud-img-row.is-main{border-color:#2271b1;box-shadow:0 0 0 1px #2271b1}.aud-img-row img{width:64px;height:64px;object-fit:contain;background:#f6f7f7;border-radius:6px}
		.aud-img-row input[type=url]{flex:1}.aud-img-row label{white-space:nowrap;font-weight:600}.aud-checks{display:flex;flex-wrap:wrap;gap:6px 18px}
	</style>
	<table class="aud-mb" role="presentation">
		<tr><th><?php esc_html_e( 'Amazon link', 'audira' ); ?></th><td><input type="url" class="large-text" name="aud[url]" value="<?php echo esc_attr( audira_meta( $id, 'url' ) ); ?>" placeholder="https://amzn.to/…"><p class="description"><?php esc_html_e( 'Your SiteStripe short link (amzn.to) or the full amazon.com product link.', 'audira' ); ?></p></td></tr>
		<tr><th><?php esc_html_e( 'Images', 'audira' ); ?></th><td>
			<div class="aud-imgs" id="aud-imgs">
				<?php foreach ( $images as $i => $src ) : ?>
					<div class="aud-img-row<?php echo $i === $main ? ' is-main' : ''; ?>">
						<img src="<?php echo esc_url( $src ); ?>" alt="">
						<input type="url" name="aud[images][]" value="<?php echo esc_attr( $src ); ?>" placeholder="https://m.media-amazon.com/images/I/…">
						<label><input type="radio" name="aud[main]" value="<?php echo esc_attr( $i ); ?>" <?php checked( $i, $main ); ?>> <?php esc_html_e( 'Main', 'audira' ); ?></label>
						<button type="button" class="button-link-delete aud-img-remove"><?php esc_html_e( 'Remove', 'audira' ); ?></button>
					</div>
				<?php endforeach; ?>
			</div>
			<p><button type="button" class="button" id="aud-img-add">+ <?php esc_html_e( 'Add image', 'audira' ); ?></button></p>
			<p class="description"><?php esc_html_e( 'On Amazon, open each product photo, right-click → "Copy image address" and paste one per row. Pick the Main image visitors see first. Photos stay on Amazon’s servers, as Amazon requires.', 'audira' ); ?></p>
		</td></tr>
		<tr><th><?php esc_html_e( 'Top pick on home page', 'audira' ); ?></th><td>
			<select name="aud[top_rank]">
				<option value="0"><?php esc_html_e( 'No — show under "More products"', 'audira' ); ?></option>
				<?php for ( $r = 1; $r <= 5; $r++ ) : ?>
					<option value="<?php echo esc_attr( $r ); ?>" <?php selected( (int) audira_meta( $id, 'top_rank', 0 ), $r ); ?>>#<?php echo esc_html( $r ); ?><?php echo 1 === $r ? ' (featured)' : ''; ?></option>
				<?php endfor; ?>
			</select>
		</td></tr>
		<tr><th><?php esc_html_e( 'Badge', 'audira' ); ?></th><td><input type="text" class="regular-text" name="aud[badge]" value="<?php echo esc_attr( audira_meta( $id, 'badge' ) ); ?>" placeholder="Best value"></td></tr>
		<tr><th><?php esc_html_e( 'Your score (0–10)', 'audira' ); ?></th><td><input type="number" step="0.1" min="0" max="10" class="small-text" name="aud[score]" value="<?php echo esc_attr( audira_meta( $id, 'score' ) ); ?>"></td></tr>
		<tr><th><?php esc_html_e( 'Best for', 'audira' ); ?></th><td><input type="text" class="large-text" name="aud[best_for]" value="<?php echo esc_attr( audira_meta( $id, 'best_for' ) ); ?>"></td></tr>
		<tr><th><?php esc_html_e( 'Highlights (one per line)', 'audira' ); ?></th><td><textarea class="large-text" rows="4" name="aud[bullets]"><?php echo esc_textarea( implode( "\n", (array) audira_meta( $id, 'bullets', array() ) ) ); ?></textarea></td></tr>
		<tr><th><?php esc_html_e( 'Style', 'audira' ); ?></th><td><select name="aud[style]"><?php foreach ( audira_product_styles() as $k => $label ) : ?><option value="<?php echo esc_attr( $k ); ?>" <?php selected( audira_meta( $id, 'style', 'ric' ), $k ); ?>><?php echo esc_html( $label ); ?></option><?php endforeach; ?></select></td></tr>
		<tr><th><?php esc_html_e( 'Hearing loss it suits', 'audira' ); ?></th><td><div class="aud-checks"><?php foreach ( audira_product_losses() as $k => $label ) : ?><label><input type="checkbox" name="aud[loss][]" value="<?php echo esc_attr( $k ); ?>" <?php checked( in_array( $k, $loss, true ) ); ?>> <?php echo esc_html( $label ); ?></label><?php endforeach; ?></div></td></tr>
		<tr><th><?php esc_html_e( 'Features', 'audira' ); ?></th><td><div class="aud-checks"><?php foreach ( audira_product_features() as $k => $label ) : ?><label><input type="checkbox" name="aud[features][]" value="<?php echo esc_attr( $k ); ?>" <?php checked( in_array( $k, $features, true ) ); ?>> <?php echo esc_html( $label ); ?></label><?php endforeach; ?></div></td></tr>
		<tr><th><?php esc_html_e( 'Price range', 'audira' ); ?></th><td><select name="aud[tier]"><?php foreach ( audira_product_tiers() as $k => $label ) : ?><option value="<?php echo esc_attr( $k ); ?>" <?php selected( audira_meta( $id, 'tier', 'mid' ), $k ); ?>><?php echo esc_html( $label ); ?></option><?php endforeach; ?></select><p class="description"><?php esc_html_e( 'Used by the Hearing Aid Finder budget question. Exact prices are always shown on Amazon.', 'audira' ); ?></p></td></tr>
	</table>
	<p class="description"><?php esc_html_e( 'Write the full product description in the editor above. The short "Excerpt" (optional) is used on catalog cards.', 'audira' ); ?></p>
	<script>
	( function () {
		var box = document.getElementById( 'aud-imgs' );
		function renumber() {
			box.querySelectorAll( '.aud-img-row' ).forEach( function ( row, i ) {
				var r = row.querySelector( 'input[type=radio]' );
				r.value = i;
				row.classList.toggle( 'is-main', r.checked );
			} );
		}
		document.getElementById( 'aud-img-add' ).addEventListener( 'click', function () {
			var row = box.querySelector( '.aud-img-row' ).cloneNode( true );
			row.querySelector( 'input[type=url]' ).value = '';
			row.querySelector( 'img' ).removeAttribute( 'src' );
			row.querySelector( 'input[type=radio]' ).checked = false;
			box.appendChild( row );
			renumber();
		} );
		box.addEventListener( 'click', function ( e ) {
			if ( e.target.classList.contains( 'aud-img-remove' ) ) {
				if ( box.querySelectorAll( '.aud-img-row' ).length > 1 ) {
					e.target.closest( '.aud-img-row' ).remove();
				} else {
					e.target.closest( '.aud-img-row' ).querySelector( 'input[type=url]' ).value = '';
				}
				if ( ! box.querySelector( 'input[type=radio]:checked' ) ) {
					box.querySelector( 'input[type=radio]' ).checked = true;
				}
				renumber();
			}
		} );
		box.addEventListener( 'change', renumber );
		box.addEventListener( 'input', function ( e ) {
			if ( 'url' === e.target.type ) {
				e.target.closest( '.aud-img-row' ).querySelector( 'img' ).src = e.target.value;
			}
		} );
	} )();
	</script>
	<?php
}

function audira_save_product( $post_id ) {
	if ( ! isset( $_POST['audira_product_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['audira_product_nonce'] ) ), 'audira_product_save' ) ) {
		return;
	}
	if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	$in = isset( $_POST['aud'] ) && is_array( $_POST['aud'] ) ? wp_unslash( $_POST['aud'] ) : array(); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput

	$images = array();
	$main   = isset( $in['main'] ) ? absint( $in['main'] ) : 0;
	$new    = 0;
	foreach ( (array) ( $in['images'] ?? array() ) as $i => $src ) {
		$src = esc_url_raw( trim( (string) $src ) );
		if ( $src && preg_match( '#^https://#', $src ) ) {
			if ( $i === $main ) {
				$new = count( $images );
			}
			$images[] = $src;
		}
	}

	$features = array_values( array_intersect( (array) ( $in['features'] ?? array() ), array_keys( audira_product_features() ) ) );
	$loss     = array_values( array_intersect( (array) ( $in['loss'] ?? array() ), array_keys( audira_product_losses() ) ) );
	$score    = isset( $in['score'] ) && is_numeric( $in['score'] ) ? number_format( min( 10, max( 0, (float) $in['score'] ) ), 1 ) : '';
	$bullets  = array_values( array_filter( array_map( 'sanitize_text_field', preg_split( '/\r\n|\r|\n/', (string) ( $in['bullets'] ?? '' ) ) ) ) );
	$style    = sanitize_key( $in['style'] ?? 'ric' );
	$tier     = sanitize_key( $in['tier'] ?? 'mid' );

	$meta = array(
		'url'      => audira_clean_url( $in['url'] ?? '' ),
		'images'   => $images,
		'main'     => $new,
		'top_rank' => min( 5, absint( $in['top_rank'] ?? 0 ) ),
		'badge'    => sanitize_text_field( $in['badge'] ?? '' ),
		'score'    => $score,
		'best_for' => sanitize_text_field( $in['best_for'] ?? '' ),
		'bullets'  => $bullets,
		'style'    => array_key_exists( $style, audira_product_styles() ) ? $style : 'ric',
		'loss'     => $loss,
		'features' => $features,
		'tier'     => array_key_exists( $tier, audira_product_tiers() ) ? $tier : 'mid',
	);
	foreach ( $meta as $key => $value ) {
		update_post_meta( $post_id, '_aud_' . $key, $value );
	}
}
add_action( 'save_post_' . AUDIRA_PRODUCT, 'audira_save_product' );

/* Admin list columns */
function audira_product_columns( $cols ) {
	$new = array();
	foreach ( $cols as $k => $v ) {
		if ( 'title' === $k ) {
			$new['aud_img'] = '';
		}
		$new[ $k ] = $v;
		if ( 'title' === $k ) {
			$new['aud_rank']  = __( 'Top pick', 'audira' );
			$new['aud_score'] = __( 'Score', 'audira' );
		}
	}
	return $new;
}
add_filter( 'manage_' . AUDIRA_PRODUCT . '_posts_columns', 'audira_product_columns' );

function audira_product_column( $col, $id ) {
	if ( 'aud_img' === $col ) {
		$p = audira_product_data( $id );
		printf( '<img src="%s" alt="" style="width:48px;height:48px;object-fit:contain;background:#fff;border-radius:6px">', audira_product_image( $p ) );
	} elseif ( 'aud_rank' === $col ) {
		$r = (int) audira_meta( $id, 'top_rank', 0 );
		echo $r ? '#' . esc_html( $r ) : '—';
	} elseif ( 'aud_score' === $col ) {
		echo esc_html( audira_meta( $id, 'score', '—' ) );
	}
}
add_action( 'manage_' . AUDIRA_PRODUCT . '_posts_custom_column', 'audira_product_column', 10, 2 );

/* -------------------------------------------------------------------------
 * One-time import of the products that used to live in Settings
 * ---------------------------------------------------------------------- */
function audira_seed_products() {
	if ( get_option( 'audira_products_seeded' ) || ! post_type_exists( AUDIRA_PRODUCT ) ) {
		return;
	}
	update_option( 'audira_products_seeded', 1 );

	$seed = audira_catalog_seed_products();

	// Keep anything the site owner already saved in Settings (names, images, links…).
	foreach ( audira_catalog_picks_legacy() as $i => $p ) {
		foreach ( $seed as &$s ) {
			if ( isset( $s['legacy_pick'] ) && $s['legacy_pick'] === $i ) {
				foreach ( array( 'title', 'url', 'badge', 'score', 'best_for', 'bullets' ) as $k ) {
					if ( ! empty( $p[ $k ] ) ) {
						$s[ $k ] = $p[ $k ];
					}
				}
				if ( ! empty( $p['image'] ) && preg_match( '#^https://#', $p['image'] ) ) {
					$s['images'] = array( $p['image'] );
				}
			}
		}
		unset( $s );
	}

	foreach ( $seed as $order => $s ) {
		$id = wp_insert_post(
			array(
				'post_type'    => AUDIRA_PRODUCT,
				'post_status'  => 'publish',
				'post_title'   => $s['title'],
				'post_content' => '<!-- wp:paragraph --><p>' . esc_html( $s['description'] ) . '</p><!-- /wp:paragraph -->',
				'menu_order'   => $order,
			)
		);
		if ( ! $id || is_wp_error( $id ) ) {
			continue;
		}
		$meta = array(
			'url'      => $s['url'],
			'images'   => isset( $s['images'] ) ? $s['images'] : array(),
			'main'     => 0,
			'fallback' => $s['fallback'],
			'top_rank' => $s['rank'],
			'badge'    => $s['badge'],
			'score'    => $s['score'],
			'best_for' => $s['best_for'],
			'bullets'  => $s['bullets'],
			'style'    => $s['style'],
			'loss'     => array( 'mild', 'moderate' ),
			'features' => $s['features'],
			'tier'     => $s['tier'],
		);
		foreach ( $meta as $k => $v ) {
			update_post_meta( $id, '_aud_' . $k, $v );
		}
	}
}
add_action( 'init', 'audira_seed_products', 30 );
