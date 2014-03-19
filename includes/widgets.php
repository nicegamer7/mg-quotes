<?php
add_action('widgets_init', 'mg_qt_register_widgets');

class mg_qt_Random_Quote extends WP_Widget {

	public function __construct() {
		parent::__construct(
			'mg_qt_widget_random_quote',
			__('mg qt Random Quote', 'mg_qt'),
			array(
				'description' => __('Pick a random quotes', 'mg_qt'),
				'classname' => 'mg_qt_widget_random_quote'
			) 
		);
	}
	
	public function widget($args, $instance) {
		extract($args);
		
		$title = apply_filters('widget_title', 
			empty($instance['title']) ? '' : $instance['title'], 
			$instance, $this->id_base 
		);

		echo $before_widget;
		if ($title)
			echo $before_title . $title . $after_title;

		mg_qt_random_quote();

		echo $after_widget;
	}
	
	public function form($instance) {
		$instance = wp_parse_args((array)$instance, array('title' => ''));
		$title = $instance['title'];
		?>
			<p>
				<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?> 
					<input 
						id="<?php echo $this->get_field_id('title'); ?>" 
						name="<?php echo $this->get_field_name('title'); ?>" 
						type="text" 
						value="<?php echo esc_attr($title); ?>" 
						class="widefat" 
					/>
				</label>
			</p>
		<?php
	}
	
	public function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$new_instance = wp_parse_args((array)$new_instance, array( 'title' => ''));
		$instance['title'] = strip_tags($new_instance['title']);
		return $instance;
	}

}

function mg_qt_register_widgets() {
	register_widget('mg_qt_Random_Quote');
}