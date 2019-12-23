<?php

namespace Rtwpvs\Helpers;


class Functions {

	static function write_log( $log ) {
		if ( true === WP_DEBUG ) {
			if ( is_array( $log ) || is_object( $log ) ) {
				error_log( print_r( $log, true ) );
			} else {
				error_log( $log );
			}
		}
	}

	static function get_all_image_sizes() {
		return apply_filters( 'rtwpvs_get_all_image_sizes', array_reduce( get_intermediate_image_sizes(), function ( $carry, $item ) {
			$carry[ $item ] = ucwords( str_ireplace( array( '-', '_' ), ' ', $item ) );

			return $carry;
		}, array() ) );
	}

	static function get_wc_attribute_taxonomy( $attribute_name ) {

		$transient = sprintf( 'rtwpvs_get_wc_attribute_taxonomy_%s', $attribute_name );

		if ( ( defined( 'WP_DEBUG' ) && WP_DEBUG ) || isset( $_GET['rtwpvs_clear_transient'] ) ) {
			delete_transient( $transient );
		}

		if ( false === ( $attribute_taxonomy = get_transient( $transient ) ) ) {
			global $wpdb;

			$attribute_name     = str_replace( 'pa_', '', wc_sanitize_taxonomy_name( $attribute_name ) );
			$attribute_taxonomy = $wpdb->get_row( "SELECT * FROM " . $wpdb->prefix . "woocommerce_attribute_taxonomies WHERE attribute_name='{$attribute_name}'" );
			set_transient( $transient, $attribute_taxonomy );
		}

		return apply_filters( 'rtwpvs_get_wc_attribute_taxonomy', $attribute_taxonomy, $attribute_name );
	}

	static function wc_product_has_attribute_type( $type, $attribute_name ) {
		$attribute = self::get_wc_attribute_taxonomy( $attribute_name );

		return apply_filters( 'rtwpvs_wc_product_has_attribute_type', ( isset( $attribute->attribute_type ) && ( $attribute->attribute_type == $type ) ), $type, $attribute_name, $attribute );
	}

	static function generate_variation_attribute_option_html( $args = array() ) {
		$args = wp_parse_args( $args, array(
			'options'          => false,
			'attribute'        => false,
			'product'          => false,
			'selected'         => false,
			'name'             => '',
			'id'               => '',
			'class'            => '',
			'type'             => '',
			'show_option_none' => esc_html__( 'Choose an option', 'woo-product-variation-swatches' )
		) );

		$type                  = $args['type'];
		$options               = $args['options'];
		$product               = $args['product'];
		$attribute             = $args['attribute'];
		$name                  = $args['name'] ? $args['name'] : wc_variation_attribute_name( $attribute );
		$id                    = $args['id'] ? $args['id'] : sanitize_title( $attribute );
		$class                 = $args['class'];
		$show_option_none      = $args['show_option_none'] ? true : false;
		$show_option_none_text = $args['show_option_none'] ? $args['show_option_none'] : esc_html__( 'Choose an option', 'woo-product-variation-swatches' );

		if ( empty( $options ) && ! empty( $product ) && ! empty( $attribute ) ) {
			$attributes = $product->get_variation_attributes();
			$options    = $attributes[ $attribute ];
		}
		$html = '';
		if ( $product && taxonomy_exists( $attribute ) ) {
			$html .= '<select id="' . esc_attr( $id ) . '" class="' . esc_attr( $class ) . ' hide rtwpvs-wc-select rtwpvs-wc-type-' . esc_attr( $type ) . '" style="display:none" name="' . esc_attr( $name ) . '" data-attribute_name="' . esc_attr( wc_variation_attribute_name( $attribute ) ) . '" data-show_option_none="' . ( $show_option_none ? 'yes' : 'no' ) . '">';
		} else {
			$html .= '<select id="' . esc_attr( $id ) . '" class="' . esc_attr( $class ) . '" name="' . esc_attr( $name ) . '" data-attribute_name="' . esc_attr( wc_variation_attribute_name( $attribute ) ) . '" data-show_option_none="' . ( $show_option_none ? 'yes' : 'no' ) . '">';
		}

		if ( $args['show_option_none'] ) {
			$html .= '<option value="">' . esc_html( $show_option_none_text ) . '</option>';
		}
		if ( ! empty( $options ) ) {
			if ( $product && taxonomy_exists( $attribute ) ) {
				$terms = wc_get_product_terms( $product->get_id(), $attribute, array( 'fields' => 'all' ) );

				foreach ( $terms as $term ) {
					if ( in_array( $term->slug, $options ) ) {
						$html .= '<option value="' . esc_attr( $term->slug ) . '" ' . selected( sanitize_title( $args['selected'] ), $term->slug, false ) . '>' . apply_filters( 'woocommerce_variation_option_name', $term->name ) . '</option>';
					}
				}
			} else {
				foreach ( $options as $option ) {
					// This handles < 2.4.0 bw compatibility where text attributes were not sanitized.
					$selected = sanitize_title( $args['selected'] ) === $args['selected'] ? selected( $args['selected'], sanitize_title( $option ), false ) : selected( $args['selected'], $option, false );
					$html     .= '<option value="' . esc_attr( $option ) . '" ' . $selected . '>' . esc_html( apply_filters( 'woocommerce_variation_option_name', $option ) ) . '</option>';
				}
			}
		}

		$html .= '</select>';

		return $html . self::get_variable_items_contents( $type, $options, $args );
	}

	static private function get_variable_items_contents( $type, $options, $args, $saved_attribute = array() ) {

		$product   = $args['product'];
		$attribute = $args['attribute'];
		$data      = '';

		if ( ! empty( $options ) ) {
			if ( $product && taxonomy_exists( $attribute ) ) {
				$terms = wc_get_product_terms( $product->get_id(), $attribute, array( 'fields' => 'all' ) );
				$name  = uniqid( wc_variation_attribute_name( $attribute ) );
				foreach ( $terms as $term ) {
					if ( in_array( $term->slug, $options ) ) {
						$selected_class = ( sanitize_title( $args['selected'] ) == $term->slug ) ? 'selected' : '';
						$tooltip        = trim( apply_filters( 'rtwpvs_variable_item_tooltip', $term->name, $term, $args ) );

						$tooltip_html_attr = ! empty( $tooltip ) ? sprintf( 'data-rtwpvs-tooltip="%s"', esc_attr( $tooltip ) ) : '';

						if ( wp_is_mobile() ) {
							$tooltip_html_attr .= ! empty( $tooltip ) ? ' tabindex="2"' : '';
						}

						$data .= sprintf( '<div %1$s class="rtwpvs-term rtwpvs-%2$s-term %2$s-variable-term-%3$s %4$s" title="%5$s" data-term="%3$s">', $tooltip_html_attr, esc_attr( $type ), esc_attr( $term->slug ), esc_attr( $selected_class ), esc_html( $term->name ) );

						switch ( $type ):
							case 'color':
								$color = sanitize_hex_color( get_term_meta( $term->term_id, 'product_attribute_color', true ) );
								$data  .= sprintf( '<span class="rtwpvs-term-span rtwpvs-term-span-%s" style="background-color:%s;"></span>', esc_attr( $type ), esc_attr( $color ) );
								break;

							case 'image':
								$attachment_id = absint( get_term_meta( $term->term_id, 'product_attribute_image', true ) );
								$image_size    = rtwpvs()->get_option( 'attribute_image_size' );
								$image_url     = wp_get_attachment_image_url( $attachment_id, apply_filters( 'rtwpvs_product_attribute_image_size', $image_size ) );
								$data          .= sprintf( '<img alt="%s" src="%s" />', esc_attr( $term->name ), esc_url( $image_url ) );
								break;

							case 'button':
								$data .= sprintf( '<span class="rtwpvs-term-span rtwpvs-term-span-%s">%s</span>', esc_attr( $type ), esc_html( $term->name ) );
								break;

							case 'radio':
								$id   = uniqid( $term->slug );
								$data .= sprintf( '<input name="%1$s" id="%2$s" class="rtwpvs-radio-button-term" %3$s  type="radio" value="%4$s" data-term="%4$s" /><label for="%2$s">%5$s</label>', $name, $id, checked( sanitize_title( $args['selected'] ), $term->slug, false ), esc_attr( $term->slug ), esc_html( $term->name ) );
								break;

							default:
								$data .= apply_filters( 'rtwpvs_variable_default_item_content', '', $term, $args, $saved_attribute );
								break;
						endswitch;
						$data .= '</div>';
					}
				}
			}
		}

		$contents = apply_filters( 'rtwpvs_variable_term', $data, $type, $options, $args, $saved_attribute );

		$attribute = $args['attribute'];

		$css_classes = apply_filters( 'rtwpvs_variable_terms_wrapper_class', array( "{$type}-variable-wrapper" ), $type, $args, $saved_attribute );

		$data = sprintf( '<div class="rtwpvs-terms-wrapper %s" data-attribute_name="%s">%s</div>', trim( implode( ' ', array_unique( $css_classes ) ) ), esc_attr( wc_variation_attribute_name( $attribute ) ), $contents );

		return apply_filters( 'rtwpvs_variable_items_wrapper', $data, $contents, $type, $args, $saved_attribute );
	}

	static function get_product_list_html( $products = array() ) {
		$html = null;
		if ( ! empty( $products ) ) {
			$htmlProducts = null;
			foreach ( $products as $product ) {
				$image_url       = isset( $product['image_url'] ) ? $product['image_url'] : null;
				$image_thumb_url = isset( $product['image_thumb_url'] ) ? $product['image_thumb_url'] : null;
				$image_thumb_url = $image_thumb_url ? $image_thumb_url : $image_url;
				$price           = isset( $product['price'] ) ? $product['price'] : null;
				$title           = isset( $product['title'] ) ? $product['title'] : null;
				$url             = isset( $product['url'] ) ? $product['url'] : null;
				$buy_url         = isset( $product['buy_url'] ) ? $product['buy_url'] : null;
				$buy_url         = $buy_url ? $buy_url : $url;
				$doc_url         = isset( $product['doc_url'] ) ? $product['doc_url'] : null;
				$demo_url        = isset( $product['demo_url'] ) ? $product['demo_url'] : null;
				$feature_list = null;
				$info_html       = sprintf( '<div class="rt-product-info">%s%s%s</div>',
					$title ? sprintf( "<h3 class='rt-product-title'><a href='%s' target='_blank'>%s%s</a></h3>", esc_url( $url ), $title, $price ? " ($" . $price . ")" : null ) : null,
					$feature_list,
					$buy_url || $demo_url || $doc_url ?
						sprintf(
							'<div class="rt-product-action">%s%s%s</div>',
							$buy_url ? sprintf( '<a class="rt-buy button button-primary" href="%s" target="_blank">%s</a>', esc_url( $buy_url ), esc_html__( 'Buy', 'woo-product-variation-swatches' ) ) : null,
							$demo_url ? sprintf( '<a class="rt-demo button" href="%s" target="_blank">%s</a>', esc_url( $demo_url ), esc_html__( 'Demo', 'woo-product-variation-swatches' ) ) : null,
							$doc_url ? sprintf( '<a class="rt-doc button" href="%s" target="_blank">%s</a>', esc_url( $doc_url ), esc_html__( 'Documentation', 'woo-product-variation-swatches' ) ) : null
						)
						: null
				);

				$htmlProducts .= sprintf(
					'<div class="rt-product">%s%s</div>',
					$image_thumb_url ? sprintf(
						'<div class="rt-media"><img src="%s" alt="%s" /></div>',
						esc_url( $image_thumb_url ),
						esc_html( $title )
					) : null,
					$info_html
				);

			}

			$html = sprintf( '<div class="rt-product-list">%s</div>', $htmlProducts );

		}

		return $html;
	}

}