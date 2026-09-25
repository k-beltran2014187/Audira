<?php
/**
 * Title: Style comparison table
 * Slug: audira/compare
 * Categories: audira
 *
 * @package Audira
 */

$rows = audira_catalog_compare();
?>
<!-- wp:group {"tagName":"section","className":"aud-section aud-compare-section","layout":{"type":"constrained"}} -->
<section id="compare" class="wp-block-group aud-section aud-compare-section"><!-- wp:group {"className":"aud-section-head"} -->
<div class="wp-block-group aud-section-head"><!-- wp:paragraph {"className":"aud-eyebrow"} -->
<p class="aud-eyebrow">Side by side</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"className":"aud-section-head__title"} -->
<h2 class="wp-block-heading aud-section-head__title">Compare the four styles at a glance</h2>
<!-- /wp:heading --></div>
<!-- /wp:group -->

<!-- wp:table {"hasFixedLayout":false,"align":"wide","className":"aud-compare"} -->
<figure class="wp-block-table alignwide aud-compare"><table><thead><tr><th>Feature</th><th>Behind-the-Ear (BTE)</th><th>Receiver-in-Canal (RIC)</th><th>In-the-Ear (ITE / ITC)</th><th>Invisible (IIC / CIC)</th></tr></thead><tbody><?php foreach ( $rows as $row ) : ?><tr><?php foreach ( $row as $cell ) : ?><td><?php echo esc_html( $cell ); ?></td><?php endforeach; ?></tr><?php endforeach; ?></tbody></table><figcaption class="wp-element-caption">General guidance only. A licensed hearing professional can give you a personal recommendation.</figcaption></figure>
<!-- /wp:table --></section>
<!-- /wp:group -->
