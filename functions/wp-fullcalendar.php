<?php

//function remove_qtip_content_action() {
//	remove_action( 'init', 'parent_function' );
//}
//add_action( 'wp_loaded', 'remove_qtip_content_action' );

add_action('wp_ajax_wpfc_qtip_content', 'qtip_content_override', 50 );
add_action('wp_ajax_nopriv_wpfc_qtip_content', 'qtip_content_override', 50 );

function qtip_content_override($content) {
	$stop = true;
}

/**
 * Overrides the original qtip_content function and provides Event Manager formatted event information
 * @param string $content
 * @return string
 */
function ysp_qtip_content( $content='' ){
	if( !empty($_REQUEST['event_id'] ) && trim(get_option('dbem_emfc_qtips_format')) != '' ){
		global $EM_Event;
		$EM_Event = em_get_event($_REQUEST['event_id']);
		if( !empty($EM_Event->event_id) ){
			$content = $EM_Event->output(get_option('dbem_emfc_qtips_format', '#_EXCERPT'));
		}
	}
	return $content;
}
add_filter('wpfc_qtip_content', 'ysp_qtip_content');