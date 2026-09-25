<?php
/**
 * Title: Hearing aid styles (expandable cards with quantity buttons)
 * Slug: audira/styles
 * Categories: audira
 * Description: One card per style. "Details & buy" expands the card to full width with features and 1/2/3 quantity buttons that add the product to the Amazon cart.
 *
 * @package Audira
 */

$styles      = audira_catalog_styles();
$qty_options = array( 1, 2, 3 );
?>
<!-- wp:group {"tagName":"section","className":"aud-section aud-section--tint aud-styles","layout":{"type":"constrained"}} -->
<section id="styles" class="wp-block-group aud-section aud-section--tint aud-styles"><!-- wp:group {"className":"aud-section-head"} -->
<div class="wp-block-group aud-section-head"><!-- wp:paragraph {"className":"aud-eyebrow"} -->
<p class="aud-eyebrow">Hearing aid styles</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"className":"aud-section-head__title"} -->
<h2 class="wp-block-heading aud-section-head__title">Four styles. One will fit your life.</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"aud-lead"} -->
<p class="aud-lead">Every ear and every routine is different. Open a card to see who each style suits best, choose a quantity, and we’ll send you to Amazon with it waiting in your cart.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"align":"wide","className":"aud-style-grid"} -->
<div class="wp-block-group alignwide aud-style-grid">
<?php foreach ( $styles as $p ) : $asin = audira_product_asin( $p ); ?>
<!-- wp:group {"tagName":"article","className":"aud-style"} -->
<article class="wp-block-group aud-style"><!-- wp:image {"sizeSlug":"full","linkDestination":"none","className":"aud-style__img"} -->
<figure class="wp-block-image size-full aud-style__img"><img src="<?php echo audira_img( $p['image'] ); ?>" alt="<?php echo esc_attr( $p['alt'] ); ?>"/></figure>
<!-- /wp:image -->

<!-- wp:paragraph {"className":"aud-tag"} -->
<p class="aud-tag"><?php echo esc_html( $p['tag'] ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3,"className":"aud-style__title"} -->
<h3 class="wp-block-heading aud-style__title"><?php echo esc_html( $p['title'] ); ?></h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"aud-style__desc"} -->
<p class="aud-style__desc"><?php echo esc_html( $p['desc'] ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:details {"className":"aud-style__details"} -->
<details class="wp-block-details aud-style__details"><summary>Details &amp; buy</summary><!-- wp:heading {"level":4,"className":"aud-panel__title"} -->
<h4 class="wp-block-heading aud-panel__title">Why people choose it</h4>
<!-- /wp:heading -->

<!-- wp:list {"className":"aud-checklist"} -->
<ul class="wp-block-list aud-checklist"><?php foreach ( $p['features'] as $feature ) : ?><!-- wp:list-item -->
<li><?php echo esc_html( $feature ); ?></li>
<!-- /wp:list-item --><?php endforeach; ?></ul>
<!-- /wp:list -->

<!-- wp:paragraph {"className":"aud-panel__ideal"} -->
<p class="aud-panel__ideal"><strong>Ideal for</strong> <?php echo esc_html( $p['ideal'] ); ?></p>
<!-- /wp:paragraph -->

<?php if ( $asin ) : ?>
<!-- wp:heading {"level":4,"className":"aud-panel__title aud-panel__title--qty"} -->
<h4 class="wp-block-heading aud-panel__title aud-panel__title--qty">Choose a quantity · adds to your Amazon cart</h4>
<!-- /wp:heading -->

<!-- wp:buttons {"className":"aud-qty"} -->
<div class="wp-block-buttons aud-qty"><?php foreach ( $qty_options as $qty ) : $qcls = 1 === $qty ? 'aud-btn aud-btn--amazon' : 'aud-btn aud-btn--outline'; ?><!-- wp:button {"className":"<?php echo esc_attr( $qcls ); ?>"} -->
<div class="wp-block-button <?php echo esc_attr( $qcls ); ?>"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( audira_amazon_cart_url( $asin, $qty ) ); ?>" target="_blank" rel="sponsored nofollow noopener">Add <?php echo esc_html( $qty ); ?> to cart</a></div>
<!-- /wp:button --><?php endforeach; ?></div>
<!-- /wp:buttons -->

<!-- wp:paragraph {"className":"aud-panel__note"} -->
<p class="aud-panel__note">Amazon opens with the item and quantity already in your cart — you review everything before paying. <a href="<?php echo esc_url( audira_product_url( $p ) ); ?>" target="_blank" rel="sponsored nofollow noopener">View the full listing</a></p>
<!-- /wp:paragraph -->
<?php else : ?>
<!-- wp:buttons {"className":"aud-qty"} -->
<div class="wp-block-buttons aud-qty"><!-- wp:button {"className":"aud-btn aud-btn--amazon"} -->
<div class="wp-block-button aud-btn aud-btn--amazon"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( audira_product_url( $p ) ); ?>" target="_blank" rel="sponsored nofollow noopener">See it on Amazon</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons -->

<!-- wp:paragraph {"className":"aud-panel__note"} -->
<p class="aud-panel__note">Check the current price, reviews and delivery date on Amazon. Amazon handles payment, shipping and returns.</p>
<!-- /wp:paragraph -->
<?php endif; ?></details>
<!-- /wp:details --></article>
<!-- /wp:group -->
<?php endforeach; ?>
</div>
<!-- /wp:group --></section>
<!-- /wp:group -->
