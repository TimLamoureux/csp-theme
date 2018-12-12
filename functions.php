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
