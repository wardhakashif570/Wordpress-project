<?php

namespace Rtwpvs\Controllers;

use Rtwpvs\Helpers\Functions;
use Rtwpvs\Helpers\Options;

class Hooks {

	static function init() {
		add_filter( 'product_attributes_type_selector', array( __CLASS__, 'product_attributes_types' ) );
		add_action( 'admin_init', array( __CLASS__, 'add_product_taxonomy_meta' ) );
		add_action( 'woocommerce_product_option_terms', array( __CLASS__, 'product_option_terms' ), 20, 2 );
		add_action( 'dokan_product_option_terms', array( __CLASS__, 'product_option_terms' ), 20, 2 );
		add_filter( 'woocommerce_dropdown_variation_attribute_options_html', array(
			__CLASS__,
			'variation_attribute_options_html'
		), 200, 2 );

		add_filter( 'woocommerce_ajax_variation_threshold', array( __CLASS__, 'ajax_variation_threshold' ), 8 );
		add_action( 'admin_init', array( __CLASS__, 'after_plugin_active' ) );
        add_filter('woocommerce_available_variation', array(__CLASS__, 'available_variation'), 100, 3);
	}

    /**
     * @param $variation
     * @param $product      \WC_Product
     * @param $variationObj \WC_Product_Variable
     *
     * @return bool
     */
    static function available_variation($variation, $product, $variationObj) {

        if (rtwpvs()->get_option('disable_out_of_stock')) {
            return $variationObj->is_in_stock() ? $variation : false;
        }

        return $variation;
    }


	static function ajax_variation_threshold( $threshold ) {
		return absint( rtwpvs()->get_option( 'threshold', $threshold ) );
	}

	/**
	 * Unused
	 */
	static function get_available_product_variations() {
		if ( is_ajax() && isset( $_GET['product_id'] ) ) {
			$product_id           = absint( $_GET['product_id'] );
			$product              = wc_get_product( $product_id );
			$available_variations = array_values( $product->get_available_variations() );

			wp_send_json_success( wp_json_encode( $available_variations ) );
		} else {
			wp_send_json_error();
		}
	}

	static function product_attributes_types( $selector ) {
		$types = Options::get_available_attributes_types();
		if ( ! empty( $types ) ) {
			foreach ( $types as $key => $type ) {
				$selector[ $key ] = $type;
			}
		}

		return $selector;
	}

	static function add_product_taxonomy_meta() {

		$fields         = Options::get_taxonomy_meta_fields();
		$meta_added_for = apply_filters( 'rtwpvs_product_taxonomy_meta_for', array_keys( $fields ) );

		if ( function_exists( 'wc_get_attribute_taxonomies' ) ):

			$attribute_taxonomies = wc_get_attribute_taxonomies();
			if ( $attribute_taxonomies ) :
				foreach ( $attribute_taxonomies as $tax ) :
					$product_attr      = wc_attribute_taxonomy_name( $tax->attribute_name );
					$product_attr_type = $tax->attribute_type;
					if ( in_array( $product_attr_type, $meta_added_for ) ) :
						new TermMeta( $product_attr, $fields[ $product_attr_type ] );
						do_action( 'rtwpvs_wc_attribute_taxonomy_meta_added', $product_attr, $product_attr_type );
					endif;
				endforeach;
			endif;
		endif;

	}

	static function product_option_terms( $attribute_taxonomy, $i ) {
		global $thepostid;
		if ( in_array( $attribute_taxonomy->attribute_type, array_keys( Options::get_available_attributes_types() ) ) ) {

			$taxonomy = wc_attribute_taxonomy_name( $attribute_taxonomy->attribute_name );

			$product_id = $thepostid;

			if ( is_null( $thepostid ) && isset( $_POST['post_id'] ) ) {
				$product_id = absint( $_POST['post_id'] );
			}

			$args = array(
				'orderby'    => 'name',
				'hide_empty' => 0,
			);
			?>
            <select multiple="multiple"
                    data-placeholder="<?php esc_attr_e( 'Select terms', 'woo-product-variation-swatches' ); ?>"
                    class="multiselect attribute_values wc-enhanced-select"
                    name="attribute_values[<?php echo $i; ?>][]">
				<?php
				$all_terms = get_terms( $taxonomy, apply_filters( 'woocommerce_product_attribute_terms', $args ) );
				if ( $all_terms ) :
					foreach ( $all_terms as $term ) :
						echo '<option value="' . esc_attr( $term->term_id ) . '" ' . selected( has_term( absint( $term->term_id ), $taxonomy, $product_id ), true, false ) . '>' . esc_attr( apply_filters( 'woocommerce_product_attribute_term_name', $term->name, $term ) ) . '</option>';
					endforeach;
				endif;
				?>
            </select>
			<?php do_action( 'before_rtwpvs_product_option_terms_button', $attribute_taxonomy, $taxonomy ); ?>
            <button class="button plus select_all_attributes"><?php esc_html_e( 'Select all', 'woo-product-variation-swatches' ); ?></button>
            <button class="button minus select_no_attributes"><?php esc_html_e( 'Select none', 'woo-product-variation-swatches' ); ?></button>

			<?php
			$fields = Options::get_available_attributes_types( $attribute_taxonomy->attribute_type );

			if ( ! empty( $fields ) ): ?>
                <button class="button fr plus rtwpvs_add_new_attribute"
                        data-dialog_title="<?php printf( esc_html__( 'Add new %s', 'woo-product-variation-swatches' ), esc_attr( $attribute_taxonomy->attribute_label ) ) ?>"><?php esc_html_e( 'Add new', 'woo-product-variation-swatches' ); ?></button>
			<?php else: ?>
                <button class="button fr plus add_new_attribute"><?php esc_html_e( 'Add new', 'woo-product-variation-swatches' ); ?></button>
			<?php endif; ?>
			<?php
			do_action( 'after_rtwpvs_product_option_terms_button', $attribute_taxonomy, $taxonomy, $product_id );
		}
	}

	static function variation_attribute_options_html( $html, $args ) {
		$available_types = Options::get_available_attributes_types();
		foreach ( $available_types as $type => $typeName ) {
			if ( Functions::wc_product_has_attribute_type( $type, $args['attribute'] ) ) {
				$html = Functions::generate_variation_attribute_option_html( apply_filters( 'rtwpvs_variation_attribute_options_args', wp_parse_args( $args, array(
					'options'    => $args['options'],
					'attribute'  => $args['attribute'],
					'product'    => $args['product'],
					'selected'   => $args['selected'],
					'type'       => $type,
					'is_archive' => ( isset( $args['is_archive'] ) && $args['is_archive'] )
				) ) ) );
			}
		}

		return apply_filters( 'rtwpvs_variation_attribute_options_html', $html, $args );
	}

	static function after_plugin_active() {
		if ( get_option( 'rtwpvs_activate' ) === 'yes' ) {
			delete_option( 'rtwpvs_activate' );
			wp_safe_redirect( add_query_arg( array(
				'page'    => 'wc-settings',
				'tab'     => 'rtwpvs',
				'section' => 'general',
			), admin_url( 'admin.php' ) ) );
		}
	}
}