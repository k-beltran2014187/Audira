<?php
/**
 * Title: Audira footer
 * Slug: audira/footer
 * Categories: audira, footer
 * Block Types: core/template-part/footer
 * Inserter: false
 *
 * @package Audira
 */

$home = esc_url( home_url( '/' ) );
$name = get_bloginfo( 'name' ) ?: 'Audira';
?>
<!-- wp:group {"className":"aud-footer__wrap","layout":{"type":"constrained"}} -->
<div class="wp-block-group aud-footer__wrap"><!-- wp:columns {"align":"wide","className":"aud-footer__grid"} -->
<div class="wp-block-columns alignwide aud-footer__grid"><!-- wp:column {"width":"36%","className":"aud-footer__brand"} -->
<div class="wp-block-column aud-footer__brand" style="flex-basis:36%"><!-- wp:image {"sizeSlug":"full","linkDestination":"custom","className":"aud-logo aud-logo--footer"} -->
<figure class="wp-block-image size-full aud-logo aud-logo--footer"><a href="<?php echo $home; ?>"><img src="<?php echo audira_img( 'logo-light.svg' ); ?>" alt="<?php echo esc_attr( $name ); ?> — home"/></a></figure>
<!-- /wp:image -->

<!-- wp:paragraph {"className":"aud-footer__about"} -->
<p class="aud-footer__about">Independent, plain-English hearing aid guides for older adults and their families. We research the options so you can hear the moments that matter.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:heading {"level":2,"className":"aud-footer__title"} -->
<h2 class="wp-block-heading aud-footer__title">Explore</h2>
<!-- /wp:heading -->

<!-- wp:list {"className":"aud-footer__links"} -->
<ul class="wp-block-list aud-footer__links"><!-- wp:list-item -->
<li><a href="<?php echo $home; ?>#picks">Top picks</a></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><a href="<?php echo esc_url( audira_catalog_url() ); ?>">Product catalog</a></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><a href="<?php echo esc_url( audira_catalog_url() ); ?>#finder">Hearing Aid Finder</a></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><a href="<?php echo $home; ?>#styles">Hearing aid styles</a></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><a href="<?php echo $home; ?>#compare">Compare styles</a></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><a href="<?php echo $home; ?>#otc">OTC guide</a></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><a href="<?php echo $home; ?>#faq">FAQ</a></li>
<!-- /wp:list-item --></ul>
<!-- /wp:list --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:heading {"level":2,"className":"aud-footer__title"} -->
<h2 class="wp-block-heading aud-footer__title">Company</h2>
<!-- /wp:heading -->

<!-- wp:list {"className":"aud-footer__links"} -->
<ul class="wp-block-list aud-footer__links"><!-- wp:list-item -->
<li><a href="<?php echo esc_url( audira_page_url( 'about' ) ); ?>">About us</a></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><a href="<?php echo esc_url( audira_page_url( 'affiliate-disclosure' ) ); ?>">Affiliate disclosure</a></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><a href="<?php echo esc_url( audira_page_url( 'medical-disclaimer' ) ); ?>">Medical disclaimer</a></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><a href="<?php echo esc_url( audira_page_url( 'privacy-policy' ) ); ?>">Privacy policy</a></li>
<!-- /wp:list-item --></ul>
<!-- /wp:list --></div>
<!-- /wp:column -->

<!-- wp:column {"width":"28%"} -->
<div class="wp-block-column" style="flex-basis:28%"><!-- wp:heading {"level":2,"className":"aud-footer__title"} -->
<h2 class="wp-block-heading aud-footer__title">Transparency</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"aud-footer__note"} -->
<p class="aud-footer__note">As an Amazon Associate we earn from qualifying purchases. Content is for information only and is not medical advice.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->

<!-- wp:group {"align":"wide","className":"aud-footer__bottom","layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between"}} -->
<div class="wp-block-group alignwide aud-footer__bottom"><!-- wp:paragraph -->
<p>© <?php echo esc_html( gmdate( 'Y' ) . ' ' . $name ); ?>. All rights reserved.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Amazon and the Amazon logo are trademarks of Amazon.com, Inc. or its affiliates.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
