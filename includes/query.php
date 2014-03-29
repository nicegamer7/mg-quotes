<?php 

function mg_qt_query_quote($query_args) {
	$quote = array();
	
	$query = new WP_Query($query_args);
	
	if (!$query->have_posts())
		return $quote;
		
	$query->the_post();
	
	$quote['quote'] = get_the_content();
	$quote['quote'] = apply_filters('the_content', $quote['quote']);
	$quote['quote'] = str_replace(']]>', ']]&gt;', $quote['quote']);
	
	$post_id = get_the_ID();
	$quote['author'] = get_post_meta($post_id, 'mg_qt_author', true);
	$quote['mg_qt_where'] = get_post_meta($post_id, 'mg_qt_where', true);
	$quote['mg_qt_url'] = get_post_meta($post_id, 'mg_qt_url', true);
	$quote['mg_qt_when'] = get_post_meta($post_id, 'mg_qt_when', true);
	$quote['mg_qt_notes'] = get_post_meta($post_id, 'mg_qt_notes', true);
	
	wp_reset_postdata();
	
	return $quote;
}

function mg_qt_query_authors() {
	global $wpdb;

	$authors = $wpdb->get_col(
		"SELECT DISTINCT meta_value FROM $wpdb->postmeta WHERE meta_key = 'mg_qt_author' AND post_id IN (SELECT ID from $wpdb->posts where post_type = 'mg_qt_quote' AND post_status = 'publish') ORDER BY meta_value ASC"
	);
	
	return $authors;
}

function mg_qt_query_quotes() {
	$quotes = array();
	
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