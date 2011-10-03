<?php
/**
 * Ajax gateway
 *
 * @package         wp-bannerize
 * @subpackage      ajax_sorter.php
 * @author          =undo= <g.fazioli@saidmade.com>
 * @copyright       Copyright (C) 2010 Saidmade Srl
 *
 */
if ( @isset($_SERVER['HTTP_X_REQUESTED_WITH']) ) {
	// @todo: questo è bruttino, trovare soluzione
    require_once('../../../wp-config.php');
    // Database connetc
	$_db = @mysql_connect ( DB_HOST, DB_USER, DB_PASSWORD );
	mysql_select_db( DB_NAME );

	$limit = intval($_POST['limit']);
	$page_offset = (intval($_POST['offset']) - 1) * $limit;

    foreach($_POST["item"] as $key => $value){
		$sql = sprintf("UPDATE `%s` SET `sorter` = %s WHERE id = %s", $wpdb->prefix ."bannerize_a", (intval($key)+$page_offset ), $value );
        $result = mysql_query($sql);
    }
}
?>