<?php
/**
 * Title: How it works (4 steps)
 * Slug: audira/process
 * Categories: audira
 *
 * @package Audira
 */

$steps = array(
	array( 'Check your hearing', 'Take a free online hearing test or visit a clinic. OTC hearing aids are for perceived mild to moderate loss.' ),
	array( 'Pick your style', 'Use our style guide and comparison table to match a design to your hands, glasses and lifestyle.' ),
	array( 'Order on Amazon', 'Choose a quantity and we open Amazon with it in your cart. Amazon handles payment, shipping and returns.' ),
	array( 'Give it 2–4 weeks', 'Wear them daily and adjust in the app. Your brain needs a few weeks to relearn everyday sounds.' ),
);
?>
<!-- wp:group {"tagName":"section","className":"aud-section aud-section--tint aud-process","layout":{"type":"constrained"}} -->
<section id="how-it-works" class="wp-block-group aud-section aud-section--tint aud-process"><!-- wp:group {"className":"aud-section-head"} -->
<div class="wp-block-group aud-section-head"><!-- wp:paragraph {"className":"aud-eyebrow"} -->
<p class="aud-eyebrow">How it works</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"className":"aud-section-head__title"} -->
<h2 class="wp-block-heading aud-section-head__title">From “What did you say?” to hearing clearly, in four steps</h2>
<!-- /wp:heading --></div>
<!-- /wp:group -->

<!-- wp:group {"align":"wide","className":"aud-steps"} -->
<div class="wp-block-group alignwide aud-steps">
<?php foreach ( $steps as $i => $s ) : ?>
<!-- wp:group {"tagName":"article","className":"aud-step"} -->
<article class="wp-block-group aud-step"><!-- wp:paragraph {"className":"aud-step__num"} -->
<p class="aud-step__num"><?php echo esc_html( $i + 1 ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3,"className":"aud-step__title"} -->
<h3 class="wp-block-heading aud-step__title"><?php echo esc_html( $s[0] ); ?></h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"aud-step__text"} -->
<p class="aud-step__text"><?php echo esc_html( $s[1] ); ?></p>
<!-- /wp:paragraph --></article>
<!-- /wp:group -->
<?php endforeach; ?>
</div>
<!-- /wp:group --></section>
<!-- /wp:group -->
