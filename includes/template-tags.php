<?php

/*
 * Pick one random quote
 */
function mg_qt_get_rnd_quote() {
	return mg_qt_markup(mg_qt_Query::rnd_quote());
}

/*
 * Get a quote by its ID.
 */
function mg_qt_get_quote_by_id($id) {
	return mg_qt_markup(mg_qt_Query::quote_by_id($id));
}

/*
 * Get all quotes within a given category
 *
 * $cat: the category name
 */
function mg_qt_get_rnd_quote_from_category_name($cat_name) {
	return mg_qt_markup(mg_qt_Query::rnd_quote_from_category_name($cat_name));
}
 
function mg_qt_get_rnd_quote_from_category_id($id) {
	return mg_qt_markup(mg_qt_Query::rnd_quote_from_category_id($id));
}

function mg_qt_get_rnd_quote_from_category_slug($cat_slug) {
	return mg_qt_markup(mg_qt_Query::rnd_quote_from_category_slug($cat_slug));
}

/*
 * Get a random quote from an author
 */
function mg_qt_get_rnd_quote_from_author_name($author_name) {
	return mg_qt_markup(mg_qt_Query::rnd_quote_from_author_name($author_name));
}
 
/*
 * echoing template tags
 *
 */
 
function mg_qt_rnd_quote() {
	echo mg_qt_get_rnd_quote();
}

function mg_qt_quote($id) {
	echo mg_qt_get_quote($id);
}
 
function mg_qt_rnd_quote_from_category_id($id) {
	echo mg_qt_get_rnd_quote_from_category_id($id);
}

function mg_qt_rnd_quote_from_category_name($name) {
	echo mg_qt_get_rnd_quote_from_category_name($name);
}

function mg_qt_rnd_quote_from_category_slug($slug) {
	echo mg_qt_get_rnd_quote_from_category_slug($slug);
}

function mg_qt_rnd_quote_from_author($author) {
	echo mg_qt_get_rnd_quote_from_author($author);
}

function mg_qt_markup($q) {
	global $mg_qt_template_loader, $quote;
	
	if ($q === false)
		return '';
		
	$quote = $q;
	
	ob_start();
		require_once MG_QT_INCLUDES . 'vendor/gamajo-template-loader/class-gamajo-template-loader.php';
		require_once MG_QT_INCLUDES . 'vendor/gamajo-template-loader/class-mg-qt-template-loader.php';
		$mg_qt_template_loader = new mg_qt_Template_Loader();
		$mg_qt_template_loader->get_template_part('quote');
	$html = ob_get_clean();
	
	return apply_filters('mg_qt_quote_markup', $html, $quote);
}
