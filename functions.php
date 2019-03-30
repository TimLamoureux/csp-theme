<?php

/**
 * Load theme widgets
 * TODO: Load in a loop
 */
require_once( get_stylesheet_directory() . '/widgets/user-bookings-list.php' );




/**
 * Disable mobile menu for secondary navigation
 */
add_action( 'wp_enqueue_scripts', 'generate_dequeue_secondary_nav_mobile', 999 );
function generate_dequeue_secondary_nav_mobile() {
	wp_dequeue_style( 'generate-secondary-nav-mobile' );
}


add_action( 'wp_enqueue_scripts', 'theme_scripts' );
function theme_scripts() {
	wp_enqueue_script(
		'csp-theme',
			get_stylesheet_directory_uri() . '/assets/js/skipatrol.js',
			array('jquery-ui-autocomplete'),
			false,
			true
	);
}


// TODO: Implement min in all enqueues
$min = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';
require_once( get_stylesheet_directory() . '/functions/conditional_enqueue.php' );
$conditional_enqueue = new Conditional_Enqueuer();
if ( wp_script_is( 'bp-jquery-cookie', 'enqueued' ) ) {
	return;
} else {
	$conditional_enqueue->add_script(
		'bp-jquery-cookie',
		plugin_dir_url( __FILE__ ) . 'assets/js/jquery-cookie.min.js',
		array( 'jquery' ),
		false,
		true,
		Conditional_Enqueuer::TYPE_PAGE_TEMPLATE,
		[
			'page-templates/page-booking-manage.php'
		]
	);
	// Autocomplete
	$conditional_enqueue->add_script(
		'jquery-ui-autocomplete',
		'',
		null,
		null,
		true,
		Conditional_Enqueuer::TYPE_PAGE_TEMPLATE,
		[
			'page-templates/page-booking-manage.php'
		]
	);
}


// Modifications to WP_Full_Calendar
require_once( get_stylesheet_directory() . '/functions/wp-fullcalendar.php' );

require_once( get_stylesheet_directory() . '/functions/menus.php' );

require_once( get_stylesheet_directory() . '/functions/events-manager.php' );

//require_once( get_stylesheet_directory() . '/functions/attendance.php' );



add_filter( 'ass_send_email_args', 'prevent_attendance_email', 50, 2 );
function prevent_attendance_email( $args, $email_type ) {
	if ( "bp-ges-single" == $email_type && "new_booking" == $args['activity']->type ) {
		$args['tokens']['recipient.id'] = -1;
	}

	return $args;
}


//get patrollers name for Bookings and Attendance, TODO: Move to separate plugin
/*add_action( 'wp_ajax_get_patrollers', 'ajax_patrollers' );
function ajax_patrollers() {
	global $wpdb; //get access to the WordPress database object variable

	//get names of all businesses
	$name = '%' . $wpdb->esc_like( stripslashes( $_POST['patroller'] ) ) . '%'; //escape for use in LIKE statement
	$sql = "select ID, display_name 
		from $wpdb->users
		where display_name like '%s'";

	//$sql = $wpdb->prepare( $sql, $wpdb->esc_like($name) );
	$sql = $wpdb->prepare( $sql, $name );

	$results = $wpdb->get_results( $sql );

	$patrollers = array();
	foreach ( $results as $r )
		$patrollers[] = [
			"value" => $r->ID,
			"label" => addslashes( $r->display_name )
		];

	echo json_encode( $patrollers );

	die(); //stop "0" from being output
}*/

add_action('after_setup_theme', 'remove_admin_bar_users');
function remove_admin_bar_users() {
	if (!current_user_can('administrator') && !is_admin()) {
		show_admin_bar(false);
	}
}

add_action('generate_after_footer', 'print_template_comment');
function print_template_comment() {
	global $template;
	echo "<!-- Page Template: $template -->\n";
}
