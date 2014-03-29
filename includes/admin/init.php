<?php

// Setup CPT
add_action('init', 'mg_qt_setup_post_type');
add_action('init', 'mg_qt_register_taxonomies');
require_once MG_QT_INCLUDES . 'admin/meta-box.php';

//
add_action('wp_insert_post_data', 'mg_qt_quote_title', 10, 2);

// CPT list table customization
add_filter('manage_mg_qt_quote_posts_columns', 'mg_qt_custom_columns');
add_filter('manage_posts_custom_column', 'mg_qt_custom_columns_data', 10, 2);
add_filter('months_dropdown_results', 'mg_qt_remove_months_dropdown', 10, 2);
add_action('restrict_manage_posts', 'mg_qt_category_dropdown');

function mg_qt_setup_post_type() {
	$labels =  array(
		'name' 				=> __('Quotes', 'mg_qt'),
		'singular_name' 	=> __('Quote', 'mg_qt'),
		'add_new' 			=> __( 'Add New', 'mg_qt' ),
		'add_new_item' 		=> __( 'Add New Quote', 'mg_qt' ),
		'edit_item' 		=> __( 'Edit Quote', 'mg_qt' ),
		'new_item' 			=> __( 'New Quote', 'mg_qt' ),
		'all_items' 		=> __( 'All Quotes', 'mg_qt' ),
		'view_item' 		=> __( 'View Quote', 'mg_qt' ),
		'search_items' 		=> __( 'Search Quotes', 'mg_qt' ),
		'not_found' 		=> __( 'No Quotes found', 'mg_qt' ),
		'not_found_in_trash'=> __( 'No Quotes found in Trash', 'mg_qt' ),
		'menu_name' 		=> __( 'Quotes', 'mg_qt' )
	);
	
	$args = array(
		'labels'               => $labels,
		'description'          => '',
		'hierarchical'         => false,
		'public'               => true,
		//'menu_position'        => null,
		'menu_icon'            => null,
		'capability_type'      => 'post',
		'capabilities'         => array(),
		'map_meta_cap'         => null,
		'supports'             => array(/* 'title',  */'editor'),
		'register_meta_box_cb' => null,
		'taxonomies'           => array(),
		'has_archive'          => false,
		'rewrite'              => array('slug' => 'quotes'),
		'query_var'            => true,
		'can_export'           => true,
		'delete_with_user'     => null,
		'_builtin'             => false,
		'_edit_link'           => 'post.php?post=%d',
	);
	
	register_post_type('mg_qt_quote', $args);
}

function mg_qt_register_taxonomies() {
	register_taxonomy('mg_qt_category', 'mg_qt_quote', array(
		'query_var' => true,
		'label' => __('Quote Categories', 'mg_qt'),
		'rewrite' => array('slug' => __('Category', 'mg_qt')),
		'hierarchical' => true
		//'show_admin_column' => true
		//'capabilities' => array()
	));
}

function mg_qt_quote_title($data, $postarr) {
	if ($data['post_type'] === 'mg_qt_quote' && empty($data['post_title'])) {
		$title = $data['post_content'];
		$title = strip_shortcodes($title);
		$title = apply_filters('the_content', $title);
		$title = str_replace(']]>', ']]&gt;', $title);
		$title = wp_trim_words($title, 10);
		
		$data['post_title'] = $title;
	}
	
	return $data;
}

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
