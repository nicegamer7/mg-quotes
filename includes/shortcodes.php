<?php

if (!defined('ABSPATH')) exit;

class mg_qt_Shortcodes {

	public function __construct() {
		add_shortcode('quote', array($this, 'quote'));
		add_shortcode('rnd_quote', array($this, 'rnd_quote'));
		add_shortcode('quotes', array($this, 'quotes'));
	}
	
	function quote($atts) {
		extract(shortcode_atts(array(
			'id' => '0'
		), $atts));
		
		$id = absint($id);
		
		return $id > 0 ? mg_qt_markup(mg_qt_Query::quote_by_id($id)) : '';
	}
	
	function rnd_quote($atts) {
		extract(shortcode_atts(array(
			'category' => '',
			'author' => ''
		), $atts));
	
		switch (2 * !empty($category) + !empty($author)) {
			case 0:
				$quote = mg_qt_Query::rnd_quote();
				break;
			case 1:
				$quote = mg_qt_Query::rnd_quote_from_author_name($author);
				break;
			case 2:
				$quote = mg_qt_Query::rnd_quote_from_category_name($category);
				break;
			case 3:
				$category = get_term_by('name', $category, 'mg_qt_category');
				$author = get_term_by('name', $author, 'mg_qt_author');
				$quote = mg_qt_Query::rnd_quote_from_cat_and_author($category->term_id, $author->term_id);
				break;
		}
		
		return mg_qt_markup($quote);
	}
	
	function quotes($atts) {
		/* extract(shortcode_atts(array(
		), $atts)); */
		
		$quotes = mg_qt_Query::get_quotes();
		
		if (empty($quotes))
			return '';
			
		$wrapper_begin = apply_filter('mg_qt_quotes_begin', '<div class="mg-qt-quotes">');
		$wrapper_end = apply_filter('mg_qt_quotes_end', '</div>');
		
		return $wrapper_begin . implode(array_map('mg_qt_markup', $quotes)) . $wrapper_end; 
	}

}

new mg_qt_Shortcodes();
