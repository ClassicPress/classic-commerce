<?php
/**
 * Content wrappers
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/global/wrapper-start.php.
 *
 * Template Overrides: https://docs.woocommerce.com/document/template-structure/#section-1
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package Classic Commerce/Templates
 * @version WC-3.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$template = wc_get_theme_slug_for_templates();

switch ( $template ) {
	case 'twentyten' :
		echo '<div id="container"><div id="content" role="main">';
		break;
	case 'twentyeleven' :
		echo '<div id="primary"><div id="content" role="main" class="twentyeleven">';
		break;
	case 'twentytwelve' :
		echo '<div id="primary" class="site-content"><div id="content" role="main" class="twentytwelve">';
		break;
	case 'twentythirteen' :
		echo '<div id="primary" class="site-content"><div id="content" role="main" class="entry-content twentythirteen">';
		break;
	case 'twentyfourteen' :
		echo '<div id="primary" class="content-area"><div id="content" role="main" class="site-content twentyfourteen"><div class="tfwc">';
		break;
	case 'twentyfifteen' :
		echo '<div id="primary" role="main" class="content-area twentyfifteen"><div id="main" class="site-main t15wc">';
		break;
	case 'twentysixteen' :
		echo '<div id="primary" class="content-area twentysixteen"><main id="main" class="site-main" role="main">';
		break;
	default :
		echo '<div id="primary" class="content-area"><main id="main" class="site-main" role="main">';
		break;
}
