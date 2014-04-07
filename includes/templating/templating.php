<?php

function mg_qt_markup($q) {
	global $mg_qt_template_loader, $quote;
	
	if ($q === false)
		return '';
		
	$quote = $q;
	
	ob_start();
		require_once MG_QT_INCLUDES . 'templating/gamajo-template-loader/class-gamajo-template-loader.php';
		require_once MG_QT_INCLUDES . 'templating/gamajo-template-loader/class-mg-qt-template-loader.php';
		$mg_qt_template_loader = new mg_qt_Template_Loader();
		$mg_qt_template_loader->get_template_part('quote');
	$html = ob_get_clean();
	
	return apply_filters('mg_qt_quote_markup', $html, $quote);
}
