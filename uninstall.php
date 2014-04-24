<?php

class mg_qt_Uninstall {

	public function __construct() {
		$this->delete_quotes();
		$this->delete_taxonomies();
	}
	
	private function delete_quotes() {
		$quotes = get_posts(array(
			'post_type' => 'mg_qt_quote', 
			'post_status' => 'any', 
			'numberposts' => -1, 
			'fields' => 'ids'
		));

		if (!empty($quotes))
			foreach ($quotes as $quote)
				wp_delete_post($item, true);
	}
	
	private function delete_taxonomies() {
		global $wp_taxonomies;
		
		$taxs = array('mg_qt_category', 'mg_qt_author');
		foreach ($taxs as $tax) {
			$terms = get_terms($tax);
			if ($terms)
				foreach ($terms as $term)
					wp_delete_term($term->term_id, $tax);
			unset($wp_taxonomies[$tax]);
		}
	}

}

new mg_qt_Uninstall();
