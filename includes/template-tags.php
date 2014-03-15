<?php

/*
 * Pick one random quote
 */
function mg_qt_get_random_quote() {
	global $mg_qt_template_loader, $mg_qt;
	
	$mg_qt = array();
	
	$q = new WP_Query(array(
		'post_type' => 'mg_qt_quote',
		'orderby' => 'rand',
		'posts_per_page' => 1
	));
	
	if (!$q->have_posts())
		return;
		
	$q->the_post();
	$mg_qt['quote'] = get_the_content();
	$mg_qt['author'] = get_post_meta(get_the_ID(), 'mg_qt_author', true);
	
	wp_reset_postdata();
	
	ob_start();
	$mg_qt_template_loader->get_template_part('quote');
	$html = ob_get_clean();
	
	return $html;
}

/*
 * Get a quote by its ID.
 */
function mg_qt_get_quote($id) {
	global $mg_qt_template_loader, $mg_qt;
	
	// check $id
	
	$mg_qt = array();
	
	$q = new WP_Query(array(
		'post_type' => 'mg_qt_quote',
		'p' => $id
	));
	
	if (!$q->have_posts())
		return;
		
	$q->the_post();
	$mg_qt['quote'] = get_the_content();
	$mg_qt['author'] = get_post_meta($id, 'mg_qt_author', true);
	
	wp_reset_postdata();
	
	ob_start();
	$mg_qt_template_loader->get_template_part('quote');
	$html = ob_get_clean();
	
	return $html;
}

/*
 * Get all quotes within a given category
 *
 * $cat: the category name
 */
function mg_qt_get_random_quote_from_category_name($cat_name) {
	$term = get_term_by('name', $cat_name, 'mg_qt_category');
	// check if found
	return mg_qt_get_random_quote_from_category_slug($term->slug); // get_term_field()
}
 
function mg_qt_get_random_quote_from_category_id($cat_id) {
	$term = get_term($cat_id, 'mg_qt_category');
	// check if found
	return mg_qt_get_random_quote_from_category_slug($term->slug); // get_term_field()
}

function mg_qt_get_random_quote_from_category_slug($cat_slug) {
	global $mg_qt_template_loader, $mg_qt;
	
	$q = new WP_Query(array(
		'post_type' => 'mg_qt_quote',
		'orderby' => 'rand',
		'posts_per_page' => 1,
		'mg_qt_category' => $cat_slug
	));
	
	if (!$q->have_posts())
		return;
		
	$mg_qt = array();
		
	$q->the_post();
	$mg_qt['quote'] = get_the_content();
	$mg_qt['author'] = get_post_meta(get_the_ID(), 'mg_qt_author', true);
	
	wp_reset_postdata();
	
	ob_start();
	$mg_qt_template_loader->get_template_part('quote');
	$html = ob_get_clean();
	
	return $html;
}
 
/*
 * echoing template tags
 *
 */
 
/* function mg_qt_random_quote() {
	echo mg_qt_get_random_quote();
}

function mg_qt_quote($id) {
	echo mg_qt_get_quote($id);
}
 
function mg_qt_random_category($cat) {
	echo mg_qt_get_random_category($cat);
} */