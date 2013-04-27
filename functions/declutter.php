<?php

/**
 * Cleaner menu code
 *
 * @since 0.1.1
 */

// Class cleanup
add_filter( 'nav_menu_css_class', 'hybrid_addons_nav_menu_css_class', 1, 1 );
add_filter( 'nav_menu_item_id', 'hybrid_addons_nav_menu_item_id', 1, 3 );
//add_filter( 'walker_nav_menu_start_el', 'hybrid_addons_walker_nav_menu_item_id', 1, 4 );

/*
 *
 * I just want these classes: menu-item, current-menu-item, and active
 *
 */
function hybrid_addons_nav_menu_css_class( $classes ) {
	$active_matches = 0;
	$dropdown_matches = 0;
	
	// check all menu items for 'current-menu-item'
	foreach ( $classes as $class ) {
		$int = preg_match( '/current-menu-item/', $class );
		$active_matches = $active_matches + $int;
	}

	// check all menu items for 'has-dropdown'
	foreach ( $classes as $class ) {
		$int = preg_match( '/has-dropdown/', $class );
		$dropdown_matches = $dropdown_matches + $int;
	}

	$classes = array( 'menu-item' );

	if ( $active_matches > 0 ) {
		$classes[] = 'current-menu-item';
		$classes[] = 'active';
	}
	if ( $dropdown_matches > 0 ) {
		$classes[] = 'has-dropdown';
	}
	return $classes;
}

/*
 * What's more useful than all those classes? An id that matches my menu item name!
 *
 */
function hybrid_addons_nav_menu_item_id( $id, $item, $args ) {
	$id = 'menu-' . $item->post_name;
	return $id;
}

function hybrid_addons_walker_nav_menu_item_id( $item_output, $item, $depth, $args ) {
	print_r($item);
}

?>