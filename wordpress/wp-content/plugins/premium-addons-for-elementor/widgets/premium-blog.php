<?php

namespace PremiumAddons\Widgets;

use PremiumAddons\Helper_Functions;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Box_Shadow;

if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.

class Premium_Blog extends Widget_Base {
    
    public function get_name() {
        return 'premium-addon-blog';
    }

    public function get_title() {
		return sprintf( '%1$s %2$s', Helper_Functions::get_prefix(), __('Blog', 'premium-addons-for-elementor') );
	}

    public function is_reload_preview_required() {
        return true;
    }
    
    public function get_style_depends() {
        return [
            'premium-addons'
        ];
    }
    
    public function get_script_depends() {
        return [
            'isotope-js',
            'jquery-slick',
            'premium-addons-js'
        ];
    }

    public function get_icon() {
        return 'pa-blog';
    }
    
    public function get_keywords() {
		return [ 'posts', 'grid', 'item', 'loop', 'query', 'portfolio' ];
	}

    public function get_categories() {
        return [ 'premium-elements' ];
    }

    // Adding the controls fields for Premium Blog
    // This will controls the animation, colors and background, dimensions etc
    protected function _register_controls() {

        $this->start_controls_section('premium_blog_general_settings',
            [
                'label'         => __('Image', 'premium-addons-for-elementor'),
            ]
        );
        
        $this->add_control('premium_blog_hover_image_effect',
            [
                'label'         => __('Hover Effect', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SELECT,
                'description'   => __('Choose a hover effect for the image','premium-addons-for-elementor'),
                'options'       => [
                    'none'   => __('None', 'premium-addons-for-elementor'),
                    'zoomin' => __('Zoom In', 'premium-addons-for-elementor'),
                    'zoomout'=> __('Zoom Out', 'premium-addons-for-elementor'),
                    'scale'  => __('Scale', 'premium-addons-for-elementor'),
                    'gray'   => __('Grayscale', 'premium-addons-for-elementor'),
                    'blur'   => __('Blur', 'premium-addons-for-elementor'),
                    'bright' => __('Bright', 'premium-addons-for-elementor'),
                    'sepia'  => __('Sepia', 'premium-addons-for-elementor'),
                    'trans'  => __('Translate', 'premium-addons-for-elementor'),
                ],
                'default'       => 'zoomin',
                'label_block'   => true
            ]
        );
        
        $this->add_control('premium_blog_hover_color_effect',
            [
                'label'         => __('Color Effect', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SELECT,
                'description'   => __('Choose an overlay color effect','premium-addons-for-elementor'),
                'options'       => [
                    'none'     => __('None', 'premium-addons-for-elementor'),
                    'framed'   => __('Framed', 'premium-addons-for-elementor'),
                    'diagonal' => __('Diagonal', 'premium-addons-for-elementor'),
                    'bordered' => __('Bordered', 'premium-addons-for-elementor'),
                    'squares'  => __('Squares', 'premium-addons-for-elementor'),
                ],
                'default'       => 'framed',
                'label_block'   => true
            ]
        );
        
        $this->end_controls_section();
        
        $this->start_controls_section('premium_blog_content_settings',
            [
                'label'         => __('Display Options', 'premium-addons-for-elementor'),
            ]
        );
        
        $this->add_control('premium_blog_skin',
            [
                'label'         => __('Skin', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SELECT,
                'options'       => [
                    'classic'       => __('Classic', 'premium-addons-for-elementor'),
                    'cards'         => __('Cards', 'premium-addons-for-elementor'),
                ],
                'default'       => 'classic',
                'label_block'   => true
            ]
        );
        
        $this->add_control('premium_blog_title_tag',
			[
				'label'			=> __( 'Title HTML Tag', 'premium-addons-for-elementor' ),
				'description'	=> __( 'Select a heading tag for the post title.', 'premium-addons-for-elementor' ),
				'type'			=> Controls_Manager::SELECT,
				'default'		=> 'h2',
				'options'       => [
                    'h1'    => 'H1',
                    'h2'    => 'H2',
                    'h3'    => 'H3',
                    'h4'    => 'H4',
                    'h5'    => 'H5',
                    'h6'    => 'H6',
                ],
				'label_block'	=> true,
			]
		);
        
        $this->add_control('premium_blog_grid',
            [
                'label'         => __('Grid', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SWITCHER,
                'default'       => 'yes'
            ]
        );
        
        $this->add_control('premium_blog_layout',
            [
                'label'             => __('Layout', 'premium-addons-for-elementor'),
                'type'              => Controls_Manager::SELECT,
                'options'           => [
                    'even'      => __('Even', 'premium-addons-for-elementor'),
                    'masonry'   => __('Masonry', 'premium-addons-for-elementor'),
                ],
                'default'           => 'masonry',
                'condition'         => [
                    'premium_blog_grid' => 'yes'
                ]
            ]
        );
        
        $this->add_responsive_control('premium_blog_columns_number',
            [
                'label'         => __('Number of Columns', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SELECT,
                'options'       => [
                    '100%'  => __('1 Column', 'premium-addons-for-elementor'),
                    '50%'   => __('2 Columns', 'premium-addons-for-elementor'),
                    '33.33%'=> __('3 Columns', 'premium-addons-for-elementor'),
                    '25%'   => __('4 Columns', 'premium-addons-for-elementor'),
                ],
                'default'       => '33.33%',
                'render_type'   => 'template',
                'condition'     => [
                    'premium_blog_grid' =>  'yes',
                ],
                'label_block'   => true,
                'selectors'     => [
                    '{{WRAPPER}} .premium-blog-post-outer-container'  => 'width: {{VALUE}};'
                ],
            ]
        );
        
        $this->add_responsive_control('premium_blog_thumb_min_height',
            [
                'label'         => __('Thumbnail Min Height', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => ['px', '%', "em"],
                'range'         => [
                    'px'    => [
                        'min'   => 1, 
                        'max'   => 300,
                    ],
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-blog-thumbnail-container img' => 'min-height: {{SIZE}}{{UNIT}};'
                ]
            ]
        );
        
        $this->add_responsive_control('premium_blog_thumb_max_height',
            [
                'label'         => __('Thumbnail Max Height', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => ['px', '%', "em"],
                'range'         => [
                    'px'    => [
                        'min'   => 1, 
                        'max'   => 300,
                    ],
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-blog-thumbnail-container img' => 'max-height: {{SIZE}}{{UNIT}};'
                ]
            ]
        );
        
        $this->add_responsive_control('premium_blog_thumbnail_fit',
            [
                'label'         => __('Thumbnail Fit', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SELECT,
                'options'       => [
                    'cover'  => __('Cover', 'premium-addons-for-elementor'),
                    'fill'   => __('Fill', 'premium-addons-for-elementor'),
                    'contain'=> __('Contain', 'premium-addons-for-elementor'),
                ],
                'default'       => 'cover',
                'selectors'     => [
                    '{{WRAPPER}} .premium-blog-thumbnail-container img' => 'object-fit: {{VALUE}}'
                ],
                'condition'     => [
                    'premium_blog_grid' =>  'yes'
                ]
            ]
        );
        
        $this->add_responsive_control('premium_blog_posts_columns_spacing',
            [
                'label'         => __('Rows Spacing', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => ['px', '%', "em"],
                'range'         => [
                    'px'    => [
                        'min'   => 1, 
                        'max'   => 200,
                    ],
                ],
                'condition'     => [
                    'premium_blog_grid'   => 'yes'
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-blog-post-outer-container' => 'margin-bottom: {{SIZE}}{{UNIT}}'
                ]
            ]
        );
        
        $this->add_responsive_control('premium_blog_posts_spacing',
            [
                'label'         => __('Columns Spacing', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => ['px', '%', "em"],
                'range'         => [
                    'px'    => [
                        'min'   => 1, 
                        'max'   => 200,
                    ],
                ],
                'render_type'   => 'template',
                'condition'     => [
                    'premium_blog_grid'   => 'yes'
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-blog-post-outer-container' => 'padding-left: {{SIZE}}{{UNIT}}; padding-right: {{SIZE}}{{UNIT}}'
                ]
            ]
        );
        
        $this->add_responsive_control('premium_flip_text_align',
            [
                'label'         => __( 'Alignment', 'premium-addons-for-elementor' ),
                'type'          => Controls_Manager::CHOOSE,
                'options'       => [
                    'left'      => [
                        'title'=> __( 'Left', 'premium-addons-for-elementor' ),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center'    => [
                        'title'=> __( 'Center', 'premium-addons-for-elementor' ),
                        'icon' => 'fa fa-align-center',
                    ],
                    'right'     => [
                        'title'=> __( 'Right', 'premium-addons-for-elementor' ),
                        'icon' => 'fa fa-align-right',
                    ],
                ],
                'default'       => 'left',
                'selectors'     => [
                    '{{WRAPPER}} .premium-blog-content-wrapper' => 'text-align: {{VALUE}};',
                ],
            ]
        );
        
        $this->end_controls_section();
        
        $this->start_controls_section('premium_blog_posts_options',
            [
                'label'         => __('Post Options', 'premium-addons-for-elementor'),
            ]
        );
        
        $this->add_control('premium_blog_excerpt',
            [
                'label'         => __('Excerpt', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SWITCHER,
                'description'   => __('Excerpt is used for article summary with a link to the whole entry. The default except length is 55','premium-addons-for-elementor'),
                'default'       => 'yes',
            ]
        );
        
        $this->add_control('premium_blog_excerpt_box',
            [
                'label'         => __('Pull From Content Box', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SWITCHER,
                'description'   => __('Post content will be pulled from post content box','premium-addons-for-elementor'),
                'default'       => 'true',
                'return_value'  => 'true',
            ]
        );

        $this->add_control('premium_blog_excerpt_length',
            [
                'label'         => __('Excerpt Length', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::NUMBER,
                'default'       => 55,
                'condition'     => [
                    'premium_blog_excerpt'  => 'yes',
                ]
            ]
        );
        
        $this->add_control('premium_blog_excerpt_type',
            [
                'label'         => __('Excerpt Type', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SELECT,
                'options'       => [
                    'dots'   => __('Dots', 'premium-addons-for-elementor'),
                    'link'   => __('Link', 'premium-addons-for-elementor'),
                ],
                'default'       => 'dots',
                'label_block'   => true
            ]
        );

        $this->add_control('premium_blog_excerpt_text',
			[
				'label'			=> __( 'Link Text', 'premium-addons-for-elementor' ),
				'type'			=> Controls_Manager::TEXT,
                'default'       => __('continue reading','premium-addons-for-elementor'),
                'condition'     => [
                    'premium_blog_excerpt'      => 'yes',
                    'premium_blog_excerpt_type' => 'link'
                ]
			]
		);
        
        $this->add_control('premium_blog_author_meta',
            [
                'label'         => __('Author Meta', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SWITCHER,
                'default'       => 'yes',
            ]
        );
        
        $this->add_control('premium_blog_date_meta',
            [
                'label'         => __('Date Meta', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SWITCHER,
                'default'       => 'yes',
            ]
        );
        
        $this->add_control('premium_blog_categories_meta',
            [
                'label'         => __('Categories Meta', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SWITCHER,
                'description'   => __('Display or hide categories meta','premium-addons-for-elementor'),
                'default'       => 'yes',
            ]
        );

        $this->add_control('premium_blog_comments_meta',
            [
                'label'         => __('Comments Meta', 'premium-addons-for-elementor'),
                'description'   => __('Display or hide comments meta','premium-addons-for-elementor'),
                'type'          => Controls_Manager::SWITCHER,
                'default'       => 'yes',
            ]
        );
        
        $this->add_control('premium_blog_tags_meta',
            [
                'label'         => __('Tags Meta', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SWITCHER,
                'description'   => __('Display or hide post tags','premium-addons-for-elementor'),
                'default'       => 'yes',
            ]
        );
        
        $this->add_control('premium_blog_post_format_icon',
            [
                'label'         => __( 'Post Format Icon', 'premium-addons-for-elementor' ),
                'type'          => Controls_Manager::SWITCHER,
                'default'       => 'yes',
            ]
        );
        
        $this->end_controls_section();
        
        $this->start_controls_section('premium_blog_advanced_settings',
            [
                'label'         => __('Advanced Settings', 'premium-addons-for-elementor'),
            ]
        );
        
        $this->add_control('premium_blog_number_of_posts',
            [
                'label'         => __('Posts Per Page', 'premium-addons-for-elementor'),
                'description'   => __('Choose how many posts do you want to be displayed per page','premium-addons-for-elementor'),
                'type'          => Controls_Manager::NUMBER,
                'min'			=> 1,
                'default'		=> 3,
            ]
        );
        
        $this->add_control('premium_blog_total_posts_number',
            [
                'label'         => __('Total Number of Posts', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::NUMBER,
                'default'       => wp_count_posts()->publish,
                'min'			=> 1,
                'condition'     => [
                    'premium_blog_paging'      => 'yes',
                ]
            ]
        );

		$this->add_control('premium_blog_offset',
			[
				'label'         => __( 'Offset Count', 'premium-addons-for-elementor' ),
                'description'   => __('The index of post to start with','premium-addons-for-elementor'),
				'type' 			=> Controls_Manager::NUMBER,
                'default' 		=> '0',
				'min' 			=> '0',
			]
		);
        
        $this->add_control('premium_blog_categories',
            [
                'label'         => __( 'Filter By Category', 'premium-addons-for-elementor' ),
                'type'          => Controls_Manager::SELECT2,
                'description'   => __('Get posts for specific category(s)','premium-addons-for-elementor'),
                'label_block'   => true,
                'multiple'      => true,
                'options'       => premium_addons_post_type_categories(),        
            ]
        );
        
        $this->add_control('premium_blog_cat_tabs',
            [
                'label'         => __('Filter Tabs', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SWITCHER,
                'condition'     => [
                    'premium_blog_carousel!'  => 'yes'
                ]
            ]
        );
        
        $this->add_responsive_control('premium_blog_filter_align',
            [
                'label'         => __( 'Alignment', 'premium-addons-for-elementor' ),
                'type'          => Controls_Manager::CHOOSE,
                'options'       => [
                    'flex-start'    => [
                        'title' => __( 'Left', 'premium-addons-for-elementor' ),
                        'icon'  => 'fa fa-align-left',
                    ],
                    'center'        => [
                        'title' => __( 'Center', 'premium-addons-for-elementor' ),
                        'icon'  => 'fa fa-align-center',
                    ],
                    'flex-end'      => [
                        'title' => __( 'Right', 'premium-addons-for-elementor' ),
                        'icon'  => 'fa fa-align-right',
                    ],
                ],
                'default'       => 'center',
                'condition'     => [
                    'premium_blog_cat_tabs'     => 'yes',
                    'premium_blog_carousel!'    => 'yes'
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-blog-filter' => 'justify-content: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control('premium_blog_tags',
            [
                'label'         => __( 'Filter By Tag', 'premium-addons-for-elementor' ),
                'type'          => Controls_Manager::SELECT2,
                'description'   => __('Get posts for specific tag(s)','premium-addons-for-elementor'),
                'label_block'   => true,
                'multiple'      => true,
                'options'       => premium_addons_post_type_tags(),        
            ]
        );
        
        $this->add_control('premium_blog_users',
            [
                'label'         => __( 'Filter By Author', 'premium-addons-for-elementor' ),
                'type'          => Controls_Manager::SELECT2,
                'description'   => __('Get posts for specific author(s)','premium-addons-for-elementor'),
                'label_block'   => true,
                'multiple'      => true,
                'options'       => premium_addons_post_type_users(),        
            ]
        );
        
        $this->add_control('premium_blog_posts_exclude',
            [
                'label'         => __( 'Posts to Exclude', 'premium-addons-for-elementor' ),
                'type'          => Controls_Manager::SELECT2,
                'description'   => __('Add post(s) to exclude','premium-addons-for-elementor'),
                'label_block'   => true,
                'multiple'      => true,
                'options'       => premium_addons_posts_list(),        
            ]
        );
        
        $this->add_control('premium_blog_order_by',
            [
                'label'         => __( 'Order By', 'premium-addons-for-elementor' ),
                'type'          => Controls_Manager::SELECT,
                'label_block'   => true,
                'options'       => [
                    'none'  => __('None', 'premium-addons-for-elementor'),
                    'ID'    => __('ID', 'premium-addons-for-elementor'),
                    'author'=> __('Author', 'premium-addons-for-elementor'),
                    'title' => __('Title', 'premium-addons-for-elementor'),
                    'name'  => __('Name', 'premium-addons-for-elementor'),
                    'date'  => __('Date', 'premium-addons-for-elementor'),
                    'modified'=> __('Last Modified', 'premium-addons-for-elementor'),
                    'rand'  => __('Random', 'premium-addons-for-elementor'),
                    'comment_count'=> __('Number of Comments', 'premium-addons-for-elementor'),
                ],
                'default'       => 'date'
            ]
        );
        
        $this->add_control('premium_blog_order',
            [
                'label'         => __( 'Order', 'premium-addons-for-elementor' ),
                'type'          => Controls_Manager::SELECT,
                'label_block'   => true,
                'options'       => [
                    'DESC'  => __('Descending', 'premium-addons-for-elementor'),
                    'ASC'   => __('Ascending', 'premium-addons-for-elementor'),
                ],
                'default'       => 'DESC'
            ]
        );
        
        $this->add_control('premium_blog_paging',
            [
                'label'         => __('Pagination', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SWITCHER,
                'description'   => __('Pagination is the process of dividing the posts into discrete pages','premium-addons-for-elementor'),
            ]
        );
        
        $this->add_control('premium_blog_next_text',
			[
				'label'			=> __( 'Next Page String', 'premium-addons-for-elementor' ),
				'type'			=> Controls_Manager::TEXT,
                'default'       => __('Next','premium-addons-for-elementor'),
                'condition'     => [
                    'premium_blog_paging'      => 'yes',
                ]
			]
		);
        
        $this->add_control('premium_blog_prev_text',
			[
				'label'			=> __( 'Previous Page String', 'premium-addons-for-elementor' ),
				'type'			=> Controls_Manager::TEXT,
                'default'       => __('Previous','premium-addons-for-elementor'),
                'condition'     => [
                    'premium_blog_paging'      => 'yes',
                ]
			]
		);
        
        $this->add_responsive_control('premium_blog_pagination_align',
            [
                'label'         => __( 'Alignment', 'premium-addons-for-elementor' ),
                'type'          => Controls_Manager::CHOOSE,
                'options'       => [
                    'left'      => [
                        'title'=> __( 'Left', 'premium-addons-for-elementor' ),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center'    => [
                        'title'=> __( 'Center', 'premium-addons-for-elementor' ),
                        'icon' => 'fa fa-align-center',
                    ],
                    'right'     => [
                        'title'=> __( 'Right', 'premium-addons-for-elementor' ),
                        'icon' => 'fa fa-align-right',
                    ],
                ],
                'default'       => 'right',
                'condition'     => [
                    'premium_blog_paging'      => 'yes',
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-blog-pagination-container' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_control('premium_blog_new_tab',
            [
                'label'         => __('Links in New Tab', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SWITCHER,
                'description'   => __('Enable links to be opened in a new tab','premium-addons-for-elementor'),
                'default'       => 'yes',
            ]
        );
 
        $this->end_controls_section();
        
        $this->start_controls_section('premium_blog_carousel_settings',
            [
                'label'         => __('Carousel', 'premium-addons-for-elementor'),
                'condition'     => [
                    'premium_blog_grid' => 'yes'
                ]
            ]
        );
        
        $this->add_control('premium_blog_carousel',
            [
                'label'         => __('Enable Carousel', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SWITCHER
            ]
        );
        
        $this->add_control('premium_blog_carousel_fade',
            [
                'label'         => __('Fade', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SWITCHER,
                'condition'     => [
                    'premium_blog_columns_number' => '100%'
                ]
            ]
        );
        
        $this->add_control('premium_blog_carousel_play',
            [
                'label'         => __('Auto Play', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SWITCHER,
                'condition'     => [
                    'premium_blog_carousel'  => 'yes'
                ]
            ]
        );
        
        $this->add_control('premium_blog_carousel_autoplay_speed',
			[
				'label'			=> __( 'Autoplay Speed', 'premium-addons-for-elementor' ),
				'description'	=> __( 'Autoplay Speed means at which time the next slide should come. Set a value in milliseconds (ms)', 'premium-addons-for-elementor' ),
				'type'			=> Controls_Manager::NUMBER,
				'default'		=> 5000,
				'condition'		=> [
					'premium_blog_carousel' => 'yes',
                    'premium_blog_carousel_play' => 'yes',
				],
			]
		);
        
        $this->add_control('premium_blog_carousel_dots',
            [
                'label'         => __('Navigation Dots', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SWITCHER,
                'condition'     => [
                    'premium_blog_carousel'  => 'yes'
                ]
            ]
        );
        
        $this->add_control('premium_blog_carousel_arrows',
            [
                'label'         => __('Navigation Arrows', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SWITCHER,
                'default'       => 'yes',
                'condition'     => [
                    'premium_blog_carousel'  => 'yes'
                ]
            ]
        );
        
        $this->add_responsive_control('premium_blog_carousel_arrows_pos',
            [
                'label'         => __('Arrows Position', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => ['px', "em"],
                'range'         => [
                    'px'    => [
                        'min'       => -100, 
                        'max'       => 100,
                    ],
                    'em'    => [
                        'min'       => -10, 
                        'max'       => 10,
                    ],
                ],
                'condition'		=> [
					'premium_blog_carousel'         => 'yes',
                    'premium_blog_carousel_arrows'  => 'yes'
				],
                'selectors'     => [
                    '{{WRAPPER}} .premium-blog-wrap a.carousel-arrow.carousel-next' => 'right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .premium-blog-wrap a.carousel-arrow.carousel-prev' => 'left: {{SIZE}}{{UNIT}};',
                ]
            ]
        );
        
        
        $this->end_controls_section();
        
        $this->start_controls_section('premium_blog_image_style_section',
            [
                'label'         => __('Image', 'premium-addons-for-elementor'),
                'tab'           => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control('premium_blog_plus_color',
            [
                'label'         => __('Plus Sign Color', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::COLOR,
                'scheme'        => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_2,
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-blog-thumbnail-container:before, {{WRAPPER}} .premium-blog-thumbnail-container:after' => 'background-color: {{VALUE}};',
                ]
            ]
        );
        
        $this->add_control('premium_blog_overlay_color',
            [
                'label'         => __('Overlay Color', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::COLOR,
                'scheme'        => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-blog-framed-effect, {{WRAPPER}} .premium-blog-bordered-effect,{{WRAPPER}} .premium-blog-squares-effect:before,{{WRAPPER}} .premium-blog-squares-effect:after,{{WRAPPER}} .premium-blog-squares-square-container:before,{{WRAPPER}} .premium-blog-squares-square-container:after, {{WRAPPER}} .premium-blog-format-container:hover' => 'background-color: {{VALUE}};',
                ]
            ]
        );
        
        $this->add_control('premium_blog_border_effect_color',
            [
                'label'         => __('Border Color', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::COLOR,
                'scheme'        => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_2,
                ],
                'description'   => 'Used with Bordered style only',
                'condition'     => [
                    'premium_blog_hover_color_effect'  => 'bordered',
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-blog-bordered-border-container' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Css_Filter::get_type(),
            [
                'name' => 'css_filters',
                'selector' => '{{WRAPPER}} .premium-blog-thumbnail-container img',
            ]
        );
        
        $this->end_controls_section();
        
        $this->start_controls_section('premium_blog_title_style_section',
            [
                'label'         => __('Title', 'premium-addons-for-elementor'),
                'tab'           => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control('premium_blog_title_color',
            [
                'label'         => __('Color', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::COLOR,
                'scheme'        => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_2,
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-blog-entry-title a'  => 'color: {{VALUE}};',
                ]
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'          => 'premium_blog_title_typo',
                'selector'      => '{{WRAPPER}} .premium-blog-entry-title',
            ]
        );
        
        $this->add_control('premium_blog_title_hover_color',
            [
                'label'         => __('Hover Color', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::COLOR,
                'scheme'        => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-blog-entry-title:hover a'  => 'color: {{VALUE}};',
                ]
            ]
        );
        
        $this->end_controls_section();
        
        $this->start_controls_section('premium_blog_meta_style_section',
            [
                'label'         => __('Meta', 'premium-addons-for-elementor'),
                'tab'           => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control('premium_blog_meta_color',
            [
                'label'         => __('Color', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::COLOR,
                'scheme'        => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_2,
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-blog-entry-meta, {{WRAPPER}} .premium-blog-entry-meta a'  => 'color: {{VALUE}};',
                ]
            ]
        );
        
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'          => 'premium_blog_meta_typo',
                'selector'      => '{{WRAPPER}} .premium-blog-entry-meta a',
            ]
        );

        $this->add_control('premium_blog_meta_hover_color',
            [
                'label'         => __('Hover Color', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::COLOR,
                'scheme'        => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-blog-entry-meta a:hover'  => 'color: {{VALUE}};',
                ]
            ]
        );
        
        $this->end_controls_section();
        
        $this->start_controls_section('premium_blog_tags_style_section',
            [
                'label'         => __('Tags', 'premium-addons-for-elementor'),
                'tab'           => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control('premium_blog_tags_color',
            [
                'label'         => __('Color', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::COLOR,
                'scheme'        => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_2,
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-blog-post-tags-container, {{WRAPPER}} .premium-blog-post-tags-container a'  => 'color: {{VALUE}};',
                ]
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'          => 'premium_blog_tags_typo',
                'selector'      => '{{WRAPPER}} .premium-blog-post-tags-container a',
            ]
        );
        
        $this->add_control('premium_blog_tags_hoer_color',
            [
                'label'         => __('Hover Color', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::COLOR,
                'scheme'        => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-blog-post-tags-container a:hover'  => 'color: {{VALUE}};',
                ]
            ]
        );
        
        $this->end_controls_section();
        
        $this->start_controls_section('premium_blog_format_style_section',
            [
                'label'         => __('Post Format Icon', 'premium-addons-for-elementor'),
                'tab'           => Controls_Manager::TAB_STYLE,
                'condition'     => [
                    'premium_blog_post_format_icon' => 'yes'
                ]
            ]
        );
        
        $this->add_control('premium_blog_format_icon_size',
            [
                'label'         => __('Size', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SLIDER,
                'description'   => __('Choose icon size in (PX, EM)', 'premium-addons-for-elementor'),
                'range'         => [
                    'em'    => [
                        'min'       => 1,
                        'max'       => 10,
                    ],
                ],
                'size_units'    => ['px', "em"],
                'label_block'   => true,
                'selectors'     => [
                    '{{WRAPPER}} .premium-blog-format-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                ]
            ]
        );
        
        $this->add_control('premium_blog_format_icon_color',
            [
                'label'         => __('Color', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::COLOR,
                'scheme'        => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_2,
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-blog-format-container i'  => 'color: {{VALUE}};',
                ]
            ]
        );

        $this->add_control('premium_blog_format_icon_hover_color',
            [
                'label'         => __('Hover Color', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::COLOR,
                'scheme'        => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-blog-format-container:hover i'  => 'color: {{VALUE}};',
                ]
            ]
        );

        $this->add_control('premium_blog_format_back_color',
            [
                'label'         => __('Background Color', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::COLOR,
                'scheme'        => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-blog-format-container'  => 'background-color: {{VALUE}};',
                ]
            ]
        );
        
        $this->add_control('premium_blog_format_back_hover_color',
            [
                'label'         => __('Hover Background Color', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::COLOR,
                'scheme'        => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_2,
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-blog-format-container:hover'  => 'background-color: {{VALUE}};',
                ]
            ]
        );
        
        $this->end_controls_section();
        
        $this->start_controls_section('premium_blog_content_style_section',
            [
                'label'         => __('Box', 'premium-addons-for-elementor'),
                'tab'           => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control('premium_blog_post_content_color',
            [
                'label'         => __('Text Color', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::COLOR,
                'scheme'        => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_3,
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-blog-post-content'  => 'color: {{VALUE}};',
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'          => 'premium_blog_content_typo',
                'selector'      => '{{WRAPPER}} .premium-blog-post-content',
            ]
        );
        
        $this->add_control('premium_blog_box_background_color',
            [
                'label'         => __('Background Color', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::COLOR,
                'selectors'     => [
                    '{{WRAPPER}} .premium-blog-post-container'  => 'background-color: {{VALUE}};',
                ]
            ]
        );
        
        $this->add_control('premium_blog_content_background_color',
            [
                'label'         => __('Content Background Color', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::COLOR,
                'default'       => '#f5f5f5',
                'selectors'     => [
                    '{{WRAPPER}} .premium-blog-content-wrapper'  => 'background-color: {{VALUE}};',
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
                [
                    'name'          => 'premium_blog_box_shadow',
                    'selector'      => '{{WRAPPER}} .premium-blog-content-wrapper',
                ]
                );
        
        $this->add_responsive_control('prmeium_blog_box_padding',
            [
                'label'         => __('Padding', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => ['px', 'em', '%'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-blog-post-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ]
            ]
        );
        
        $this->add_responsive_control('prmeium_blog_content_margin',
            [
                'label'         => __('Content Margin', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => ['px', 'em', '%'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-blog-content-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ]
            ]
        );
        
        $this->add_responsive_control('prmeium_blog_content_padding',
            [
                'label'         => __('Content Padding', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => ['px', 'em', '%'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-blog-content-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ]
            ]
        );
        
        $this->end_controls_section();
        
        $this->start_controls_section('premium_blog_pagination_Style',
            [
                'label'         => __('Pagination Style', 'premium-addons-for-elementor'),
                'tab'           => Controls_Manager::TAB_STYLE,
                'condition'     => [
                    'premium_blog_paging'   => 'yes',
                ]
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'              => 'premium_blog_pagination_typo',
                'selector'          => '{{WRAPPER}} .premium-blog-pagination-container li *',
            ]
            );
        
        $this->start_controls_tabs('premium_blog_pagination_colors');
        
        $this->start_controls_tab('premium_blog_pagination_nomral',
            [
                'label'         => __('Normal', 'premium-addons-for-elementor'),
                
            ]
        );
        
        $this->add_control('prmeium_blog_pagination_color', 
            [
                'label'         => __('Color', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::COLOR,
                'scheme'        => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_2,
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-blog-pagination-container li *' => 'color: {{VALUE}};'
                ]
            ]
        );
        
        $this->add_control('prmeium_blog_pagination_back_color', 
            [
                'label'         => __('Background Color', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::COLOR,
                'scheme'        => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-blog-pagination-container li' => 'background-color: {{VALUE}};'
                ]
            ]
        );
        
        $this->end_controls_tab();
        
        $this->start_controls_tab('premium_blog_pagination_hover',
            [
                'label'         => __('Hover', 'premium-addons-for-elementor'),
                
            ]
        );
        
        $this->add_control('prmeium_blog_pagination_hover_color', 
            [
                'label'         => __('Color', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::COLOR,
                'scheme'        => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-blog-pagination-container li:hover *' => 'color: {{VALUE}};'
                ]
            ]
        );
        
        $this->add_control('prmeium_blog_pagination_back_hover_color', 
            [
                'label'         => __('Background Color', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::COLOR,
                'scheme'        => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_2,
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-blog-pagination-container li:hover' => 'background-color: {{VALUE}};'
                ]
            ]
        );
        
        $this->end_controls_tab();
        
        $this->end_controls_tabs();
        
        $this->add_group_control(
            Group_Control_Border::get_type(), 
            [
                'name'          => 'premium_blog_border',
                'separator'     => 'before',
                'selector'      => '{{WRAPPER}} .premium-blog-pagination-container li',
            ]
        );
        
        $this->add_control('premium_blog_border_radius',
                [
                    'label'         => __('Border Radius', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SLIDER,
                    'size_units'    => ['px', '%' ,'em'],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-blog-pagination-container li' => 'border-radius: {{SIZE}}{{UNIT}};'
                    ]
                ]
                );
        
        $this->add_responsive_control('prmeium_blog_pagination_margin',
            [
                'label'         => __('Margin', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => ['px', 'em', '%'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-blog-pagination-container li' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ]
            ]
        );
        
        $this->add_responsive_control('prmeium_blog_pagination_padding',
            [
                'label'         => __('Padding', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => ['px', 'em', '%'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-blog-pagination-container li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ]
            ]
        );
        
        $this->end_controls_section();
        
        $this->start_controls_section('carousel_dots_style',
            [
                'label'         => __('Carousel Dots', 'premium-addons-for-elementor'),
                'tab'           => Controls_Manager::TAB_STYLE,
                'condition'     => [
                    'premium_blog_carousel'         => 'yes',
                    'premium_blog_carousel_dots'  => 'yes'
                ]
            ]
        );
        
        $this->add_control('carousel_dot_navigation_color',
			[
				'label' 		=> __( 'Color', 'premium-addons-for-elementor' ),
				'type' 			=> Controls_Manager::COLOR,
				'scheme' 		=> [
				    'type' 	=> Scheme_Color::get_type(),
				    'value' => Scheme_Color::COLOR_2,
				],
				'selectors'		=> [
					'{{WRAPPER}} ul.slick-dots li' => 'color: {{VALUE}}'
				]
			]
		);

		$this->add_control('carousel_dot_navigation_active_color',
			[
				'label' 		=> __( 'Active Color', 'premium-addons-for-elementor' ),
				'type' 			=> Controls_Manager::COLOR,
				'scheme' 		=> [
				    'type' 	=> Scheme_Color::get_type(),
				    'value' => Scheme_Color::COLOR_1,
				],
				'selectors'		=> [
					'{{WRAPPER}} ul.slick-dots li.slick-active' => 'color: {{VALUE}}'
				]
			]
		);
        
        $this->end_controls_section();
        
        $this->start_controls_section('carousel_arrows_style',
            [
                'label'         => __('Carousel Arrows', 'premium-addons-for-elementor'),
                'tab'           => Controls_Manager::TAB_STYLE,
                'condition'     => [
                    'premium_blog_carousel'         => 'yes',
                    'premium_blog_carousel_arrows'  => 'yes'
                ]
            ]
        );
        
        $this->add_control('arrow_color',
            [
                'label'         => __('Color', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::COLOR,
                'scheme'        => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-blog-wrap .slick-arrow' => 'color: {{VALUE}};',
                ]
            ]
        );

        $this->add_responsive_control('premium_blog_carousel_arrow_size',
            [
                'label'         => __('Size', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => ['px', '%' ,'em'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-blog-wrap .slick-arrow i' => 'font-size: {{SIZE}}{{UNIT}};'
                ]
            ]
        );
        
        $this->add_control('premium_blog_carousel_arrow_background',
            [
                'label'         => __('Background Color', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::COLOR,
                'scheme'        => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_2,
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-blog-wrap .slick-arrow' => 'background-color: {{VALUE}};',
                ]
            ]
        );
        
        $this->add_control('premium_blog_carousel_border_radius',
            [
                'label'         => __('Border Radius', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => ['px', '%' ,'em'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-blog-wrap .slick-arrow' => 'border-radius: {{SIZE}}{{UNIT}};'
                ]
            ]
        );

        $this->add_control('premium_blog_carousel_arrow_padding',
            [
                'label'         => __('Padding', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => ['px', '%' ,'em'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-blog-wrap .slick-arrow' => 'padding: {{SIZE}}{{UNIT}};'
                ]
            ]
        );
        
        $this->end_controls_section();
        
        $this->start_controls_section('premium_blog_read_more_style',
            [
                'label'         => __('Read More Text', 'premium-addons-for-elementor'),
                'tab'           => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control('premium_blog_read_more_color',
            [
                'label'         => __('Color', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::COLOR,
                'selectors'     => [
                    '{{WRAPPER}} .premium-blog-post-content .premium-blog-excerpt-link'  => 'color: {{VALUE}};',
                ]
            ]
        );
        
        $this->add_control('premium_blog_read_more_hover_color',
            [
                'label'         => __('Hover Color', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::COLOR,
                'selectors'     => [
                    '{{WRAPPER}} .premium-blog-post-content .premium-blog-excerpt-link:hover'  => 'color: {{VALUE}};',
                ]
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'          => 'premium_blog_read_more_typo',
                'selector'      => '{{WRAPPER}} .premium-blog-post-content .premium-blog-excerpt-link',
            ]
        );
        
        $this->end_controls_section();
        
        $this->start_controls_section('premium_blog_filter_style',
            [
                'label'     => __('Filter','premium-addons-for-elementor'),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'premium_blog_cat_tabs'    => 'yes'
                ]
            ]);
        
        $this->add_control('premium_blog_filter_color',
                [
                    'label'         => __('Color', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::COLOR,
                    'scheme'        => [
                        'type'  => Scheme_Color::get_type(),
                        'value' => Scheme_Color::COLOR_2,
                    ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-blog-cats-container li a.category span' => 'color: {{VALUE}};',
                    ]
                ]
                );
        
        $this->add_control('premium_blog_filter_active_color',
                [
                    'label'         => __('Active Color', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::COLOR,
                    'scheme'        => [
                        'type'  => Scheme_Color::get_type(),
                        'value' => Scheme_Color::COLOR_1,
                    ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-blog-cats-container li a.active span' => 'color: {{VALUE}};',
                    ]
                ]
                );
        
        $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name'          => 'premium_blog_filter_typo',
                    'scheme'        => Scheme_Typography::TYPOGRAPHY_1,
                    'selector'      => '{{WRAPPER}} .premium-blog-cats-container li a.category',
                    ]
                );
        
        $this->add_control('premium_blog_background_color',
           [
               'label'         => __('Background Color', 'premium-addons-for-elementor'),
               'type'          => Controls_Manager::COLOR,
               'scheme'        => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
               'selectors'     => [
                   '{{WRAPPER}} .premium-blog-cats-container li a.category' => 'background-color: {{VALUE}};',
               ],
           ]
       );
        
        $this->add_control('premium_blog_background_active_color',
           [
               'label'         => __('Background Active Color', 'premium-addons-for-elementor'),
               'type'          => Controls_Manager::COLOR,
               'scheme'        => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_2,
                ],
               'selectors'     => [
                   '{{WRAPPER}} .premium-blog-cats-container li a.active' => 'background-color: {{VALUE}};',
               ],
           ]
       );
        
        $this->add_group_control(
            Group_Control_Border::get_type(), 
                [
                    'name'              => 'premium_blog_filter_border',
                    'selector'          => '{{WRAPPER}} .premium-blog-cats-container li a.category',
                ]
                );

        $this->add_control('premium_blog_filter_border_radius',
                [
                    'label'             => __('Border Radius', 'premium-addons-for-elementor'),
                    'type'              => Controls_Manager::SLIDER,
                    'size_units'        => ['px','em','%'],
                    'selectors'         => [
                        '{{WRAPPER}} .premium-blog-cats-container li a.category'  => 'border-radius: {{SIZE}}{{UNIT}};',
                        ]
                    ]
                );
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
                [
                    'name'          => 'premium_blog_filter_shadow',
                    'selector'      => '{{WRAPPER}} .premium-blog-cats-container li a.category',
                ]
                );
        
        $this->add_responsive_control('premium_blog_filter_margin',
                [
                    'label'             => __('Margin', 'premium-addons-for-elementor'),
                    'type'              => Controls_Manager::DIMENSIONS,
                    'size_units'        => ['px', 'em', '%'],
                    'selectors'             => [
                        '{{WRAPPER}} .premium-blog-cats-container li a.category' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_responsive_control('premium_blog_filter_padding',
                [
                    'label'             => __('Padding', 'premium-addons-for-elementor'),
                    'type'              => Controls_Manager::DIMENSIONS,
                    'size_units'        => ['px', 'em', '%'],
                'selectors'             => [
                    '{{WRAPPER}} .premium-blog-cats-container li a.category' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->end_controls_section();
       
    }
    
    /*
     * Renders post content
     * 
     * @since 3.0.5
     * @access protected
     */
    protected function get_post_content() {
        
        $settings = $this->get_settings();
        
        $excerpt_type = $settings['premium_blog_excerpt_type'];
        $excerpt_text = $settings['premium_blog_excerpt_text'];
        $excerpt_src  = $settings['premium_blog_excerpt_box'];
        
    ?>
        <div class="premium-blog-post-content" style="<?php if ( $settings['premium_blog_post_format_icon'] !== 'yes' ) : echo 'margin-left:0px;'; endif; ?>">
            <?php if ( $settings['premium_blog_excerpt'] === 'yes' ) {
                echo premium_addons_get_excerpt_by_id( get_the_ID(), $settings['premium_blog_excerpt_length'], $excerpt_type, $excerpt_text, $excerpt_src );
            } else { 
                the_content();
            } ?>
        </div>
    <?php
    }

    /*
     * Renders post format icon
     * 
     * @since 3.0.5
     * @access protected
     */
    protected function get_post_format_icon() {
        
        $post_format = get_post_format();
        
        switch( $post_format ) {
            case 'aside':
                $post_format = 'file-text-o';
                break;
            case 'audio':
                $post_format = 'music';
                break;
            case 'gallery':
                $post_format = 'file-image-o';
                break;
            case 'image':
                $post_format = 'picture-o';
                break;
            case 'link':
                $post_format = 'link';
                break;
            case 'quote':
                $post_format = 'quote-left';
                break;
            case 'video':
                $post_format = 'video-camera';
                break;
            default: 
                $post_format = 'thumb-tack';
        }
    ?>
        <i class="premium-blog-format-icon fa fa-<?php echo $post_format; ?>"></i>
    <?php 
    }
    
    /*
     * Render post title
     * 
     * @since 3.4.4
     * @access protected
     */
    protected function get_post_title( $link_target ) {
        
        $settings = $this->get_settings_for_display();
        
        $this->add_render_attribute( 'title', 'class', 'premium-blog-entry-title' );
        
    ?>
        
        <<?php echo $settings['premium_blog_title_tag'] . ' ' . $this->get_render_attribute_string('title'); ?>><a href="<?php the_permalink(); ?>" target="<?php echo esc_attr( $link_target ); ?>"><?php the_title(); ?></a></<?php echo $settings['premium_blog_title_tag']; ?>>
        
    <?php   
    }
    
    protected function get_post_meta( $link_target ) {
        
        $settings = $this->get_settings();
        
        $date_format = get_option('date_format');
        
    ?>
        
        <div class="premium-blog-entry-meta" style="<?php if( $settings['premium_blog_post_format_icon'] !== 'yes' ) : echo 'margin-left:0px'; endif; ?>">
            <?php if( $settings['premium_blog_author_meta'] === 'yes' ) : ?>
                <span class="premium-blog-post-author premium-blog-meta-data"><i class="fa fa-user fa-fw"></i><?php the_author_posts_link();?></span>
            <?php endif; ?>
            <?php if( $settings['premium_blog_date_meta'] === 'yes' ) : ?>
                <span class="premium-blog-post-time premium-blog-meta-data"><i class="fa fa-calendar fa-fw"></i><a href="<?php the_permalink(); ?>" target="<?php echo esc_attr($link_target); ?>"><?php the_time($date_format); ?></a></span>
            <?php endif; ?>
            <?php if( $settings['premium_blog_categories_meta'] === 'yes' ) : ?>
                <span class="premium-blog-post-categories premium-blog-meta-data"><i class="fa fa-align-left fa-fw"></i><?php the_category(', '); ?></span>
            <?php endif; ?>
            <?php if( $settings['premium_blog_comments_meta'] === 'yes' ) : ?>
                <span class="premium-blog-post-comments premium-blog-meta-data"><i class="fa fa-comments-o fa-fw"></i><a href="<?php the_permalink(); ?>" target="<?php echo esc_attr($link_target); ?>"><?php comments_number('0 Comments', '1', '%'); ?>  </a></span>
            <?php endif; ?>
        </div>
        
    <?php
    }

    /*
     * Renders post skin
     * 
     * @since 3.0.5
     * @access protected
     */
    protected function get_post_layout() {
        
        $settings = $this->get_settings();
        
        $image_effect = $settings['premium_blog_hover_image_effect'];
        
        $post_effect = $settings['premium_blog_hover_color_effect'];
        
        if( $settings['premium_blog_new_tab'] == 'yes' ) {
            $target = '_blank';
        } else {
            $target = '_self';
        }
        
        $skin = $settings['premium_blog_skin'];
        
        $post_id = get_the_ID();
        
        $key = 'post_' . $post_id;
        
        $tax_key = sprintf( '%s_tax', $key );
        
        $wrap_key = sprintf( '%s_wrap', $key );
        
        $content_key = sprintf( '%s_content', $key );
        
        $this->add_render_attribute( $tax_key, 'class', 'premium-blog-post-outer-container' );
        
        $this->add_render_attribute( $wrap_key, 'class', [ 
            'premium-blog-post-container',
            $skin,
        ] );
        
        $thumb = ( ! has_post_thumbnail() ) ? 'empty-thumb' : '';
        
        if ( 'yes' === $settings['premium_blog_cat_tabs'] && 'yes' !== $settings['premium_blog_carousel'] ) {
            
            $categories = get_the_category( $post_id );
        
            foreach( $categories as $index => $category ) {
            
                $category = str_replace( ' ', '-', $category->cat_name );
                
                $this->add_render_attribute( $tax_key, 'class', strtolower( $category ) );
            }
            
        }
        
        $this->add_render_attribute( $content_key, 'class', [ 
            'premium-blog-content-wrapper',
            $thumb,
        ] );
        
    ?>
        <div <?php echo $this->get_render_attribute_string( $tax_key ); ?>>
            <div <?php echo $this->get_render_attribute_string( $wrap_key ); ?>>
                <div class="premium-blog-thumb-effect-wrapper">
                    <div class="premium-blog-thumbnail-container <?php echo 'premium-blog-' . $image_effect . '-effect';?>">
                        <a href="<?php the_permalink(); ?>" target="<?php echo esc_attr( $target ); ?>"><?php the_post_thumbnail('full'); ?></a>
                    </div>
                    <div class="premium-blog-effect-container <?php echo 'premium-blog-'. $post_effect . '-effect'; ?>">
                        <a class="premium-blog-post-link" href="<?php the_permalink(); ?>" target="<?php echo esc_attr( $target ); ?>"></a>
                        <?php if( $settings['premium_blog_hover_color_effect'] === 'bordered' ) : ?>
                            <div class="premium-blog-bordered-border-container"></div>
                        <?php elseif( $settings['premium_blog_hover_color_effect'] === 'squares' ) : ?>
                            <div class="premium-blog-squares-square-container"></div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php if( 'cards' === $skin ) : ?>
                    <div class="premium-blog-author-thumbnail">
                        <?php echo get_avatar( get_the_author_meta( 'ID' ), 128, '', get_the_author_meta( 'display_name' ) ); ?>
                    </div>
                <?php endif; ?>
                <div <?php echo $this->get_render_attribute_string( $content_key ); ?>>
                    <div class="premium-blog-inner-container">
                        <?php if( $settings['premium_blog_post_format_icon'] === 'yes' ) : ?>
                        <div class="premium-blog-format-container">
                            <a class="premium-blog-format-link" href="<?php the_permalink(); ?>" title="<?php if( get_post_format() === ' ') : echo 'standard' ; else : echo get_post_format();  endif; ?>" target="<?php echo esc_attr( $target ); ?>"><?php $this->get_post_format_icon(); ?></a>
                        </div>
                        <?php endif; ?>
                        <div class="premium-blog-entry-container">
                            <?php
                                $this->get_post_title( $target );
                                if ( 'classic' === $skin ) {
                                    $this->get_post_meta( $target );
                                }

                            ?>

                        </div>
                    </div>

                    <?php
                        $this->get_post_content();
                        if ( 'cards' === $skin ) {
                            $this->get_post_meta( $target );
                        }
                        
                    ?>
                    
                    <?php if( $settings['premium_blog_tags_meta'] === 'yes' && has_tag() ) : ?>
                        <div class="premium-blog-post-tags-container" style="<?php if( $settings['premium_blog_post_format_icon'] !== 'yes' ) : echo 'margin-left:0px;'; endif; ?>">
                            <span class="premium-blog-post-tags">
                                <i class="fa fa-tags fa-fw"></i>
                                    <?php the_tags(' ', ', '); ?>
                            </span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    <?php }


    protected function render() {
        
        if ( get_query_var('paged') ) { 
            $paged = get_query_var('paged');
        } elseif ( get_query_var('page') ) {
            $paged = get_query_var('page');             
        } else {
            $paged = 1;
        }
        
        $settings = $this->get_settings();

        $offset = $settings['premium_blog_offset'];
        
        $post_per_page = $settings['premium_blog_number_of_posts'];
        
        $new_offset = $offset + ( ( $paged - 1 ) * $post_per_page );
        
        $post_args = premium_blog_get_post_settings( $settings );

        $posts = premium_blog_get_post_data( $post_args, $paged , $new_offset );
        
        switch( $settings['premium_blog_columns_number'] ) {
            case '100%' :
                $col_number = 'col-1';
                break;
            case '50%' :
                $col_number = 'col-2';
                break;
            case '33.33%' :
                $col_number = 'col-3';
                break;
            case '25%' :
                $col_number = 'col-4';
                break;
        }
        
        $posts_number = intval ( 100 / substr( $settings['premium_blog_columns_number'], 0, strpos( $settings['premium_blog_columns_number'], '%') ) );
        
        $carousel = 'yes' == $settings['premium_blog_carousel'] ? true : false; 
        
        $this->add_render_attribute('blog', 'class', [
            'premium-blog-wrap',
            'premium-blog-' . $settings['premium_blog_layout'],
            'premium-blog-' . $col_number
            ]
        );
        
        if ( $carousel ) {
            
            $play   = 'yes' == $settings['premium_blog_carousel_play'] ? true : false;
            $fade   = 'yes' == $settings['premium_blog_carousel_fade'] ? 'true' : 'false';
            $arrows = 'yes' == $settings['premium_blog_carousel_arrows'] ? 'true' : 'false';
            $grid   = 'yes' == $settings['premium_blog_grid'] ? 'true' : 'false';
            
            $speed  = ! empty( $settings['premium_blog_carousel_autoplay_speed'] ) ? $settings['premium_blog_carousel_autoplay_speed'] : 5000;
            $dots   = 'yes' == $settings['premium_blog_carousel_dots'] ? 'true' : 'false';
        
            $this->add_render_attribute('blog', 'data-carousel', $carousel );
            
            $this->add_render_attribute('blog', 'data-grid', $grid );
            
            $this->add_render_attribute('blog', 'data-fade', $fade );

            $this->add_render_attribute('blog', 'data-play', $play );

            $this->add_render_attribute('blog', 'data-speed', $speed );

            $this->add_render_attribute('blog', 'data-col', $posts_number );
            
            $this->add_render_attribute('blog', 'data-arrows', $arrows );
            
            $this->add_render_attribute('blog', 'data-dots', $dots );
            
        }
        
    ?>
    <div class="premium-blog">
        <?php if ( 'yes' === $settings['premium_blog_cat_tabs'] && 'yes' !== $settings['premium_blog_carousel'] ) { ?>
            <div class="premium-blog-filter">
                <ul class="premium-blog-cats-container">
                    <li>
                        <a href="javascript:;" class="category active" data-filter="*">
                            <span><?php echo __('All', 'premium-addons-for-elementor'); ?></span>
                        </a>
                    </li>
                    <?php foreach( $settings['premium_blog_categories'] as $index => $id ) {
                            $cat_list_key = 'blog_category_' . $index;

                            $name = get_cat_name( $id );

                            $name_filter = str_replace(' ', '-', $name );
                            $name_lower = strtolower( $name_filter );

                            $this->add_render_attribute( $cat_list_key,
                                'class', [
                                    'category'
                                ]
                            );
                        ?>
                            <li>
                                <a href="javascript:;" <?php echo $this->get_render_attribute_string($cat_list_key); ?> data-filter=".<?php echo esc_attr( $name_lower ); ?>"
                                   ><span><?php echo $name; ?></span>
                                </a>
                            </li>
                    <?php } ?>
                </ul>
            </div>
        <?php } ?>
        <div <?php echo $this->get_render_attribute_string('blog'); ?>>

            <?php

            if( count( $posts ) ) {
                global $post;
                foreach( $posts as $post ) {
                    setup_postdata( $post );
                    $this->get_post_layout();
                }
            ?>
        </div>
    </div>
    <?php if ( $settings['premium_blog_paging'] === 'yes' ) : ?>
        <div class="premium-blog-pagination-container">
            <?php 
            $count_posts = wp_count_posts();
            $published_posts = $count_posts->publish;

            $total_posts = ! empty ( $settings['premium_blog_total_posts_number'] ) ? $settings['premium_blog_total_posts_number'] : $published_posts;
            
            $page_tot = ceil( ( $total_posts - $offset ) / $settings['premium_blog_number_of_posts'] );
            if ( $page_tot > 1 ) {
                $big        = 999999999;
                echo paginate_links( 
                    array(
                        'base'      => str_replace( $big, '%#%',get_pagenum_link( 999999999, false ) ),
                        'format'    => '?paged=%#%',
                        'current'   => max( 1, $paged ),
                        'total'     => $page_tot,
                        'prev_next' => true,
                        'prev_text' => sprintf( "&lsaquo; %s", $settings['premium_blog_prev_text'] ),
                        'next_text' => sprintf( "%s &rsaquo;", $settings['premium_blog_next_text'] ),
                        'end_size'  => 1,
                        'mid_size'  => 2,
                        'type'      => 'list'
                    ));
                }
            ?>
        </div>
    <?php endif;
        wp_reset_postdata();   
        }
    }
}
