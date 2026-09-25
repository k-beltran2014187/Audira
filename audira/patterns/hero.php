<?php
/**
 * Title: Hero
 * Slug: audira/hero
 * Categories: audira
 * Description: Headline, promise, calls to action and product visual with floating proof cards.
 *
 * @package Audira
 */
?>
<!-- wp:group {"tagName":"section","className":"aud-hero","layout":{"type":"constrained"}} -->
<section id="top" class="wp-block-group aud-hero"><!-- wp:columns {"verticalAlignment":"center","align":"wide","className":"aud-hero__grid"} -->
<div class="wp-block-columns alignwide are-vertically-aligned-center aud-hero__grid"><!-- wp:column {"verticalAlignment":"center","className":"aud-hero__copy"} -->
<div class="wp-block-column is-vertically-aligned-center aud-hero__copy"><!-- wp:paragraph {"className":"aud-eyebrow aud-eyebrow--dot"} -->
<p class="aud-eyebrow aud-eyebrow--dot">Independent hearing aid guide · Updated for <?php echo esc_html( gmdate( 'Y' ) ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":1,"className":"aud-hero__title"} -->
<h1 class="wp-block-heading aud-hero__title">Hear every laugh, every word, <em>every moment.</em></h1>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"aud-hero__lead"} -->
<p class="aud-hero__lead">Plain-English guides and hand-picked OTC hearing aids for older adults and the families who love them. Compare the options in minutes, then order with the speed and easy returns of Amazon.</p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"className":"aud-hero__actions"} -->
<div class="wp-block-buttons aud-hero__actions"><!-- wp:button {"className":"aud-btn aud-btn--amazon aud-btn--lg"} -->
<div class="wp-block-button aud-btn aud-btn--amazon aud-btn--lg"><a class="wp-block-button__link wp-element-button" href="#picks">See our top picks</a></div>
<!-- /wp:button -->

<!-- wp:button {"className":"aud-btn aud-btn--ghost aud-btn--lg"} -->
<div class="wp-block-button aud-btn aud-btn--ghost aud-btn--lg"><a class="wp-block-button__link wp-element-button" href="#styles">Find my style</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons -->

<!-- wp:list {"className":"aud-hero__points"} -->
<ul class="wp-block-list aud-hero__points"><!-- wp:list-item -->
<li>Ships &amp; returns through Amazon</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>No prescription needed for OTC</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>No paid rankings, ever</li>
<!-- /wp:list-item --></ul>
<!-- /wp:list --></div>
<!-- /wp:column -->

<!-- wp:column {"verticalAlignment":"center","className":"aud-hero__media"} -->
<div class="wp-block-column is-vertically-aligned-center aud-hero__media"><!-- wp:image {"sizeSlug":"full","linkDestination":"none","className":"aud-hero__img"} -->
<figure class="wp-block-image size-full aud-hero__img"><img src="<?php echo audira_img( 'hero-device.svg' ); ?>" alt="Rechargeable receiver-in-canal hearing aid with a soft dome"/></figure>
<!-- /wp:image -->

<!-- wp:group {"className":"aud-float aud-float--top"} -->
<div class="wp-block-group aud-float aud-float--top"><!-- wp:paragraph {"className":"aud-float__kicker"} -->
<p class="aud-float__kicker">Best overall · <?php echo esc_html( gmdate( 'Y' ) ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"aud-float__title"} -->
<p class="aud-float__title">Rechargeable RIC</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"aud-float__score"} -->
<p class="aud-float__score"><strong>9.6</strong> Audira score</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"aud-float aud-float--bottom"} -->
<div class="wp-block-group aud-float aud-float--bottom"><!-- wp:paragraph {"className":"aud-float__kicker"} -->
<p class="aud-float__kicker">FDA OTC category</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"aud-float__text"} -->
<p class="aud-float__text">For adults 18+ with perceived mild to moderate hearing loss.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></section>
<!-- /wp:group -->
