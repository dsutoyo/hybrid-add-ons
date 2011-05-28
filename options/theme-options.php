<?php

global $the_theme_options;
global $the_theme_admin_options_hook;

// Initialize Theme options
add_action( 'after_setup_theme', 'remix_options_init', 9 );

/**
 * Theme Options Init
 *
 * @since Remix 0.1.0
 */
function remix_options_init() {
  
    $prefix = hybrid_get_prefix();
    
    // set options equal to defaults
    global $the_theme_options;
    $the_theme_options = get_option( $prefix . '_theme_options' );
    if ( false === $the_theme_options ) {
        $the_theme_options = remix_get_default_options();
    }
    update_option( $prefix . '_theme_options', $the_theme_options );

    add_action('admin_menu', 'remix_menu_options');
    add_action('admin_init', 'remix_register_options');
   	add_action('admin_print_styles-appearance_page_' . $prefix . '_settings', 'remix_enqueue_admin_style', 11 );
   	add_action('admin_print_scripts-appearance_page_' . $prefix . '_settings', 'remix_enqueue_admin_script', 11 );
    
}

/**
 * Return the default option settings
 *
 * @since Remix 0.1.0
 */
function remix_get_default_options() {
    $options = array(
        'footer_insert' => '[the-year]',
    );
    $options = apply_filters( 'default_options', $options );
    return $options;
}

/**
 * Add options page to the Appearance Menu
 *
 * @since Remix 0.1.0
 */
function remix_menu_options() {
    $prefix = hybrid_get_prefix();
    $data = get_theme_data( trailingslashit( STYLESHEETPATH ) . 'style.css' );
	  add_theme_page($data['Name'] . ' Options', $data['Name'] . ' Options', 'edit_theme_options', $prefix . '_settings', 'remix_admin_options_page');
}


/**
 * Display the options page
 *
 * @since Remix 0.1.0
 */
function remix_admin_options_page() {
    $prefix = hybrid_get_prefix();
    $data = get_theme_data( trailingslashit( STYLESHEETPATH ) . 'style.css' );
    $theme_name = $data['Name'];
    
    if ( isset( $_GET['settings-updated'] ) ) {
  			echo "<div class='updated'><p>Theme settings updated successfully.</p></div>";
	  } ?>
    <div id="<?php echo $prefix; ?>_options" class="wrap">
        <div id="header">
            <h1><?php echo $data['Name']; ?></h1>
        </div>
        <form id="options_tabs" method="post" action="options.php" enctype="multipart/form-data">
            <ul class="clearfix">
                <li><a href="#<?php echo $prefix; ?>_options_general">General</a></li>
                <li><a href="#<?php echo $prefix; ?>_options_styles">Fonts and Colors</a></li>
            </ul>

        		<?php settings_fields($prefix.'_theme_options'); ?>
      			<div id="<?php echo $prefix; ?>_options_general">
      			<?php //remix_do_settings_sections($data['Name'], $prefix.'_options_general'); ?>
      			<?php //remix_do_settings_sections($data['Name'], $prefix.'_options_section_slideshow'); ?>
      			</div>
      			<div id="<?php echo $prefix; ?>_options_styles">
      			<?php remix_do_settings_sections($data['Name'], $prefix.'_options_styles'); ?>
      			</div>
        		<div class="footer">
        			<input name="<?php echo $prefix.'_theme_options[submit]' ?>" type="submit" value="<?php esc_attr_e('Save Settings', hybrid_get_textdomain()); ?>" />
        			<input name="<?php echo $prefix.'_theme_options[reset]' ?>" type="submit" value="<?php esc_attr_e('Reset Defaults', hybrid_get_textdomain()); ?>" />
        		</div>
        </form>
		</div>
<?php }

/**
 * Register Options
 *
 * @since Remix 0.1.0
 */
function remix_register_options(){
	  require( trailingslashit( HYBRID_ADDONS ) . 'options/register.php' );
	  require( trailingslashit( HYBRID_ADDONS ) . 'options/field-checkbox.php' );
    require( trailingslashit( HYBRID_ADDONS ) . 'options/field-textarea.php' );
    require( trailingslashit( HYBRID_ADDONS ) . 'options/field-text.php' );
    require( trailingslashit( HYBRID_ADDONS ) . 'options/field-select.php' );
    require( trailingslashit( HYBRID_ADDONS ) . 'options/field-colorpicker.php' );
}

/**
 * Register and enqueue styles
 *
 * @since Remix 0.1.0
 */
function remix_enqueue_admin_style() {
    $prefix = hybrid_get_prefix();
    wp_enqueue_style( 'thickbox' );
	  wp_enqueue_style( 'theme-options', trailingslashit( HYBRID_ALT_ADMIN_CSS ) . 'options.css' );
}


/**
 * Register and enqueue scripts
 *
 * @since Remix 0.1.0
 */
function remix_enqueue_admin_script() {
    $prefix = hybrid_get_prefix();
    wp_deregister_script( 'colorpicker' );
    
    wp_enqueue_script( 'media-upload' );
    wp_enqueue_script( 'thickbox' );
   	wp_enqueue_script( $prefix . '_jquery_ui', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.10/jquery-ui.min.js' );
    wp_enqueue_script( $prefix . '_jquery_colorpicker', trailingslashit( HYBRID_ALT_ADMIN_JS ) . 'jquery.color.picker.js' );
    wp_enqueue_script( 'formalize', trailingslashit( HYBRID_ALT_ADMIN_JS ) . 'jquery.formalize.js' );
    wp_enqueue_script( 'admin_custom', trailingslashit( HYBRID_ALT_ADMIN_JS ) . 'custom.js' );
}

/**
 * Displays the label for each settings option
 *
 * @since Remix 0.1.0
 */
function remix_options_label_for($var, $txt, $class = '') {
    $prefix = hybrid_get_prefix();
    if ($class == '') { 
        echo '<label for="'.$prefix.'_theme_options['.$var.']">'.$txt.'</label>';
    } else {
        echo '<label for="'.$prefix.'_theme_options['.$var.']" class="'.$class.'">'.$txt.'</label>';
    }
} 


/**
 * Registers each setting section and field using standard functions
 *
 * @since Remix 0.1.0
 * @uses add_settings_section()
 * @uses add_settings_field()
 */
function remix_build_options_section($section_args, $field_args) {
    $prefix = hybrid_get_prefix();
    $data = get_theme_data( trailingslashit( STYLESHEETPATH ) . 'style.css' );
    $theme_name = $data['Name'];
    
    add_settings_section($prefix.'_'.$section_args['section_id'], $section_args['section_title'], $prefix.'_options_styles_section_text', $theme_name);
    foreach ($field_args as $key => $args) {
        // auto generate these array items
        $args['label_for'] = $prefix.'_theme_options['.$key.']';
        $args['field_name'] = $key;
        $args['div_for'] = 'options_'.$args['field_type'];
        
        add_settings_field($prefix.'_options_'.$key, $args['field_title'], 'remix_build_options_field', $theme_name, $prefix.'_'.$section_args['section_id'], $args);
    }
}

/**
 * Displays each individual option setting fields using prebuilt functions
 *
 * @since Remix 0.1.0
 */
function remix_build_options_field($args) {
    if ( $args['field_type'] == 'colorpicker' ) {
        echo '<div class="element">';
        remix_options_colorpicker( $args['field_name'] );
        echo '</div>';
    } elseif ( $args['field_type'] == 'select' ) {
      	echo '<div class="element">';
      	remix_options_select( $args['field_name'], $args['field_array']);
      	echo '</div>';
    } elseif ( $args['field_type'] == 'textarea' ) {
        echo '<div class="element">';
        remix_options_textarea( $args['field_name'] );
        echo '</div>';
    }
    echo '<span class="description">'.$args['field_description'].'</span>';
}

/**
 * Replaces do_settings_sections, because we don't want to use tables
 *
 * @since Remix 0.1.0
 */
function remix_do_settings_sections($page, $section_id) {
  	global $wp_settings_sections, $wp_settings_fields;

  	if ( !isset($wp_settings_sections) || !isset($wp_settings_sections[$page]) )
  		return;
  	$section = $wp_settings_sections[$page][$section_id];
    echo '<div class="section clearfix">';
  	echo "<h3>{$section['title']}</h3>\n";
  	call_user_func($section['callback'], $section);
  	if ( !isset($wp_settings_fields) || !isset($wp_settings_fields[$page]) || !isset($wp_settings_fields[$page][$section['id']]) )
  		continue;
  	remix_do_settings_fields($page, $section_id);
  	echo "</div>";
}

/**
 * Replaces do_settings_fields, because we don't want to use tables
 *
 * @since Remix 0.1.0
 */
function remix_do_settings_fields($page, $section) {
  	global $wp_settings_fields;

  	if ( !isset($wp_settings_fields) || !isset($wp_settings_fields[$page]) || !isset($wp_settings_fields[$page][$section]) )
  		return;

  	foreach ( (array) $wp_settings_fields[$page][$section] as $field ) {
  	  if ( !empty($field['args']['div_for']) ) {
  	    echo '<div class="options clearfix ' . $field['args']['div_for'] . '">';
  	  } else {
  	    echo '<div class="options clearfix">';
  	  }
  		if ( !empty($field['args']['label_for']) )
  			echo '<label for="' . $field['args']['label_for'] . '">' . $field['title'] . '</label>';
  		else
  			echo '<h4>' . $field['title'] . '</h4>';
  		call_user_func($field['callback'], $field['args']);
      echo '</div>';
  	}
}


?>