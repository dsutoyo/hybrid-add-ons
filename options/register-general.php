<?php 

add_settings_section(THEME_PREFIX.'options_general', 'General Options', THEME_PREFIX.'options_general_section_text', THEME_NAME);

add_settings_field(THEME_PREFIX.'options_custom_favicon', 'Custom Favicon', THEME_PREFIX.'options_custom_favicon', THEME_NAME, THEME_PREFIX.'options_general', array('label_for' => THEME_PREFIX.'theme_options[custom_favicon]'));

add_settings_field(THEME_PREFIX.'options_analytics', 'Analytics', THEME_PREFIX.'options_analytics', THEME_NAME, THEME_PREFIX.'options_general', array('label_for' => THEME_PREFIX.'theme_options[analytics]'));

function evoke_options_general_section_text() { ?>
    <p><?php _e('General Options', THEME_NAME) ?></p>
<?php }

function evoke_options_custom_favicon() {
    $the_theme_options = get_option( THEME_PREFIX.'theme_options' );
    if ( $the_theme_options['custom_favicon'] ) {
      echo '<img src="'.$the_theme_options['custom_favicon'].'" />';
      echo '<div class="remove_image">';
      th_options_checkbox('delete_favicon');
      th_options_label_for('delete_favicon', 'Remove image', 'option_checkbox_label');
      echo '</div>';
    }
    echo '<div class="element">';
    echo '<input id="'. THEME_PREFIX . 'theme_options[custom_favicon]" type="text" size="36" name="'. THEME_PREFIX . 'theme_options[custom_favicon]" value="" />';
    echo '<a id="upload_custom_favicon" href="#">Upload Image</a>';
    echo '</div>';
    echo '<span class="description">Enter the URL of the image or select one from the media library.</span>';
}

function evoke_options_analytics() {
    echo '<div class="element">';
    th_options_textarea('analytics');
    echo '</div>';
    echo '<span class="description">This is a side description</span>';
}

?>