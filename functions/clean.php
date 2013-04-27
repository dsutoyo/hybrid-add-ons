<?php

// launching operation cleanup
add_action( 'init', 'hybrid_addons_cleaner_wp_head' );

// remove WP version from RSS
add_filter( 'the_generator', 'hybrid_addons_remove_wp_version_from_rss' );

/*
 * Clean up wp_head()
 *
 * http://themefortress.com/
 **/
function hybrid_addons_cleaner_wp_head() {
	// EditURI link
	remove_action( 'wp_head', 'rsd_link' );
	// windows live writer
	remove_action( 'wp_head', 'wlwmanifest_link' );
	// index link
	remove_action( 'wp_head', 'index_rel_link' );
	// previous link
	remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 );
	// start link
	remove_action( 'wp_head', 'start_post_rel_link', 10, 0 );
	// links for adjacent posts
	remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );
	// WP version
	remove_action( 'wp_head', 'wp_generator' );
	// remove WP version from css
	//add_filter( 'style_loader_src', 'hybrid_addons_remove_wp_version_from_css_js', 9999 );
	// remove WP version from scripts
	//add_filter( 'script_loader_src', 'remove_wp_version_from_css_js', 9999 );
}

function hybrid_addons_remove_wp_version_from_rss() {
	return '';
}

// remove WP version from scripts
function hybrid_addons_remove_wp_version_from_css_js( $src ) {
	if ( strpos( $src, 'ver=' ) )
		$src = remove_query_arg( 'ver', $src );
	return $src;
}