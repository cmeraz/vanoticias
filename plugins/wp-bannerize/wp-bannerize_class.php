<?php
/**
 * Main class for sub.classing backend and frontend class
 * 
 * @package         wp-bannerize
 * @subpackage      wp-bannerize_class
 * @author          =undo= <g.fazioli@saidmade.com>
 * @copyright       Copyright (C) 2010 Saidmade Srl
 *
 */

define('WP_BANNERIZE_TABLE_2411', 'bannerize');	// Name of Database table up 2.4.11
define('WP_BANNERIZE_TABLE', 'bannerize_a');	// Name of Database table fom 2.5.0

class WPBANNERIZE_CLASS {

	/**
	 * Plugin version (see above)
	 *
	 * @since 2.4.7
	 * @var string
	 */
	var $version 						= "2.6.11";

    /**
     * WP Bannerize release.minor.revision
     * 
     * @since 2.3.0
     * @var integer
     */
    var $release                        = "";
    var $minor                          = "";
    var $revision                       = "";
    
    /**
     * Plugin name
     *
     * @since 1.0.0
     * @var string
     */
    var $plugin_name 					= "Publicidad";

    /**
     * Plugin slug
     *
     * @since 2.5.0
     * @var string
     */
    var $plugin_slug 					= "wp-bannerize";

    /**
     * Key for database options
     *
     * @since 1.0.0
     * @var string
     */
    var $options_key 					= "wp-bannerize";

    /**
     * Options array containing all options for this plugin
     * 
     * @since 1.0.0 
     * @var array
     */
    var $options						= array();
    
    /**
     * Backend title
     *
     * @since 1.0.0
     * @var string
     */
    var $options_title					= "WP Bannerize";

    /**
     * Property for table name
     *
     * @since 1.4.0
     * @var string
     */
    var $table_bannerize;
    var $old_table_bannerize;

    var $content_url					= "";
    var $plugin_url						= "";
    var $ajax_sorter					= "";
    var $ajax_clickcounter				= "";

    var $path 							= "";
    var $file 							= "";
    var $directory						= "";
    var $uri 							= "";
    var $siteurl 						= "";
    var $wpadminurl 					= "";

    /**
     * Standard PHP 4 constructor
     *
     * @since 1.0.0
     * @global object $wpdb
     */
    function WPBANNERIZE_CLASS() {
		global $wpdb;

		/**
		 * Split version for more detail
		 */
		$split_version  = explode(".", $this->version);
		$this->release  = $split_version[0];
		$this->minor    = $split_version[1];
		$this->revision = $split_version[2];

		/**
		 * Build the common and usefull path
		 */
		$this->url          = plugins_url("", __FILE__);

		if (! defined('WP_CONTENT_DIR'))
			define('WP_CONTENT_DIR', ABSPATH . 'wp-content');

		if (! defined('WP_CONTENT_URL'))
			define('WP_CONTENT_URL', get_option('siteurl') . '/wp-content');

		if (! defined('WP_ADMIN_URL'))
			define('WP_ADMIN_URL', get_option('siteurl') . '/wp-admin');

		if (! defined('WP_PLUGIN_DIR'))
			define('WP_PLUGIN_DIR', WP_CONTENT_DIR . '/plugins');

		if (! defined('WP_PLUGIN_URL'))
			define('WP_PLUGIN_URL', WP_CONTENT_URL . '/plugins');

		/**
		 * Add $wpdb->prefix to table name define in WP_BANNERIZE_TABLE. This
		 * featured makes wp-bannerize compatible with Wordpress MU and Wordpress
		 * with different database prefix
		 *
		 * @since 2.2.1
		 */
		$this->table_bannerize              = $wpdb->prefix . WP_BANNERIZE_TABLE;

		/**
		 * Build internal usefull paths
		 */

		/**
		 * Conversion Old Database
		 *
		 * @since 2.5.0
		 */
		$this->old_table_bannerize          = $wpdb->prefix . WP_BANNERIZE_TABLE_2411;

		$this->path 						= dirname(__FILE__);
		$this->file 						= basename(__FILE__);
		$this->directory 					= basename($this->path);
		$this->uri                          = plugins_url("", __FILE__);
		$this->siteurl						= get_bloginfo('url');
		$this->wpadminurl					= admin_url();

		$this->content_url 					= get_option('siteurl') . '/wp-content';
		$this->plugin_url 					= $this->content_url . '/plugins/' . plugin_basename( dirname(__FILE__) ) . '/';
		$this->ajax_sorter					= $this->plugin_url . "ajax_sorter.php";
		$this->ajax_clickcounter			= $this->plugin_url . "ajax_clickcounter.php";
    }

    /**
     * Check the Wordpress relase for more setting
     *
     * @deprecated
     */
    function checkWordpressRelease() {
        global $wp_version;
        if ( strpos($wp_version, '2.7') !== false || strpos($wp_version, '2.8') !== false  ) {}
    }
} // end of class

/**
 * Avoid widget support
 *
 * @since 2.3.5
 */
if(class_exists("WP_Widget")) {
    require_once('wp-bannerize_widget.php');
    add_action('widgets_init', create_function('', 'return register_widget("WP_BANNERIZE_WIDGET");'));
}
?>