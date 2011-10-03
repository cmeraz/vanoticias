<?php
/*
Plugin Name: Wp Post to Twitter by MikiUrl
Plugin URI: http://mikiurl.com/eklenti.html
Description: Publish your article to your blog, by shortening their links with <a href="http://www.mikiurl.com" target="_blank">Mikiurl</a> sends them to your Twitter account. (tr)Blogunuzda yayınladığınız yazılarınızı, Mikiurl ile linklerini kısaltarak Twitter hesabınıza gönderir.
Author: Miki Url
Version: 1.0
Author URI: http://mikiurl.com
*/

register_activation_hook('mikiurl.php', 'mikiurl_kurulum');
add_action('publish_post', 'mikiurl_gonder');
add_action('admin_menu', 'mikiurl_menu_ekle');

function mikiurl_menu_ekle() {
	add_submenu_page('options-general.php', 'MikiUrl Settings/Ayarlar', 'MikiUrl', 10, __FILE__, 'mikiurl_menu');
}

function mikiurl_kurulum() {
	add_option('mikiurl_ayarlar', serialize(array('twitter_kullanici' => '','twitter_sifre' => '', 'onek'=> '')));
}

function mikiurl_ekle($baslik, $url,  $kullanici, $sifre)
{
	$mikiurl_api = 'http://mikiurl.com/api2/?';
	$degerler = 'mesaj='.urlencode($baslik).'&url='.urlencode($url).'&kullanici='.urlencode($kullanici).'&sifre='.urlencode($sifre);
	$sonuc = file_get_contents( $mikiurl_api.$degerler );
	return $sonuc;
}

function mikiurl_gonder( $postID )
{
	$mikiurl_ayarlar = get_option('mikiurl_ayarlar');
	$mikiurl_ayarlar = unserialize($mikiurl_ayarlar);
	$mikiurl_ayarlar["twitter_sifre"] = stripslashes($mikiurl_ayarlar["twitter_sifre"]);
	$mikiurl_ayarlar["onek"] = stripslashes($mikiurl_ayarlar["onek"]);
	$baslik = !empty($mikiurl_ayarlar["onek"]) ? $mikiurl_ayarlar["onek"] . ": " . $_POST['post_title'] : $_POST['post_title'];
	$permalink = get_permalink($postID);

	if( !isset($_POST['publish'])):
		return false;
	else:
		if(empty($mikiurl_ayarlar['twitter_kullanici']) || empty($mikiurl_ayarlar['twitter_sifre']) || $_POST['post_type'] != 'post' ):
			return false;
		else:
			if(!ini_get('allow_url_fopen')):
				return false;
			else:
				$sonuc = mikiurl_ekle($baslik, $permalink, $mikiurl_ayarlar['twitter_kullanici'], $mikiurl_ayarlar['twitter_sifre']);
				return ( $sonuc ) ? true : false;				
			endif;
		endif;
	endif;	
}

function mikiurl_kaydet() {

	$ayarlar = array();
	
	$ayarlar['twitter_kullanici'] = ( !empty($_POST['twitter_kullanici']) ) ? $_POST['twitter_kullanici'] : '';
	$ayarlar['twitter_sifre'] = ( !empty($_POST['twitter_sifre']) ) ? $_POST['twitter_sifre'] : '';
	$ayarlar['onek'] = ( !empty($_POST['onek']) ) ? $_POST['onek'] : '';
	
	$ayarlar = serialize($ayarlar);
	update_option('mikiurl_ayarlar',$ayarlar);
}

function mikiurl_menu()
{
	if( $_GET['action'] == 'kaydet'):
		mikiurl_kaydet();
		echo '<div class="updated fade" id="message"><p><strong>Configuración Guardada!</strong></p></div>';
	endif;

	$mikiurl_ayarlar = get_option('mikiurl_ayarlar');
	$mikiurl_ayarlar = unserialize($mikiurl_ayarlar);
	$mikiurl_ayarlar["twitter_sifre"] = stripslashes($mikiurl_ayarlar["twitter_sifre"]);
	
	if( !ini_get('allow_url_fopen') ):
		echo '<div class="error" id="message"><p><strong>allow_url_fopen fonksiyonu kapalı! Lütfen bu fonksiyonu açın</strong></p></div>';
	endif;
	
	echo '<div id="mikiurl_wp" style="margin:120px 0px 0px 20px; font-family:tahoma;">
	<div style="margin: 20px 0px; width: 800px; text-align: left;">
	<!--
	<a href="http://mikiurl.com" target="_blank"><img border="0" alt="logo" src="'.get_option('siteurl').'/wp-content/plugins/wp-post-to-twitter-by-mikiurl/mikiurl_wp_logo.png"/></a></div> -->
	<div style="width: 800px; margin-bottom: 20px; height: auto;"><h2 style="color:#2583AD;">Configuraciones</h2>
	<div id="options">
	<form action="options-general.php?page=wp-post-to-twitter-by-mikiurl/mikiurl.php&amp;action=kaydet" name="ff_form" method="post">
	<table class="form-table">
	<tbody>
	<tr valign="top">
	<th scope="row">Nombre de Usuario Twitter</th>
	<td><input type="text" value="'.$mikiurl_ayarlar['twitter_kullanici'].'" size="40" id="twitter_username" name="twitter_kullanici"/></td>
	</tr>
	<tr valign="top">
	<th scope="row">Password de Twitter</th>
	<td><input type="password" value="'.stripslashes($mikiurl_ayarlar['twitter_sifre']).'" size="40" id="twitter_password" name="twitter_sifre"/></td>
	</tr>
		<tr valign="top">
	<th scope="row">Prefijo del mensaje<br/><small>(ejemplo: Mi nuevo post: )</small></th>
	<td><input type="text" value="'.stripslashes($mikiurl_ayarlar['onek']).'" size="40" id="onek" name="onek"/></td>
	</tr>
	</tbody>
	</table>
	
	<p class="submit"><input type="submit" value="Guardar" name="btnSave"/></p></form></div></div></div>';
}
?>