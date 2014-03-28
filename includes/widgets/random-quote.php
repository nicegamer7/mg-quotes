<?php

class mg_qt_Random_Quote extends WP_Widget {

	private $factory_settings = array(
		'title' => '',
		'category' => 0
	);

	public function __construct() {
		parent::__construct(
			'mg_qt_widget_random_quote',
			__('Random Quote', 'mg_qt'),
			array(
				'description' => __('Pick a random quotes. Supports categories', 'mg_qt'),
				'classname' => 'mg_qt_widget_random_quote'
			) 
		);
	}
	
	public function form($instance) {
		$instance = wp_parse_args((array)$instance, $this->factory_settings);
		
		$title = esc_attr($instance['title']);
		
		$category = $instance['category'];
		if (wp_count_terms('mg_qt_category') === 0)
			$category = -1;
		else if ($category > 0 && term_exists($category, 'mg_qt_category') === 0)
			$category = 0;
			
		?>
			<p>
				<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'mg_qt'); ?> 
					<input 
						id="<?php echo $this->get_field_id('title'); ?>" 
						name="<?php echo $this->get_field_name('title'); ?>" 
						type="text" 
						value="<?php echo $title; ?>" 
						class="widefat" 
					/>
				</label>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('category'); ?>"><?php _e('Category:', 'mg_qt'); ?></label> 
				<?php
					wp_dropdown_categories(array(
						'taxonomy' => 'mg_qt_category',
						'name' => $this->get_field_name('category'),
						'selected' => $category,
						'show_option_all' => __('From all categories', 'mg_qt'),
						'show_option_none' => __('From all categories', 'mg_qt'),
						'hierarchical' => 1,
						'orderby' => 'name',
						'class' => 'widefat'
					));
				?>
			</p>
		<?php
	}
	
	public function update($new_instance, $old_instance) {
		$instance = $old_instance;
		
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['category'] = absint($new_instance['category']);
		
		return $instance;
	}
	
	public function widget($args, $instance) {
		$instance = wp_parse_args((array)$instance, $this->factory_settings);
		
		$quote_markup = $instance['category'] === 0 ? 
			mg_qt_get_random_quote() : 
			mg_qt_get_random_quote_from_category_id($instance['category'])
		;
		
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

}

