<?php
/**
 * Front-end views for the Products menu:
 *  [audira_catalog]  catalog page (/products/) with the Hearing Aid Finder and filters
 *  [audira_product]  single product page with image gallery
 * Both are placed in the theme templates, so there is nothing to set up.
 *
 * @package Audira
 */

defined( 'ABSPATH' ) || exit;

function audira_catalog_url() {
	$url = get_post_type_archive_link( AUDIRA_PRODUCT );
	return $url ? $url : home_url( '/products/' );
}

/**
 * Remove whitespace between tags so WordPress' automatic <p>/<br> formatting
 * of shortcode output can't break the layout.
 */
function audira_compact_html( $html ) {
	return trim( preg_replace( '/>\s+</', '><', $html ) );
}

function audira_catalog_assets() {
	if ( is_post_type_archive( AUDIRA_PRODUCT ) || is_singular( AUDIRA_PRODUCT ) ) {
		wp_enqueue_script( 'audira-catalog', get_theme_file_uri( 'assets/js/catalog.js' ), array(), audira_asset_version( 'assets/js/catalog.js' ), array( 'in_footer' => true, 'strategy' => 'defer' ) );
	}
}
add_action( 'wp_enqueue_scripts', 'audira_catalog_assets' );

/**
 * Product card used by the catalog and the home page.
 */
function audira_product_card( array $p, $heading = 'h3' ) {
	$features = audira_product_features();
	$chips    = array_slice( array_intersect_key( $features, array_flip( $p['features'] ) ), 0, 3 );
	$photo    = preg_match( '#^https?://#', $p['image'] );
	$details  = ! empty( $p['permalink'] ) ? $p['permalink'] : audira_product_url( $p );
	ob_start();
	?>
	<article class="aud-card<?php echo $photo ? ' aud-card--photo' : ''; ?>" data-style="<?php echo esc_attr( $p['style'] ?? '' ); ?>" data-features="<?php echo esc_attr( implode( ' ', $p['features'] ?? array() ) ); ?>" data-tier="<?php echo esc_attr( $p['tier'] ?? '' ); ?>" data-score="<?php echo esc_attr( $p['score'] ?? 0 ); ?>" data-rank="<?php echo esc_attr( ! empty( $p['rank'] ) ? $p['rank'] : 99 ); ?>">
		<a class="aud-card__img" href="<?php echo esc_url( $details ); ?>" tabindex="-1" aria-hidden="true"><img src="<?php echo audira_product_image( $p ); ?>" alt="" loading="lazy"></a>
		<div class="aud-card__body">
			<div class="aud-card__meta">
				<?php if ( ! empty( $p['badge'] ) ) : ?><span class="aud-badge"><?php echo esc_html( $p['badge'] ); ?></span><?php endif; ?>
				<?php if ( ! empty( $p['score'] ) ) : ?><span class="aud-score"><strong><?php echo esc_html( $p['score'] ); ?></strong><span>/10</span></span><?php endif; ?>
			</div>
			<<?php echo esc_html( $heading ); ?> class="aud-card__title"><a href="<?php echo esc_url( $details ); ?>"><?php echo esc_html( $p['title'] ); ?></a></<?php echo esc_html( $heading ); ?>>
			<?php if ( ! empty( $p['best_for'] ) ) : ?><p class="aud-card__for"><?php echo esc_html( $p['best_for'] ); ?></p><?php endif; ?>
			<?php if ( $chips ) : ?><ul class="aud-chips"><?php foreach ( $chips as $label ) : ?><li><?php echo esc_html( $label ); ?></li><?php endforeach; ?></ul><?php endif; ?>
			<div class="aud-card__actions">
				<?php if ( ! empty( $p['permalink'] ) ) : ?><a class="aud-cta aud-cta--ghost" href="<?php echo esc_url( $p['permalink'] ); ?>">View details</a><?php endif; ?>
				<a class="aud-cta" href="<?php echo esc_url( audira_product_url( $p ) ); ?>" target="_blank" rel="sponsored nofollow noopener">Check price</a>
			</div>
		</div>
	</article>
	<?php
	return audira_compact_html( ob_get_clean() );
}

/* -------------------------------------------------------------------------
 * Catalog page + Hearing Aid Finder
 * ---------------------------------------------------------------------- */
function audira_catalog_shortcode() {
	$products = audira_all_products();
	$styles   = audira_product_styles();
	$present  = array_unique( wp_list_pluck( $products, 'style' ) );
	$data     = array();
	foreach ( $products as $p ) {
		$data[] = array(
			'id'       => $p['id'],
			'title'    => $p['title'],
			'score'    => (float) $p['score'],
			'style'    => $p['style'],
			'loss'     => $p['loss'],
			'features' => $p['features'],
			'tier'     => $p['tier'],
			'bestFor'  => $p['best_for'],
			'image'    => html_entity_decode( audira_product_image( $p ) ),
			'photo'    => (bool) preg_match( '#^https?://#', $p['image'] ),
			'link'     => $p['permalink'],
			'buy'      => audira_product_url( $p ),
		);
	}
	ob_start();
	?>
	<section class="aud-catalog-hero">
		<div class="aud-wrap">
			<p class="aud-eyebrow">Catalog · <?php echo esc_html( count( $products ) ); ?> hearing aids</p>
			<h1 class="aud-catalog-hero__title">Find the hearing aid that fits <em>your</em> life</h1>
			<p class="aud-lead">Answer five quick questions and we’ll match you with the options that suit your hearing, habits and budget — or browse the full catalog below.</p>
			<p class="aud-catalog-hero__actions"><a class="aud-cta aud-cta--lg" href="#finder">Start the Hearing Aid Finder</a> <a class="aud-cta aud-cta--ghost aud-cta--lg" href="#catalog">Browse all</a></p>
		</div>
	</section>

	<section id="finder" class="aud-finder">
		<div class="aud-wrap">
			<div class="aud-finder__panel">
				<div class="aud-finder__intro">
					<p class="aud-eyebrow">Hearing Aid Finder</p>
					<h2 class="aud-finder__title">Your personal match in about a minute</h2>
					<p>Your answers stay in your browser — nothing is stored or sent anywhere.</p>
				</div>
				<form class="aud-finder__form" novalidate>
					<fieldset>
						<legend><span>1</span> How old are you (or the person you’re shopping for)?</legend>
						<div class="aud-opts">
							<label><input type="radio" name="age" value="u18"> Under 18</label>
							<label><input type="radio" name="age" value="18-59"> 18–59</label>
							<label><input type="radio" name="age" value="60-74" checked> 60–74</label>
							<label><input type="radio" name="age" value="75"> 75+</label>
						</div>
					</fieldset>
					<fieldset>
						<legend><span>2</span> Which best describes the hearing difficulty?</legend>
						<div class="aud-opts aud-opts--stack">
							<label><input type="radio" name="loss" value="mild" checked> <strong>Mild</strong> — I miss words in noisy places like restaurants</label>
							<label><input type="radio" name="loss" value="moderate"> <strong>Moderate</strong> — I often ask people to repeat, and turn the TV up loud</label>
							<label><input type="radio" name="loss" value="severe"> <strong>Severe</strong> — I struggle even in quiet rooms or on the phone</label>
							<label><input type="radio" name="loss" value="unsure"> I’m not sure</label>
						</div>
					</fieldset>
					<fieldset>
						<legend><span>3</span> Does any of this apply? <small>(optional)</small></legend>
						<div class="aud-opts">
							<label><input type="checkbox" name="cond" value="tinnitus"> Ringing in the ears (tinnitus)</label>
							<label><input type="checkbox" name="cond" value="dexterity"> Trouble handling small objects</label>
							<label><input type="checkbox" name="cond" value="glasses"> I wear glasses</label>
							<label><input type="checkbox" name="cond" value="phone"> I’m often on the phone</label>
							<label><input type="checkbox" name="cond" value="redflag"> Sudden change, one ear only, pain or dizziness</label>
						</div>
					</fieldset>
					<fieldset>
						<legend><span>4</span> What matters most? <small>(pick up to 2)</small></legend>
						<div class="aud-opts" data-max="2">
							<label><input type="checkbox" name="prio" value="sound"> Best sound</label>
							<label><input type="checkbox" name="prio" value="discreet"> Discreet look</label>
							<label><input type="checkbox" name="prio" value="battery"> Battery life</label>
							<label><input type="checkbox" name="prio" value="streaming"> Calls, TV &amp; music</label>
							<label><input type="checkbox" name="prio" value="support"> Expert support</label>
							<label><input type="checkbox" name="prio" value="price"> Lowest price</label>
						</div>
					</fieldset>
					<fieldset>
						<legend><span>5</span> What’s your budget for a pair?</legend>
						<div class="aud-opts">
							<label><input type="radio" name="budget" value="budget"> Under $300</label>
							<label><input type="radio" name="budget" value="mid" checked> $300–$700</label>
							<label><input type="radio" name="budget" value="premium"> Over $700</label>
							<label><input type="radio" name="budget" value="any"> No limit</label>
						</div>
					</fieldset>
					<button type="submit" class="aud-cta aud-cta--lg">Show my matches</button>
				</form>
				<div class="aud-finder__results" aria-live="polite" hidden></div>
			</div>
		</div>
	</section>

	<section id="catalog" class="aud-catalog">
		<div class="aud-wrap">
			<div class="aud-catalog__head">
				<h2 class="aud-catalog__title">All recommended hearing aids</h2>
				<label class="aud-sort">Sort by <select data-sort><option value="rank">Our ranking</option><option value="score">Highest score</option></select></label>
			</div>
			<div class="aud-filters" role="group" aria-label="Filter products">
				<button type="button" class="is-active" data-filter="all">All</button>
				<?php foreach ( $styles as $key => $label ) : if ( in_array( $key, $present, true ) ) : ?>
					<button type="button" data-filter="style:<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $label ); ?></button>
				<?php endif; endforeach; ?>
				<button type="button" data-filter="feature:rechargeable">Rechargeable</button>
				<button type="button" data-filter="feature:tinnitus">Tinnitus relief</button>
				<button type="button" data-filter="feature:discreet">Very discreet</button>
				<button type="button" data-filter="feature:audiology">Expert support</button>
				<button type="button" data-filter="tier:budget">Budget</button>
			</div>
			<div class="aud-grid" data-grid>
				<?php foreach ( $products as $p ) { echo audira_product_card( $p ); } // phpcs:ignore WordPress.Security.EscapeOutput ?>
			</div>
			<p class="aud-empty" hidden>No products match this filter yet.</p>
			<p class="aud-disclosure-note">We may earn a commission when you buy through our links, at no extra cost to you. Prices and availability are shown on Amazon.</p>
		</div>
	</section>
	<script type="application/json" id="aud-products-data"><?php echo wp_json_encode( $data, JSON_HEX_TAG | JSON_HEX_AMP | JSON_UNESCAPED_SLASHES ); ?></script>
	<?php
	return audira_compact_html( ob_get_clean() );
}
add_shortcode( 'audira_catalog', 'audira_catalog_shortcode' );

/* -------------------------------------------------------------------------
 * Single product page
 * ---------------------------------------------------------------------- */
function audira_product_shortcode() {
	$post = get_queried_object();
	if ( ! $post instanceof WP_Post || AUDIRA_PRODUCT !== $post->post_type ) {
		return '';
	}
	$p        = audira_product_data( $post );
	$features = array_intersect_key( audira_product_features(), array_flip( $p['features'] ) );
	$styles   = audira_product_styles();
	$tiers    = audira_product_tiers();
	$losses   = array_intersect_key( audira_product_losses(), array_flip( $p['loss'] ) );
	$images   = $p['images'] ? $p['images'] : array( audira_product_image( $p ) );
	$photo    = (bool) $p['images'];
	$related  = array_slice(
		array_values(
			array_filter(
				audira_all_products(),
				static function ( $o ) use ( $p ) {
					return $o['id'] !== $p['id'];
				}
			)
		),
		0,
		3
	);
	ob_start();
	?>
	<div class="aud-wrap aud-pdp">
		<nav class="aud-crumbs" aria-label="Breadcrumb"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a> <span>/</span> <a href="<?php echo esc_url( audira_catalog_url() ); ?>">Catalog</a> <span>/</span> <span aria-current="page"><?php echo esc_html( $p['title'] ); ?></span></nav>

		<div class="aud-pdp__grid">
			<div class="aud-gallery<?php echo $photo ? ' aud-gallery--photo' : ''; ?>" data-gallery>
				<div class="aud-gallery__main"><img src="<?php echo esc_url( $images[0] ); ?>" alt="<?php echo esc_attr( $p['title'] ); ?>" data-main></div>
				<?php if ( count( $images ) > 1 ) : ?>
					<div class="aud-gallery__thumbs">
						<?php foreach ( $images as $i => $src ) : ?>
							<button type="button" class="<?php echo 0 === $i ? 'is-active' : ''; ?>" data-src="<?php echo esc_url( $src ); ?>" aria-label="<?php echo esc_attr( sprintf( 'Show image %d', $i + 1 ) ); ?>"><img src="<?php echo esc_url( $src ); ?>" alt="" loading="lazy"></button>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>

			<div class="aud-pdp__info">
				<div class="aud-card__meta">
					<?php if ( $p['badge'] ) : ?><span class="aud-badge"><?php echo $p['rank'] ? '<span class="aud-badge__rank">#' . esc_html( $p['rank'] ) . '</span> ' : ''; ?><?php echo esc_html( $p['badge'] ); ?></span><?php endif; ?>
					<?php if ( $p['score'] ) : ?><span class="aud-score"><strong><?php echo esc_html( $p['score'] ); ?></strong><span>/10 Audira score</span></span><?php endif; ?>
				</div>
				<h1 class="aud-pdp__title"><?php echo esc_html( $p['title'] ); ?></h1>
				<?php if ( $p['best_for'] ) : ?><p class="aud-pdp__for"><strong>Best for:</strong> <?php echo esc_html( $p['best_for'] ); ?></p><?php endif; ?>
				<?php if ( $p['bullets'] ) : ?>
					<ul class="aud-checklist"><?php foreach ( $p['bullets'] as $b ) : ?><li><?php echo esc_html( $b ); ?></li><?php endforeach; ?></ul>
				<?php endif; ?>
				<div class="aud-pdp__buy">
					<a class="aud-cta aud-cta--lg" href="<?php echo esc_url( audira_product_url( $p ) ); ?>" target="_blank" rel="sponsored nofollow noopener">Check price on Amazon</a>
					<p>Current price, reviews and delivery date on Amazon. Amazon handles payment, shipping and returns.</p>
				</div>
				<dl class="aud-specs">
					<div><dt>Style</dt><dd><?php echo esc_html( $styles[ $p['style'] ] ?? '' ); ?></dd></div>
					<?php if ( $losses ) : ?><div><dt>Hearing loss</dt><dd><?php echo esc_html( implode( ' to ', $losses ) ); ?></dd></div><?php endif; ?>
					<div><dt>Price range</dt><dd><?php echo esc_html( $tiers[ $p['tier'] ] ?? '' ); ?></dd></div>
				</dl>
				<?php if ( $features ) : ?><ul class="aud-chips"><?php foreach ( $features as $label ) : ?><li><?php echo esc_html( $label ); ?></li><?php endforeach; ?></ul><?php endif; ?>
			</div>
		</div>

		<?php if ( trim( $post->post_content ) ) : ?>
			<section class="aud-pdp__about">
				<h2>About this hearing aid</h2>
				<div class="aud-pdp__content"><?php echo apply_filters( 'the_content', $post->post_content ); // phpcs:ignore WordPress.Security.EscapeOutput ?></div>
			</section>
		<?php endif; ?>

		<p class="aud-disclosure-note">OTC hearing aids are for adults 18+ with perceived mild to moderate hearing loss. This page is for information only and is not medical advice. We may earn a commission when you buy through our links.</p>

		<?php if ( $related ) : ?>
			<section class="aud-pdp__related">
				<div class="aud-catalog__head"><h2 class="aud-catalog__title">You may also like</h2><a class="aud-cta aud-cta--ghost" href="<?php echo esc_url( audira_catalog_url() ); ?>">See the full catalog</a></div>
				<div class="aud-grid"><?php foreach ( $related as $r ) { echo audira_product_card( $r ); } // phpcs:ignore WordPress.Security.EscapeOutput ?></div>
			</section>
		<?php endif; ?>
	</div>
	<?php
	return audira_compact_html( ob_get_clean() );
}
add_shortcode( 'audira_product', 'audira_product_shortcode' );
