<?php
/**
 * TODO: Rename me to make more sense... and move me to a different file as required
 * Inside additional hook to output the form into the content section
 */
add_action('output_booking_form', 'output_booking_form');
function output_booking_form() {
	add_action('generate_after_entry_content', 'generate_booking_form');
}

function generate_booking_form() {
	get_template_part( 'partials/form-booking-add' );
}


