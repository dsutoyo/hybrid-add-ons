<?php

function remix_textfield( $args ) { ?>
	<tr>
		<th>
			<label for="<?php echo hybrid_settings_field_id( $args['id'] ); ?>"><?php echo $args['label']; ?></label>
		</th>
		<td>
			<p><input type="text" id="<?php echo hybrid_settings_field_id( $args['id'] ); ?>" name="<?php echo hybrid_settings_field_name( $args['id'] ); ?>" value="<?php echo esc_attr( hybrid_get_setting( $args['id'] ) ); ?>" /></p>
			<p><?php echo $args['description'] ?></p>
		</td>
	</tr>
<?php
}

function remix_textarea( $args ) { ?>
	<tr>
		<th>
			<label for="<?php echo hybrid_settings_field_id( 'example_textarea' ); ?>"><?php _e( 'Example Textarea:', 'theme-folder-name' ); ?></label>
		</th>
		<td>
			<p><?php _e( 'Example Description', 'theme-folder-name' ); ?></p>
			<p><textarea id="<?php echo hybrid_settings_field_id( 'example_textarea' ); ?>" name="<?php echo hybrid_settings_field_name( 'example_textarea' ); ?>" cols="60" rows="5" style="width: 98%;"><?php echo wp_htmledit_pre( stripslashes( hybrid_get_setting( 'example_textarea' ) ) ); ?></textarea></p>
		</td>
	</tr>
<?php
}