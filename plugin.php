<?php
/*
Plugin Name: mg WP Quotes
Plugin URI: http://wp-labs.dev/admin
Description: An easy to use plugin for quotes.
Version: 1.0
Author: Giulio 'mgiulio' Mainardi
Author URI: http://m-giulio.me
License: GPL2
*/

if ( ! defined( 'ABSPATH' ) ) exit;

define('MG_QT_PLUGIN_DIR_PATH', plugin_dir_path(__FILE__));

require_once MG_QT_PLUGIN_DIR_PATH . 'includes/tax-dropdown.php';

require_once MG_QT_PLUGIN_DIR_PATH . 'includes/class-gamajo-template-loader.php';
require_once MG_QT_PLUGIN_DIR_PATH . 'includes/class-mg-qt-template-loader.php';
$mg_qt_template_loader = new mg_qt_Template_Loader();

add_action('init', 'mg_qt_setup_post_type');
add_action('init', 'mg_qt_register_taxonomies');

add_action('wp_insert_post_data', 'mg_qt_quote_title', 10, 2);

// Metaboxes
add_action('add_meta_boxes_mg_qt_quote', 'mg_qp_add_register_meta_boxes');
add_action('save_post_mg_qt_quote', 'mg_qt_save_meta_box');
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
		'public'               => true,
		'hierarchical'         => false,
		'exclude_from_search'  => null,
		'publicly_queryable'   => null,
		'show_ui'              => true,
		'show_in_menu'         => true,
		'show_in_nav_menus'    => null,
		'show_in_admin_bar'    => true,
		'menu_position'        => null,
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
		'label' => __('Category', 'mg_qt'),
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

function mg_qp_add_register_meta_boxes() {
	add_meta_box(
		'mg_qt_quote_info',
		'Attribution Fields',
		'mg_qt_render_attribution_meta_box'
	);
	
	add_meta_box(
		'mg_qt_quote_title',
		'Quote Title',
		'mg_qt_render_title_meta_box'
	);
}

function mg_qt_render_attribution_meta_box($post) {
	$author = get_post_meta($post->ID, 'mg_qt_author', true);
	$where = get_post_meta($post->ID, 'mg_qt_where', true);
	$notes = get_post_meta($post->ID, 'mg_qt_notes', true);
	?>
		<p>
			<label for="quote_author">Who?</label>
			<input id="quote_author" name="quote_info[author]" type="text" value="<?php echo $author; ?>"> <!-- escaping -->
		</p>
		
		<p>
			<label for="quote_where">Where?</label>
			<input id="quote_where" name="quote_info[where]" type="text" value="<?php echo $where; ?>"> <!-- escaping -->
		</p>
		
		<p>
			<label for="quote_notes">Notes</label>
			<textarea id="quote_notes" name="quote_info[notes]"><?php echo $notes; ?></textarea><!-- escaping -->
		</p>
	<?php
}

function mg_qt_render_title_meta_box($post) {
	global $post_type_object;
	?>
	<div id="titlediv">
		<div id="titlewrap">
			<label 
				class="screen-reader-text" 
				id="title-prompt-text" 
				for="title"
			>
				<?php echo __('Enter an optional title here', 'mg_qt'); ?>
			</label>
			<input 
				id="title"
				type="text" 
				name="post_title" 
				size="30" 
				value="<?php echo esc_attr( htmlspecialchars( $post->post_title ) ); ?>"  
				autocomplete="off" 
			/>
		</div>

		<div class="inside">
		<?php
			$sample_permalink_html = $post_type_object->public ? get_sample_permalink_html($post->ID) : '';
			$shortlink = wp_get_shortlink($post->ID, 'post');
			$permalink = get_permalink( $post->ID );
			if ( !empty( $shortlink ) && $shortlink !== $permalink && $permalink !== home_url('?page_id=' . $post->ID) )
				$sample_permalink_html .= '<input id="shortlink" type="hidden" value="' . esc_attr($shortlink) . '" /><a href="#" class="button button-small" onclick="prompt(&#39;URL:&#39;, jQuery(\'#shortlink\').val()); return false;">' . __('Get Shortlink') . '</a>';

			if ( $post_type_object->public && ! ( 'pending' == get_post_status( $post ) && !current_user_can( $post_type_object->cap->publish_posts ) ) ) {
			$has_sample_permalink = $sample_permalink_html && 'auto-draft' != $post->post_status;
				?>
				<div id="edit-slug-box" class="hide-if-no-js">
				<?php
					if ( $has_sample_permalink )
						echo $sample_permalink_html;
				?>
				</div>
			<?php
			}
		?>
		</div>
		<?php wp_nonce_field( 'samplepermalink', 'samplepermalinknonce', false ); ?>
	</div><!-- /titlediv -->
	<?php
}

function mg_qt_save_meta_box($post_id) {
	if (!isset($_POST['quote_info']))
		return;
		
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
		return;
	
	/* if ( !isset($_POST['my_nonce_name']) )
        return; */
		
	// Check user permissions
	if ( !current_user_can('edit_post', $post_id) )
        return;
  
	// Check nonce
	//check_admin_referer('my_action_xyz_'.$post_id, 'my_nonce_name');

	$quote_info = $_POST['quote_info'];
	
	$author = $quote_info['author'];
	// sanitize
	if (!empty($author))
			update_post_meta($post_id, 'mg_qt_author', $author);
	else
		delete_post_meta($post_id, 'mg_qt_author');
		
	$where = $quote_info['where'];
	// sanitize
	if (!empty($where))
			update_post_meta($post_id, 'mg_qt_where', $where);
	else
		delete_post_meta($post_id, 'mg_qt_where');
		
	$notes = $quote_info['notes'];
	// sanitize
	if (!empty($notes))
			update_post_meta($post_id, 'mg_qt_notes', $notes);
	else
		delete_post_meta($post_id, 'mg_qt_notes');
	
	/* foreach (array('author', 'where') as $field) {
		// assert(isset($_POST[$field]))
		
		$value = apply_filter("perfect_quote_sanitize_$field", $_POST[$field]);
		
	} */
}

function mg_qt_log($x) {	
	$out = '';
	ob_start();
	var_dump($x);
	$out .= ob_get_clean();
	
	error_log($out);
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

require_once 'includes/template-tags.php';
require_once 'includes/shortcodes.php';
require_once 'includes/widgets.php';
