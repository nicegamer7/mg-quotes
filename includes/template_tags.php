<?php

function mg_qt_quote($id) {
	global $mg_qt_template_loader, $mg_qt;
	
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
	
	$mg_qt_template_loader->get_template_part('quote');
}

function mg_qt_random() {
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
	
	$mg_qt_template_loader->get_template_part('quote');
}
			
//mg_qt_random_category(/* cat slug or/and name? */);
//mg_qt_random_author(/* the author string */);