<?php
function hybrid_addons_options_text_field($var) {
    $prefix = hybrid_get_prefix();
    $the_theme_options = get_option($prefix.'_theme_options');
    echo '<input class="option_field" id="'.$prefix.'_theme_options['.$var.']" type="text" name="'.$prefix.'_theme_options['.$var.']" value="'.$the_theme_options[$var].'" />';
}
?>