<?php

add_action('init', 'mg_qt_register_taxonomies');
add_action('admin_init', 'mg_qt_register_script_styles');
add_action('admin_print_styles-post-new.php', 'mg_qt_injection');
add_action('admin_print_styles-post.php', 'mg_qt_injection');

function mg_qt_register_script_styles() {
	//wp_register_style('mg-links', MG_LINKS_PLUGIN_DIR_URL . 'css/style.css');
	wp_register_script('mg_qt_mb', MG_QT_ASSETS . 'js/mb.js', array('jquery', 'suggest'), '', true);
}

function mg_qt_injection() {
	global $post_type;
	
	if ($post_type !== 'mg_qt_quote')
		return;
	
	//wp_enqueue_style('mg_qt_links');
	wp_enqueue_script('mg_qt_mb');
}

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
