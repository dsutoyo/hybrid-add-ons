<?php 
$prefix = hybrid_get_prefix();
register_setting( $prefix . '_theme_options', $prefix . '_theme_options', 'remix_options_validate' );

/**
 * Register settings
 *
 * @since Evoke 1.0
 */
global $pagenow;
if ( ('themes.php' == $pagenow || 'admin.php' == $pagenow) && isset( $_GET['page'] ) && $prefix . '_settings' == $_GET['page'] ) :
    //require( get_template_directory() . '/library/extra/options/register-general.php' );
    //require( get_template_directory() . '/library/extra/options/register-slideshow.php' );
    require( trailingslashit( HYBRID_ADDONS ) . 'options/register-styles.php' );
endif;

/**===================================================================================
 * Validate/Whitelist User-Input Data Before Updating Theme Options
 * Codex Reference: http://codex.wordpress.org/Data_Validation
 *
 * @since Evoke 1.0
 */
function remix_options_validate( $input ) {

  	$the_theme_options = get_option( $prefix . '_theme_options' );
  	$valid_input = $the_theme_options;
	
  	// Determine which form action was submitted
  	$submit_button = ( ! empty( $input['submit']) ? true : false );	
  	$reset_button = ( ! empty($input['reset']) ? true : false );
	
  	if ( $submit_button ) { // if Settings Submit
	    	
	    	switch ( $input['slideshow_fx'] ) {
	    	    case 'horizontal-slide':
	    	        $valid_input['slideshow_fx'] = 'horizontal-slide';
	    	        break;
	    	    case 'vertical-slide':
	    	        $valid_input['slideshow_fx'] = 'vertical-slide';
	    	        break;
	    	    case 'horizontal-push':
	    	        $valid_input['slideshow_fx'] = 'horizontal-push';
	    	        break;
	    	    default:
	    	        $valid_input['slideshow_fx'] = 'fade';
	    	}
	    	
    		$valid_input['font'] = ( 'serif' == $input['font'] ? 'serif' : 'sans-serif' );
    		$valid_input['link_color'] = $input['link_color'];
    		$valid_input['hover_color'] = $input['hover_color'];
    		$valid_input['nav_link_color'] = $input['nav_link_color'];
    		
    		$valid_input['analytics'] = $input['analytics'];
    		
    		$keys = array_keys($_FILES);
        $i = 0;

        foreach ( $_FILES as $image ) {
          // if a files was upload
          if ($image['size']) {
            // if it is an image
            if ( preg_match('/(jpg|jpeg|png|gif)$/', $image['type']) ) {
              $override = array('test_form' => false);
              // save the file, and store an array, containing its location in $file
              $file = wp_handle_upload( $image, $override );
              $valid_input[$keys[$i]] = $file['url'];

            } else {
              // Not an image. 
              $valid_input[$keys[$i]] = $the_theme_options['custom_favicon'];
              // Die and let the user know that they made a mistake.
              wp_die('No image was uploaded.');
            }
          }

          // Else, the user didn't upload a file.
          // Retain the image that's already on file.
          else {
            $valid_input[$keys[$i]] = $the_theme_options['custom_favicon'];
          }
          $i++;
        }
        
        $valid_input['custom_favicon'] = ( 'true' == $input['delete_favicon'] ? '' : $input['custom_favicon'] );
              
		
  	} elseif ( $reset_button ) { // if Settings Reset Defaults
	
    		$the_default_options = remix_get_default_options();
    		
    		$valid_input = $the_default_options;
    		//$valid_input['footer_text'] = $the_default_options['footer_text'];
    		//$valid_input['footer_text_color'] = $the_default_options['footer_text_color'];
		
  	}
  	return $valid_input;		

}

?>