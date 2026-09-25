<?php
/**
 * Title: Reader stories (add real reviews only)
 * Slug: audira/testimonials
 * Categories: audira, testimonials
 * Description: Not included on the landing page by default. The FTC prohibits invented or AI-written reviews — replace the bracketed text with real, permission-granted quotes before publishing.
 *
 * @package Audira
 */

$quotes = array(
	array( '[Paste a real reader quote here, with their permission.]', '[First name, last initial]', '[Age · device they use]' ),
	array( '[Paste a real reader quote here, with their permission.]', '[First name, last initial]', '[Age · device they use]' ),
	array( '[Paste a real reader quote here, with their permission.]', '[First name, last initial]', '[Age · device they use]' ),
);
?>
<!-- wp:group {"tagName":"section","className":"aud-section aud-testimonials","layout":{"type":"constrained"}} -->
<section id="stories" class="wp-block-group aud-section aud-testimonials"><!-- wp:group {"className":"aud-section-head"} -->
<div class="wp-block-group aud-section-head"><!-- wp:paragraph {"className":"aud-eyebrow"} -->
<p class="aud-eyebrow">Reader stories</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"className":"aud-section-head__title"} -->
<h2 class="wp-block-heading aud-section-head__title">Families who are talking again</h2>
<!-- /wp:heading --></div>
<!-- /wp:group -->

<!-- wp:group {"align":"wide","className":"aud-quotes"} -->
<div class="wp-block-group alignwide aud-quotes">
<?php foreach ( $quotes as $q ) : ?>
<!-- wp:group {"tagName":"article","className":"aud-quote-card"} -->
<article class="wp-block-group aud-quote-card"><!-- wp:quote {"className":"aud-quote"} -->
<blockquote class="wp-block-quote aud-quote"><!-- wp:paragraph -->
<p><?php echo esc_html( $q[0] ); ?></p>
<!-- /wp:paragraph --></blockquote>
<!-- /wp:quote -->

<!-- wp:paragraph {"className":"aud-author__name"} -->
<p class="aud-author__name"><?php echo esc_html( $q[1] ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"aud-author__meta"} -->
<p class="aud-author__meta"><?php echo esc_html( $q[2] ); ?></p>
<!-- /wp:paragraph --></article>
<!-- /wp:group -->
<?php endforeach; ?>
</div>
<!-- /wp:group --></section>
<!-- /wp:group -->
