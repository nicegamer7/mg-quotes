<?php

add_filter('manage_mg_qt_quote_posts_columns', 'mg_qt_custom_columns');
add_filter('manage_posts_custom_column', 'mg_qt_custom_columns_data', 10, 2);
add_filter('months_dropdown_results', 'mg_qt_remove_months_dropdown', 10, 2);
add_action('restrict_manage_posts', 'mg_qt_category_dropdown');

function mg_qt_custom_columns($columns) {
	$columns = array(
		'cb' => '<input type="checkbox">',
		'title' => __('Title', 'mg_qt'),
		'quote_author' => __('Author', 'mg_qt'),
		'taxonomy-mg_qt_category' => __('Category', 'mg_qt')
		//'quote_category' => __('Category', 'mg_qt')
	);
	
	return $columns;
}

function mg_qt_custom_columns_data($column_id, $post_id) {
	switch ($column_id) {
		case 'quote_author':
			echo get_post_meta($post_id, 'mg_qt_author', true);
			break;
		/* case 'quote_category':
			 break;*/
	}
}

function mg_qt_remove_months_dropdown($months, $post_type) {
	return $post_type === 'mg_qt_quote' ? array() : $months;
}

function mg_qt_category_dropdown() {
	global $typenow;
    
	if ($typenow !== 'mg_qt_quote')
		return;
		
	$selected = get_query_var('mg_qt_category');
	if ($selected === '')
		$selected = 0;
		
	require_once MG_QT_INCLUDES . 'admin/tax-dropdown.php';
		
	wp_dropdown_categories(array(
		'taxonomy' => 'mg_qt_category',
		'name' => 'mg_qt_category',
		'selected' => $selected,
		'walker' => new mg_qt_Walker_TaxonomyDropdown(),
		'show_option_all' => __('View all categories', 'mg_qt'),
		'hierarchical' => 1,
		'orderby' => 'name'
	));
}
