<?php

/**
 * A fallback when no navigation is selected by default, otherwise it throws some nasty errors in your face.
 * From required+ Foundation http://themes.required.ch and Reverie http://themefortress.com
 */
function hybrid_addons_menu_fallback() {
	echo '<div class="alert-box secondary">';
	// Translators 1: Link to Menus, 2: Link to Customize
	printf( __( 'Please assign a menu to the primary menu location under %1$s or %2$s the design.', 'reverie' ),
		sprintf(  __( '<a href="%s">Menus</a>', 'reverie' ),
			get_admin_url( get_current_blog_id(), 'nav-menus.php' )
		),
		sprintf(  __( '<a href="%s">Customize</a>', 'reverie' ),
			get_admin_url( get_current_blog_id(), 'customize.php' )
		)
	);
	echo '</div>';
}

/**
 * class required_walker
 * Custom output to enable the the ZURB Navigation style.
 * Courtesy of Kriesi.at. http://www.kriesi.at/archives/improve-your-wordpress-navigation-menu-output
 * From required+ Foundation http://themes.required.ch and Reverie http://themefortress.com
 */
class Hybrid_Addons_Walker extends Walker_Nav_Menu {

	/**
	 * Specify the item type to allow different walkers
	 * @var array
	 */
	var $nav_bar = '';

	function __construct( $nav_args = '' ) {

		$defaults = array(
			'item_type' => 'li',
			'in_top_bar' => false,
			'divider' => false,
			'divider_content' => ''
		);
		$this->nav_bar = apply_filters( 'req_nav_args', wp_parse_args( $nav_args, $defaults ) );
	}

	function display_element( $element, &$children_elements, $max_depth, $depth=0, $args, &$output ) {

        $id_field = $this->db_fields['id'];
        if ( is_object( $args[0] ) ) {
            $args[0]->has_children = ! empty( $children_elements[$element->$id_field] );
        }
        return parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
    }

	function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {

		global $wp_query;
		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

		$class_names = $value = '';

		$classes = empty( $item->classes ) ? array() : (array) $item->classes;
		$classes[] = 'menu-item-' . $item->ID;

		// Check for flyout
		$flyout_toggle = '';
		if ( $args->has_children && $this->nav_bar['item_type'] == 'li' ) {

			if ( $depth == 0 && $this->nav_bar['in_top_bar'] == false ) {

				$classes[] = 'has-flyout';
				$flyout_toggle = '<a href="#" class="flyout-toggle"><span></span></a>';

			} else if ( $this->nav_bar['in_top_bar'] == true ) {

				$classes[] = 'has-dropdown';
				$flyout_toggle = '';
			}

		}

		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

		if ( $depth > 0 ) {
			$output .= $indent . '<li id="menu-item-'. $item->ID . '"' . $value . $class_names .'>';
		} else {
			if ( $this->nav_bar['divider'] == true ) {
				$output .= $indent . ( $this->nav_bar['in_top_bar'] == true ? '<li class="divider">' . $this->nav_bar['divider_content'] . '</li>' : '' ) . '<' . $this->nav_bar['item_type'] . ' id="menu-item-'. $item->ID . '"' . $value . $class_names .'>';
			} else {
				$output .= $indent . '<' . $this->nav_bar['item_type'] . ' id="menu-item-'. $item->ID . '"' . $value . $class_names .'>';
			}
		}

		$attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
		$attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
		$attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
		$attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';

		$item_output  = $args->before;
		$item_output .= '<a'. $attributes .'>';
		$item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
		$item_output .= '</a>';
		$item_output .= $flyout_toggle; // Add possible flyout toggle
		$item_output .= $args->after;

		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}

	function end_el( &$output, $item, $depth = 0, $args = array() ) {

		if ( $depth > 0 ) {
			$output .= "</li>\n";
		} else {
			$output .= "</" . $this->nav_bar['item_type'] . ">\n";
		}
	}

	function start_lvl( &$output, $depth = 0, $args = array() ) {

		if ( $depth == 0 && $this->nav_bar['item_type'] == 'li' ) {
			$indent = str_repeat("\t", 1);
    		$output .= $this->nav_bar['in_top_bar'] == true ? "\n$indent<ul class=\"dropdown\">\n" : "\n$indent<ul class=\"flyout\">\n";
    	} else {
			$indent = str_repeat("\t", $depth);
    		$output .= $this->nav_bar['in_top_bar'] == true ? "\n$indent<ul class=\"dropdown\">\n" : "\n$indent<ul class=\"level-$depth\">\n";
		}
  	}
}

?>