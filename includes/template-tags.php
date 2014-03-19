<?php

/*
 * Pick one random quote
 */
function mg_qt_get_random_quote() {
	return mg_qt_get_quote(array(
		'post_type' => 'mg_qt_quote',
		'orderby' => 'rand',
		'posts_per_page' => 1
	));
}

/*
 * Get a quote by its ID.
 */
function mg_qt_get_quote_by_id($id) {
	return !is_int($id) || $id === 0 ? '' :  
		mg_qt_get_quote(array(
			'post_type' => 'mg_qt_quote',
			'p' => $id
		));
}

/*
 * Get all quotes within a given category
 *
 * $cat: the category name
 */
function mg_qt_get_random_quote_from_category_name($cat_name) {
	$term = get_term_by('name', $cat_name, 'mg_qt_category');
	
	return $term === false ? '' :  
		mg_qt_get_random_quote_from_category_slug($term->slug); // get_term_field()
}
 
function mg_qt_get_random_quote_from_category_id($cat_id) {
	$term = get_term($cat_id, 'mg_qt_category');
	
	return $term === null || is_wp_error($term) ? '' :  
		mg_qt_get_random_quote_from_category_slug($term->slug); // get_term_field()
}

function mg_qt_get_random_quote_from_category_slug($cat_slug) {
	return  mg_qt_get_quote(array(
		'post_type' => 'mg_qt_quote',
		'orderby' => 'rand',
		'posts_per_page' => 1,
		'mg_qt_category' => $cat_slug
	));
}

/*
 * Get a random quote from an author
 */
function mg_qt_get_random_quote_from_author($author) {
	return  mg_qt_get_quote(array(
		'post_type' => 'mg_qt_quote',
		'meta_key' => 'mg_qt_author',
		'meta_value' => $author,
		'orderby' => 'rand',
		'posts_per_page' => 1
	));
}
 
/*
 * echoing template tags
 *
 */
 
function mg_qt_random_quote() {
	echo mg_qt_get_random_quote();
}

function mg_qt_quote($id) {
	echo mg_qt_get_quote($id);
}
 
function mg_qt_random_quote_from_category_id($id) {
	echo mg_qt_get_random_quote_from_category_id($id);
}

function mg_qt_random_quote_from_category_name($name) {
	echo mg_qt_get_random_quote_from_category_name($name);
}

function mg_qt_random_quote_from_category_slug($slug) {
	echo mg_qt_get_random_quote_from_category_slug($slug);
}

function mg_qt_random_quote_from_author($author) {
	echo mg_qt_get_random_quote_from_author($author);
}

/*
 *
 */

function mg_qt_get_quote($query) {
	global $mg_qt_template_loader, $mg_qt;
	
	$q = new WP_Query($query);
	
	if (!$q->have_posts())
		return '';
		
	$q->the_post();
	
	$mg_qt['quote'] = get_the_content();
	$post_id = get_the_ID();
	$mg_qt['author'] = get_post_meta($post_id, 'mg_qt_author', true);
	$mg_qt['mg_qt_where'] = get_post_meta($post_id, 'mg_qt_where', true);
	$mg_qt['mg_qt_url'] = get_post_meta($post_id, 'mg_qt_url', true);
	$mg_qt['mg_qt_when'] = get_post_meta($post_id, 'mg_qt_when', true);
	$mg_qt['mg_qt_notes'] = get_post_meta($post_id, 'mg_qt_notes', true);
	
	wp_reset_postdata();
	
	ob_start();
	$mg_qt_template_loader->get_template_part('quote');
	$html = ob_get_clean();
	
	return apply_filters('mg_qt_quote_markup', $html, $mg_qt);
}
