<?php
add_action( 'wcm_attendees_script', 'attendees_bookings' );
function attendees_bookings() {
	wp_enqueue_script(
		'attendees_bookings', get_stylesheet_directory_uri() . '/assets/js/attendees-booking.js',
			array( 'jquery' ),
		false,
		true
	);

	// in JavaScript, object properties are accessed as ajax_object.ajax_url, ajax_object.we_value
	wp_localize_script(
		'attendees_bookings',
		'ajax_object',
		array(
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'we_value' => 1234
		)
	);
}