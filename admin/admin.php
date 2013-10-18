<?php

add_action( 'add_meta_boxes', 'hybrid_addons_add_meta_box', 10, 2 );

add_action( 'save_post', 'hybrid_addons_save_header_element_meta_box', 10, 2 );
add_action( 'add_attachment', 'hybrid_addons_save_header_element_meta_box' );
add_action( 'edit_attachment', 'hybrid_addons_save_header_element_meta_box' );

add_action( 'save_post', 'hybrid_addons_save_custom_element_meta_box', 10, 2 );
add_action( 'add_attachment', 'hybrid_addons_save_custom_element_meta_box' );
add_action( 'edit_attachment', 'hybrid_addons_save_custom_element_meta_box' );

/**
 * Returns the type of custom header element. Returns false if it is not defined.
 *
 * @since  0.3.5
 * @access public
 * @return void
 */
function hybrid_addons_get_custom_header() {
	$element = get_post_meta( get_the_ID(), 'hybrid_addons_header_element', true );

	if ( current_theme_supports( 'header-elements' ) && $element )
		return $element;
	return false;
}

/**
 * Adds the sermon metadata box.
 *
 * @since  0.3.5
 * @access public
 * @return void
 */
function hybrid_addons_add_meta_box( $post_type, $post ) {

	$args = array(
		'public' => true,
		'capability_type' => 'post'
	);
	$post_types = get_post_types( $args );

	$args = array(
		'public' => true,
		'capability_type' => 'page'
	);
	$page_types = get_post_types( $args );

	$header_post_types = array_merge( array_values( $post_types ), array_values( $page_types ) );

	foreach ($header_post_types as $header_post_type) {
		add_meta_box( 
			'hybrid-header-element',
			__( 'Header Element Options', 'hybrid-addons' ),
			'hybrid_addons_display_header_element_meta_box',
			$header_post_type,
			'side',
			'high'
		);
		
		add_meta_box( 
			'hybrid-custom-element',
			__( 'Custom Header', 'hybrid-addons' ),
			'hybrid_addons_display_custom_element_meta_box',
			$header_post_type,
			'normal',
			'high'
		);
	}
}

/**
 * Display the header element metadata box.
 *
 * @since  0.3.5
 * @access public
 * @return void
 */
function hybrid_addons_display_header_element_meta_box( $post ) {

	$select_options = array(
		'None' => 'none',
		'Title' => 'title',
		'Custom Element' => 'custom_element',
		'Hide All Titles' => 'hide'
	);

	wp_nonce_field( basename( __FILE__ ), 'hybrid-addons-header-element-nonce' );

	?>

	<p>
		<label for="hybrid-addons-header-element"><strong><?php _e( 'Select Header Element', 'hybrid-addons' ); ?></strong></label>
	</p>

	<p>
		<select name="hybrid-addons-header-element" id="hybrid-addons-header-element" class="widefat">
			<?php foreach ( $select_options as $key => $value ) { ?>
				<option value="<?php echo esc_attr( $value ); ?>" <?php selected( esc_attr( get_post_meta( $post->ID, 'hybrid_addons_header_element', true ) ), esc_attr( $value ) ); ?>><?php echo esc_html( $key ); ?></option>
			<?php } ?>
		</select>
	</p>
<?php
}

/**
 * Display the custom element metabox
 *
 * @since  0.3.5
 * @access public
 * @return void
 */
function hybrid_addons_display_custom_element_meta_box( $post ) {

	wp_nonce_field( basename( __FILE__ ), 'hybrid-addons-custom-element-nonce' );

	?>
	<p>
		<label for="hybrid-addons-custom-element"><strong><?php _e( 'Custom Header HTML', 'hybrid-addons' ); ?></strong></label>
	</p>
	<p>
		<textarea class="textarea" rows="8" cols="40" name="hybrid-addons-custom-element" id="hybrid-addons-custom-element" style="width:98%;"><?php echo esc_attr( get_post_meta( $post->ID, 'hybrid_addons_custom_element', true ) ); ?></textarea>
	</p>
	<p>Add your own HTML custom header. Only valid when "Custom Element" is selected for Header Element Options</p>
<?php
}

/**
 * Save the header element metadata.
 *
 * @since  0.3.5
 * @access public
 * @return void
 */
function hybrid_addons_save_header_element_meta_box( $post_id, $post = '' ) {

	/* Verify the nonce before proceeding. */
	if ( !isset( $_POST['hybrid-addons-header-element'] ) || !wp_verify_nonce( $_POST['hybrid-addons-header-element-nonce'], basename( __FILE__ ) ) )
		return $post_id;

	/* Get the post type object. */
	$post_type = get_post_type_object( $post->post_type );

	/* Check if the current user has permission to edit the post. */
	if ( !current_user_can( $post_type->cap->edit_post, $post_id ) )
		return $post_id;

	$meta = array(
		'hybrid_addons_header_element' => strip_tags( $_POST['hybrid-addons-header-element'] )
	);

	foreach ( $meta as $meta_key => $new_meta_value ) {

		/* Get the meta value of the custom field key. */
		$meta_value = get_post_meta( $post_id, $meta_key, true );

		/* If a new meta value was added and there was no previous value, add it. */
		if ( $new_meta_value && '' == $meta_value )
			add_post_meta( $post_id, $meta_key, $new_meta_value, true );

		/* If the new meta value does not match the old value, update it. */
		elseif ( $new_meta_value && $new_meta_value != $meta_value )
			update_post_meta( $post_id, $meta_key, $new_meta_value );

		/* If there is no new meta value but an old value exists, delete it. */
		elseif ( '' == $new_meta_value && $meta_value )
			delete_post_meta( $post_id, $meta_key, $meta_value );
	}
}

/**
 * Save the header element metadata.
 *
 * @since  0.1.0
 * @access public
 * @return void
 */
function hybrid_addons_save_custom_element_meta_box( $post_id, $post = '' ) {

	/* Verify the nonce before proceeding. */
	if ( !wp_verify_nonce( $_POST['hybrid-addons-custom-element-nonce'], basename( __FILE__ ) ) )
		return $post_id;

	/* Get the post type object. */
	$post_type = get_post_type_object( $post->post_type );

	/* Check if the current user has permission to edit the post. */
	if ( !current_user_can( $post_type->cap->edit_post, $post_id ) )
		return $post_id;

	$meta = array(
		'hybrid_addons_custom_element' => $_POST['hybrid-addons-custom-element']
	);

	foreach ( $meta as $meta_key => $new_meta_value ) {

		/* Get the meta value of the custom field key. */
		$meta_value = get_post_meta( $post_id, $meta_key, true );

		/* If a new meta value was added and there was no previous value, add it. */
		if ( $new_meta_value && '' == $meta_value )
			add_post_meta( $post_id, $meta_key, $new_meta_value, true );

		/* If the new meta value does not match the old value, update it. */
		elseif ( $new_meta_value && $new_meta_value != $meta_value )
			update_post_meta( $post_id, $meta_key, $new_meta_value );

		/* If there is no new meta value but an old value exists, delete it. */
		elseif ( '' == $new_meta_value && $meta_value )
			delete_post_meta( $post_id, $meta_key, $meta_value );
	}
}