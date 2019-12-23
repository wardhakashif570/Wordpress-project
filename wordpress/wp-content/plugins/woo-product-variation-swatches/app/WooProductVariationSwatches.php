<?php

use Rtwpvs\Controllers\Hooks;
use Rtwpvs\Controllers\InitHooks;
use Rtwpvs\Controllers\Install;
use Rtwpvs\Controllers\Notifications;
use Rtwpvs\Controllers\ScriptLoader;
use Rtwpvs\Controllers\SettingsAPI;

require_once __DIR__ . './../vendor/autoload.php';

if (!class_exists('WooProductVariationSwatches')):
    final class WooProductVariationSwatches
    {

        protected static $_instance = null;
        private $_settings_api;

        public static function get_instance() {
            if (is_null(self::$_instance)) {
                self::$_instance = new self();
            }

            return self::$_instance;
        }

        public function __construct() {
            $this->define_constants();
            $this->hooks();
            do_action('rtwpvs_loaded', $this);
        }

        /**
         * Declared all plugin constants
         */
        public function define_constants() {
            $this->define('RTWPVS_PLUGIN_PATH', plugin_dir_path(RTWPVS_PLUGIN_FILE));
            $this->define('RTWPVS_PLUGIN_URI', plugin_dir_url(RTWPVS_PLUGIN_FILE));
            $this->define('RTWPVS_PLUGIN_DIRNAME', dirname(plugin_basename(RTWPVS_PLUGIN_FILE))); // plugin-slug
            $this->define('RTWPVS_PLUGIN_BASENAME', plugin_basename(RTWPVS_PLUGIN_FILE)); // plugin-slug/plugin-slug.php
        }

        /**
         *
         */
        public function hooks() {

            $this->load_plugin_textdomain(); // Load text domain
            Notifications::init();
            if ($this->is_valid_php_version()) {
                add_action('init', array($this, 'settings_api'), 5);
                new ScriptLoader();
                Hooks::init();
                InitHooks::init();

            }
        }

        public function settings_api() {
            if (!$this->_settings_api) {
                $this->_settings_api = new SettingsAPI();
            }

            return $this->_settings_api;
        }

        /**
         * @param      $name
         * @param      $value
         * @param bool $case_insensitive
         */
        public function define($name, $value, $case_insensitive = false) {
            if (!defined($name)) {
                define($name, $value, $case_insensitive);
            }
        }

        public function basename() {
            return RTWPVS_PLUGIN_BASENAME;
        }

        /**
         * @return String
         */
        public function dirname() {
            return RTWPVS_PLUGIN_DIRNAME;
        }

        public function version() {
            return RTWPS_VERSION;
        }

        /**
         * @return bool
         */
        public function is_valid_php_version() {
            return version_compare(PHP_VERSION, '5.6.0', '>=');
        }

        /**
         * @return bool
         */
        public function is_wc_active() {
            return class_exists('WooCommerce');
        }

        /**
         * @return bool
         */
        public function is_valid_wc_version() {
            return version_compare(WC_VERSION, '3.2', '>');
        }

        /**
         * Load Localisation files.
         * Note: the first-loaded translation file overrides any following ones if the same translation is present.
         * Locales found in:
         *      - WP_LANG_DIR/woo-product-variation-swatches/woo-product-variation-swatches-LOCALE.mo
         *      - WP_LANG_DIR/plugins/woo-product-variation-swatches-LOCALE.mo
         */
        public function load_plugin_textdomain() {
            $locale = is_admin() && function_exists('get_user_locale') ? get_user_locale() : get_locale();
            $locale = apply_filters('plugin_locale', $locale, 'woo-product-variation-swatches');
            unload_textdomain('woo-product-variation-swatches');
            load_textdomain('woo-product-variation-swatches', WP_LANG_DIR . '/woo-product-variation-swatches/woo-product-variation-swatches-' . $locale . '.mo');
            load_plugin_textdomain('woo-product-variation-swatches', false, trailingslashit($this->dirname()) . 'languages');
        }

        /**
         * @param      $id
         *
         * @param null $default
         * @return string
         */
        public function get_option($id, $default = null) {
            if (!$this->_settings_api) {
                $this->settings_api();
            }

            return $this->_settings_api->get_option($id, $default);
        }

        public function get_assets_uri($file) {
            $file = ltrim($file, '/');

            return trailingslashit(RTWPVS_PLUGIN_URI . 'assets') . $file;
        }

        public function get_images_uri($file) {
            $file = ltrim($file, '/');

            return trailingslashit(RTWPVS_PLUGIN_URI . 'assets/images') . $file;
        }

    }

    /**
     * @return null|WooProductVariationSwatches
     */
    function rtwpvs() {
        return WooProductVariationSwatches::get_instance();
    }

    register_activation_hook(RTWPVS_PLUGIN_FILE, array(Install::class, 'activate'));
    register_deactivation_hook(RTWPVS_PLUGIN_FILE, array(Install::class, 'deactivate'));

    add_action('plugins_loaded', 'rtwpvs');

endif;