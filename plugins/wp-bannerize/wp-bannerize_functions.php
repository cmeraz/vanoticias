<?php
/**
 * Wrap for simple function
 *
 * @package         wp-bannerize
 * @subpackage      wp-bannerize_functions
 * @author          =undo= <g.fazioli@saidmade.com>
 * @copyright       Copyright (C) 2010 Saidmade Srl
 *
 */

/**
 * Show banner
 * 
 * @return 
 * @param object $args[optional]
 */
function wp_bannerize( $args = '' ) {
	global $wp_bannerize_frontend;
	$wp_bannerize_frontend->bannerize( $args );
}
?>