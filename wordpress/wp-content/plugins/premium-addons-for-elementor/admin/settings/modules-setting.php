<?php

namespace PremiumAddons\Admin\Settings;

use PremiumAddons\Helper_Functions;

if( ! defined( 'ABSPATH' ) ) exit(); // Exit if accessed directly

class Modules_Settings {
    
    protected $page_slug = 'premium-addons';

    public static $pa_elements_keys = ['premium-banner', 'premium-blog','premium-carousel', 'premium-countdown','premium-counter','premium-dual-header','premium-fancytext','premium-image-separator','premium-maps','premium-modalbox','premium-person','premium-progressbar','premium-testimonials','premium-title','premium-videobox','premium-pricing-table','premium-button','premium-contactform', 'premium-image-button', 'premium-grid','premium-vscroll', 'premium-image-scroll', 'premium-templates'];
    
    private $pa_default_settings;
    
    private $pa_settings;
    
    private $pa_get_settings;
   
    public function __construct() {
        
        add_action( 'admin_menu', array( $this,'pa_admin_menu') );
        
        add_action( 'admin_enqueue_scripts', array( $this, 'pa_admin_page_scripts' ) );
        
        add_action( 'wp_ajax_pa_save_admin_addons_settings', array( $this, 'pa_save_settings' ) );
        
        add_action('admin_enqueue_scripts',array( $this, 'localize_js_script' ) );
        
        add_filter( 'plugin_action_links_' . PREMIUM_ADDONS_BASENAME, array( $this, 'plugin_settings_page' ) );
        
    }
    
    /*
    * Creates `Settings` action link
    * @since 1.0.0
    * @return void
    */
   public function plugin_settings_page( $links ) {

       $settings_link = sprintf( '<a href="%1$s">%2$s</a>', admin_url( 'admin.php?page=' . $this->page_slug ), __( 'Settings', 'premium-addons-for-elementor' ) );

       array_unshift( $links, $settings_link );

       return $links;
   }
    
    public function localize_js_script(){
        wp_localize_script(
            'pa-admin-js',
            'premiumRollBackConfirm',
            [
                'home_url'  => home_url(),
                'i18n' => [
					'rollback_confirm' => __( 'Are you sure you want to reinstall version ' . PREMIUM_ADDONS_STABLE_VERSION . ' ?', 'premium-addons-for-elementor' ),
					'rollback_to_previous_version' => __( 'Rollback to Previous Version', 'premium-addons-for-elementor' ),
					'yes' => __( 'Yes', 'premium-addons-for-elementor' ),
					'cancel' => __( 'Cancel', 'premium-addons-for-elementor' ),
				],
            ]
            );
    }

    public function pa_admin_page_scripts () {
        
        wp_enqueue_style( 'pa_admin_icon', PREMIUM_ADDONS_URL .'admin/assets/fonts/style.css' );
        
        $current_screen = get_current_screen();
        
        wp_enqueue_style( 'pa-notice-css', PREMIUM_ADDONS_URL.'admin/assets/css/notice.css' );
        
        if( strpos( $current_screen->id , $this->page_slug ) !== false ) {
            
            wp_enqueue_style(
                'pa-admin-css',
                PREMIUM_ADDONS_URL.'admin/assets/css/admin.css'
            );
            
            wp_enqueue_style(
                'pa-sweetalert-style',
                PREMIUM_ADDONS_URL . 'admin/assets/js/sweetalert2/sweetalert2.min.css'
            );
            
            wp_enqueue_script(
                'pa-admin-js',
                PREMIUM_ADDONS_URL .'admin/assets/js/admin.js',
                array('jquery'),
                PREMIUM_ADDONS_VERSION,
                true
            );
            
            wp_enqueue_script(
                'pa-admin-dialog',
                PREMIUM_ADDONS_URL . 'admin/assets/js/dialog/dialog.js',
                array('jquery-ui-position'),
                PREMIUM_ADDONS_VERSION,
                true
            );
            
            wp_enqueue_script(
                'pa-sweetalert-core',
                PREMIUM_ADDONS_URL . 'admin/assets/js/sweetalert2/core.js',
                array('jquery'),
                PREMIUM_ADDONS_VERSION,
                true
            );
            
			wp_enqueue_script(
                'pa-sweetalert',
                PREMIUM_ADDONS_URL . 'admin/assets/js/sweetalert2/sweetalert2.min.js',
                array( 'jquery', 'pa-sweetalert-core' ),
                PREMIUM_ADDONS_VERSION,
                true
            );
            
        }
    }

    public function pa_admin_menu() {
        
        $plugin_name = get_option('pa_wht_lbl_save_settings')['premium-wht-lbl-plugin-name'];
        
        if( ! defined('PREMIUM_PRO_ADDONS_VERSION') || ! isset( $plugin_name ) || '' == $plugin_name ){
            $plugin_name = 'Premium Addons for Elementor';
        }
        
        add_menu_page(
            $plugin_name,
            $plugin_name,
            'manage_options',
            'premium-addons',
            array( $this , 'pa_admin_page' ),
            '' ,
            100
        );
    }

    public function pa_admin_page() {
        
        $theme_slug = Helper_Functions::get_installed_theme();
        
        $js_info = array(
            'ajaxurl'   => admin_url( 'admin-ajax.php' ),
            'nonce' 	=> wp_create_nonce( 'pa-elements' ),
            'theme'     => $theme_slug
		);

		wp_localize_script( 'pa-admin-js', 'settings', $js_info );
        
        $this->pa_default_settings = $this->get_default_keys();
       
        $this->pa_get_settings = $this->get_enabled_keys();
       
        $pa_new_settings = array_diff_key( $this->pa_default_settings, $this->pa_get_settings );
       
        if( ! empty( $pa_new_settings ) ) {
            $pa_updated_settings = array_merge( $this->pa_get_settings, $pa_new_settings );
            update_option( 'pa_save_settings', $pa_updated_settings );
        }
        $this->pa_get_settings = get_option( 'pa_save_settings', $this->pa_default_settings );
        
        $prefix = Helper_Functions::get_prefix();
        
	?>
	<div class="wrap">
        <div class="response-wrap"></div>
        <form action="" method="POST" id="pa-settings" name="pa-settings">
            <div class="pa-header-wrapper">
                <div class="pa-title-left">
                    <h1 class="pa-title-main"><?php echo Helper_Functions::name(); ?></h1>
                    <h3 class="pa-title-sub"><?php echo sprintf(__('Thank you for using %s. This plugin has been developed by %s and we hope you enjoy using it.','premium-addons-for-elementor'), Helper_Functions::name(), Helper_Functions::author() ); ?></h3>
                </div>
                <?php if( ! Helper_Functions::is_show_logo()) : ?>
                <div class="pa-title-right">
                    <img class="pa-logo" src="<?php echo PREMIUM_ADDONS_URL . 'admin/images/premium-addons-logo.png';?>">
                </div>
                <?php endif; ?>
            </div>
            <div class="pa-settings-tabs">
                <div id="pa-modules" class="pa-settings-tab">
                    <div>
                        <br>
                        <input type="checkbox" class="pa-checkbox" checked="checked">
                        <label>Enable/Disable All</label>
                    </div>
                    <table class="pa-elements-table">
                        <tbody>
                            <tr>
                                <th><?php echo sprintf( '%1$s %2$s', $prefix, __('Banner', 'premium-addons-for-elementor') ); ?></th>
                                <td>
                                    <label class="switch">
                                        <input type="checkbox" id="premium-banner" name="premium-banner" <?php checked(1, $this->pa_get_settings['premium-banner'], true) ?>>
                                        <span class="slider round"></span>
                                </label>
                                </td>
                                <th><?php echo sprintf( '%1$s %2$s', $prefix, __('Blog', 'premium-addons-for-elementor') ); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox" id="premium-blog" name="premium-blog" <?php checked(1, $this->pa_get_settings['premium-blog'], true) ?>>
                                            <span class="slider round"></span>
                                    </label>
                                </td>
                            </tr>
                            
                            <tr>
                                <th><?php echo sprintf( '%1$s %2$s', $prefix, __('Button', 'premium-addons-for-elementor') ); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox" id="premium-button" name="premium-button" <?php checked(1, $this->pa_get_settings['premium-button'], true) ?>>
                                            <span class="slider round"></span>
                                    </label>
                                </td>
                                <th><?php echo sprintf( '%1$s %2$s', $prefix, __('Carousel', 'premium-addons-for-elementor') ); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox" id="premium-carousel" name="premium-carousel" <?php checked(1, $this->pa_get_settings['premium-carousel'], true) ?>>
                                            <span class="slider round"></span>
                                    </label>
                                </td>
                            </tr>
                            
                            <tr>
                                <th><?php echo sprintf( '%1$s %2$s', $prefix, __('Contact Form7', 'premium-addons-for-elementor') ); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox" id="premium-contactform" name="premium-contactform" <?php checked(1, $this->pa_get_settings['premium-contactform'], true) ?>>
                                            <span class="slider round"></span>
                                    </label>
                                </td>
                                <th><?php echo sprintf( '%1$s %2$s', $prefix, __('Countdown', 'premium-addons-for-elementor') ); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox" id="premium-countdown" name="premium-countdown" <?php checked(1, $this->pa_get_settings['premium-countdown'], true) ?>>
                                            <span class="slider round"></span>
                                    </label>
                                </td>
                            </tr>
                            
                            <tr>
                                <th><?php echo sprintf( '%1$s %2$s', $prefix, __('Counter', 'premium-addons-for-elementor') ); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox" id="premium-counter" name="premium-counter" <?php checked(1, $this->pa_get_settings['premium-counter'], true) ?>>
                                            <span class="slider round"></span>
                                    </label>
                                </td>
                                <th><?php echo sprintf( '%1$s %2$s', $prefix, __('Dual Heading', 'premium-addons-for-elementor') ); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox" id="premium-dual-header" name="premium-dual-header" <?php checked(1, $this->pa_get_settings['premium-dual-header'], true) ?>>
                                            <span class="slider round"></span>
                                    </label>
                                </td>
                            </tr>
                            
                            <tr>
                                <th><?php echo sprintf( '%1$s %2$s', $prefix, __('Fancy Text', 'premium-addons-for-elementor') ); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox" id="premium-fancytext" name="premium-fancytext" <?php checked(1, $this->pa_get_settings['premium-fancytext'], true) ?>>
                                            <span class="slider round"></span>
                                    </label>
                                </td>
                                
                                <th><?php echo sprintf( '%1$s %2$s', $prefix, __('Media Grid', 'premium-addons-for-elementor') ); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox" id="premium-grid" name="premium-grid" <?php checked(1, $this->pa_get_settings['premium-grid'], true) ?>>
                                            <span class="slider round"></span>
                                    </label>
                                </td>
                            </tr>
                            
                            <tr>
                                <th><?php echo sprintf( '%1$s %2$s', $prefix, __('Image Button', 'premium-addons-for-elementor') ); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox" id="premium-image-button" name="premium-image-button" <?php checked(1, $this->pa_get_settings['premium-image-button'], true) ?>>
                                            <span class="slider round"></span>
                                    </label>
                                </td>
                                
                                <th><?php echo sprintf( '%1$s %2$s', $prefix, __('Image Scroll', 'premium-addons-for-elementor') ); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox" id="premium-image-scroll" name="premium-image-scroll" <?php checked(1, $this->pa_get_settings['premium-image-scroll'], true) ?>>
                                            <span class="slider round"></span>
                                    </label>
                                </td>
                            </tr>
                            
                            <tr>
                                <th><?php echo sprintf( '%1$s %2$s', $prefix, __('Image Separator', 'premium-addons-for-elementor') ); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox" id="premium-image-separator" name="premium-image-separator" <?php checked(1, $this->pa_get_settings['premium-image-separator'], true) ?>>
                                            <span class="slider round"></span>
                                    </label>
                                </td>
                                <th><?php echo sprintf( '%1$s %2$s', $prefix, __('Maps', 'premium-addons-for-elementor') ); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox" id="premium-maps" name="premium-maps" <?php checked(1, $this->pa_get_settings['premium-maps'], true) ?>>
                                            <span class="slider round"></span>
                                    </label>
                                </td>
                            </tr>
                            
                            <tr>
                                <th><?php echo sprintf( '%1$s %2$s', $prefix, __('Modal Box', 'premium-addons-for-elementor') ); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox" id="premium-modalbox" name="premium-modalbox" <?php checked(1, $this->pa_get_settings['premium-modalbox'], true) ?>>
                                            <span class="slider round"></span>
                                    </label>
                                </td>
                                
                                <th><?php echo sprintf( '%1$s %2$s', $prefix, __('Person', 'premium-addons-for-elementor') ); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox" id="premium-person" name="premium-person" <?php checked(1, $this->pa_get_settings['premium-person'], true) ?>>
                                            <span class="slider round"></span>
                                    </label>
                                </td>
                            </tr>
                            
                            <tr>
                                <th><?php echo sprintf( '%1$s %2$s', $prefix, __('Progress Bar', 'premium-addons-for-elementor') ); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox" id="premium-progressbar" name="premium-progressbar" <?php checked(1, $this->pa_get_settings['premium-progressbar'], true) ?>>
                                            <span class="slider round"></span>
                                    </label>
                                </td>
                                
                                <th><?php echo sprintf( '%1$s %2$s', $prefix, __('Pricing Table', 'premium-addons-for-elementor') ); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox" id="premium-pricing-table" name="premium-pricing-table" <?php checked(1, $this->pa_get_settings['premium-pricing-table'], true) ?>>
                                            <span class="slider round"></span>
                                    </label>
                                </td>
                            </tr>
                            
                            <tr>
                                <th><?php echo sprintf( '%1$s %2$s', $prefix, __('Testimonials', 'premium-addons-for-elementor') ); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox" id="premium-testimonials" name="premium-testimonials" <?php checked(1, $this->pa_get_settings['premium-testimonials'], true) ?>>
                                            <span class="slider round"></span>
                                    </label>
                                </td>
                                
                                <th><?php echo sprintf( '%1$s %2$s', $prefix, __('Templates', 'premium-addons-for-elementor') ); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox" id="premium-templates" name="premium-templates" <?php checked(1, $this->pa_get_settings['premium-templates'], true) ?>>
                                            <span class="slider round"></span>
                                    </label>
                                </td>
                                
                            </tr>
                            
                            <tr>
                                
                                <th><?php echo sprintf( '%1$s %2$s', $prefix, __('Title', 'premium-addons-for-elementor') ); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox" id="premium-title" name="premium-title" <?php checked(1, $this->pa_get_settings['premium-title'], true) ?>>
                                            <span class="slider round"></span>
                                    </label>
                                </td>
                                
                                <th><?php echo sprintf( '%1$s %2$s', $prefix, __('Video Box', 'premium-addons-for-elementor') ); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox" id="premium-videobox" name="premium-videobox" <?php checked(1, $this->pa_get_settings['premium-videobox'], true) ?>>
                                            <span class="slider round"></span>
                                        </label>
                                </td>
                                
                            </tr>
                            
                            <tr>
                                
                                <th><?php echo sprintf( '%1$s %2$s', $prefix, __('Vertical Scroll', 'premium-addons-for-elementor') ); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox" id="premium-vscroll" name="premium-vscroll" <?php checked(1, $this->pa_get_settings['premium-vscroll'], true) ?>>
                                            <span class="slider round"></span>
                                    </label>
                                </td>
                                
                            </tr>

                            <?php if( ! defined('PREMIUM_PRO_ADDONS_VERSION') ) : ?> 
                            <tr class="pa-sec-elems-tr"><th><h1>PRO Elements</h1></th></tr>

                            <tr>
                                
                                <th><?php echo __('Premium Alert Box', 'premium-addons-for-elementor'); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox" id="premium-notbar" name="premium-notbar">
                                            <span class="pro-slider round"></span>
                                    </label>
                                </td>
                                
                                <th><?php echo __('Premium Icon Box', 'premium-addons-for-elementor'); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox" id="premium-iconbox" name="premium-iconbox">
                                            <span class="pro-slider round"></span>
                                    </label>
                                </td>
                                
                            </tr>
                            
                            <tr>
                                
                                <th><?php echo __('Premium Twitter Feed', 'premium-addons-for-elementor'); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox" id="premium-twitter-feed" name="premium-twitter-feed">
                                            <span class="pro-slider round"></span>
                                    </label>
                                </td>
                                
                                <th><?php echo __('Premium Instagram Feed', 'premium-addons-for-elementor'); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox" id="premium-instagram-feed" name="premium-instagram-feed">
                                            <span class="pro-slider round"></span>
                                    </label>
                                </td>
                                
                            </tr>
                            
                            <tr>
                                <th><?php echo __('Premium Flip Box', 'premium-addons-for-elementor'); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox" id="premium-flipbox" name="premium-flipbox">
                                            <span class="pro-slider round"></span>
                                    </label>
                                </td>
                                
                                <th><?php echo __('Premium Unfold', 'premium-addons-for-elementor'); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox" id="premium-unfold" name="premium-unfold">
                                            <span class="pro-slider round"></span>
                                    </label>
                                </td>
                            </tr>
                            
                            <tr>
                                
                                <th><?php echo __('Premium Messenger Chat', 'premium-addons-for-elementor'); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox" id="premium-fb-chat" name="premium-fb-chat">
                                            <span class="pro-slider round"></span>
                                    </label>
                                </td>
                                
                                <th><?php echo __('Premium Tabs', 'premium-addons-for-elementor'); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox" id="premium-tabs" name="premium-tabs">
                                            <span class="pro-slider round"></span>
                                    </label>
                                </td>
                            </tr>
                            
                            <tr>
                                <th><?php echo __('Premium Chart', 'premium-addons-for-elementor'); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox" id="premium-charts" name="premium-charts">
                                            <span class="pro-slider round"></span>
                                    </label>
                                </td>
                                
                                <th><?php echo __('Premium Preview Window', 'premium-addons-for-elementor'); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox" id="premium-prev-img" name="premium-prev-img">
                                            <span class="pro-slider round"></span>
                                    </label>
                                </td>
                            </tr>
                            
                            <tr>
                                <th><?php echo __('Premium Image Hotspots', 'premium-addons-for-elementor'); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox" id="premium-image-hotspots" name="premium-image-hotspots">
                                            <span class="pro-slider round"></span>
                                    </label>
                                </td>

                                <th><?php echo __('Premium Facebook Reviews', 'premium-addons-for-elementor'); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox" id="premium-facebook-reviews" name="premium-facebook-reviews">
                                            <span class="pro-slider round"></span>
                                    </label>
                                </td>
                            </tr>
                            
                            <tr>
                                <th><?php echo __('Premium Image Comparison', 'premium-addons-for-elementor'); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox" id="premium-image-comparison" name="premium-image-comparison">
                                            <span class="pro-slider round"></span>
                                    </label>
                                </td>
                                
                                <th><?php echo __('Premium Divider', 'premium-addons-for-elementor'); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox" id="premium-divider" name="premium-divider">
                                            <span class="pro-slider round"></span>
                                    </label>
                                </td>
                                
                            </tr>
                            
                            <tr>
                                <th><?php echo __('Premium Magic Section', 'premium-addons-for-elementor'); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox" id="premium-magic-section" name="premium-magic-section">
                                            <span class="pro-slider round"></span>
                                    </label>
                                </td>
                                
                                <th><?php echo __('Premium Google Reviews', 'premium-addons-for-elementor'); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox" id="premium-google-reviews" name="premium-google-reviews">
                                            <span class="pro-slider round"></span>
                                    </label>
                                </td>
                            </tr>
                            
                            <tr>
                                <th><?php echo __('Premium Behance Feed', 'premium-addons-for-elementor'); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox" id="premium-behance" name="premium-behance">
                                            <span class="pro-slider round"></span>
                                    </label>
                                </td>
                                
                                <th><?php echo __('Premium Tables', 'premium-addons-for-elementor'); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox" id="premium-tables" name="premium-tables">
                                            <span class="pro-slider round"></span>
                                    </label>
                                </td>
                                
                            </tr>
                            
                            <tr>
                                <th><?php echo __('Premium Image Layers', 'premium-addons-for-elementor'); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox" id="premium-img-layers" name="premium-img-layers">
                                            <span class="pro-slider round"></span>
                                    </label>
                                </td>
                                
                                <th><?php echo __('Premium iHover', 'premium-addons-for-elementor'); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox" id="premium-ihover" name="premium-ihover">
                                            <span class="pro-slider round"></span>
                                    </label>
                                </td>
                                
                            </tr>
                            
                            <tr>
                                <th><?php echo __('Premium Content Switcher', 'premium-addons-for-elementor'); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox" id="premium-content-toggle" name="premium-content-toggle">
                                            <span class="pro-slider round"></span>
                                    </label>
                                </td>
                                
                                <th><?php echo __('Premium Facebook Feed', 'premium-addons-for-elementor'); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox" id="premium-facebook-feed" name="premium-facebook-feed">
                                            <span class="pro-slider round"></span>
                                    </label>
                                </td>
                                
                            </tr>
                            
                            <tr>
                                <th><?php echo __('Premium Whatsapp Chat', 'premium-addons-for-elementor'); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox" id="premium-whatsapp-chat" name="premium-whatsapp-chat">
                                            <span class="pro-slider round"></span>
                                    </label>
                                </td>
                                
                            </tr>
                            
                            <tr>
                                <th><?php echo __('Premium Section Parallax', 'premium-addons-for-elementor'); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox" id="premium-section-parallax" name="premium-section-parallax">
                                            <span class="pro-slider round"></span>
                                    </label>
                                </td>
                                <th><?php echo __('Premium Section Particles', 'premium-addons-for-elementor'); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox" id="premium-section-particles" name="premium-section-particles">
                                            <span class="pro-slider round"></span>
                                    </label>
                                </td>
                            </tr>
                            
                            <tr>
                                <th><?php echo __('Premium Section Animated Gradient', 'premium-addons-for-elementor'); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox" id="premium-section-gradient" name="premium-section-gradient">
                                            <span class="pro-slider round"></span>
                                    </label>
                                </td>
                                <th><?php echo __('Premium Section Ken Burns', 'premium-addons-for-elementor'); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox" id="premium-section-kenburns" name="premium-section-kenburns">
                                            <span class="pro-slider round"></span>
                                    </label>
                                </td>
                            </tr>
                            
                            <?php endif; ?> 
                        </tbody>
                    </table>
                    <input type="submit" value="<?php echo __('Save Settings', 'premium-addons-for-elementor'); ?>" class="button pa-btn pa-save-button">
                    
                </div>
                <?php if( ! Helper_Functions::is_show_rate()) : ?>
                    <div>
                        <p><?php echo __('Did you like Premium Addons for Elementor Plugin? Please ', 'premium-addons-for-elementor'); ?><a href="https://wordpress.org/support/plugin/premium-addons-for-elementor/reviews/#new-post" target="_blank"><?php echo __('Click Here to Rate it ★★★★★', 'premium-addons-for-elementor'); ?></a></p>
                    </div>
                <?php endif; ?>
            </div>
            </form>
        </div>
	<?php
}

    public static function get_default_keys() {
        
        $default_keys = array_fill_keys( self::$pa_elements_keys, true );
        
        return $default_keys;
    }
    
    public static function get_enabled_keys() {
        
        $enabled_keys = get_option( 'pa_save_settings', self::get_default_keys() );
        
        return $enabled_keys;
    }
    
    /*
     * Check If Premium Templates is enabled
     * 
     * @since 3.6.0
     * @access public
     * 
     * @return boolean
     */
    public static function check_premium_templates() {
        
        $premium_templates = self::get_enabled_keys()['premium-templates'];

        $is_enabled = isset( $premium_templates ) ? $premium_templates : 1;
        
        return $is_enabled;
    }

    public function pa_save_settings() {
        
        check_ajax_referer( 'pa-elements', 'security' );

        if( isset( $_POST['fields'] ) ) {
            parse_str( $_POST['fields'], $settings );
        } else {
            return;
        }

        $this->pa_settings = array(
            'premium-banner'            => intval( $settings['premium-banner'] ? 1 : 0 ),
            'premium-blog'              => intval( $settings['premium-blog'] ? 1 : 0 ),
            'premium-carousel'          => intval( $settings['premium-carousel'] ? 1 : 0 ),
            'premium-countdown'         => intval( $settings['premium-countdown'] ? 1 : 0 ),
            'premium-counter'           => intval( $settings['premium-counter'] ? 1 : 0 ),
            'premium-dual-header'       => intval( $settings['premium-dual-header'] ? 1 : 0 ),
            'premium-fancytext'         => intval( $settings['premium-fancytext'] ? 1 : 0 ),
            'premium-image-separator'   => intval( $settings['premium-image-separator'] ? 1 : 0 ),
            'premium-maps'              => intval( $settings['premium-maps'] ? 1 : 0 ),
            'premium-modalbox' 			=> intval( $settings['premium-modalbox'] ? 1 : 0 ),
            'premium-person' 			=> intval( $settings['premium-person'] ? 1 : 0 ),
            'premium-progressbar' 		=> intval( $settings['premium-progressbar'] ? 1 : 0 ),
            'premium-testimonials' 		=> intval( $settings['premium-testimonials'] ? 1 : 0 ),
            'premium-title'             => intval( $settings['premium-title'] ? 1 : 0 ),
            'premium-templates'         => intval( $settings['premium-templates'] ? 1 : 0 ),
            'premium-videobox'          => intval( $settings['premium-videobox'] ? 1 : 0 ),
            'premium-pricing-table'     => intval( $settings['premium-pricing-table'] ? 1 : 0),
            'premium-button'            => intval( $settings['premium-button'] ? 1 : 0),
            'premium-contactform'       => intval( $settings['premium-contactform'] ? 1 : 0),
            'premium-image-button'      => intval( $settings['premium-image-button'] ? 1 : 0),
            'premium-grid'              => intval( $settings['premium-grid'] ? 1 : 0),
            'premium-vscroll'           => intval( $settings['premium-vscroll'] ? 1 : 0),
            'premium-image-scroll'      => intval( $settings['premium-image-scroll'] ? 1 : 0),
        );

        update_option( 'pa_save_settings', $this->pa_settings );

        return true;
    }
}