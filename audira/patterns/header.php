<?php
/**
 * Title: Audira header
 * Slug: audira/header
 * Categories: audira, header
 * Block Types: core/template-part/header
 * Inserter: false
 *
 * @package Audira
 */

$home = esc_url( home_url( '/' ) );
?>
<!-- wp:group {"className":"aud-disclosure","layout":{"type":"constrained"}} -->
<div class="wp-block-group aud-disclosure"><!-- wp:paragraph {"align":"center","className":"aud-disclosure__text"} -->
<p class="has-text-align-center aud-disclosure__text">Reader-supported: we may earn a commission when you buy through our links, at no extra cost to you. <a href="<?php echo esc_url( audira_page_url( 'affiliate-disclosure' ) ); ?>">Learn more</a></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"aud-header__bar","layout":{"type":"constrained"}} -->
<div class="wp-block-group aud-header__bar"><!-- wp:group {"align":"wide","className":"aud-header__inner","layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between"}} -->
<div class="wp-block-group alignwide aud-header__inner"><!-- wp:image {"sizeSlug":"full","linkDestination":"custom","className":"aud-logo"} -->
<figure class="wp-block-image size-full aud-logo"><a href="<?php echo $home; ?>"><img src="<?php echo audira_img( 'logo.svg' ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ?: 'Audira' ); ?> — home"/></a></figure>
<!-- /wp:image -->

<!-- wp:navigation {"overlayMenu":"mobile","className":"aud-nav","layout":{"type":"flex","justifyContent":"right"}} -->
<!-- wp:navigation-link {"label":"Top Picks","url":"<?php echo $home; ?>#picks","kind":"custom","isTopLevelLink":true} /-->
<!-- wp:navigation-link {"label":"Catalog","url":"<?php echo esc_url( audira_catalog_url() ); ?>","kind":"custom","isTopLevelLink":true} /-->
<!-- wp:navigation-link {"label":"Hearing Aid Finder","url":"<?php echo esc_url( audira_catalog_url() ); ?>#finder","kind":"custom","isTopLevelLink":true} /-->
<!-- wp:navigation-link {"label":"OTC Guide","url":"<?php echo $home; ?>#otc","kind":"custom","isTopLevelLink":true} /-->
<!-- wp:navigation-link {"label":"FAQ","url":"<?php echo $home; ?>#faq","kind":"custom","isTopLevelLink":true} /-->
<!-- /wp:navigation -->

<!-- wp:buttons {"className":"aud-header__cta"} -->
<div class="wp-block-buttons aud-header__cta"><!-- wp:button {"className":"aud-btn aud-btn--amazon aud-btn--sm"} -->
<div class="wp-block-button aud-btn aud-btn--amazon aud-btn--sm"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( audira_amazon_search_url() ); ?>" target="_blank" rel="sponsored nofollow noopener">Shop on Amazon</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
