<?php

add_action('init', 'mg_qt_register_taxonomies');

function mg_qt_register_taxonomies() {
	register_taxonomy('mg_qt_category', 'mg_qt_quote', array(
		'label' => __('Quote Categories', 'mg_qt'),
		'labels'                => array(),
		'description'           => 'Quote Categories',
		'public'                => true,
		'hierarchical'          => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'show_in_nav_menus'     => true,
		'show_tagcloud'         => true,
		'meta_box_cb'           => null,
		'capabilities'          => array(),
		'rewrite' => array('slug' => __('Category', 'mg_qt')),
		'query_var'             => 'mg_qt_category',
		'update_count_callback' => '',
		//'show_admin_column' => true // Where is it used?
	));
	
	register_taxonomy('mg_qt_author', 'mg_qt_quote', array(
		'label' => __('Quote Authors', 'mg_qt'),
		'labels'                => array(),
		'description'           => 'Quote Authors',
		'public'                => true,
		'hierarchical'          => false,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'show_in_nav_menus'     => true,
		'show_tagcloud'         => true,
		'meta_box_cb'           => false,
		'capabilities'          => array(),
		'rewrite' => array('slug' => __('Author', 'mg_qt')),
		'query_var'             => 'mg_qt_author',
		'update_count_callback' => '',
		//'show_admin_column' => true // Where is it used?
	));
}
