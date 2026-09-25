<?php
/**
 * Title: OTC vs. PSAP vs. prescription guide
 * Slug: audira/otc
 * Categories: audira
 *
 * @package Audira
 */

$cards = array(
	array(
		'kicker'  => 'Our recommendation for most',
		'title'   => 'OTC hearing aids',
		'text'    => 'FDA-regulated hearing aids you can buy without a prescription since 2022. They tune sound to your hearing, usually through a simple app.',
		'rows'    => array( 'For adults 18+ with perceived mild to moderate hearing loss', 'Self-fitting — no appointment needed', 'Sold on Amazon and in stores' ),
		'feature' => true,
	),
	array(
		'kicker'  => 'Good for occasional help',
		'title'   => 'Sound amplifiers (PSAPs)',
		'text'    => 'Personal sound amplification products make everything louder. Useful for TV or lectures, but they are not hearing aids.',
		'rows'    => array( 'For people with normal hearing in specific situations', 'No hearing personalization', 'The most affordable option' ),
		'feature' => false,
	),
	array(
		'kicker'  => 'For severe hearing loss',
		'title'   => 'Prescription hearing aids',
		'text'    => 'Fitted by an audiologist or hearing instrument specialist after a full exam. The right choice for severe loss or complex needs.',
		'rows'    => array( 'Severe to profound loss, or anyone under 18', 'Professional fitting and follow-ups', 'Bought through a hearing clinic' ),
		'feature' => false,
	),
);
?>
<!-- wp:group {"tagName":"section","className":"aud-section aud-section--dark aud-otc","layout":{"type":"constrained"}} -->
<section id="otc" class="wp-block-group aud-section aud-section--dark aud-otc"><!-- wp:group {"className":"aud-section-head"} -->
<div class="wp-block-group aud-section-head"><!-- wp:paragraph {"className":"aud-eyebrow"} -->
<p class="aud-eyebrow">OTC guide</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"className":"aud-section-head__title"} -->
<h2 class="wp-block-heading aud-section-head__title">OTC, amplifier or prescription? Know before you buy.</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"aud-lead"} -->
<p class="aud-lead">Since the FDA created the over-the-counter category in 2022, you can buy real hearing aids without an appointment. Here’s how the three options differ.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"align":"wide","className":"aud-otc-grid"} -->
<div class="wp-block-group alignwide aud-otc-grid">
<?php foreach ( $cards as $c ) : $cls = 'aud-otc-card' . ( $c['feature'] ? ' aud-otc-card--feature' : '' ); ?>
<!-- wp:group {"tagName":"article","className":"<?php echo esc_attr( $cls ); ?>"} -->
<article class="wp-block-group <?php echo esc_attr( $cls ); ?>"><!-- wp:paragraph {"className":"aud-otc-card__kicker"} -->
<p class="aud-otc-card__kicker"><?php echo esc_html( $c['kicker'] ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3,"className":"aud-otc-card__title"} -->
<h3 class="wp-block-heading aud-otc-card__title"><?php echo esc_html( $c['title'] ); ?></h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"aud-otc-card__text"} -->
<p class="aud-otc-card__text"><?php echo esc_html( $c['text'] ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:list {"className":"aud-checklist aud-checklist--light"} -->
<ul class="wp-block-list aud-checklist aud-checklist--light"><?php foreach ( $c['rows'] as $row ) : ?><!-- wp:list-item -->
<li><?php echo esc_html( $row ); ?></li>
<!-- /wp:list-item --><?php endforeach; ?></ul>
<!-- /wp:list --></article>
<!-- /wp:group -->
<?php endforeach; ?>
</div>
<!-- /wp:group -->

<!-- wp:paragraph {"className":"aud-otc__warning"} -->
<p class="aud-otc__warning"><strong>See a doctor first</strong> if you have sudden hearing loss, hearing loss in only one ear, ear pain, drainage or dizziness.</p>
<!-- /wp:paragraph --></section>
<!-- /wp:group -->
