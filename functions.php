<?php

// Modifications to WP_Full_Calendar
require_once( get_stylesheet_directory() . '/functions/wp-fullcalendar.php' );

// Modifications to WP_Full_Calendar
require_once( get_stylesheet_directory() . '/functions/menus.php' );

require_once( get_stylesheet_directory() . '/functions/events-manager.php' );


add_filter('ass_send_email_args', 'prevent_attendance_email', 50, 2);
function prevent_attendance_email($args, $email_type) {
	if ("bp-ges-single" == $email_type && "new_booking" == $args['activity']->type) {
		$args['tokens']['recipient.id'] = -1;
	}

	return $args;
}




//get patrollers name for Bookings and Attendance, TODO: Move to separate plugin
add_action('wp_ajax_get_patrollers', 'ajax_patrollers');
function ajax_patrollers() {
	global $wpdb; //get access to the WordPress database object variable

	//get names of all businesses
	$name = $wpdb->esc_like(stripslashes($_POST['patroller'])).'%'; //escape for use in LIKE statement
	$sql = "select ID, display_name 
		from $wpdb->users
		where display_name like %s";

	$sql = $wpdb->prepare($sql, $name);

	$results = $wpdb->get_results($sql);

	//copy the business titles to a simple array
	$patrollers = array();
	foreach( $results as $r )
		$patrollers[] = [
			"value" => $r->ID,
			"label" => addslashes($r->display_name)
		];

	echo json_encode($patrollers);

	die(); //stop "0" from being output
}