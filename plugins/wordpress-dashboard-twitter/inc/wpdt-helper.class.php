<?php
/**
 * The WPDashboardTwitter_Helper Class
 *
 * @package 	WordPress_Plugins
 * @subpackage 	WPDashboardTwitter
 */

/** 
* The WPDashboardTwitter_Helper class
* holding all of the compatibility and
* helper methods
*
* @package 		WordPress_Plugins
* @subpackage 	WPDashboardTwitter
* @since 		0.8
* @author 		info@wpdashboardtwitter.com/
*/
class WPDashboardTwitter_Helper extends WPDashboardTwitter {
	
	/**
 	* Class Constructor
 	*
 	* @since 		0.8
 	* @author 		info@wpdashboardtwitter.com/
 	*/
	function wpdashboardtwitter_helper() {
		parent::__construct();
	}
	
	/**
 	* Retrieve the url to the plugins directory or to a specific file within that directory.
 	* The original function has been changed in 2.8 but we need it for 2.7 as well
 	* so we can pass a second argument
 	*
 	* @since 		0.8
 	* @author 		info@wpdashboardtwitter.com/
 	*/
	function plugins_url($path = '', $plugin = '') {
		if( version_compare($GLOBALS['wp_version'], '2.7.999', '>') ) {
			return plugins_url($path, $plugin);
		} else {
			$scheme = ( is_ssl() ? 'https' : 'http' );

			if ( $plugin !== '' && preg_match('#^' . preg_quote(WPMU_PLUGIN_DIR . DIRECTORY_SEPARATOR, '#') . '#', $plugin) ) {
				$url = WPMU_PLUGIN_URL;
			} else {
				$url = WP_PLUGIN_URL;
			}

			if ( 0 === strpos($url, 'http') ) {
				if ( is_ssl() )
					$url = str_replace( 'http://', "{$scheme}://", $url );
			}

			if ( !empty($plugin) && is_string($plugin) ) {
				$folder = dirname(plugin_basename($plugin));
				if ('.' != $folder)
					$url .= '/' . ltrim($folder, '/');
			}

			if ( !empty($path) && is_string($path) && strpos($path, '..') === false )
				$url .= '/' . ltrim($path, '/');
	
			return apply_filters('plugins_url', $url, $path, $plugin);
		}
	}
	
	
	/**
 	* Get the available url shortening services
 	*
 	* @since 		0.8
 	* @return 		array 	$services
 	* @author 		info@wpdashboardtwitter.com
 	*/
	function get_url_shorteners() {
		$services = array(
			'wpgd' => array( 'name' => 'wp.gd', 'apiurl' => 'http://wp.gd/xxx/api.php?longurl=' ),
			'trim' => array( 'name' => 'tr.im', 'apiurl' => 'http://api.tr.im/api/trim_url.xml?url=' ),
			'bitly' => array( 'name' => 'bit.ly', 'apiurl' => 'http://api.bit.ly/shorten?version=2.0.1&format=xml&login=wpdashboardtwitter&apiKey=R_1170b54df8fff071764dad0188ed02da&longUrl=' )
		);
		return $services;
	}
	
	
	/**
 	* Displays the name of the used url shortener
 	*
 	* @since 		0.8
 	* @return 		array 	$shorteners
 	* @author 		info@wpdashboardtwitter.com
 	*/
	function display_url_shortener() {
		$shorteners = $this->dashboard_widget_options();
	}
	
	
	/**
 	* Encrypts a string for storing in the db
 	*
 	* @since 		0.8.3
 	* @param 		string	$str
 	* @return 		string	$str
 	* @author 		info@wpdashboardtwitter.com
 	*/
	function encrypt( $str ) {
		global $wp_default_secret_key;
		$str = base64_encode( $str . 'wpdtwoelfi' ); // The evil w-word again. :-(
		return $str;
	}
	
	
	/**
 	* Decrypts a string
 	*
 	* @since 		0.8.3
 	* @param 		string	$str
 	* @return 		string	$str
 	* @author 		info@wpdashboardtwitter.com
 	* @deprecated	1.0
 	*/
	function decrypt( $str ) {
		$options = parent::dashboard_widget_options();
		
		if ( $options['is_pwd_encrypted'] == 0 || $options['is_pwd_encrypted'] == '' )
			$str = $str;
		else
			$str = substr( base64_decode( $str ), 0, -10 );
			
		return $str;
	}
}
?>