<?php
/**
 * Title: How we pick (methodology)
 * Slug: audira/method
 * Categories: audira
 *
 * @package Audira
 */

$criteria = array(
	array( 'Sound in real rooms', 'Clear speech at the dinner table, in the car and in noisy restaurants — not just in quiet.' ),
	array( 'All-day comfort', 'Weight, fit and how it feels after eight hours, with or without glasses.' ),
	array( 'Easy for older hands', 'Button size, charging, app readability and how simple it is to put in and take out.' ),
	array( 'Battery & charging', 'Real-world hours per charge, charging case convenience and battery options.' ),
	array( 'Support & returns', 'Warranty length, trial period, customer service and Amazon return eligibility.' ),
	array( 'Honest value', 'What you get for the money compared with every alternative on our list.' ),
);
?>
<!-- wp:group {"tagName":"section","className":"aud-section aud-method","layout":{"type":"constrained"}} -->
<section id="how-we-pick" class="wp-block-group aud-section aud-method"><!-- wp:columns {"align":"wide","className":"aud-method__grid"} -->
<div class="wp-block-columns alignwide aud-method__grid"><!-- wp:column {"width":"40%","className":"aud-method__intro"} -->
<div class="wp-block-column aud-method__intro" style="flex-basis:40%"><!-- wp:paragraph {"className":"aud-eyebrow"} -->
<p class="aud-eyebrow">How we pick</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"className":"aud-section-head__title"} -->
<h2 class="wp-block-heading aud-section-head__title">Independent by design. Honest by default.</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"aud-lead"} -->
<p class="aud-lead">No manufacturer can pay for a spot on this page. We earn a small commission from Amazon when you buy through our links — it never changes the price you pay or the order of our picks.</p>
<!-- /wp:paragraph -->

<!-- wp:group {"className":"aud-scorecard"} -->
<div class="wp-block-group aud-scorecard"><!-- wp:paragraph {"className":"aud-scorecard__label"} -->
<p class="aud-scorecard__label">The Audira score</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"aud-scorecard__text"} -->
<p class="aud-scorecard__text">Six criteria, weighted for what matters most to older adults, combined into one number out of 10.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:column -->

<!-- wp:column {"width":"60%","className":"aud-method__cards"} -->
<div class="wp-block-column aud-method__cards" style="flex-basis:60%"><!-- wp:group {"className":"aud-criteria"} -->
<div class="wp-block-group aud-criteria">
<?php foreach ( $criteria as $i => $c ) : ?>
<!-- wp:group {"className":"aud-criterion"} -->
<div class="wp-block-group aud-criterion"><!-- wp:paragraph {"className":"aud-criterion__num"} -->
<p class="aud-criterion__num"><?php echo esc_html( sprintf( '%02d', $i + 1 ) ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3,"className":"aud-criterion__title"} -->
<h3 class="wp-block-heading aud-criterion__title"><?php echo esc_html( $c[0] ); ?></h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"aud-criterion__text"} -->
<p class="aud-criterion__text"><?php echo esc_html( $c[1] ); ?></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->
<?php endforeach; ?>
</div>
<!-- /wp:group --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></section>
<!-- /wp:group -->
