<?php
add_action('widgets_init', 'mg_qt_register_widgets');

class mg_qt_Random_Quote extends WP_Widget {

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
				<label for="<?php echo $this->get_field_id('title'); ?>"><?php __('Title:', 'mg_qt'); ?> 
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

class mg_qt_Random_Quote_From_Category extends WP_Widget {

	private $factory_settings = array(
		'title' => '', 
		'category' => 0
	);

	public function __construct() {
		parent::__construct(
			'mg_qt_widget_random_quote_from_category',
			__('Random Quote in Category', 'mg_qt'),
			array(
				'description' => __('Pick a random quotes from the available quote categories', 'mg_qt'),
				'classname' => 'mg_qt_widget_random_quote_from_category'
			) 
		);
	}
	
	public function widget($args, $instance) {
		$instance = wp_parse_args((array)$instance, $this->factory_settings);
		
		if ($instance['category'] === 0)
			return;
			
		$quote_markup = mg_qt_get_random_quote_from_category_id($instance['category']);
		if ($quote_markup === '')
			return;
		
		extract($args);
		
		$title = apply_filters('widget_title', $instance['title'], $instance, $this->id_base);
		
		echo $before_widget;
		
		if ($title)
			echo $before_title . $title . $after_title;
			
		echo $quote_markup;
		
		echo $after_widget;
	}
	
	public function form($instance) {
		$instance = wp_parse_args((array)$instance, $this->factory_settings);
		
		$title = esc_attr($instance['title']);
		
		$category = $instance['category'];
		if ($category === 0)
			$category = '';
		$category = esc_attr($category);

		?>
			<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php __('Title:', 'mg_qt'); ?></label> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>
			<p><label for="<?php echo $this->get_field_id('category'); ?>"><?php __('Category:', 'mg_qt'); ?></label> <input type="text" value="<?php echo $category; ?>" name="<?php echo $this->get_field_name('category'); ?>" id="<?php echo $this->get_field_id('category'); ?>" class="widefat" /></p>
		<?php
	}
	
	public function update($new_instance, $old_instance) {
		$instance = $old_instance;
		
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['category'] = absint($new_instance['category']);
		
		return $instance;
	}

}

function mg_qt_register_widgets() {
	register_widget('mg_qt_Random_Quote');
	register_widget('mg_qt_Random_Quote_From_Category');
}