<?php
/**
 * Title: Trust bar
 * Slug: audira/trust
 * Categories: audira
 *
 * @package Audira
 */

$items = array(
	array( 'truck', 'Fast Amazon shipping', 'Prime-eligible on many picks' ),
	array( 'return', 'Easy returns', 'Handled by Amazon' ),
	array( 'shield', 'Independent picks', 'Brands can’t pay for placement' ),
	array( 'heart', 'Made for older adults', 'Large text, zero jargon' ),
);
?>
<!-- wp:group {"tagName":"section","className":"aud-trust","layout":{"type":"constrained"}} -->
<section class="wp-block-group aud-trust"><!-- wp:group {"align":"wide","className":"aud-trust__grid"} -->
<div class="wp-block-group alignwide aud-trust__grid">
<?php foreach ( $items as $item ) : ?>
<!-- wp:group {"className":"aud-trust__item aud-ico aud-ico--<?php echo esc_attr( $item[0] ); ?>"} -->
<div class="wp-block-group aud-trust__item aud-ico aud-ico--<?php echo esc_attr( $item[0] ); ?>"><!-- wp:paragraph {"className":"aud-trust__title"} -->
<p class="aud-trust__title"><?php echo esc_html( $item[1] ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"aud-trust__text"} -->
<p class="aud-trust__text"><?php echo esc_html( $item[2] ); ?></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->
<?php endforeach; ?>
</div>
<!-- /wp:group --></section>
<!-- /wp:group -->
