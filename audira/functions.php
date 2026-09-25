<?php
/**
 * Audira — theme bootstrap.
 *
 * Everything you are likely to edit lives in two places:
 *  - Settings → Audira Affiliate  (Amazon Associate tag and marketplace)
 *  - inc/catalog.php              (products, ASINs, FAQ and comparison data)
 *
 * @package Audira
 */

defined( 'ABSPATH' ) || exit;

define( 'AUDIRA_VERSION', '2.0.0' );

require_once get_theme_file_path( 'inc/amazon.php' );
require_once get_theme_file_path( 'inc/catalog.php' );
require_once get_theme_file_path( 'inc/setup.php' );
require_once get_theme_file_path( 'inc/pages.php' );
