<?php

namespace Rtwpvs\Controllers;


class ScriptLoader
{

    private $suffix;
    private $version;

    public function __construct() {
        $this->suffix = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';
        $this->version = defined('WP_DEBUG') ? time() : rtwpvs()->version();
        add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'), 15);
    }

    public function enqueue_scripts() {

        if (apply_filters('rtwpvs_disable_register_enqueue_scripts', false)) {
            return;
        }
        wp_register_script('rtwpvs', rtwpvs()->get_assets_uri("/js/rtwpvs{$this->suffix}.js"), array('jquery', 'wp-util'), $this->version, true);
        wp_register_style('rtwpvs', rtwpvs()->get_assets_uri("/css/rtwpvs{$this->suffix}.css"), '', $this->version);
        wp_register_style('rtwpvs-tooltip', rtwpvs()->get_assets_uri("/css/rtwpvs-tooltip{$this->suffix}.css"), '', $this->version);

        wp_localize_script('rtwpvs', 'rtwpvs_params', apply_filters('rtwpvs_js_object', array(
            'is_product_page'      => is_product(),
            'reselect_clear'       => rtwpvs()->get_option('clear_on_reselect')
        )));

        if (apply_filters('rtwpvs_disable_enqueue_scripts', false)) {
            return;
        }

        wp_enqueue_script('rtwpvs');
        wp_enqueue_style('rtwpvs');
        if (rtwpvs()->get_option('tooltip')) {
            wp_enqueue_style('rtwpvs-tooltip');
        }
        $this->add_inline_style();

    }

    public function admin_enqueue_scripts() {
        wp_register_script('rtwpvs-admin', rtwpvs()->get_assets_uri("/js/admin{$this->suffix}.js"), '', $this->version, true);
        wp_register_script('wp-color-picker-alpha', rtwpvs()->get_assets_uri("/js/wp-color-picker-alpha{$this->suffix}.js"), array('wp-color-picker'), '2.1.3', true);
        wp_register_style('rtwpvs-admin', rtwpvs()->get_assets_uri("/css/admin{$this->suffix}.css"), array(), $this->version);

        wp_enqueue_style('wp-color-picker');
        wp_enqueue_script('wp-color-picker-alpha');
        wp_enqueue_script('rtwpvs-admin');
        wp_enqueue_style('rtwpvs-admin');

        wp_localize_script('rtwpvs-admin', 'rtwpvs', array(
            'media_title'  => esc_html__('Choose an Image', 'woo-product-variation-swatches'),
            'button_title' => esc_html__('Use Image', 'woo-product-variation-swatches'),
            'add_media'    => esc_html__('Add Media', 'woo-product-variation-swatches'),
            'ajaxurl'      => esc_url(admin_url('admin-ajax.php', 'relative')),
            'nonce'        => wp_create_nonce('rtwpvs_nonce'),
        ));
        if ((isset($_GET['page']) && ($_GET['page'] == "wc-settings")) && (isset($_GET['tab']) && ($_GET['tab'] == "rtwpvs_settings"))) {
            wp_enqueue_script('rtwpvs-admin');
            wp_enqueue_style('rtwpvs-admin');
        }
    }

    public function add_inline_style() {
        if (apply_filters('rtwpvs_disable_inline_style', false)) {
            return;
        }
        $width = rtwpvs()->get_option('width');
        $height = rtwpvs()->get_option('height');
        $font_size = rtwpvs()->get_option('single-font-size');
        $tooltip_background = rtwpvs()->get_option('tooltip_background');
        ob_start();
        ?>
        <style type="text/css">
            .rtwpvs-term:not(.rtwpvs-radio-term) {
                width: <?php echo $width ?>px;
                height: <?php echo $height ?>px;
            }

            .rtwpvs-squared .rtwpvs-button-term {
                min-width: <?php echo $width ?>px;
            }

            .rtwpvs-button-term span {
                font-size: <?php echo $font_size ?>px;
            }

            <?php if($tooltip_background): ?>
            .rtwpvs.rtwpvs-tooltip .rtwpvs-terms-wrapper .rtwpvs-term[data-rtwpvs-tooltip]:not(.disabled)::before {
                background-color: <?php echo $tooltip_background; ?>;
            }

            .rtwpvs.rtwpvs-tooltip .rtwpvs-terms-wrapper .rtwpvs-term[data-rtwpvs-tooltip]:not(.disabled)::after {
                border-top: 5px solid<?php echo $tooltip_background; ?>;
            }

            <?php endif; ?>
            <?php if($tooltip_text_color = rtwpvs()->get_option( 'tooltip_text_color' )): ?>
            .rtwpvs.rtwpvs-tooltip .rtwpvs-terms-wrapper .rtwpvs-term[data-rtwpvs-tooltip]:not(.disabled)::before {
                color: <?php echo $tooltip_text_color; ?>;
            }

            <?php endif; ?>
            <?php if($cross_color = rtwpvs()->get_option( 'attribute_behaviour_cross_color' )): ?>
            .rtwpvs.rtwpvs-attribute-behavior-blur .rtwpvs-term:not(.rtwpvs-radio-term).disabled::before,
            .rtwpvs.rtwpvs-attribute-behavior-blur .rtwpvs-term:not(.rtwpvs-radio-term).disabled::after,
            .rtwpvs.rtwpvs-attribute-behavior-blur .rtwpvs-term:not(.rtwpvs-radio-term).disabled:hover::before,
            .rtwpvs.rtwpvs-attribute-behavior-blur .rtwpvs-term:not(.rtwpvs-radio-term).disabled:hover::after {
                background: <?php echo $cross_color; ?> !important;
            }

            <?php endif; ?>
            <?php if($blur_opacity = rtwpvs()->get_option( 'attribute_behaviour_blur_opacity' )): ?>
            .rtwpvs.rtwpvs-attribute-behavior-blur .rtwpvs-term:not(.rtwpvs-radio-term).disabled img,
            .rtwpvs.rtwpvs-attribute-behavior-blur .rtwpvs-term:not(.rtwpvs-radio-term).disabled span,
            .rtwpvs.rtwpvs-attribute-behavior-blur .rtwpvs-term:not(.rtwpvs-radio-term).disabled:hover img,
            .rtwpvs.rtwpvs-attribute-behavior-blur .rtwpvs-term:not(.rtwpvs-radio-term).disabled:hover span {
                opacity: <?php echo $blur_opacity; ?>;
            }

            <?php endif; ?>
        </style>
        <?php
        $css = ob_get_clean();
        $css = str_ireplace(array('<style type="text/css">', '</style>'), '', $css);

        $css = apply_filters('rtwpvs_inline_style', $css);
        wp_add_inline_style('rtwpvs', $css);
    }

}