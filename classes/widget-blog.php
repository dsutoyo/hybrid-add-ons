<?php
/**
 * The Blog widget allows you to output the content of different post types
 *
 * @package Remix
 * @subpackage Classes
 */

/**
 * Blog widget class.
 *
 * @since 0.1.0
 * @link http://codex.wordpress.org/Template_Tags/wp_get_archives
 * @link http://themehybrid.com/themes/hybrid/widgets
 */
class Remix_Widget_Blog extends WP_Widget {

	/**
	 * Prefix for the widget.
	 * @since 0.1.0
	 */
	var $prefix;

	/**
	 * Textdomain for the widget.
	 * @since 0.1.0
	 */
	var $textdomain;

	/**
	 * Set up the widget's unique name, ID, class, description, and other options.
	 * @since 0.1.0
	 */
	function Remix_Widget_Blog() {

		/* Set the widget prefix. */
		$this->prefix = hybrid_get_prefix();

		/* Set the widget textdomain. */
		$this->textdomain = hybrid_get_textdomain();

		/* Set up the widget options. */
		$widget_options = array(
			'classname' => 'blog',
			'description' => esc_html__( 'An simple widget that allows you to output the content of different post types.', $this->textdomain )
		);

		/* Set up the widget control options. */
		$control_options = array(
			'id_base' => "{$this->prefix}-blog"
		);

		/* Create the widget. */
		$this->WP_Widget( "{$this->prefix}-blog", esc_attr__( 'Blog', $this->textdomain ), $widget_options, $control_options );
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
        'ignore_sticky_posts' => true
    );

 		/* Before widget (defined by themes). */
 		echo $before_widget;

		/* If a title was input by the user, display it. */
		if ( !empty( $instance['title'] ) )
			echo $before_title . apply_filters( 'widget_title',  $instance['title'], $instance, $this->id_base ) . $after_title;
     ?>

     <ul class="xoxo articles">
         <?php $r = new WP_Query($args);
    		  
    		  if ($r->have_posts()) :
    		      while ($r->have_posts()) : $r->the_post(); ?>
          		<li>
          		    <h4 class="entry-title"><a href="<?php the_permalink() ?>" title="<?php echo esc_attr(get_the_title() ? get_the_title() : get_the_ID()); ?>"><?php if ( get_the_title() ) the_title(); else the_ID(); ?></a></h4>
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
		$instance['show_content'] = $new_instance['show_content'];

		return $instance;
	}

	/**
	 * Displays the widget control options in the Widgets admin screen.
	 * @since 0.1.0
	 */
	function form( $instance ) {

    		/* Set up some default widget settings. */
    		$defaults = array( 
    		    'title' => __('Recent Posts', $this->textdomain),
    		    'posts' => __('5', $this->textdomain),
    		    'post_type' => 'post',
    		    'show_content' => 'excerpt'
    		);
    		$instance = wp_parse_args( (array) $instance, $defaults );
    		
    		$post_types = get_post_types( array( 'public' => true ), 'objects' );
    		$content_types = array( 'none' => esc_attr__( 'No content', $this->textdomain ), 'excerpt' => esc_attr__( 'Excerpt only', $this->textdomain ), 'content' => esc_attr__( 'Full content', $this->textdomain ) );
    		?>

    		<p>
    			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', $this->textdomain); ?></label>
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
    			<label for="<?php echo $this->get_field_id( 'posts' ); ?>"><?php _e('Number of Posts:', $this->textdomain); ?></label>
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

	  <?php
	}
}

?>