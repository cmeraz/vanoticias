<?php
/*
Plugin Name: nextgen-gallery-addons
Plugin URI: http://nextgen-gallery-addons.vincentprat.info
Description: A plugin for WordPress which enhances the well known NextGen Gallery plugin.
Version: 1.0.0
Author: Vincent Prat
Author URI: http://www.vincentprat.info

    Copyright 2006 Vincent Prat  (email : vpratfr@yahoo.fr)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

//############################################################################
// Stop direct call
if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { 
	die('You are not allowed to call this page directly.'); 
}
//############################################################################

//############################################################################
// plugin directory
define('NGG_ADDONS_DIR', dirname (__FILE__));	

// i18n plugin domain 
define('NGG_ADDONS_I18N_DOMAIN', 'ngg-addons');

// The options of the plugin
define('NGG_ADDONS_PLUGIN_OPTIONS', 'ngg-addons_plugin_options');	
//############################################################################

//############################################################################
// Include the plugin files
require_once(NGG_ADDONS_DIR . '/includes/ngg-filters.php');
require_once(NGG_ADDONS_DIR . '/includes/ngg-thumb-shortcode.php');
//############################################################################

//############################################################################
// Init the plugin classes
//############################################################################

//############################################################################
// Load the plugin text domain for internationalisation
//############################################################################

//############################################################################
// Add filters and actions
add_filter(
	'ngg_create_gallery_thumbcode', 
	'nggaddons_create_gallery_thumbcode', 
	10, 2);

add_shortcode(
	'thumb', 
	'nggaddons_do_thumb_shortcode');
		
//############################################################################

?>