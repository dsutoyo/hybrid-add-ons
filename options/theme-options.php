<?php

add_action( 'admin_menu', 'remix_admin_setup' );

function remix_admin_setup() {

	/* Get the theme prefix. */
	$prefix = hybrid_get_prefix();

	/* Create a settings meta box only on the theme settings page. */
	add_action( 'load-appearance_page_theme-settings', 'remix_settings_meta_boxes' );

	/* Add a filter to validate/sanitize your settings. */
	add_filter( "sanitize_option_{$prefix}_theme_settings", 'remix_validate_settings' );
}

/* Adds custom meta boxes to the theme settings page. */
function remix_settings_meta_boxes() {

	/* Add a custom meta box. */
	add_meta_box(
		'example-theme-meta-box',			// Name/ID
		__( 'Example Settings', 'theme-folder-name' ),	// Label
		'remix_meta_box',			// Callback function
		'appearance_page_theme-settings',		// Page to load on, leave as is
		'normal',					// Which meta box holder?
		'high'					// High/low within the meta box holder
	);

	/* Add additional add_meta_box() calls here. */
}

/* Function for displaying the meta box. */
function remix_meta_box() { ?>

	<table class="form-table">
		<!-- Add custom form elements below here. -->

		<!-- Text input box -->
		<tr>
			<th>
				<label for="<?php echo hybrid_settings_field_id( 'example_text_input' ); ?>"><?php _e( 'Example Text Input:', 'theme-folder-name' ); ?></label>
			</th>
			<td>
				<p><input type="text" id="<?php echo hybrid_settings_field_id( 'example_text_input' ); ?>" name="<?php echo hybrid_settings_field_name( 'example_text_input' ); ?>" value="<?php echo esc_attr( hybrid_get_setting( 'example_text_input' ) ); ?>" /></p>
				<p><?php _e( 'Example Description', 'theme-folder-name' ); ?></p>
			</td>
		</tr>

		<!-- Textarea -->
		<tr>
			<th>
				<label for="<?php echo hybrid_settings_field_id( 'example_textarea' ); ?>"><?php _e( 'Example Textarea:', 'theme-folder-name' ); ?></label>
			</th>
			<td>
				<p><?php _e( 'Example Description', 'theme-folder-name' ); ?></p>
				<p><textarea id="<?php echo hybrid_settings_field_id( 'example_textarea' ); ?>" name="<?php echo hybrid_settings_field_name( 'example_textarea' ); ?>" cols="60" rows="5" style="width: 98%;"><?php echo wp_htmledit_pre( stripslashes( hybrid_get_setting( 'example_textarea' ) ) ); ?></textarea></p>
			</td>
		</tr>

		<!-- End custom form elements. -->
	</table><!-- .form-table --><?php
}

/* Validates theme settings. */
function remix_validate_settings( $input ) {

	/* Validate and/or sanitize the textarea. */
	$input['example_textarea'] = wp_filter_nohtml_kses( $input['example_textarea'] );

	/* Validate and/or sanitize the text input. */
	$input['example_text_input'] = wp_filter_nohtml_kses( $input['example_text_input'] );

	/* Return the array of theme settings. */
	return $input;
}

?>