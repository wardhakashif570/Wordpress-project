<?php

namespace Rtwpvs\Controllers;


class Install
{

    public static function deactivate($network_deactivating) {
        delete_option('rtwpvs_activate');

        global $wpdb;

        $blog_ids = array(1);
        $original_blog_id = 1;
        $network = false;

        if (is_multisite() && $network_deactivating) {
            $blog_ids = $wpdb->get_col("SELECT blog_id FROM {$wpdb->blogs}");
            $original_blog_id = get_current_blog_id();
            $network = true;
        }

        if (function_exists('wc_get_attribute_taxonomies')) {
            foreach ($blog_ids as $blog_id) {
                if ($network) {
                    switch_to_blog($blog_id);
                }

                // Backup attribute types
                $attributes = wc_get_attribute_taxonomies();
                $default_types = array('text', 'select');
                $rtwpvs_attributes = array();

                if (!empty($attributes)) {
                    foreach ($attributes as $attribute) {
                        if (!in_array($attribute->attribute_type, $default_types)) {
                            $rtwpvs_attributes[$attribute->attribute_id] = $attribute;
                        }
                    }
                }


                // Reset attributes
                if (!empty($rtwpvs_attributes)) {
                    foreach ($rtwpvs_attributes as $id => $attribute) {
                        $wpdb->update(
                            $wpdb->prefix . 'woocommerce_attribute_taxonomies',
                            array('attribute_type' => 'select'),
                            array('attribute_id' => $id),
                            array('%s'),
                            array('%d')
                        );
                    }
                    update_option('rtwpvs_backup_attribute_types', $rtwpvs_attributes);
                }
            }
        }

        if ($network) {
            switch_to_blog($original_blog_id);
        }
    }


    public static function activate($network_wide) {

        if (!is_blog_installed()) {
            return;
        }

        // Check if we are not already running this routine.
        if ('yes' === get_transient('rtwpvs_installing')) {
            return;
        }

        // If we made it till here nothing is running yet, lets set the transient now.
        set_transient('rtwpvs_installing', 'yes', MINUTE_IN_SECONDS * 10);

        self::create_options();
        self::update_rtwpvs_version();
        self::update_attribute_types($network_wide);

        delete_transient('rtwpvs_installing');

        do_action('rtwpvs_flush_rewrite_rules');
        do_action('rtwpvs_installed');

    }

    private static function update_rtwpvs_version() {
        delete_option('rtwpvs_version');
        add_option('rtwpvs_version', RTWPVS_VERSION);
    }

    private static function create_options() {
        update_option('rtwpvs_activate', 'yes');
    }

    private static function update_attribute_types($network_wide) {

        global $wpdb;

        $blog_ids = array(1);
        $original_blog_id = 1;
        $network = false;

        if (is_multisite() && $network_wide) {
            $blog_ids = $wpdb->get_col("SELECT blog_id FROM {$wpdb->blogs}");
            $original_blog_id = get_current_blog_id();
            $network = true;
        }

        foreach ($blog_ids as $blog_id) {
            if ($network) {
                switch_to_blog($blog_id);
            }
            $rtwpvs_attributes = get_option('rtwpvs_backup_attribute_types', array());
            // Reset attributes
            if (!empty($rtwpvs_attributes)) {
                foreach ($rtwpvs_attributes as $id => $attribute) {
                    $wpdb->update(
                        $wpdb->prefix . 'woocommerce_attribute_taxonomies',
                        array('attribute_type' => $attribute->attribute_type),
                        array('attribute_id' => $id),
                        array('%s'),
                        array('%d')
                    );
                }
            }
        }

        if ($network) {
            switch_to_blog($original_blog_id);
        }
        delete_transient('wc_attribute_taxonomies');

    }
}