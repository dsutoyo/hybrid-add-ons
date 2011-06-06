<?php

add_action( 'template_redirect', 'remix_custom_css' );
add_filter( 'query_vars', 'remix_add_new_query_var' );

function remix_add_new_query_var( $public_query_vars ) {
    $prefix = hybrid_get_prefix();
    $public_query_vars[] = $prefix . '-custom-content';

    return $public_query_vars;
}

function remix_custom_css() {
    $prefix = hybrid_get_prefix();
    $css = get_query_var( $prefix . '-custom-content');
    if ($css == 'css'){
        header( 'Content-Type: text/css' );
        include_once ( trailingslashit( TEMPLATEPATH ) . 'custom-style.php');
        exit;  //This stops WP from loading any further
    }
}

?>