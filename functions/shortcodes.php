<?php

/* Register shortcodes. */
add_action( 'init', 'hybrid_addons_add_shortcodes' );

/**
 * Creates new shortcodes for use in any shortcode-ready area.  This function uses the add_shortcode() 
 * function to register new shortcodes with WordPress.
 *
 * @since 0.1.0
 * @uses add_shortcode() to create new shortcodes.
 * @uses add_filter() to allow shortcodes in widgets.
 * @link http://codex.wordpress.org/Shortcode_API
 */
function hybrid_addons_add_shortcodes() {
  
	add_shortcode( 'sidebar', 'hybrid_addons_get_sidebar' );
	add_shortcode( 'widget','hybrid_addons_widget' );

	add_shortcode( 'site-title','hybrid_addons_site_title' );
	add_shortcode( 'rss','hybrid_addons_subscribe_rss' );
	
	// Allow [SHORTCODES] in Widgets
	add_filter( 'widget_text', 'do_shortcode' );
}

/**
 * Shortcode to display the site title without a link.
 *
 * @since 0.1.0
 * @uses get_bloginfo() Gets information about the install.
 */
function hybrid_addons_site_title(){
    return '<span class="site-title">' . get_bloginfo('name') . '</span>';
}

/**
 * Shortcode to display the RSS feed link.
 *
 * @since 0.1.0
 * @uses get_bloginfo() Gets information about the install.
 */
function hybrid_addons_subscribe_rss( $atts ){
    extract(shortcode_atts(array(
        'text' => __( 'RSS Feed', hybrid_get_textdomain() ),
    ), $atts));
    
    return '<a class="rss-subscribe" href="'. get_bloginfo('rss2_url') .'" title="'. __('RSS Feeds', hybrid_get_textdomain()) .'">'. $text .'</a>';
}

/**
 * Shortcode to display a specific widget.
 *
 * @since 0.1.0
 * @uses the_widget() Displays a widget
 */
function hybrid_addons_widget( $atts ) {    
    global $wp_widget_factory;

    extract(shortcode_atts(array(
        'widget_name' => '',
        'instance' => '',
        'id' => ''
    ), $atts));
    
    // Put '&' characters back in
    $instance = str_ireplace("&#038;", '&' ,$instance);

    $widget_name = esc_html($widget_name);
    
    // Make sure our widget is registered
    if ( !is_a($wp_widget_factory->widgets[$widget_name], 'WP_Widget') ):
        $wp_class = 'WP_Widget_'.ucwords(strtolower($class));
        
        if ( !is_a($wp_widget_factory->widgets[$wp_class], 'WP_Widget') ):
            return '<p>'.sprintf(__("%s: Widget class not found. Make sure this widget exists and the class name is correct"),'<strong>'.$class.'</strong>').'</p>';
        else:
            $class = $wp_class;
        endif;
    endif;
    
    ob_start();
    
    $classname = $wp_widget_factory->widgets[$widget_name]->widget_options['classname'];
    $id = $wp_widget_factory->widgets[$widget_name]->id;
    $before_widget = sprintf('<div id="%1$s" class="widget %2$s widget-%2$s">', $id, $classname);

    // put the arguments into an array, prepping for filter
    $widget_layout_args = array(
    		'before_widget' => $before_widget . '<div class="widget-wrap widget-inside">',
    		'after_widget' => '</div></div>',
    		'before_title' => '<h3 class="widget-title">',
    		'after_title' => '</h3>'
    );

    the_widget($widget_name, $instance, $widget_layout_args);
    $output = ob_get_contents();
    
    ob_end_clean();
    
    return $output;
}


/**
 * Function equivalent of the widget shortcode.
 *
 * @since 0.1.0
 * @uses the_widget() Displays a widget
 */
function hybrid_addons_get_widget( $widget_name, $instance, $id ) {
    
    global $wp_widget_factory;
    
    $widget_name = esc_html($widget_name);
    
    // Make sure our widget is registered
    if ( !is_a($wp_widget_factory->widgets[$widget_name], 'WP_Widget') ):
        $wp_class = 'WP_Widget_'.ucwords(strtolower($class));
        
        if ( !is_a($wp_widget_factory->widgets[$wp_class], 'WP_Widget') ):
            return '<p>'.sprintf(__("%s: Widget class not found. Make sure this widget exists and the class name is correct"),'<strong>'.$class.'</strong>').'</p>';
        else:
            $class = $wp_class;
        endif;
    endif;
    
    ob_start();
    
    $classname = $wp_widget_factory->widgets[$widget_name]->widget_options['classname'];
    $id = $wp_widget_factory->widgets[$widget_name]->id;
    $before_widget = sprintf('<div id="%1$s" class="widget %2$s widget-%2$s">', $id, $classname);

    // put the arguments into an array, prepping for filter
    $widget_layout_args = array(
		'before_widget' => $before_widget . '<div class="widget-wrap widget-inside">',
		'after_widget' => '</div></div>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>'
    );

    the_widget($widget_name, $instance, $widget_layout_args);
    $output = ob_get_contents();
    
    ob_end_clean();
    
    return $output;
}

/**
 * Shortcode to display a specific widget area.
 *
 * @since 0.1.0
 * @uses wp_get_sidebars_widgets() Retrieve full list of sidebars and their widgets.
 * @uses hybrid_addons_get_widget_settings() Returns settings of a widget instance
 */
function hybrid_addons_get_sidebar( $atts ) {
    global $wp_registered_widgets;
    
    extract(shortcode_atts(array(
        'id' => 'primary'
    ), $atts));
    
    // Get the widgets associated with each sidebar
    $sidebars_widgets = wp_get_sidebars_widgets();
    
    $output = '';
    
    if ( $sidebars_widgets ) {
        // display widgets active within our widget area
        foreach ( $sidebars_widgets[$id] as $widget ) {        
			// find the active widgets for the sidebar
			$wp_registered_widgets[$widget];

			$the_widget = $wp_registered_widgets[$widget]['callback'][0];

			$widget_name = get_class($the_widget);
			$id = $wp_registered_widgets[$widget]['params'][0]['number'];
			$instance_array = hybrid_addons_get_widget_settings($the_widget);

			$instance = array();

			foreach ( $instance_array[$id] as $key => $value ) {
			    $instance[] = $key . '=' . $value;
			}
			$instance_str = implode('&', $instance);

			$output .= hybrid_addons_get_widget( $widget_name, $instance_array[$id], $id );
        }
    }
    
    return $output;
}

/**
 * Returns settings about a specific instance of a widget.
 *
 * @since 0.1.0
 */
function hybrid_addons_get_widget_settings($widget) {
	$settings = get_option($widget->option_name);

	if ( false === $settings && isset($widget->alt_option_name) )
		$settings = get_option($widget->alt_option_name);

	if ( !is_array($settings) )
		$settings = array();

	if ( !array_key_exists('_multiwidget', $settings) ) {
		// old format, conver if single widget
		$settings = wp_convert_widget_settings($widget->id_base, $widget->option_name, $settings);
	}

	unset($settings['_multiwidget'], $settings['__i__']);
	return $settings;
}

?>