<?php
/**
 * Title: Editor's top picks
 * Slug: audira/picks
 * Categories: audira
 * Description: Ranked product cards with badge, score and Amazon button. Edit products in inc/catalog.php.
 *
 * @package Audira
 */

$picks = audira_catalog_picks();
$more  = audira_more_products();
?>
<!-- wp:group {"tagName":"section","className":"aud-section aud-picks","layout":{"type":"constrained"}} -->
<section id="picks" class="wp-block-group aud-section aud-picks"><!-- wp:group {"align":"wide","className":"aud-section-head aud-section-head--split","layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between","verticalAlignment":"bottom"}} -->
<div class="wp-block-group alignwide aud-section-head aud-section-head--split"><!-- wp:group {"className":"aud-section-head__main"} -->
<div class="wp-block-group aud-section-head__main"><!-- wp:paragraph {"className":"aud-eyebrow"} -->
<p class="aud-eyebrow">Editor’s top picks · <?php echo esc_html( gmdate( 'F Y' ) ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:heading {"className":"aud-section-head__title"} -->
<h2 class="wp-block-heading aud-section-head__title">The best hearing aids you can buy on Amazon</h2>
<!-- /wp:heading --></div>
<!-- /wp:group -->

<!-- wp:paragraph {"className":"aud-lead aud-section-head__aside"} -->
<p class="aud-lead aud-section-head__aside">Every pick is scored on sound, comfort, ease of use, battery and support. <a href="#how-we-pick">How we score</a></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"align":"wide","className":"aud-pick-grid"} -->
<div class="wp-block-group alignwide aud-pick-grid">
<?php
foreach ( $picks as $i => $p ) :
	$cls = 'aud-pick' . ( $p['featured'] ? ' aud-pick--featured' : '' );
	$btn = $p['featured'] ? 'aud-btn aud-btn--amazon aud-btn--lg' : 'aud-btn aud-btn--amazon';
	?>
<!-- wp:group {"tagName":"article","className":"<?php echo esc_attr( $cls ); ?>"} -->
<article class="wp-block-group <?php echo esc_attr( $cls ); ?>"><!-- wp:image {"sizeSlug":"full","linkDestination":"custom","className":"aud-pick__img"} -->
<figure class="wp-block-image size-full aud-pick__img"><a href="<?php echo esc_url( audira_product_url( $p ) ); ?>" target="_blank" rel="sponsored nofollow noopener"><img src="<?php echo audira_product_image( $p ); ?>" alt="<?php echo esc_attr( $p['title'] ); ?>"/></a></figure>
<!-- /wp:image -->

<!-- wp:group {"className":"aud-pick__body"} -->
<div class="wp-block-group aud-pick__body"><!-- wp:group {"className":"aud-pick__meta","layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between"}} -->
<div class="wp-block-group aud-pick__meta"><!-- wp:paragraph {"className":"aud-badge"} -->
<p class="aud-badge"><span class="aud-badge__rank">#<?php echo esc_html( $i + 1 ); ?></span> <?php echo esc_html( $p['badge'] ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"aud-score"} -->
<p class="aud-score"><strong><?php echo esc_html( $p['score'] ); ?></strong><span>/10</span></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:heading {"level":3,"className":"aud-pick__title"} -->
<h3 class="wp-block-heading aud-pick__title"><?php echo esc_html( $p['title'] ); ?></h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"aud-pick__for"} -->
<p class="aud-pick__for"><strong>Best for:</strong> <?php echo esc_html( $p['best_for'] ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:list {"className":"aud-checklist"} -->
<ul class="wp-block-list aud-checklist"><?php foreach ( $p['bullets'] as $bullet ) : ?><!-- wp:list-item -->
<li><?php echo esc_html( $bullet ); ?></li>
<!-- /wp:list-item --><?php endforeach; ?></ul>
<!-- /wp:list -->

<!-- wp:buttons {"className":"aud-pick__actions"} -->
<div class="wp-block-buttons aud-pick__actions"><!-- wp:button {"className":"<?php echo esc_attr( $btn ); ?>"} -->
<div class="wp-block-button <?php echo esc_attr( $btn ); ?>"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( audira_product_url( $p ) ); ?>" target="_blank" rel="sponsored nofollow noopener">Check price on Amazon</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons -->

<!-- wp:paragraph {"className":"aud-pick__note"} -->
<p class="aud-pick__note">Current price, reviews and delivery date on Amazon</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></article>
<!-- /wp:group -->
<?php endforeach; ?>
</div>
<!-- /wp:group -->

<?php if ( $more ) : ?>
<!-- wp:heading {"level":3,"align":"wide","className":"aud-more__title"} -->
<h3 class="wp-block-heading alignwide aud-more__title">More products we recommend</h3>
<!-- /wp:heading -->

<!-- wp:group {"align":"wide","className":"aud-more-grid"} -->
<div class="wp-block-group alignwide aud-more-grid">
<?php foreach ( $more as $m ) : ?>
<!-- wp:group {"className":"aud-more"} -->
<div class="wp-block-group aud-more"><!-- wp:paragraph {"className":"aud-more__name"} -->
<p class="aud-more__name"><?php echo esc_html( $m['title'] ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button {"className":"aud-btn aud-btn--outline aud-btn--sm"} -->
<div class="wp-block-button aud-btn aud-btn--outline aud-btn--sm"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( $m['url'] ); ?>" target="_blank" rel="sponsored nofollow noopener">View on Amazon</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group -->
<?php endforeach; ?>
</div>
<!-- /wp:group -->
<?php endif; ?>

<!-- wp:buttons {"className":"aud-center-actions"} -->
<div class="wp-block-buttons aud-center-actions"><!-- wp:button {"className":"aud-btn aud-btn--ghost"} -->
<div class="wp-block-button aud-btn aud-btn--ghost"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( audira_amazon_search_url() ); ?>" target="_blank" rel="sponsored nofollow noopener">Browse all OTC hearing aids on Amazon</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></section>
<!-- /wp:group -->
