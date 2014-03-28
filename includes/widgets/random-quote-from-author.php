<?php

class mg_qt_Random_Quote_From_Author extends WP_Widget {

	private $factory_settings = array(
		'title' => '', 
		'author' => ''
	);

	public function __construct() {
		parent::__construct(
			'mg_qt_widget_random_quote_from_author',
			__('Random Quote from Author', 'mg_qt'),
			array(
				'description' => __('Pick a random quotes from an author', 'mg_qt'),
				'classname' => 'mg_qt_widget_random_quote_from_author'
			) 
		);
	}
	
	public function widget($args, $instance) {
		$instance = wp_parse_args((array)$instance, $this->factory_settings);
		
		if (empty($instance['author']))
			return;
			
		$quote_markup = mg_qt_get_random_quote_from_author($instance['author']);
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
		$authors = mg_qt_get_authors();
		$quote_author = $instance['author'];
		if ($quote_author !== '' && !in_array($quote_author, $authors))
			$quote_author = '';
		
		$title_field_id = $this->get_field_id('title');
		$title_field_name = $this->get_field_name('title');
		$author_field_id = $this->get_field_id('author');
		$author_field_name = $this->get_field_name('author');
		
		?>
			<p>
				<label for="<?php echo $title_field_id; ?>"><?php _e('Title:', 'mg_qt'); ?></label> 
				<input 
					id="<?php echo $title_field_id; ?>" 
					type="text" 
					name="<?php echo $title_field_name; ?>" 
					value="<?php echo $title; ?>"
					class="widefat" 
				>
			</p>
			<p>
				<label for="<?php echo $author_field_id; ?>"><?php _e('Author:', 'mg_qt'); ?></label> 
				<select id="<?php echo $author_field_id?>" name="<?php echo $author_field_name; ?>" class="widefat">
					<option value=""<?php selected($quote_author, ''); ?>>Select an author</option>
					<?php foreach ($authors as $author): ?>
						<option 
							value="<?php echo esc_attr($author); ?>"
							<?php selected($quote_author, $author); ?>
						>
							<?php echo esc_html($author); ?>
						</option>
					<?php endforeach; ?>
				</select>
			</p>
		<?php
	}
	
	public function update($new_instance, $old_instance) {
		$instance = $old_instance;
		
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['author'] = strip_tags($new_instance['author']);
		
		return $instance;
	}

}