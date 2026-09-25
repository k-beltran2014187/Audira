<?php
/**
 * Title: Frequently asked questions
 * Slug: audira/faq
 * Categories: audira
 * Description: Questions come from inc/catalog.php and are also output as FAQPage structured data.
 *
 * @package Audira
 */

$faqs = audira_catalog_faqs();
?>
<!-- wp:group {"tagName":"section","className":"aud-section aud-faq","layout":{"type":"constrained"}} -->
<section id="faq" class="wp-block-group aud-section aud-faq"><!-- wp:columns {"align":"wide","className":"aud-faq__grid"} -->
<div class="wp-block-columns alignwide aud-faq__grid"><!-- wp:column {"width":"38%","className":"aud-faq__intro"} -->
<div class="wp-block-column aud-faq__intro" style="flex-basis:38%"><!-- wp:paragraph {"className":"aud-eyebrow"} -->
<p class="aud-eyebrow">FAQ</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"className":"aud-section-head__title"} -->
<h2 class="wp-block-heading aud-section-head__title">Straight answers to common questions</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"aud-lead"} -->
<p class="aud-lead">Medicare, HSA/FSA, prescriptions, returns — the questions families ask us most, answered in plain English.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column -->

<!-- wp:column {"width":"62%","className":"aud-faq__list"} -->
<div class="wp-block-column aud-faq__list" style="flex-basis:62%">
<?php foreach ( $faqs as $f ) : ?>
<!-- wp:details {"className":"aud-faq-item"} -->
<details class="wp-block-details aud-faq-item"><summary><?php echo esc_html( $f[0] ); ?></summary><!-- wp:paragraph -->
<p><?php echo esc_html( $f[1] ); ?></p>
<!-- /wp:paragraph --></details>
<!-- /wp:details -->
<?php endforeach; ?>
</div>
<!-- /wp:column --></div>
<!-- /wp:columns --></section>
<!-- /wp:group -->
