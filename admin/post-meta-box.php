<?php

/**
 * Outputs a text input box with the given arguments for use with the post meta box.
 *
 * @since 0.7.0
 * @param array $args 
 * @param string|bool $value Custom field value.
 */
function hybrid_post_meta_box_checkbox( $args = array(), $value = false ) {
	$name = preg_replace( "/[^A-Za-z_-]/", '-', $args['name'] ); ?>
	<p>
		<input type="checkbox" name="<?php echo $name; ?>" id="<?php echo $name; ?>" value="1" <?php if ( $value ) echo "checked=\"checked\""; ?> size="30" tabindex="30" style="float: left; margin-right: 5px;" />
		<label for="<?php echo $name; ?>"><?php echo $args['title']; ?></label>
		<?php if ( !empty( $args['description'] ) ) echo '<br /><span class="howto">' . $args['description'] . '</span>'; ?>
	</p>
	<?php
}

?>