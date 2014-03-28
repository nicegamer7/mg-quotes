<?php

class mg_qt_Random_Quote extends WP_Widget {

	private $factory_settings = array(
		'title' => ''
	);

	public function __construct() {
		parent::__construct(
			'mg_qt_widget_random_quote',
			__('Random Quote', 'mg_qt'),
			array(
				'description' => __('Pick a random quotes', 'mg_qt'),
				'classname' => 'mg_qt_widget_random_quote'
			) 
		);
	}
	
	public function widget($args, $instance) {
		$instance = wp_parse_args((array)$instance, $this->factory_settings);
		
		$quote_markup = mg_qt_get_random_quote();
		if ($quote_markup === '')
			return;
		
		$title = apply_filters('widget_title', 
			empty($instance['title']) ? '' : $instance['title'], 
			$instance, $this->id_base 
		);
		
		extract($args);

		echo $before_widget;
		if ($title)
			echo $before_title . $title . $after_title;
		echo $quote_markup;
		echo $after_widget;
	}
	
	public function form($instance) {
		$instance = wp_parse_args((array)$instance, $this->factory_settings);
		$title = esc_attr($instance['title']);
		?>
			<p>
				<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'mg_qt'); ?> 
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
		
		$instance['title'] = strip_tags($new_instance['title']);
		
		return $instance;
	}

}

