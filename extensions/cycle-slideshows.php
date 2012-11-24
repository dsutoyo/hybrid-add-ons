<?php
/**
 * Cycle Slideshows - A script to set up slideshows for use with jQuery Cycle.
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU 
 * General Public License version 2, as published by the Free Software Foundation.  You may NOT assume 
 * that you can use any other version of the GPL.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without 
 * even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @package CycleSlideshows
 * @version 0.1.0
 * @author David Sutoyo
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */
 
$prefix = hybrid_get_prefix();

add_action( 'init', 'slideshow_register_taxonomies' );
add_action( 'wp_enqueue_scripts', 'slideshow_enqueue_script' );

// add slideshow post type
add_post_type_support( 'slideshow_slides', array( hybrid_get_prefix() . '-post-settings', 'entry-views' ) );

// add slideshow post meta boxes
add_filter( "{$prefix}_slideshow_slides_meta_box_args", 'slideshow_meta_box' );


function slideshow_register_taxonomies() {
	$labels_for_slides = array(
		'name' => _x('Slideshow', 'post type general name'),
		'singular_name' => _x('Slide', 'post type singular name'),
		'add_new' => _x('Add New', 'Slide'),
		'add_new_item' => __('Add New Slide'),
		'edit_item' => __('Edit Slide'),
		'new_item' => __('New Slide'),
		'view_item' => __('View Slide'),
		'search_items' => __('Search Slides'),
		'not_found' =>  __('No slides found'),
		'not_found_in_trash' => __('No slides found in Trash'), 
		'parent_item_colon' => '',
		'menu_name' => 'Slideshow'      
	);
	
	register_post_type('slideshow_slides', array(
		'labels' => $labels_for_slides,
		'public' => true,
		'show_ui' => true,
		'menu_position' => 5,
		'capability_type' => 'post',
		'hierarchical' => false,
		'rewrite' => array('slug' => 'slideshow'),
		'query_var' => false,
		'supports' => array('title','thumbnail'),
		'show_in_nav_menus' => 'false',
	));
}

function slideshow_meta_box( $metabox ) {
	$metabox = array();
	$metabox['slide_caption'] = array( 'name' => 'slide_caption', 'title' => sprintf( __( 'Slide Caption', hybrid_get_textdomain() ) ), 'type' => 'text' );
	$metabox['hide_title'] = array( 'name' => 'hide_title', 'title' => sprintf( __( 'Hide Title', hybrid_get_textdomain() ) ), 'type' => 'checkbox' );
	$metabox['hide_caption'] = array( 'name' => 'hide_caption', 'title' => sprintf( __( 'Hide Captions', hybrid_get_textdomain() ) ), 'type' => 'checkbox' );
	return $metabox;
}

function slideshow_enqueue_script() {
	wp_enqueue_script('cycle', trailingslashit( HYBRID_ALT_ADMIN_JS ) . 'jquery.orbit.min.js', array('jquery'));
}

function get_the_slideshow() {
	$prefix = hybrid_get_prefix();
	
	$args = array(
		'post_type' => 'slideshow_slides',
		'posts_per_page' => 99,
	);
	
	$slides = new WP_Query( $args );

	if ( $slides->have_posts() ) {
	  
		$iteration = 1;
		
		if ( $slides->post_count == 1 ) {
			$before_slides = '<div id="single-slide">';
			$after_slides = '</div>';
		} else {
			$before_slides = '<div id="slides-container" class="clearfix"><div id="slideshow">';
			$after_slides = '</div></div>';
		}
		
		echo $before_slides;
		
		while ( $slides->have_posts() ) {
			$slides->the_post();
			$meta_caption = get_post_meta($slides->post->ID, 'slide_caption', true);
			$hide_caption = get_post_meta($slides->post->ID, 'hide_caption', true);
			$hide_title = get_post_meta($slides->post->ID, 'hide_title', true);

			echo '<div>';
			
			if ( has_post_thumbnail() ) {
				  if ( $meta_caption ) {
					  $attr = array(
						  'alt' => "HTML Captions",
							'title'	=> get_the_title(),
					);
				} else {
					  $attr = array(
							'title'	=> get_the_title(),
					);
				}
			  the_post_thumbnail( 'slideshow', $attr );
			} // if has_post_thumbnail 
			
			if ( $meta_caption && !$hide_caption || !$hide_title ) { ?>
				<div class="slide-caption">
					<?php if ( !$hide_title ) : ?>
						<h3><?php the_title(); ?></h3>
					<?php endif; ?>
					<?php if ( !$hide_caption ) : ?>
						<p><?php echo $meta_caption; ?></p>
					<?php endif; ?>
				</div>
			<?php
			}
	
			echo '</div>'; // end containing div
				
			  $iteration++;
		}
		
		echo $after_slides;
		
	} // end the_query loop
	wp_reset_postdata();
}

?>