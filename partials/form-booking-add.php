<?php



$events = "";
if ( class_exists( 'EM_Events' ) ) {
	$args = array(
		'event_status' => 1,
		'scope' => 'future',
		'orderby' => 'event_start_date, event_name',
		'order' => 'ASC',
		'limit' => 50,
	);
	$events = EM_Events::get( $args );
}
?>

<div id="container-booking-add">
	<h1 style="color:#ff0084">TEST MY BOOKING FORM</h1>
	<form action="" id="attendance-form" method="POST">
		<fieldset>
			<label for="event"><?php _e( 'Event:', 'wcm' ) ?></label>
			<select name="event" id="event" class="required">
				<option>Please select an event</option>
				<?php foreach ( $events as $event ): ?>
					<?php
					/* Filter events without RSVP enabled
					*
					 * TODO: Test to make sure events with no space left are not shown.
					 */
					if ($event->get_bookings()->get_available_spaces() <= 0) {
						break;
					}
					?>

					<option
						value="<?php echo $event->event_id; ?>"
						data-start-datetime="<?php echo date( 'Y-m-d H:i', strtotime( $event->event_start_date . " " . $event->event_start_time ) ); ?>"
						data-end-datetime="<?php echo date( 'Y-m-d H:i', strtotime( $event->event_end_date . " " . $event->event_end_time ) ); ?>"
					>
						<?php printf( __('%s - %s (%s)'),
							$event->event_start_date,
							$event->event_name,
							date('l', strtotime($event->event_start_date))
						);
						?>
					</option>
				<?php endforeach; ?>
			</select>
		</fieldset>

		<fieldset>
			<label for="patroller"><?php _e( 'Patroller:' ) ?></label>

			<input type="text" name="patroller" id="patroller" class="required"
				   autocomplete="off"/>
			<input type="hidden" name="patroller-id" id="patroller-id" class="required"
				   autocomplete="off"/>
		</fieldset>
	</form>
</div>