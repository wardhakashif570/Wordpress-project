<?php 

if ( ! defined( 'ABSPATH' ) ) exit;

function premium_blog_get_post_data( $args, $paged, $new_offset ) {
    
    $defaults = array(
        'author'            => '',
        'category'          => '',
        'orderby'           => '',
        'posts_per_page'    => 1,
        'paged'             => $paged,
        'offset'            => $new_offset,
    );
    
    $atts = wp_parse_args( $args, $defaults );
    
    $posts = get_posts( $atts );
    
    return $posts;
}

function premium_blog_get_post_settings( $settings ) {
    
        $authors = $settings['premium_blog_users'];
        
        if( ! empty( $authors ) ) {
            $post_args['author'] = implode(',', $authors);
        }
        
        $post_args['category'] = $settings['premium_blog_categories'];
        
        $post_args['tag__in'] = $settings['premium_blog_tags'];
        
        $post_args['post__not_in']  = $settings['premium_blog_posts_exclude'];
        
        $post_args['order'] = $settings['premium_blog_order'];
        
        $post_args['orderby'] = $settings['premium_blog_order_by'];
        
		$post_args['posts_per_page'] = $settings['premium_blog_number_of_posts'];
        
        return $post_args;
} 

function premium_addons_get_excerpt_by_id( $post_id, $excerpt_length, $excerpt_type, $exceprt_text, $excerpt_src ) {
    
    $the_post = get_post( $post_id );

    $the_excerpt = null;
    
    if ( $the_post ) {
        $the_excerpt = ( $excerpt_src ) ? $the_post->post_content : $the_post->post_excerpt;
    }

    $the_excerpt = strip_tags( strip_shortcodes( $the_excerpt ) );
    
    $words = explode( ' ', $the_excerpt, $excerpt_length + 1 );

     if( count( $words ) > $excerpt_length ) :
         array_pop( $words );
         if( 'dots' == $excerpt_type ) {
            array_push( $words, '…' );
         } else {
            array_push( $words, ' <a href="' . get_permalink( $post_id ) .'" class="premium-blog-excerpt-link">' . $exceprt_text . '</a>' ); 
         }
         
         $the_excerpt = implode( ' ', $words );
     endif;

     return $the_excerpt;
     
}

function premium_addons_post_type_categories() {
    $terms = get_terms(
        array( 
            'taxonomy' => 'category',
            'hide_empty' => true,
        )
    );
    
    $options = array();
    
    if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){
        foreach ( $terms as $term ) {
            $options[ $term->term_id ] = $term->name;
        }
    }
    
    return $options;
}

function premium_addons_post_type_users() {
    $users = get_users();
    
    $options = array();
    
    if ( ! empty( $users ) && ! is_wp_error( $users ) ){
        foreach ( $users as $user ) {
            if( $user->display_name !== 'wp_update_service' ) {
                $options[ $user->ID ] = $user->display_name;
            }
        }
    }
    
    return $options;
}

function premium_addons_post_type_tags() {
    $tags = get_tags();
    
    $options = array();
    
    if ( ! empty( $tags ) && ! is_wp_error( $tags ) ){
        foreach ( $tags as $tag ) {
            $options[ $tag->term_id ] = $tag->name;
        }
    }
    
    return $options;
}
function premium_addons_posts_list() {
    $list = get_posts( array(
        'post_type'         => 'post',
        'posts_per_page'    => -1,
    ) );

    $options = array();

    if ( ! empty( $list ) && ! is_wp_error( $list ) ) {
        foreach ( $list as $post ) {
           $options[ $post->ID ] = $post->post_title;
        }
    }

    return $options;
}