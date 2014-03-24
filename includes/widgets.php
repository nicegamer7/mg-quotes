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
		?>
			<p>
				<label for="<?php echo $this->get_field_id('title'); ?>">
					<?php _e('Title:', 'mg_qt'); ?>
				</label> 
				<input
					id="<?php echo $this->get_field_id('title'); ?>" 
					type="text" 
					name="<?php echo $this->get_field_name('title'); ?>" 
					value="<?php echo $title; ?>"
					class="widefat" 
				>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('category'); ?>"><?php _e('Category:', 'mg_qt'); ?></label> 
				<?php
					wp_dropdown_categories(array(
						'taxonomy' => 'mg_qt_category',
						'name' => $this->get_field_name('category'),
						'selected' => $category,
						'show_option_all' => __('View all categories', 'mg_qt'),
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

}

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
		$current_author = $instance['author'];
		$authors = mg_qt_get_authors();
		
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
					<option value="">Select an author</option>
					<?php foreach ($authors as $author): ?>
						<option 
							value="<?php echo esc_attr($author); ?>"
							<?php selected($current_author, $author); ?>
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

class mg_qt_Single_Quote extends WP_Widget {

	private $factory_settings = array(
		'title' => '', 
		'quote' => 0
	);

	public function __construct() {
		parent::__construct(
			'mg_qt_widget_single_quote',
			__('Single Quote', 'mg_qt'),
			array(
				'description' => __('Pick a single quote', 'mg_qt'),
				'classname' => 'mg_qt_widget_single_quote'
			) 
		);
	}
	
	public function widget($args, $instance) {
		$instance = wp_parse_args((array)$instance, $this->factory_settings);
		
		if ($instance['quote'] === 0)
			return;
			
		$quote_markup = mg_qt_get_quote_by_id($instance['quote']);
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
		$current_quote = $instance['quote'];
		$quotes = mg_qt_get_quotes();
		
		$title_field_id = $this->get_field_id('title');
		$title_field_name = $this->get_field_name('title');
		$quote_field_id = $this->get_field_id('quote');
		$quote_field_name = $this->get_field_name('quote');
		
		?>
			<p><label for="<?php echo $title_field_id; ?>"><?php _e('Title:', 'mg_qt'); ?></label> 
			<input class="widefat" id="<?php echo $title_field_id; ?>" name="<?php echo $title_field_name; ?>" type="text" value="<?php echo $title; ?>" /></p>
			<p>
				<label for="<?php echo $quote_field_id; ?>"><?php _e('Author:', 'mg_qt'); ?></label> 
				<select id="<?php echo $quote_field_id?>" name="<?php echo $quote_field_name; ?>" class="widefat">
					<option value="0">Select a quote</option>
					<?php foreach ($quotes as $id => $title): ?>
						<?php
						if (strlen($title) > 30)
							$title = substr($title, 0, 30) . '...';
						?>
						<option 
							value="<?php echo esc_attr($id); ?>"
							<?php selected($current_quote, $id); ?>
						>
							<?php echo esc_html($title); ?>
						</option>
					<?php endforeach; ?>
				</select>
			</p>
		<?php
	}
	
	public function update($new_instance, $old_instance) {
		$instance = $old_instance;
		
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['quote'] = absint($new_instance['quote']);
		
		return $instance;
	}

}

function mg_qt_register_widgets() {
	register_widget('mg_qt_Random_Quote');
	register_widget('mg_qt_Random_Quote_From_Category');
	register_widget('mg_qt_Random_Quote_From_Author');
	register_widget('mg_qt_Single_Quote');
}