<?php

add_action('add_meta_boxes_mg_qt_quote', 'mg_qt_add_register_meta_boxes');
add_action('save_post_mg_qt_quote', 'mg_qt_save_meta_box');

function mg_qt_add_register_meta_boxes() {
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
	$url = get_post_meta($post->ID, 'mg_qt_url', true);
	$when = get_post_meta($post->ID, 'mg_qt_when', true);
	$notes = get_post_meta($post->ID, 'mg_qt_notes', true);
	?>
		<p>
			<label for="quote_author">Who</label>
			<input id="quote_author" name="quote_info[author]" type="text" value="<?php echo $author; ?>" style="width: 50%;"> <!-- escaping -->
		</p>
		
		<p>
			<label for="quote_where">Where</label>
			<input id="quote_where" name="quote_info[where]" type="text" value="<?php echo $where; ?>" style="width: 50%;"> <!-- escaping -->
		</p>
		
		<p>
			<label for="quote_url">Url</label>
			<input id="quote_url" name="quote_info[url]" type="text" value="<?php echo $url; ?>" style="width: 50%;"> <!-- escaping -->
		</p>
		
		<p>
			<label for="quote_when">When</label>
			<input id="quote_when" name="quote_info[when]" type="text" value="<?php echo $when; ?>" style="width: 50%;"> <!-- escaping -->
		</p>
		
		<p>
			<label for="quote_notes">Notes</label>
			<textarea id="quote_notes" name="quote_info[notes]" style="width: 50%; height: 120px;"><?php echo $notes; ?></textarea><!-- escaping -->
		</p>
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
		
	$url = $quote_info['url'];
	// sanitize
	if (!empty($url))
			update_post_meta($post_id, 'mg_qt_url', $url);
	else
		delete_post_meta($post_id, 'mg_qt_url');
		
	$when = $quote_info['when'];
	// sanitize
	if (!empty($when))
			update_post_meta($post_id, 'mg_qt_when', $url);
	else
		delete_post_meta($post_id, 'mg_qt_when');
		
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
