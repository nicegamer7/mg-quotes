<?php

add_action('widgets_init', 'mg_qt_register_widgets');

class mg_qt_Random_Quote extends WP_Widget {

	public function __construct() {
		parent::__construct(
			'mg_qt_widget_random_quote', // Base ID
			__('mg qt Random Quote', 'mg_qt'), // Name
			array('description' => __( 'Pick a random quotes', 'mg_qt' ), ) // Args
		);
	}
	
	public function widget($args, $instance) {
		//$title = apply_filters('widget_title', $instance['title']);

		echo $args['before_widget'];
		
		/* if (!empty($title))
			echo $args['before_title'] . $title . $args['after_title']; */
		echo __('Hello, World!', 'mg_qt');
		
		echo $args['after_widget'];
	}
	
	public function form($instance) {
		/* if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = __( 'New title', 'text_domain' );
		}
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<?php  */
	}
	
	public function update($new_instance, $old_instance) {
		/* $instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';

		return $instance; */
	}

}

function mg_qt_register_widgets() {
	register_widget('mg_qt_Random_Quote');
}