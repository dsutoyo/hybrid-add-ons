<?php
/**
 * Twitter Widget
 *
 * @package Remix
 * @since 0.1.0
 */
class Remix_Widget_Twitter extends WP_Widget {
    
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
  	function Remix_Widget_Twitter() {
  	  
  	    /* Set the widget prefix. */
    		$this->prefix = hybrid_get_prefix();

    		/* Set the widget textdomain. */
    		$this->textdomain = hybrid_get_textdomain();
    		
    		/* Widget settings. */
    		$widget_options = array( 
    		    'classname' => 'twitter', 
    		    'description' => esc_html__('A simple widget to display your Twitter stream.', $this->textdomain)
    		);

    		/* Widget control settings. */
    		$control_options = array( 
    		    'id_base' => "{$this->prefix}-twitter"
    		);

    		/* Create the widget. */
    		$this->WP_Widget( "{$this->prefix}-twitter", esc_attr__( 'Twitter', $this->textdomain ), $widget_options, $control_options );
  	}

  	/* How to display the widget on the screen */
  	function widget( $args, $instance ) {
    		extract( $args );

    		/* Our variables from the widget settings. */
    		$username = $instance['username'];
    		$tweets = $instance['tweets'];

    		/* Before widget (defined by themes). */
    		echo $before_widget;

    		/* If a title was input by the user, display it. */
    		if ( !empty( $instance['title'] ) )
    			echo $before_title . apply_filters( 'widget_title',  $instance['title'], $instance, $this->id_base ) . $after_title;

    		if ( $username )	
      		echo "<div id=\"twitter_div\" class=\"widget\">
          <ul id=\"twitter_update_list\"></ul>
          <p class=\"continue\"><a href=\"http://www.twitter.com/". $username . "/\">follow me on Twitter &rarr;</a></p>
          </div>";
    	
      		echo "<script type=\"text/javascript\" src=\"http://twitter.com/javascripts/blogger.js\"></script>
          <script type=\"text/javascript\" src=\"http://twitter.com/statuses/user_timeline/" . $username . ".json?callback=twitterCallback2&amp;count=" . $tweets . "\"></script>";

    		/* After widget (defined by themes). */
    		echo $after_widget;
  	}

  	/* Update the widget settings. */
  	function update( $new_instance, $old_instance ) {
    		$instance = $old_instance;

    		/* Strip tags for title and name to remove HTML (important for text inputs). */
    		$instance['title'] = strip_tags( $new_instance['title'] );
    		$instance['username'] = strip_tags( $new_instance['username'] );

    		$instance['tweets'] = $new_instance['tweets'];

    		return $instance;
  	}

  	/**
  	 * Displays the widget settings controls on the widget panel.
  	 * Make use of the get_field_id() and get_field_name() function
  	 * when creating your form elements. This handles the confusing stuff.
  	 */
  	function form( $instance ) {

    		/* Set up some default widget settings. */
    		$defaults = array( 'title' => __('Twitter', $this->textdomain), 'username' => __('twitter', $this->textdomain), 'tweets' => '5');
    		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

    		<p>
    			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', $this->textdomain); ?></label>
    			<input id="<?php echo $this->get_field_id( 'title' ); ?>" type="text" class="widefat" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" />
    		</p>
		
    		<p>
    			<label for="<?php echo $this->get_field_id( 'username' ); ?>"><?php _e('Twitter Username:', $this->textdomain); ?></label>
    			<input id="<?php echo $this->get_field_id( 'username' ); ?>" type="text" class="widefat" name="<?php echo $this->get_field_name( 'username' ); ?>" value="<?php echo $instance['username']; ?>" />
    		</p>

    		<p>
    			<label for="<?php echo $this->get_field_id( 'tweets' ); ?>"><?php _e('Number of Tweets:', $this->textdomain); ?></label>
    			<input id="<?php echo $this->get_field_id( 'tweets' ); ?>" type="text" class="widefat" name="<?php echo $this->get_field_name( 'tweets' ); ?>" value="<?php echo $instance['tweets']; ?>" />
    		</p>

    <?php
  	}
}

?>