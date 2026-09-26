<?php
/**
 * Audira — theme bootstrap.
 *
 * Everything you are likely to edit lives in two places:
 *  - Settings → Audira Affiliate  (Amazon tag, product links, names, images)
 *  - inc/catalog.php              (default products, FAQ and comparison data)
 *
 * @package Audira
 */

defined( 'ABSPATH' ) || exit;

define( 'AUDIRA_VERSION', '3.0.0' );

require_once get_theme_file_path( 'inc/amazon.php' );
require_once get_theme_file_path( 'inc/catalog.php' );
require_once get_theme_file_path( 'inc/products.php' );
require_once get_theme_file_path( 'inc/product-cpt.php' );
require_once get_theme_file_path( 'inc/catalog-views.php' );
require_once get_theme_file_path( 'inc/assistant.php' );
require_once get_theme_file_path( 'inc/setup.php' );
require_once get_theme_file_path( 'inc/pages.php' );
