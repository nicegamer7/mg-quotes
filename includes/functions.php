<?php

function mg_qt_get_authors() {
	global $wpdb;

	$authors = $wpdb->get_col(
		"SELECT DISTINCT meta_value FROM $wpdb->postmeta WHERE meta_key = 'mg_qt_author' AND post_id IN (SELECT ID from $wpdb->posts where post_type = 'mg_qt_quote' AND post_status = 'publish') ORDER BY meta_value ASC"
	);
	
	return $authors;
}

function mg_qt_get_quotes() {
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