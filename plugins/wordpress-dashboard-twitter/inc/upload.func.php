<?php
switch( $_GET['action'] ) {
	case 'upload-image':
		require_once( '../../../../wp-load.php' );
		$uploaddir = str_replace('inc/', '', dirname( __FILE__ ) . '/uploads/' );
		$uploadfile = $uploaddir . wp_unique_filename($uploaddir, $_FILES['userfile']['name']);
		
		if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
			echo "Uploaded.";
		} else {}
		break;
}
?>