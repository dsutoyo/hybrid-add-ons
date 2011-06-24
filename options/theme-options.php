<?php

global $the_theme_options;
global $media_upload_ids;

// Initialize Theme options
add_action( 'after_setup_theme', 'remix_options_init', 9 );

/**
 * Theme Options Init
 *
 * @since Remix 0.1.0
 */
function remix_options_init() {
    global $the_theme_options;
  
    $prefix = hybrid_get_prefix();
    
    // set options equal to defaults
    $the_theme_options = get_option( $prefix . '_theme_options' );
    if ( false === $the_theme_options ) {
        $the_theme_options = remix_get_default_options();
    }
    update_option( $prefix . '_theme_options', $the_theme_options );
    
    add_action( 'admin_init', 'remix_register_options' ); 
    add_action( 'admin_menu', 'remix_menu_options' );
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
    $icon = trailingslashit( HYBRID_ALT_ADMIN_IMAGES ) . 'th-icon.png';
	  //add_theme_page($data['Name'] . ' Options', $data['Name'] . ' Options', 'edit_theme_options', $prefix . '_settings', 'remix_admin_options_page' );
	  
	  $options_page = add_menu_page( 'Page Title', $data['Name'], 'manage_options', $prefix . '_settings', 'remix_admin_options_page', $icon, 50 );
	  
	  add_submenu_page( $prefix . '_settings', 'Theme Options', 'Theme Options', 'manage_options', $prefix . '_settings', 'remix_admin_options_page' );
	  
	  add_action( 'admin_print_styles-' . $options_page, 'remix_enqueue_admin_style', 11 );
   	add_action( 'admin_print_scripts-' . $options_page, 'remix_enqueue_admin_script', 11 );
}


/**
 * Display the options page
 *
 * @since Remix 0.1.0
 */
function remix_admin_options_page() {
    global $wp_settings_sections;
    $prefix = hybrid_get_prefix();
    $data = get_theme_data( trailingslashit( STYLESHEETPATH ) . 'style.css' );
    $theme_name = $data['Name']; ?>
    <div class="wrap">
        <div id='icon-options-general' class="icon32"><br></div>
        <h2>Theme Settings</h2>
    
        <?php if ( isset( $_GET['settings-updated'] ) ) {
      			echo "<div class='updated'><p>Theme settings updated successfully.</p></div>";
    	  }
    	  ?>
        <div id="<?php echo $prefix; ?>_options">
            <div id="header">
          
                <h1><?php echo $data['Name']; ?></h1>

            </div>
            <form id="options_tabs" method="post" action="options.php" enctype="multipart/form-data">
                <ul class="clearfix">
                    <?php foreach ($wp_settings_sections[$prefix . '_settings'] as $key => $value) { ?>
                  
                    <li><a href="#<?php echo $value['id']; ?>"><?php echo $value['title']; ?></a></li>
                
                    <?php } ?>
                </ul>

            		<?php settings_fields($prefix . '_theme_options'); // nonce ?>
        		
            		<?php foreach ($wp_settings_sections[$prefix . '_settings'] as $key => $value) { ?>
            
                <div id="<?php echo $value['id']; ?>">
            
                <?php remix_do_settings_sections($prefix . '_settings', $value['id']); ?>
            
                </div>
            
                <?php } ?>
        		
            		<div class="footer">
            			<input id="button-save" name="<?php echo $prefix.'_theme_options[submit]' ?>" type="submit" value="<?php esc_attr_e('Save Settings', hybrid_get_textdomain()); ?>" />
            			<input id="button-reset" name="<?php echo $prefix.'_theme_options[reset]' ?>" type="submit" value="<?php esc_attr_e('Reset Defaults', hybrid_get_textdomain()); ?>" />
            		</div>
            </form>
    		</div>
    </div>
<?php }

/**
 * Register Options
 *
 * @since Remix 0.1.0
 */
function remix_register_options(){
	  require( trailingslashit( STYLESHEETPATH ) . 'options/setup.php' );
	  require( trailingslashit( HYBRID_ADDONS ) . 'options/field-checkbox.php' );
    require( trailingslashit( HYBRID_ADDONS ) . 'options/field-textarea.php' );
    require( trailingslashit( HYBRID_ADDONS ) . 'options/field-text.php' );
    require( trailingslashit( HYBRID_ADDONS ) . 'options/field-select.php' );
    require( trailingslashit( HYBRID_ADDONS ) . 'options/field-colorpicker.php' );
    require( trailingslashit( HYBRID_ADDONS ) . 'options/field-media.php' );    
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
    wp_deregister_script( 'colorpicker' ); // we'll use our own colorpicker
    
    wp_enqueue_script( 'media-upload' );
    wp_enqueue_script( 'thickbox' );
   	wp_enqueue_script( $prefix . '_jquery_ui', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.10/jquery-ui.min.js' );
    wp_enqueue_script( $prefix . '_jquery_colorpicker', trailingslashit( HYBRID_ALT_ADMIN_JS ) . 'jquery.color.picker.js' );
    wp_enqueue_script( 'formalize', trailingslashit( HYBRID_ALT_ADMIN_JS ) . 'jquery.formalize.js' );
    wp_enqueue_script( 'admin_functions', trailingslashit( HYBRID_ALT_ADMIN_JS ) . 'functions.js' );
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
    
    add_settings_section($prefix . '_' . $section_args['section_id'], $section_args['section_title'], $prefix . '_' . $section_args['section_id'] . '_section_text', $prefix . '_settings');
    foreach ($field_args as $key => $args) {
        // auto generate these array items
        if ( $args['type'] != 'checkbox' ) {
            $args['label_for'] = $prefix.'_theme_options['.$key.']';
        }
        $args['field_name'] = $key;
        $args['div_for'] = 'options_'.$args['type'];
        
        add_settings_field($prefix.'_options_'.$key, $args['title'], 'remix_build_options_field', $prefix . '_settings', $prefix.'_'.$section_args['section_id'], $args);
    }
}

/**
 * Displays each individual option setting fields using prebuilt functions
 *
 * @since Remix 0.1.0
 */
function remix_build_options_field( $args ) {
    global $media_upload_ids;
    
    if ( $args['type'] == 'colorpicker' ) {
        echo '<div class="element">';
        remix_options_colorpicker( $args['field_name'] );
        echo '</div>';
    } elseif ( $args['type'] == 'select' ) {
      	echo '<div class="element">';
      	remix_options_select( $args['field_name'], $args['data']);
      	echo '</div>';
    } elseif ( $args['type'] == 'textarea' ) {
        echo '<div class="element">';
        remix_options_textarea( $args['field_name'] );
        echo '</div>';
    } elseif ( $args['type'] == 'media' ) {
        $media_upload_ids[] = $args['field_name'];
        remix_options_media( $args['field_name'] );
    } elseif ( $args['type'] == 'checkbox' ) {
        echo '<div class="element">';
      	remix_options_checkbox( $args['field_name'], $args['label'] );
      	echo '</div>';
    } elseif ( $args['type'] == 'text' ) {
            echo '<div class="element">';
          	remix_options_text_field( $args['field_name'] );
          	echo '</div>';
    }
    if ( $args['description'] ) {
        echo '<span class="description">' . $args['description'] . '</span>';
    }
}

/**
 * Replaces do_settings_sections, found in wp-admin/includes/template.php
 * because we don't want to use tables
 *
 * @since Remix 0.1.0
 */
function remix_do_settings_sections($page, $section_id) {
  	global $wp_settings_sections, $wp_settings_fields;
  	
  	if ( !isset($wp_settings_sections) || !isset($wp_settings_sections[$page]) )
  		return;
  	$section = $wp_settings_sections[$page][$section_id];
    echo '<div class="section clearfix">';
    
    $section_title = '<h3>' . $section['title'] . '</h3>' . "\n";    
  	echo apply_filters( 'options_section_title', $section_title );
  	
  	call_user_func($section['callback'], $section);
  	
  	if ( !isset($wp_settings_fields) || !isset($wp_settings_fields[$page]) || !isset($wp_settings_fields[$page][$section['id']]) )
  		continue;
  	remix_do_settings_fields($page, $section_id);
  	echo "</div>";
}

/**
* Replaces do_settings_fields, found in wp-admin/includes/template.php
* because we don't want to use tables
 *
 * @since Remix 0.1.0
 */
function remix_do_settings_fields($page, $section) {
  	global $wp_settings_fields;

  	if ( !isset($wp_settings_fields) || !isset($wp_settings_fields[$page]) || !isset($wp_settings_fields[$page][$section]) )
  		return;

  	foreach ( (array) $wp_settings_fields[$page][$section] as $field ) {
  	  $div_id = 'options_' . $field['args']['field_name'];
  	  
  	  if ( !empty($field['args']['div_for']) ) {
  	    echo '<div id="' . $div_id . '" class="options clearfix ' . $field['args']['div_for'] . '">';
  	  } else {
  	    echo '<div id="' . $div_id . '" class="options clearfix">';
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