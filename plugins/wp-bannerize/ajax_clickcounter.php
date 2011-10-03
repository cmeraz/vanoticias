<?php
/**
 * Ajax gateway
 *
 * @package         wp-bannerize
 * @subpackage      ajax_clickcounter.php
 * @author          =undo= <g.fazioli@saidmade.com>
 * @copyright       Copyright (C) 2010 Saidmade Srl
 *
 */
if ( @isset($_SERVER['HTTP_X_REQUESTED_WITH']) ) {
	// @todo: questo è bruttino, trovare soluzione
    require_once('../../../wp-config.php');
    $_db = @mysql_connect ( DB_HOST, DB_USER, DB_PASSWORD ); mysql_select_db( DB_NAME );
	$sql = "UPDATE `" . $wpdb->prefix ."bannerize_a` SET `clickcount` = `clickcount`+1 WHERE id = " . $_POST['id'];
	$result = mysql_query($sql);
}
?>