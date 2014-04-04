<?php 

class mg_qt_Query {
	
	public static function quote_by_id($id) {
		if (!is_int($id) || $id === 0)
			return false;
			
		return self::single_quote(array(
			'post_type' => 'mg_qt_quote',
			'p' => $id
		));
	}
	
	public static function rnd_quote() {
		return self::single_quote(array(
			'post_type' => 'mg_qt_quote',
			'orderby' => 'rand',
			'posts_per_page' => 1
		));
	}
	
	/*
	 * Pick a random quote that has been assigned the given category, 
	 * or one of the category children
	 */
	public static function rnd_quote_from_category_name($cat_name) {
		$term = get_term_by('name', $cat_name, 'mg_qt_category');
	
		if ($term === false)
			return false;
			
		return self::rnd_quote_from_category_slug($term->slug);
	}
	
	public static function rnd_quote_from_category_id($id) {
		$term = get_term($id, 'mg_qt_category');
		
		if ($term === null || is_wp_error($term))
			return false;
	
		return self::rnd_quote_from_category_slug($term->slug); 
	}
	
	public static function rnd_quote_from_category_slug($slug) {
		return self::single_quote(array(
			'post_type' => 'mg_qt_quote',
			'orderby' => 'rand',
			'posts_per_page' => 1,
			'mg_qt_category' => $slug
		));
	}
	
	public static function rnd_quote_from_author_name($name) {
		$term = get_term_by('name', $name, 'mg_qt_author');
	
		if ($term === false)
			return false;
			
		return self::rnd_quote_from_author_slug($term->slug);
	}
	
	public static function rnd_quote_from_author_slug($slug) {
		return self::single_quote(array(
			'post_type' => 'mg_qt_quote',
			'orderby' => 'rand',
			'posts_per_page' => 1,
			'mg_qt_author' => $slug
		));
	}
	
	public static function quote_author_name($post_id) {
		$author_terms = get_the_terms($post_id, 'mg_qt_author');
		return empty($author_terms) ?
			'' :
			array_values($author_terms)[0]->name
		;
	}
	
	public static function authors() {
		return get_terms('mg_qt_authors', array(
			'hide_empty' => false,
			'fields' => 'names'
		));
	}
	
	public static function quote_titles() {
		$titles = array();
		
		$query = new WP_Query(array(
			'post_type' => 'mg_qt_quote',
			'post_status' => 'publish',
			'posts_per_page' => -1
		));
		
		while ($query->have_posts()) {
			$query->the_post();
			$quotes[get_the_ID()] = get_the_title();
		}
		
		wp_reset_postdata();
		
		return $quotes;
	}
	
	/*
	 * Execute a single quote query.
	 * Returns an associative array with the quote fields.
	 * If the quote is not found returns false.
	 */
	private static function single_quote($args) {
		$query = new WP_Query($args);
		
		if (!$query->have_posts())
			return false;
			
		$quote = array();
			
		$query->the_post();
		
		$quote['quote'] = get_the_content();
		$quote['quote'] = apply_filters('the_content', $quote['quote']);
		$quote['quote'] = str_replace(']]>', ']]&gt;', $quote['quote']);
		
		$post_id = get_the_ID();
		
		$quote['author'] = self::quote_author_name($post_id);
		$quote['mg_qt_where'] = get_post_meta($post_id, 'mg_qt_where', true);
		$quote['mg_qt_url'] = get_post_meta($post_id, 'mg_qt_url', true);
		$quote['mg_qt_when'] = get_post_meta($post_id, 'mg_qt_when', true);
		$quote['mg_qt_notes'] = get_post_meta($post_id, 'mg_qt_notes', true);
		
		wp_reset_postdata();
		
		return $quote;
	}
	
}
