<?php
/*  Copyright 2006 Vincent Prat  (email : vpratfr@yahoo.fr)

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

if (!function_exists('nggaddons_ngg_create_gallery_thumbcode')) {

/**
*
*/
function nggaddons_create_gallery_thumbcode($thumbcode, $picture) {
	$new_thumbcode = str_replace("%PICTURE_ALT%", $picture->alttext, $thumbcode);
	$new_thumbcode = str_replace("%PICTURE_DESC%", $picture->description, $new_thumbcode);
	return $new_thumbcode;
}

}

?>