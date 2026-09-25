<?php
/**
 * Title: Full Audira landing page
 * Slug: audira/landing
 * Categories: audira, featured
 * Block Types: core/post-content
 * Post Types: page
 * Description: Every landing section. Use it with the "Audira Landing" template.
 *
 * @package Audira
 */

foreach ( audira_landing_sections() as $slug ) {
	echo '<!-- wp:pattern {"slug":"audira/' . esc_attr( $slug ) . '"} /-->' . "\n";
}
