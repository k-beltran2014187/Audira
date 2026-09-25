<?php
/**
 * Title: Final call to action
 * Slug: audira/cta
 * Categories: audira, call-to-action
 *
 * @package Audira
 */
?>
<!-- wp:group {"tagName":"section","className":"aud-cta","layout":{"type":"constrained"}} -->
<section class="wp-block-group aud-cta"><!-- wp:group {"align":"wide","className":"aud-cta__panel"} -->
<div class="wp-block-group alignwide aud-cta__panel"><!-- wp:paragraph {"className":"aud-eyebrow"} -->
<p class="aud-eyebrow">Take the first step</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"className":"aud-cta__title"} -->
<h2 class="wp-block-heading aud-cta__title">Get back to the conversations you love.</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"aud-cta__text"} -->
<p class="aud-cta__text">Start with our #1 pick, or browse every OTC hearing aid on Amazon — delivered fast, with easy returns.</p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"className":"aud-cta__actions"} -->
<div class="wp-block-buttons aud-cta__actions"><!-- wp:button {"className":"aud-btn aud-btn--amazon aud-btn--lg"} -->
<div class="wp-block-button aud-btn aud-btn--amazon aud-btn--lg"><a class="wp-block-button__link wp-element-button" href="#picks">See the top picks</a></div>
<!-- /wp:button -->

<!-- wp:button {"className":"aud-btn aud-btn--light aud-btn--lg"} -->
<div class="wp-block-button aud-btn aud-btn--light aud-btn--lg"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( audira_amazon_search_url() ); ?>" target="_blank" rel="sponsored nofollow noopener">Browse on Amazon</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group --></section>
<!-- /wp:group -->
