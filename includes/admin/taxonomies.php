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
		'meta_box_cb'           => 'mg_qt_author_meta_box',
		'capabilities'          => array(),
		'rewrite' => array('slug' => __('Author', 'mg_qt')),
		'query_var'             => 'mg_qt_author',
		'update_count_callback' => '',
		//'show_admin_column' => true // Where is it used?
	));
}

function mg_qt_author_meta_box($post, $box) {
	//mg_qt_log($post);
	//mg_qt_log($box);
	
	$author_terms = get_the_terms($post->ID, 'mg_qt_author');
	if (!empty($author_terms))
		$author_id = $author_terms[0]->term_id;
	else
		$author_id = 0;
		
	$authors = get_terms('mg_qt_author');
	//mg_qt_log($authors);
	?>
		<select name="tax_input[mg_qt_author]">
			<!-- select author ? -->
			<?php foreach ($authors as $author): ?>
				<option value="<?php echo esc_attr($author->term_id); ?>"<?php selected($author_id, $author->term_id); ?>><?php echo esc_html($author->name); ?></option>
			<?php endforeach; ?>
		</select>
	<?php
}
