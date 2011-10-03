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

//############################################################################
// Debug mode
//############################################################################

if (!function_exists('nggaddons_do_thumb_shortcode')) {

/*
* Function to show a thumbnail or a set of thumbnails with shortcode of type:
*     [thumb id="1,2,4,5,..." caption="none|alttext|desc" group="thumbnail-group" float="|left|right" /]
* where 
*  - id is one or more picture ids
*  - caption is the text to put under the thumbnail
*  - group is a group name for the thumbnails in case you want to group them when using thickbox or highslide
*  - float is the CSS float property to apply to the thumbnail
*/
function nggaddons_do_thumb_shortcode($atts, $content=null) {	
	global $nggRewrite;
	
	$out = '';

	// Extract attributes
	//--
	extract(shortcode_atts(array(
		'id' 		=> '',
		'caption' 	=> 'none',
		'group' 	=> '',
		'float'		=> ''
	), $atts));
	
	// make an array out of the ids
	//--
	$pids = explode(",", $id);
	
	// Some error checks
	//--
	if (count($pids)==0) {
		return "<p style='color: red; border: 2px solid red;'>At least one picture ID must be supplied for the shortcode [thumb]</p>";
	}
	
	if ($caption!='none' && $caption!='alttext' && $caption!='desc') {
		return "<p style='color: red; border: 2px solid red;'>Invalid value for the caption parameter of the shortcode [thumb]</p>";		
	}
	
	// Get ngg options
	//--
	$ngg_options = nggallery::get_option('ngg_options');
	
	// set thumb size 
	//--
	$thumbwidth = $ngg_options['thumbwidth'];
	$thumbheight = $ngg_options['thumbheight'];	
	$thumbsize = "";
	if ($ngg_options['thumbfix'])  $thumbsize = 'style="width:'.$thumbwidth.'px; height:'.$thumbheight.'px;"';
	if ($ngg_options['thumbcrop']) $thumbsize = 'style="width:'.$thumbwidth.'px; height:'.$thumbwidth.'px;"';
	
	// a description below the picture, require fixed width
	//--
	$setwidth = ($caption!="none") ? 'style="width:' . $thumbwidth . 'px;"' : '';
	$class_desc = ($caption!="none") ? 'desc' : '';
	
	// get the effect code
	$thumbcode = nggallery::get_thumbcode($group);
		
	// Start building the output HTML
	//--
	if (count($pids)>1) {
		$out .= '<div class="ngg-galleryoverview">';
	}
	
	// For each picture ID
	//--
	foreach ($pids as $pid) {	
		// Get picture
		//--
		$picture = new nggImage($pid);
	
		// set image url
		//--
		$folder_url 	= get_option ('siteurl') . "/" . $picture->path . "/";
		$thumbnailURL 	= get_option ('siteurl') . "/" . $picture->path . nggallery::get_thumbnail_folder($picture->path, FALSE);
		$thumb_prefix   = nggallery::get_thumbnail_prefix($picture->path, FALSE);

		// choose link between imagebrowser or effect
		//--
		$link = ($ngg_options['galImgBrowser']) ? $nggRewrite->get_permalink(array('pid'=>$picture->pid)) : $folder_url.$picture->filename;

		// add filter for the link
		//--
		$link 		= apply_filters('ngg_create_gallery_link', $link, $picture);
		$thumbcode2  = apply_filters('ngg_create_gallery_thumbcode', $thumbcode, $picture);
		
		// The float code
		//--
		$style = ($float=='') ? '' : ' style="float:' . $float . ';" ';
		
		// create output
		//--
		$out .= '<div id="ngg-image-' . $picture->pid . '" class="ngg-gallery-thumbnail-box ' . $class_desc . '" ' . $style . '>' . "\n\t";
		$out .= '<div class="ngg-gallery-thumbnail" ' . $setwidth . ' >' . "\n\t";
		$out .= '<a id="thumb' . $picture->pid . '" href="' . $link . '" title="' . stripslashes($picture->description) . '" ' . $thumbcode2 . ' >';
		$out .= '<img title="' . stripslashes($picture->alttext) . '" alt="' . stripslashes($picture->alttext) . '" ';
		$out .= 'src="' . $thumbnailURL . $thumb_prefix . $picture->filename . '" ' . $thumbsize . ' />';
		$out .= '</a>' . "\n";
		
		if ($caption == "alttext") {
			$out .= '<span>' . html_entity_decode(stripslashes($picture->alttext)) . '</span>' . "\n";
		} else if ($caption == "desc") {
			$out .= '<span>' . html_entity_decode(stripslashes($picture->description)) . '</span>' . "\n";
		}
		
		// add filter for the output
		//--
		$out  = apply_filters('ngg_inner_gallery_thumbnail', $out, $picture);		
		$out .= '</div>'. "\n" .'</div>'."\n";
		$out  = apply_filters('ngg_after_gallery_thumbnail', $out, $picture);
	}
	
	if (count($pids)>1) {
		$out .= '</div>';
	}
	
	return $out;
}

}

?>