<?php
/**
 * Flickr Widget
 *
 * @package Remix
 * @since 0.1.0
 */
class Remix_Widget_Flickr extends WP_Widget {
    
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
    
  	/* Widget setup */
  	function Remix_Widget_Flickr() {
  	  
  	    /* Set the widget prefix. */
    		$this->prefix = hybrid_get_prefix();

    		/* Set the widget textdomain. */
    		$this->textdomain = hybrid_get_textdomain();
    		
    		/* Widget settings. */
    		$widget_options = array( 
    		    'classname' => 'flickr', 
    		    'description' => esc_html__('A simple widget to display your latest photos on Flickr.', $this->textdomain)
    		);

    		/* Widget control settings. */
    		$control_options = array( 
    		    'id_base' => "{$this->prefix}-flickr"
    		);

    		/* Create the widget. */
    		$this->WP_Widget( "{$this->prefix}-flickr", esc_attr__( 'Flickr', $this->textdomain ), $widget_options, $control_options );
  	}

  	/* How to display the widget on the screen. */
  	function widget( $args, $instance ) {
    		extract( $args );

    		/* Our variables from the widget settings. */
    		$username = $instance['username'];
    		$photos = $instance['photos'];

    		/* Before widget (defined by themes). */
    		echo $before_widget;

    		/* If a title was input by the user, display it. */
    		if ( !empty( $instance['title'] ) )
    			echo $before_title . apply_filters( 'widget_title',  $instance['title'], $instance, $this->id_base ) . $after_title;

    		if ( $username )

          echo "<div class=\"flickr_photos\">
        		<script type=\"text/javascript\" src=\"http://www.flickr.com/badge_code_v2.gne?count=" . $photos . "&amp;display=latest&amp;size=s&amp;layout=x&amp;source=user&amp;user=" . $username . "\"></script>
          </div>
          <div class=\"clearfix\"></div>";

    		/* After widget (defined by themes). */
    		echo $after_widget;
  	}

  	/**
  	 * Update the widget settings.
  	 */
  	function update( $new_instance, $old_instance ) {
    		$instance = $old_instance;

    		/* Strip tags for title and name to remove HTML (important for text inputs). */
    		$instance['title'] = strip_tags( $new_instance['title'] );
    		$instance['username'] = strip_tags( $new_instance['username'] );
    		$instance['photos'] = $new_instance['photos'];

    		return $instance;
  	}

  	/**
  	 * Displays the widget settings controls on the widget panel.
  	 * Make use of the get_field_id() and get_field_name() function
  	 * when creating your form elements. This handles the confusing stuff.
  	 */
  	function form( $instance ) {

    		/* Set up some default widget settings. */
    		$defaults = array( 'title' => __('Flickr', $this->textdomain), 'username' => __('61094143@N00', $this->textdomain), 'photos' => '4');
    		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

    		<p>
    			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', $this->textdomain); ?></label>
    			<input id="<?php echo $this->get_field_id( 'title' ); ?>" type="text" class="widefat" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" />
    		</p>
		
    		<p>
    			<label for="<?php echo $this->get_field_id( 'username' ); ?>"><?php _e('Flickr ID (find it using <a href="http://www.idgettr.com">idGettr</a>):', $this->textdomain); ?></label>
    			<input id="<?php echo $this->get_field_id( 'username' ); ?>" type="text" class="widefat" name="<?php echo $this->get_field_name( 'username' ); ?>" value="<?php echo $instance['username']; ?>" />
    		</p>

    		<p>
    			<label for="<?php echo $this->get_field_id( 'photos' ); ?>"><?php _e('Number of Photos:', $this->textdomain); ?></label>
    			<input id="<?php echo $this->get_field_id( 'photos' ); ?>" type="text" class="widefat" name="<?php echo $this->get_field_name( 'photos' ); ?>" value="<?php echo $instance['photos']; ?>" />
    		</p>

  	<?php
  	}
}

?>