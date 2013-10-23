<?php
/**
 * The Blog widget allows you to output the content of different post types
 *
 * @package Hybrid Addons
 * @subpackage Classes
 */

/**
 * Blog widget class.
 *
 * @since 0.1.0
 */
class Hybrid_Addons_Widget_Blog extends WP_Widget {

	/**
	 * Prefix for the widget.
	 * @since 0.1.0
	 */
	var $prefix;

	/**
	 * Set up the widget's unique name, ID, class, description, and other options.
	 * @since 0.1.0
	 */
	function Hybrid_Addons_Widget_Blog() {

		/* Set the widget prefix. */
		$this->prefix = hybrid_get_prefix();

		/* Set up the widget options. */
		$widget_options = array(
			'classname' => 'blog',
			'description' => esc_html__( 'An simple widget that allows you to output the content of different post types.', 'hybrid-addons' )
		);

		/* Set up the widget control options. */
		$control_options = array(
			'id_base' => "{$this->prefix}-blog"
		);

		/* Create the widget. */
		$this->WP_Widget( "{$this->prefix}-blog", esc_attr__( 'Custom Post Archives', 'hybrid-addons' ), $widget_options, $control_options );
	}

	/**
	 * Outputs the widget based on the arguments input through the widget controls.
	 * @since 0.1.0
	 */
	function widget( $args, $instance ) {
		extract( $args );

		/* Set up the arguments for WP_Query. */
		$args = array(
			'posts_per_page' => $instance['posts'],
			'nopaging' => 0,
			'post_status' => 'publish',
			'post_type' => $instance['post_type'],
			'ignore_sticky_posts' => true,
			'cat' => $instance['category']
		);

 		/* Before widget (defined by themes). */
 		echo $before_widget;

		/* If a title was input by the user, display it. */
		if ( !empty( $instance['title'] ) )
			echo $before_title . apply_filters( 'widget_title',  $instance['title'], $instance, $this->id_base ) . $after_title; ?>
     
		<?php if ( $instance['show_content'] == 'none' ) { ?>
		<ul class="xoxo articles no-excerpt">
		<?php } else { ?>
		<ul class="xoxo articles">
		<?php } ?>
		
		<?php $r = new WP_Query( $args );
			if ( $r->have_posts() ) :
				while ( $r->have_posts() ) : $r->the_post(); ?>
					<li>

						<?php if ( $instance['show_date'] == true ) : ?>

							<h5 class="entry-title has-subtitle"><a href="<?php the_permalink() ?>" title="<?php echo esc_attr(get_the_title() ? get_the_title() : get_the_ID()); ?>"><?php if ( get_the_title() ) the_title(); else the_ID(); ?></a></h5>
							<p class="entry-date"><?php echo get_the_date( 'M, j, Y' ); ?></p>
						
						<?php else : ?>

							<h5 class="entry-title"><a href="<?php the_permalink() ?>" title="<?php echo esc_attr(get_the_title() ? get_the_title() : get_the_ID()); ?>"><?php if ( get_the_title() ) the_title(); else the_ID(); ?></a></h5>

						<?php endif; ?>

						<?php if ( $instance['show_content'] == 'excerpt' ) {
							the_excerpt();
						} elseif ( $instance['show_content'] == 'content' ) {
							the_content();
						} ?>
					</li>
				<?php endwhile;

				// Reset the global $the_post as this query will have stomped on it
				wp_reset_postdata();

			endif; ?>

		</ul>

		<?php $post_obj = get_post_type_object($instance['post_type']); ?>

		<?php if ( !empty( $post_obj->rewrite ) ) : ?>

		<p class="widget-blog-link"><a href="<?php echo get_bloginfo('url') . $post_obj->rewrite['slug']; ?>/">View All <?php echo $post_obj->labels->all_items; ?></a></p>

		<?php endif; ?>

		<?php echo $after_widget;
	}

	/**
	 * Updates the widget control options for the particular instance of the widget.
	 * @since 0.1.0
	 */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		/* Strip tags for title and name to remove HTML (important for text inputs). */
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['posts'] = strip_tags( $new_instance['posts'] );
		$instance['post_type'] = strip_tags( $new_instance['post_type'] );
		$instance['category'] = strip_tags( $new_instance['category'] );
		$instance['show_content'] = $new_instance['show_content'];
		$instance['show_date'] = ( isset( $new_instance['show_date'] ) ? 1 : 0 );

		return $instance;
	}

	/**
	 * Displays the widget control options in the Widgets admin screen.
	 * @since 0.1.0
	 */
	function form( $instance ) {

		/* Set up some default widget settings. */
		$defaults = array( 
			'title' => __( 'Recent Posts', 'hybrid-addons' ),
			'posts' => __( '5', 'hybrid-addons' ),
			'post_type' => 'post',
			'category' => '',
			'show_content' => 'excerpt',
			'show_date' => false
		);
		$instance = wp_parse_args( (array) $instance, $defaults );

		$post_types = get_post_types( array( 'public' => true ), 'objects' );

		$post_type_categories = array();

		foreach ( $post_types as $type ) {
			$post_type_categories[$type->name] = array();

			// Get all taxonomies of each post type
			$taxonomies = get_object_taxonomies( $type->name, 'objects' );

			// Get only the hierarchical (like categories) taxonomies
			foreach ( $taxonomies as $taxonomy ) {
				if ( is_taxonomy_hierarchical( $taxonomy->name ) ) {
					// Get the terms associated with each taxonomy
					$args = array(
						'order' => 'ASC',
					);
					$terms = get_terms( $taxonomy->name, $args );

					foreach ( $terms as $term ) {
						$post_type_categories[$type->name][$term->term_id] = $term->name;
					}
				}
			}

		}
		
		$content_types = array( 'none' => esc_attr__( 'Title only', 'hybrid-addons' ), 'excerpt' => esc_attr__( 'Excerpt only', 'hybrid-addons' ), 'content' => esc_attr__( 'Full content', 'hybrid-addons' ) );
		?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'hybrid-addons' ); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" type="text" class="widefat" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" />
		</p>
		

		<p>
			<label for="<?php echo $this->get_field_id( 'post_type' ); ?>">Post Type:</label>
			<select class="widefat" id="<?php echo $this->get_field_id( 'post_type' ); ?>" name="<?php echo $this->get_field_name( 'post_type' ); ?>">
				<?php foreach ( $post_types as $type ) { ?>
					<option value="<?php echo esc_attr( $type->name ); ?>"<?php selected( $instance['post_type'], $type->name ); ?>><?php echo esc_html( $type->labels->name ); ?></option>
				<?php } ?>
			</select>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'category' ); ?>">Categories: (beta, post categories only)</label>
			<select class="widefat" id="<?php echo $this->get_field_id( 'category' ); ?>" name="<?php echo $this->get_field_name( 'category' ); ?>">
				<option value="" <?php selected( $instance['category'], '' ); ?>>---</option>
				<?php foreach ( $post_type_categories['post'] as $cat_id => $cat_name ) { ?>
					<option class="hybrid-addons-blog-widget_<?php echo $post_type_category; ?>" value="<?php echo esc_attr( $cat_id ); ?>"<?php selected( $instance['category'], $cat_id ); ?>><?php echo esc_html( $cat_name ); ?></option>
				<?php } ?>
			</select>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'posts' ); ?>"><?php _e('Number of Posts:', 'hybrid-addons'); ?></label>
			<input id="<?php echo $this->get_field_id( 'posts' ); ?>" type="text" class="widefat" name="<?php echo $this->get_field_name( 'posts' ); ?>" value="<?php echo $instance['posts']; ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'show_content' ); ?>">Show:</label>
			<select class="widefat" id="<?php echo $this->get_field_id( 'show_content' ); ?>" name="<?php echo $this->get_field_name( 'show_content' ); ?>">
    
			<?php foreach ( $content_types as $option_value => $option_label ) { ?>
				<option value="<?php echo esc_attr( $option_value ); ?>"<?php selected( $instance['show_content'], $option_value ); ?>><?php echo esc_html( $option_label ); ?></option>
			<?php } ?>
			</select>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'show_date' ); ?>">
				<input type="checkbox" <?php checked( $instance['show_date'], true ); ?> id="<?php echo $this->get_field_id( 'show_date' ); ?>" name="<?php echo $this->get_field_name( 'show_date' ); ?>" /> <?php _e( 'Show date?', 'hybrid-addons' ); ?></label>
		</p>

	<?php
	}
}

?>