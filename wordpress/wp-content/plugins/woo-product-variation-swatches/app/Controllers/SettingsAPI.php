<?php

namespace Rtwpvs\Controllers;

use Rtwpvs\Helpers\Options;


class SettingsAPI
{

    private $setting_id = 'rtwpvs';
    private $defaults = array();
    private $sections = array();

    public function __construct() {
        $this->sections = Options::get_settings_sections();
        add_action('init', array($this, 'set_defaults'), 8);
        add_filter('plugin_action_links_' . rtwpvs()->basename(), array(
            $this,
            'plugin_action_links'
        ));
        add_filter('woocommerce_settings_tabs_array', array($this, 'add_settings_tab'), 50);
        add_action('woocommerce_settings_tabs_' . $this->setting_id, array($this, 'settings_tab'));
        add_action('woocommerce_update_options_' . $this->setting_id, array($this, 'update_settings'));
        add_action('woocommerce_admin_field_' . $this->setting_id, array($this, 'global_settings'));
        if (apply_filters('rtwpvs_settings_link_on_admin_bar', true)) {
            add_action('wp_before_admin_bar_render', array($this, 'add_admin_bar'), 999);
        }
    }

    public function add_admin_bar() {

        global $wp_admin_bar;
        $url = add_query_arg(array(
            'page'    => 'wc-settings',
            'tab'     => 'rtwpvs',
            'section' => 'general',
        ), admin_url('admin.php'));
        $menu_title = esc_html__('Product Swatches', 'woo-product-variation-swatches');

        $args = array(
            'id'    => $this->setting_id,
            'title' => $menu_title,
            'href'  => $url,
            'meta'  => array(
                'class' => sprintf('%s-admin-toolbar', $this->setting_id)
            )
        );
        $wp_admin_bar->add_menu($args);

        if (class_exists('WooCommerce')) {
            $wp_admin_bar->add_menu(array(
                'id'     => 'rtwpvs-clear-transient',
                'title'  => esc_html__('Clear transient', 'woo-product-variation-swatches'),
                'href'   => esc_url(remove_query_arg(array('variation_id', 'remove_item', 'add-to-cart', 'added-to-cart'), add_query_arg('rtwpvs_clear_transient', ''))),
                'parent' => $this->setting_id,
                'meta'   => array(
                    'class' => sprintf('%s-admin-toolbar-cache', $this->setting_id)
                )
            ));
        }

        do_action('rtwpvs_admin_bar_menu', $wp_admin_bar, $this->setting_id);
    }

    public function set_defaults() {
        foreach ($this->sections as $section) {
            foreach ($section['fields'] as $field) {
                $field['default'] = isset($field['default']) ? $field['default'] : null;
                $this->set_default($field['id'], $field['type'], $field['default']);
            }
        }
    }

    private function set_default($key, $type, $value) {
        $this->defaults[$key] = array('id' => $key, 'type' => $type, 'value' => $value);
    }

    private function get_default($key) {
        return isset($this->defaults[$key]) ? $this->defaults[$key] : null;
    }

    public function get_defaults() {
        return $this->defaults;
    }

    public function plugin_action_links($links) {
        $new_links = array(
            '<a href="' . admin_url('/admin.php?page=wc-settings&tab=' . $this->setting_id) . '">' . esc_html__("Settings", 'woo-product-variation-swatches') . '</a>',
            '<a target="_blank" href="' . esc_url('https://radiustheme.com/demo/wordpress/wooplugins/product/variable-product-test/') . '">' . esc_html__("Demo", 'woo-product-variation-swatches') . '</a>',
            '<a target="_blank" href="' . esc_url('https://www.radiustheme.com/setup-configure-woocommerce-product-variation-swatches/') . '">' . esc_html__("Documentation", 'woo-product-variation-swatches') . '</a>',
            '<a style="color: #39b54a;font-weight: 700;" target="_blank" href="' . esc_url('https://www.radiustheme.com/downloads/woocommerce-variation-swatches/') . '">' . esc_html__("Get Pro", 'woo-product-variation-swatches') . '</a>'
        );

        return array_merge($links, $new_links);
    }

    public function add_settings_tab($settings_tabs) {
        $settings_tabs[$this->setting_id] = esc_html__('Product Swatches', 'woo-product-variation-swatches');

        return $settings_tabs;
    }

    public function settings_tab() {
        woocommerce_admin_fields($this->get_settings());
    }

    public function update_settings() {
        woocommerce_update_options($this->get_settings());
    }

    public function get_settings() {
        $settings = array(
            array(
                'name' => __('WooCommerce Product Variation Swatches Settings', 'woo-product-variation-swatches'),
                'type' => 'title',
                'desc' => '',
                'id'   => 'wc_rtwpvs_settings_section'
            ),
            array(
                'type' => $this->setting_id,
                'id'   => $this->setting_id
            ),
            'section_end' => array(
                'type' => 'sectionend',
                'id'   => 'wc_rtwpvs_settings_section'
            )
        );

        return apply_filters('wc_rtwpvs_settings', $settings);
    }

    public function options_tabs() {
        ?>
        <h2 class="nav-tab-wrapper wp-clearfix">
            <?php foreach ($this->sections as $tabs): ?>
                <a data-target="<?php echo $tabs['id'] ?>"
                   class="rtwpvs-setting-nav-tab nav-tab <?php echo $this->get_options_tab_css_classes($tabs) ?> "
                   href="#<?php echo $tabs['id'] ?>"><?php echo $tabs['title'] ?></a>
            <?php endforeach; ?>
        </h2>
        <?php
    }

    function global_settings() {
        echo '</table>';
        ?>
        <div id="rtwpvs-settings-wrapper">
            <?php $this->options_tabs(); ?>
            <div id="settings-tabs">
                <?php foreach ($this->sections as $section):
                    if (!isset($section['active'])) {
                        $section['active'] = false;
                    }
                    $is_active = ($this->get_last_active_tab() == $section['id']);
                    ?>
                    <div id="<?php echo $section['id'] ?>"
                         class="settings-tab rtwpvs-setting-tab"
                         style="<?php echo !$is_active ? 'display: none' : '' ?>">
                        <div class="section-heading">
                            <h2><?php echo $section['title']; ?></h2>
                            <?php echo $this->get_field_description($section) ?>
                        </div>
                        <table class="form-table"><?php $this->do_settings_fields($section['fields']); ?></table>
                    </div>
                <?php endforeach; ?>
            </div>
            <?php $this->last_tab_input(); ?>
        </div>

        <?php
        echo '<table class="form-table">';
    }

    private function do_settings_fields($fields) {
        foreach ((array)$fields as $field) {
            $custom_attributes = $this->array2html_attr(isset($field['attributes']) ? $field['attributes'] : array());
            $wrapper_id = !empty($field['id']) ? esc_attr($field['id']) . '-wrapper' : '';
            $dependency = !empty($field['require']) ? $this->build_dependency($field['require']) : '';

            printf('<tr id="%s" %s %s>', $wrapper_id, $custom_attributes, $dependency);

            if ($field['type'] == 'title') {
                echo '<td colspan="2">';
                if ($field['title']) {
                    echo "<h3>{$field['title']}</h3>";
                }
                echo $this->get_field_description($field);
                echo '</td>';
            } else if ($field['type'] == 'feature') {
                echo '<td>';
                if (isset($field['title']) && $field['title']) {
                    echo "<h3>{$field['title']}</h3>";
                }
                echo $this->get_field_description($field);
                $this->field_callback($field);
                echo '</td>';
            } else {

                if (!empty($field['label_for'])) {
                    echo '<th scope="row"><label for="' . esc_attr($field['label_for']) . '">' . $field['title'] . '</label></th>';
                } else {
                    echo '<th scope="row">' . $field['title'] . '</th>';
                }

                echo '<td>';
                $this->field_callback($field);
                echo '</td>';
            }

            echo '</tr>';
        }
    }

    private function last_tab_input() {
        printf('<input type="hidden" id="_last_active_tab" name="%s[_last_active_tab]" value="%s">', $this->setting_id, $this->get_last_active_tab());
    }

    public function field_callback($field) {

        switch ($field['type']) {
            case 'radio':
                $this->radio_field_callback($field);
                break;

            case 'checkbox':
                $this->checkbox_field_callback($field);
                break;

            case 'select':
                $this->select_field_callback($field);
                break;

            case 'number':
                $this->number_field_callback($field);
                break;

            case 'color':
                $this->color_field_callback($field);
                break;

            case 'post_select':
                $this->post_select_field_callback($field);
                break;

            case 'feature':
                $this->feature_field_callback($field);
                break;

            case 'title':
                $this->title_field_callback($field);
                break;

            default:
                $this->text_field_callback($field);
                break;
        }

        do_action('rtwpvs_settings_field_callback', $field);
    }

    public function checkbox_field_callback($args) {

        $value = (bool)$this->get_option($args['id']);

        $attrs = isset($args['attrs']) ? $this->make_implode_html_attributes($args['attrs']) : '';

        $html = sprintf('<fieldset><label><input %1$s type="checkbox" id="%2$s-field" name="%4$s[%2$s]" value="%3$s" %5$s/> %6$s</label></fieldset>', $attrs, $args['id'], true, $this->setting_id, checked($value, true, false), esc_attr($args['desc']));

        echo $html;
    }

    public function radio_field_callback($args) {
        $options = apply_filters("rtwpvs_settings_{$args[ 'id' ]}_radio_options", $args['options']);
        $value = esc_attr($this->get_option($args['id']));

        $attrs = isset($args['attrs']) ? $this->make_implode_html_attributes($args['attrs']) : '';


        $html = '<fieldset>';
        $html .= implode('<br />', array_map(function ($key, $option) use ($attrs, $args, $value) {
            return sprintf('<label><input %1$s type="radio" id="%2$s-field" name="%4$s[%2$s]" value="%3$s" %5$s/> %6$s</label>', $attrs, $args['id'], $key, $this->setting_id, checked($value, $key, false), $option);
        }, array_keys($options), $options));
        $html .= $this->get_field_description($args);
        $html .= '</fieldset>';

        echo $html;
    }

    public function select_field_callback($args) {
        $options = apply_filters("rtwpvs_settings_{$args[ 'id' ]}_select_options", $args['options']);
        $value = esc_attr($this->get_option($args['id']));
        $options = array_map(function ($key, $option) use ($value) {
            return "<option value='{$key}'" . selected($key, $value, false) . ">{$option}</option>";
        }, array_keys($options), $options);
        $size = isset($args['size']) && !is_null($args['size']) ? $args['size'] : 'regular';

        $attrs = isset($args['attrs']) ? $this->make_implode_html_attributes($args['attrs']) : '';

        $html = sprintf('<select %5$s class="%1$s-text" id="%2$s-field" name="%4$s[%2$s]">%3$s</select>', $size, $args['id'], implode('', $options), $this->setting_id, $attrs);
        $html .= $this->get_field_description($args);

        echo $html;
    }

    public function get_field_description($args) {
        if (isset($args['desc']) && !empty($args['desc'])) {
            $desc = sprintf('<p class="description">%s</p>', $args['desc']);
        } else {
            $desc = '';
        }

        return $desc;
    }

    public function post_select_field_callback($args) {

        $options = apply_filters("rtwpvs_settings_{$args[ 'id' ]}_post_select_options", $args['options']);

        $value = esc_attr($this->get_option($args['id']));

        $options = array_map(function ($option) use ($value) {
            return "<option value='{$option->ID}'" . selected($option->ID, $value, false) . ">$option->post_title</option>";
        }, $options);

        $size = isset($args['size']) && !is_null($args['size']) ? $args['size'] : 'regular';
        $html = sprintf('<select class="%1$s-text" id="%2$s-field" name="%4$s[%2$s]">%3$s</select>', $size, $args['id'], implode('', $options), $this->setting_id);
        $html .= $this->get_field_description($args);
        echo $html;
    }

    public function text_field_callback($args) {
        $value = esc_attr($this->get_option($args['id']));
        $size = isset($args['size']) && !is_null($args['size']) ? $args['size'] : 'regular';

        $attrs = isset($args['attrs']) ? $this->make_implode_html_attributes($args['attrs']) : '';

        $html = sprintf('<input %5$s type="text" class="%1$s-text" id="%2$s-field" name="%4$s[%2$s]" value="%3$s"/>', $size, $args['id'], $value, $this->setting_id, $attrs);
        $html .= $this->get_field_description($args);

        echo $html;
    }

    public function feature_field_callback($args) {

        $is_html = isset($args['html']);

        if ($is_html) {
            $html = $args['html'];
        } else {
            $image = isset($args['screen_shot']) ? esc_url($args['screen_shot']) : null;
            $link = isset($args['product_link']) ? esc_url($args['product_link']) : null;


            $width = isset($args['width']) ? $args['width'] : '70%';

            $html = sprintf('<a target="_blank" href="%s"><img style="width: %s" src="%s" /></a>', $link, $width, $image);
            $html .= $this->get_field_description($args);
        }


        echo $html;
    }

    public function color_field_callback($args) {
        $value = esc_attr($this->get_option($args['id']));
        $alpha = isset($args['alpha']) && $args['alpha'] === true ? ' data-alpha="true"' : '';
        $html = sprintf('<input type="text" %1$s class="rtwpvs-color-picker" id="%2$s-field" name="%4$s[%2$s]" value="%3$s"  data-default-color="%3$s" />', $alpha, $args['id'], $value, $this->setting_id);
        $html .= $this->get_field_description($args);

        echo $html;
    }

    public function number_field_callback($args) {
        $value = esc_attr($this->get_option($args['id']));
        $size = isset($args['size']) && !is_null($args['size']) ? $args['size'] : 'small';
        $min = isset($args['min']) && !is_null($args['min']) ? 'min="' . $args['min'] . '"' : '';
        $max = isset($args['max']) && !is_null($args['max']) ? 'max="' . $args['max'] . '"' : '';
        $step = isset($args['step']) && !is_null($args['step']) ? 'step="' . $args['step'] . '"' : '';
        $suffix = isset($args['suffix']) && !is_null($args['suffix']) ? ' <span>' . $args['suffix'] . '</span>' : '';
        $attrs = isset($args['attrs']) ? $this->make_implode_html_attributes($args['attrs']) : '';
        $html = sprintf('<input %9$s type="number" class="%1$s-text" id="%2$s-field" name="%4$s[%2$s]" value="%3$s" %5$s %6$s %7$s /> %8$s', $size, $args['id'], $value, $this->setting_id, $min, $max, $step, $suffix, $attrs);
        $html .= $this->get_field_description($args);
        echo $html;
    }


    /**
     * @param      $option
     * @param null $givenDefault
     *
     * @return mixed|void
     */
    public function get_option($option, $givenDefault = null) {
        $default = $this->get_default($option);
        $options = get_option($this->setting_id);
        $is_new = (!is_array($options) && is_bool($options));

        if ($is_new) {
            $value = $default['value'];
        } else {
            $value = isset($options[$option]) ? $options[$option] : '';
            if ($givenDefault && !$value) {
                $value = $givenDefault;
            }
        }

        return apply_filters('rtwpvs_get_option', $value, $default, $option, $options, $is_new);
    }

    private function get_options_tab_css_classes($tabs) {
        $classes = array();
        $classes[] = ($this->get_last_active_tab() == $tabs['id']) ? 'nav-tab-active' : '';

        return implode(' ', array_unique(apply_filters('rtwpvs_get_options_tab_css_classes', $classes)));
    }

    private function get_last_active_tab() {
        $last_tab = trim($this->get_option('_last_active_tab'));
        if (isset($_GET['tab']) && !empty($_GET['tab']) && $this->setting_id == $_GET['tab'] && isset($_GET['section']) && !empty($_GET['section'])) {
            $last_tab = trim($_GET['section']);
        }
        $default_tab = 'general';
        foreach ($this->sections as $tabs) {
            if (isset($tabs['active']) && $tabs['active']) {
                $default_tab = $tabs['id'];
                break;
            }
        }

        return !empty($last_tab) ? $last_tab : $default_tab;
    }

    public function update_option($key, $value) {
        $options = get_option($this->setting_id);
        $options[$key] = $value;
        update_option($this->setting_id, $options);
    }

    public function sanitize_callback($options) {
        foreach ($this->get_defaults() as $opt) {
            if ($opt['type'] === 'checkbox' && !isset($options[$opt['id']])) {
                $options[$opt['id']] = 0;
            }
        }

        return $options;
    }

    public function make_implode_html_attributes($raw_attributes, $except = array('type', 'id', 'name', 'value')) {
        $attributes = array();
        foreach ($raw_attributes as $name => $value) {
            if (in_array($name, $except)) {
                continue;
            }
            $attributes[] = esc_attr($name) . '="' . esc_attr($value) . '"';
        }

        return implode(' ', $attributes);
    }

    public function array2html_attr($attributes, $do_not_add = array()) {

        $attributes = wp_parse_args($attributes, array());
        if (!empty($do_not_add) and is_array($do_not_add)) {
            foreach ($do_not_add as $att_name) {
                unset($attributes[$att_name]);
            }
        }
        $attributes_array = array();
        foreach ($attributes as $key => $value) {
            if (is_bool($attributes[$key]) and $attributes[$key] === true) {
                return $attributes[$key] ? $key : '';
            } elseif (is_bool($attributes[$key]) and $attributes[$key] === false) {
                $attributes_array[] = '';
            } else {
                $attributes_array[] = $key . '="' . $value . '"';
            }
        }

        return implode(' ', $attributes_array);
    }

}

