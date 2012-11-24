<?php
/**
 * Remix - An add-on library for the Hybrid WordPress theme development framework.
 *
 * Remix is a collection of scripts intended for use with the Hybrid framework. Although it is
 * used with Hybrid, it is not affiliated in any way with Hybrid or Justin Tadlock. Remix provides some
 * slideshow scripts, extra widgets, as well as an alternative interface/back-end for theme settings.
 * It also makes use of WPAlchemy to generate extra meta boxes.
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU 
 * General Public License version 2, as published by the Free Software Foundation.  You may NOT assume 
 * that you can use any other version of the GPL.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without 
 * even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * You should have received a copy of the GNU General Public License along with this program; if not, write 
 * to the Free Software Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
 *
 * @package Remix
 * @version 0.1.0
 * @author David Sutoyo
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

// run actions to set up remix options and widgets 	
add_action( 'after_setup_theme', 'remix_init', 14 );
add_action( 'widgets_init', 'remix_register_widgets' );
		
function remix_init() {
/* Sets the path to the core framework extensions directory. */
	define( 'HYBRID_ADDONS', trailingslashit( HYBRID_DIR ) . 'add-ons' );
	
	define( 'HYBRID_ALT_ADMIN_JS', trailingslashit( HYBRID_URI ) . 'add-ons/assets/javascripts' );
	
	define( 'HYBRID_ALT_ADMIN_CSS', trailingslashit( HYBRID_URI ) . 'add-ons/assets/stylesheets' );
	
	define( 'HYBRID_ALT_ADMIN_IMAGES', trailingslashit( HYBRID_URI ) . 'add-ons/assets/images' );
  
	require_if_theme_supports( 'alt-theme-options', trailingslashit( HYBRID_ADDONS ) . 'options/theme-options.php' );
  
	require_if_theme_supports( 'orbit-slideshows', trailingslashit( HYBRID_ADDONS ) . 'extensions/orbit-slideshows.php' );
	
	require_if_theme_supports( 'cycle-slideshows', trailingslashit( HYBRID_ADDONS ) . 'extensions/cycle-slideshows.php' );
	
	/* Load the shortcodes if supported. */
		require_if_theme_supports( 'hybrid-core-shortcodes', trailingslashit( HYBRID_ADDONS ) . 'functions/shortcodes.php' );
		
		/* Load the post meta box if supported. */
		require_if_theme_supports( 'hybrid-core-post-meta-box', trailingslashit( HYBRID_ADDONS ) . 'admin/post-meta-box.php' );
	
}

function remix_register_widgets() {
	if ( current_theme_supports( 'hybrid-core-widgets' ) ) :
		/* Load the core framework widget files. */
		require_once( trailingslashit( HYBRID_ADDONS ) . 'classes/widget-blog.php' );
		require_once( trailingslashit( HYBRID_ADDONS ) . 'classes/widget-flickr.php' );
		require_once( trailingslashit( HYBRID_ADDONS ) . 'classes/widget-twitter.php' );

		/* Register each of the core framework widgets. */
		register_widget( 'Remix_Widget_Blog' );
		register_widget( 'Remix_Widget_Flickr' );
		register_widget( 'Remix_Widget_Twitter' );
	endif;
}


/**
 * Loads the theme options once and allows the input of the specific field the user would 
 * like to show. Theme settings are added with 'autoload' set to 'yes', so the settings are 
 * only loaded once on each page load. USE ONLY WITH THE REMIX THEME OPTIONS ADD-ON.
 *
 * @since 0.1.0
 * @uses get_option() Gets an option from the database.
 * @uses hybrid_get_prefix() Gets the prefix of the theme.
 * @global object $hybrid The global Hybrid object.
 * @global array $hybrid_settings Deprecated. Developers should use hybrid_get_setting().
 * @param string $option The specific theme setting the user wants.
 * @return string|int|array $settings[$option] Specific setting asked for.
 */
function hybrid_get_option( $option = '' ) {
	global $hybrid, $hybrid_settings;

	if ( !$option )
		return false;

	if ( !isset( $hybrid->settings ) )
		$hybrid->settings = $hybrid_settings = get_option( hybrid_get_prefix() . '_theme_options' );

	if ( !is_array( $hybrid->settings ) || empty( $hybrid->settings[$option] ) )
		return false;

	if ( is_array( $hybrid->settings[$option] ) )
		return $hybrid->settings[$option];
	else
		return wp_kses_stripslashes( $hybrid->settings[$option] );
}

function remix_get_featured_media( $id, $args = '' ) {
	
	$prefix = hybrid_get_prefix();
	
	$embed_defaults = wp_embed_defaults();
	
	$defaults = array(
		'size' => '',
		'width' => $embed_defaults['width'],
		'height' => round($embed_defaults['width'] * 9 / 16),
		'ratio' => '',
	);
	
	$args = wp_parse_args( $args, $defaults );

	$use_registered_sizes = false;

	switch ( $args['size'] ) :
		case 'thumbnail' :
			$args['width'] = get_option( 'thumbnail_size_w' );
			$args['height'] = get_option( 'thumbnail_size_h' );
			$use_registered_sizes = true;
			break;
		case 'medium' :
			$args['width'] = get_option( 'medium_size_w' );
			$args['height'] = get_option( 'medium_size_h' );
			$use_registered_sizes = true;
			break;
		case 'large' :
			$args['width'] = get_option( 'large_size_w' );
			$args['height'] = get_option( 'large_size_h' );
			$use_registered_sizes = true;
			break;
		case '' :
			break;
	endswitch;
	
	if ( $args['ratio'] != '' ) {
		$aspect_ratio = split('/', $args['ratio']);
		$args['height'] = $args['width'] * $aspect_ratio[0] / $aspect_ratio[1];
	}

	if ( has_post_thumbnail( $id ) ) {
	  
		if ( $use_registered_sizes ) {
			return get_the_post_thumbnail( $id, $args['size'] );
		} else {
			return get_the_post_thumbnail( $id, array( $args['width'], $args['height'] ) );
		}

	} elseif ( get_post_meta( $id, 'featured_video' ) ) {

		$data = get_post_meta( $id, 'featured_video', true );
		
		$media = '';
		
		if ( preg_match("/youtube\.com\/watch/i", $data) ) {
					list($domain, $video_id) = split("v=", $data);
					$video_id = esc_attr($video_id);	
			$media .= '<iframe width=' . esc_attr( $args['width'] ) . ' height=' . esc_attr( $args['height'] ) . "src=\"http://www.youtube.com/embed/" . $video_id . "\" frameborder=\"0\" allowfullscreen></iframe>";
			} elseif ( preg_match("/vimeo\.com\/[0-9]+/i", $data) ) {
					list($domain, $video_id) = split(".com/", $data);
					$video_id = esc_attr($video_id);
			$media .= "<iframe src=\"http://player.vimeo.com/video/" . $video_id . '?portrait=0" width="' . esc_attr( $args['width'] ) . '" height="' . $args['height'] . '"></iframe>';
		  
		}
		return $media;
	}
	
}

?>