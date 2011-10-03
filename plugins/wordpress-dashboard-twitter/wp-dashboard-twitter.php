<?php
/**
 * The main plugin file
 *
 * @package 	WordPress_Plugins
 * @subpackage 	WPDashboardTwitter
 */

/*
Plugin Name: WordPress Dashboard Twitter
Version: 1.0.2
Plugin URI: http://wpdashboardtwitter.com/
Description: <strong>WordPress 2.7+ only.</strong> Display Twitter @replies, direct messages, sent messages, favorites and send tweets and direct messages the convenient way within your WordPress Dashboard.
Author: Oliver Schl&ouml;be &amp; Robert Pfotenhauer
Author URI: http://wpdashboardtwitter.com/

Copyright 2009-2010 Oliver Schl&ouml;be &amp; Robert Pfotenhauer (email : info@wpdashboardtwitter.com)

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
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

// Do anything if we are in admin area only
if ( is_admin() ) {
	/** 
 	* This file loads all of the actual plugin classes and methods
 	*/
	require_once( dirname( __FILE__ ) . '/wpdt.class.php' );
}
?>