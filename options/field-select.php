<?php
  
function hybrid_addons_options_select($var, $opt_array) {
    $prefix = hybrid_get_prefix();
    $the_theme_options = get_option($prefix.'_theme_options');

		echo '<select class="option_select" id="'.$prefix.'_theme_options['.$var.']" name="'.$prefix.'_theme_options['.$var.']">';
		echo "\n";
		foreach($opt_array as $key => $opt) {
		    echo '<option value="'.$key.'"';		    
	      selected( $key == $the_theme_options[$var] );
		    echo '>'.$opt.'</option>';
		    echo "\n";
		}
		echo '</select>';
}

?>