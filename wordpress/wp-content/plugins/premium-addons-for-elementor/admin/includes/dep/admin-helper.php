<?php

namespace PremiumAddons\Admin\Includes;

if ( ! defined( 'ABSPATH' ) ) exit;

class Admin_Helper {
    
	private static $instance = null;
    
    public static $current_screen = null;
    
    /**
    * Constructor for the class
    */
    public function __construct() {
        
        add_action( 'current_screen', array( $this, 'get_current_screen' ) );
        
    }
    
    /**
     * Gets current screen slug
     * 
     * @since 3.3.8
     * @access public
     * 
     * @return string current screen slug
     */
    public static function get_current_screen() {
        
        self::$current_screen = get_current_screen()->id;
        
        return isset( self::$current_screen ) ? self::$current_screen : false;
        
    }
    
    public static function get_instance() {
        if( self::$instance == null ) {
            self::$instance = new self;
        }
        return self::$instance;
    }
       
}

if( ! function_exists('get_admin_helper_instance') ) {
    /**
	 * Returns an instance of the plugin class.
     * 
	 * @since  3.3.8
     * 
	 * @return object
	 */
    function get_admin_helper_instance() {
        return Admin_Helper::get_instance();
    }
}

get_admin_helper_instance();