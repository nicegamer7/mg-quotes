<?php

add_shortcode('mg_qt_rnd_quote', 'mg_qt_sc_rnd_quote');
add_shortcode('mg_qt_quote_by_id', 'mg_qt_sc_quote_by_id');
add_shortcode('mg_qt_rnd_quote_from_category_name', 'mg_qt_sc_rnd_quote_from_category_name');
add_shortcode('mg_qt_rnd_quote_from_category_id', 'mg_qt_sc_rnd_quote_from_category_id');
add_shortcode('mg_qt_rnd_quote_from_category_slug', 'mg_qt_sc_rnd_quote_from_category_slug');
add_shortcode('mg_qt_rnd_quote_from_author_name', 'mg_qt_sc_rnd_quote_from_author_name');

function mg_qt_sc_rnd_quote() {
	return mg_qt_get_rnd_quote();
}

function mg_qt_sc_quote_by_id($atts) {
	extract(shortcode_atts(array(
		'id' => '0'
	), $atts));
	
	$id = absint($id);
	
	return $id > 0 ? mg_qt_get_quote_by_id($id) : '';
}

function mg_qt_sc_rnd_quote_from_category_name($atts) {
	extract(shortcode_atts(array(
		'name' => ''
	), $atts));
	
	return !empty($name) ? mg_qt_get_rnd_quote_from_category_name($name) : '';
}

function mg_qt_sc_rnd_quote_from_category_id($atts) {
	extract(shortcode_atts(array(
		'id' => 0
	), $atts));
	
	$id = absint($id);
	
	return $id > 0 ? mg_qt_get_rnd_quote_from_category_id($id) : '';
}

function mg_qt_sc_rnd_quote_from_category_slug($atts) {
	extract(shortcode_atts(array(
		'slug' => ''
	), $atts));
	
	return !empty($slug) ? mg_qt_get_rnd_quote_from_category_slug($slug) : '';
}

function mg_qt_sc_rnd_quote_from_author_name($atts) {
	extract(shortcode_atts(array(
		'name' => ''
	), $atts));
	
	return !empty($name) ? mg_qt_get_rnd_quote_from_author_name($name) : '';
}
