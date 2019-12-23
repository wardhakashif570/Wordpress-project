<?php

namespace Rtwpvs\Helpers;

class Options {

	static function get_available_attributes_types( $type = false ) {
		$types = array(
			'color'  => esc_html__( 'Color', 'woo-product-variation-swatches' ),
			'image'  => esc_html__( 'Image', 'woo-product-variation-swatches' ),
			'button' => esc_html__( 'Button', 'woo-product-variation-swatches' ),
			'radio'  => esc_html__( 'Radio', 'woo-product-variation-swatches' )
		);

		$types = apply_filters( 'rtwpvs_available_attributes_types', $types );

		if ( $type ) {
			return isset( $types[ $type ] ) ? $types[ $type ] : array();
		}

		return $types;
	}

	public static function get_taxonomy_meta_fields( $field_id = false ) {

		$fields = array();

		$fields['color'] = array(
			array(
				'label' => esc_html__( 'Color', 'woo-product-variation-swatches' ), // <label>
				'desc'  => esc_html__( 'Choose a color', 'woo-product-variation-swatches' ), // description
				'id'    => 'product_attribute_color',
				'type'  => 'color'
			)
		);

		$fields['image'] = array(
			array(
				'label' => esc_html__( 'Image', 'woo-product-variation-swatches' ), // <label>
				'desc'  => esc_html__( 'Choose an Image', 'woo-product-variation-swatches' ), // description
				'id'    => 'product_attribute_image',
				'type'  => 'image'
			)
		);

		$fields = apply_filters( 'rtwpvs_get_product_taxonomy_meta_fields', $fields );

		if ( $field_id ) {
			return isset( $fields[ $field_id ] ) ? $fields[ $field_id ] : array();
		}

		return $fields;

	}

	public static function get_settings_sections() {
		$fields = array(
			'general'         => array(
				'id'     => 'general',
				'title'  => esc_html__( 'General', 'woo-product-variation-swatches' ),
				'desc'   => esc_html__( 'Simple change some visual styles', 'woo-product-variation-swatches' ),
				'active' => apply_filters( 'rtwpvs_simple_setting_active', true ),
				'fields' => apply_filters( 'rtwpvs_simple_setting_fields', array(
					array(
						'id'      => 'tooltip',
						'type'    => 'checkbox',
						'title'   => esc_html__( 'Enable Tooltip', 'woo-product-variation-swatches' ),
						'desc'    => esc_html__( 'Enable / Disable plugin default tooltip on each product attribute.', 'woo-product-variation-swatches' ),
						'default' => true
					),
					array(
						'id'      => 'style',
						'type'    => 'radio',
						'title'   => esc_html__( 'Shape style', 'woo-product-variation-swatches' ),
						'desc'    => esc_html__( 'Attribute Shape Style', 'woo-product-variation-swatches' ),
						'options' => array(
							'rounded' => esc_html__( 'Rounded Shape', 'woo-product-variation-swatches' ),
							'squared' => esc_html__( 'Squared Shape', 'woo-product-variation-swatches' )
						),
						'default' => 'rounded'
					),
					array(
						'id'      => 'attribute_image_size',
						'type'    => 'select',
						'title'   => esc_html__( 'Attribute image size', 'woo-product-variation-swatches' ),
						'desc'    => has_filter( 'rtwpvs_product_attribute_image_size' ) ? __( '<span style="color: red">Attribute image size changed by <code>rtwpvs_product_attribute_image_size</code> hook. So this option will not apply any effect.</span>', 'woo-product-variation-swatches' ) : __( sprintf( 'Choose attribute image size. <a target="_blank" href="%s">Media Settings</a>', esc_url( admin_url( 'options-media.php' ) ) ), 'woo-product-variation-swatches' ),
						'options' => Functions::get_all_image_sizes(),
						'default' => 'thumbnail'
					),
					array(
						'id'      => 'width',
						'type'    => 'number',
						'title'   => esc_html__( 'Width', 'woo-product-variation-swatches' ),
						'desc'    => esc_html__( 'Variation item width', 'woo-product-variation-swatches' ),
						'default' => 30,
						'min'     => 10,
						'max'     => 200,
						'suffix'  => 'px'
					),
					array(
						'id'      => 'height',
						'type'    => 'number',
						'title'   => esc_html__( 'Height', 'woo-product-variation-swatches' ),
						'desc'    => esc_html__( 'Variation item height', 'woo-product-variation-swatches' ),
						'default' => 30,
						'min'     => 10,
						'max'     => 200,
						'suffix'  => 'px'
					),
					array(
						'id'      => 'single-font-size',
						'type'    => 'number',
						'title'   => esc_html__( 'Font Size', 'woo-product-variation-swatches' ),
						'desc'    => esc_html__( 'Single product variation item font size', 'woo-product-variation-swatches' ),
						'default' => 16,
						'min'     => 8,
						'max'     => 24,
						'suffix'  => 'px'
					),
					array(
						'id'   => 'tooltip_options_title',
						'type' => 'feature',
						'html' => sprintf( '<img src="%s" alt="%s">',
							rtwpvs()->get_images_uri( 'tooltip-pro-feature.jpg' ),
							esc_html__( 'Tooltip Pro feature', 'woo-product-variation-swatches' )
						)
					)
				) )
			),
			'advanced'        => array(
				'id'     => 'advanced',
				'title'  => esc_html__( 'Advanced', 'woo-product-variation-swatches' ),
				'desc'   => esc_html__( 'Advanced change some visual styles', 'woo-product-variation-swatches' ),
				'active' => apply_filters( 'rtwpvs_simple_setting_active', false ),
				'fields' => apply_filters( 'rtwpvs_advanced_setting_fields', array(
					array(
						'id'      => 'clear_on_reselect',
						'type'    => 'checkbox',
						'title'   => esc_html__( 'Clear on Reselect', 'woo-product-variation-swatches' ),
						'desc'    => esc_html__( 'Clear selected attribute on select again', 'woo-product-variation-swatches' ),
						'default' => false
					),
					array(
						'id'      => 'threshold',
						'type'    => 'number',
						'title'   => esc_html__( 'Ajax variation threshold', 'woo-product-variation-swatches' ),
						'desc'    => __( 'Default value is <code>30</code>, If you want all product variation set it to <code>1</code> then all variation will be load via ajax.<br><span style="color: red">Note: It\'s recommended to keep this number between 30 - 40.</span>', 'woo-product-variation-swatches' ),
						'default' => 30,
						'min'     => 1,
						'max'     => 400,
					),
					array(
						'id'      => 'disable_out_of_stock',
						'type'    => 'checkbox',
						'title'   => esc_html__( 'Out of stock for variation', 'woo-product-variation-swatches' ),
						'desc'    => esc_html__( 'Disable out of stock for variation product attribute item', 'woo-product-variation-swatches' ),
						'default' => true
					),
					array(
						'id'      => 'attribute_behavior',
						'type'    => 'radio',
						'title'   => esc_html__( 'Attribute behavior', 'woo-product-variation-swatches' ),
						'desc'    => __( 'Disabled attribute will be hide / blur.', 'woo-product-variation-swatches' ),
						'options' => array(
							'blur'          => esc_html__( 'Blur with cross', 'woo-product-variation-swatches' ),
							'blur-no-cross' => esc_html__( 'Blur without cross', 'woo-product-variation-swatches' ),
							'hide'          => esc_html__( 'Hide', 'woo-product-variation-swatches' ),
						),
						'default' => 'blur'
					)
				) )
			),
			'style'           => array(
				'id'     => 'style',
				'title'  => esc_html__( 'Style', 'woo-product-variation-swatches' ),
				'desc'   => esc_html__( 'Advanced change some visual styles', 'woo-product-variation-swatches' ),
				'active' => apply_filters( 'rtwpvs_style_setting_active', false ),
				'fields' => apply_filters( 'rtwpvs_style_setting_fields', array(
					array(
						'id'      => 'tooltip_background',
						'type'    => 'color',
						'title'   => esc_html__( 'Tooltip background', 'woo-product-variation-swatches' ),
						'desc'    => esc_html__( 'Tooltip background color', 'woo-product-variation-swatches' ),
						'default' => '',
						'alpha'   => true
					),
					array(
						'id'      => 'tooltip_text_color',
						'type'    => 'color',
						'title'   => esc_html__( 'Tooltip text color', 'woo-product-variation-swatches' ),
						'desc'    => esc_html__( 'Tooltip text color', 'woo-product-variation-swatches' ),
						'default' => '',
					),
					array(
						'id'    => 'title_attribute_behaviour',
						'type'  => 'title',
						'title' => esc_html__( 'Attribute behavior', 'woo-product-variation-swatches' ),
						'desc'  => esc_html__( 'This will work for (blur and blur-no-cross)', 'woo-product-variation-swatches' ),
					),
					array(
						'id'      => 'attribute_behaviour_cross_color',
						'type'    => 'color',
						'title'   => esc_html__( 'Cross background color', 'woo-product-variation-swatches' ),
						'desc'    => esc_html__( 'Cross background color for disabled item', 'woo-product-variation-swatches' ),
						'default' => '#ff0000'
					),
					array(
						'id'      => 'attribute_behaviour_blur_opacity',
						'type'    => 'number',
						'title'   => esc_html__( 'Blur Opacity', 'woo-product-variation-swatches' ),
						'desc'    => esc_html__( 'Blur Opacity for disabled item range[.1 to 1]', 'woo-product-variation-swatches' ),
						'default' => .3,
						'step'    => "0.1",
						'min'     => .1,
						'max'     => 1,
					)
				) )
			),
			'archive'         => array(
				'id'     => 'archive',
				'title'  => esc_html__( 'Archive / Shop', 'woo-product-variation-swatches' ),
				'desc'   => esc_html__( 'Advanced settings on shop / archive pages', 'woo-product-variation-swatches' ),
				'fields' => apply_filters( 'rtwpvs_archive_setting_fields', array(
					array(
						'id'   => 'archive_swatches',
						'type' => 'feature',
						'html' => sprintf( '<img src="%s" alt="%s">',
							rtwpvs()->get_images_uri( 'archive-pro-feature.jpg' ),
							esc_html__( 'Archive premium feature', 'woo-product-variation-swatches' )
						)
					)
				) )
			),
			'tools'           => array(
				'id'     => 'tools',
				'title'  => esc_html__( 'Tools', 'woo-product-variation-swatches' ),
				'desc'   => esc_html__( 'Tools define some system tasks', 'woo-product-variation-swatches' ),
				'active' => apply_filters( 'rtwpvs_tools_setting_active', false ),
				'fields' => apply_filters( 'rtwpvs_tools_setting_fields', array(
					array(
						'id'    => 'remove_all_data',
						'type'  => 'checkbox',
						'title' => esc_html__( '
Enable to delete all data', 'woo-product-variation-swatches' ),
						'desc'  => esc_html__( 'Enable / Disable Allow to delete all data for WooCommerce Product variation plugin during delete this plugin', 'woo-product-variation-swatches' )
					)
				) )
			),
			'premium_plugins' => array(
				'id'     => 'premium_plugins',
				'title'  => esc_html__( 'Premium Plugins', 'woo-product-variation-swatches' ),
				'desc'   => esc_html__( 'You can try our premium plugins', 'woo-product-variation-swatches' ),
				'fields' => apply_filters( 'rtwpvs_premium_plugins_setting_fields', array(
					array(
						'id'         => 'premium_feature',
						'type'       => 'feature',
						'attributes' => array(
							'class' => 'rt-feature'
						),
						'html'       => Functions::get_product_list_html( array(
							'rtwpvs-pro' => array(
								'title'     => "WooCommerce Variation Swatches Pro",
								'price'     => 29,
								'image_url' => rtwpvs()->get_images_uri( 'rtwpvs-pro.png' ),
								'url'       => 'https://www.radiustheme.com/downloads/woocommerce-variation-swatches/',
								'demo_url'  => 'https://radiustheme.com/demo/wordpress/woopluginspro/product/woocoommerce-variation-swatches/',
								'buy_url'   => 'https://www.radiustheme.com/downloads/woocommerce-variation-swatches/',
								'doc_url'   => 'https://www.radiustheme.com/setup-configure-woocommerce-product-variation-swatches-pro/'
							),
							'rtwpvg-pro' => array(
								'price'     => 29,
								'title'     => "WooCommerce Variation images gallery PRO",
								'image_url' => rtwpvs()->get_images_uri( 'rtwpvg-pro.png' ),
								'url'       => 'https://www.radiustheme.com/downloads/woocommerce-variation-images-gallery/',
								'demo_url'  => 'https://radiustheme.com/demo/wordpress/woopluginspro/product/woocommerce-variation-images-gallery/',
								'buy_url'   => 'https://www.radiustheme.com/downloads/woocommerce-variation-images-gallery/',
								'doc_url'   => 'https://www.radiustheme.com/how-to-use-woocommerce-variation-images-gallery-pro/'
							)
						) )
					)
				) )
			)
		);

		return apply_filters( 'rtwpvs_settings_fields', $fields );
	}
}