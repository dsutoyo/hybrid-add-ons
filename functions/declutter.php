<?php

/**
 * Cleaner menu code
 *
 * @since 0.1.1
 */
function remix_nav_menu_css_class( $classes ) {
    $matches = 0;
    foreach ( $classes as $class ) {
        $int = preg_match( '/current-menu-item/', $class );
        $matches = $matches + $int;
    }
    $classes = array( 'menu-item' );
    if ( $matches > 0 ) {
        $classes[] = 'current-menu-item';
    }
    return $classes;
}

function remix_nav_menu_item_id( $id, $item, $args ) {
    $id = 'menu-' . $item->post_name;
    return $id;
}

// Class cleanup
add_filter( 'nav_menu_css_class', 'remix_nav_menu_css_class', 1, 1 );
add_filter( 'nav_menu_item_id', 'remix_nav_menu_item_id', 1, 3 );

?>