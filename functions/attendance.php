<?php
add_action( 'output_attendance_form', 'output_attendance_form' );
function output_attendance_form() { ?>





    <?php if ( current_user_can( 'pods_add_wcm-attendance' ) ): ?>
	<?php
	// TODO: Move this logic to a plugin
	$events = "";
	if ( class_exists( 'EM_Events' ) ) {
		$args = array(
			'event_status' => 1,
			/*'scope' => 'past-month',*/
			'scope' => 'past',
			'orderby' => 'event_start_date, event_name',
			'order' => 'DESC',
			'limit' => 50,
		);
		$events = EM_Events::get( $args );
	}

	$users = "";

    // End TODO;
	?>

    <div id="attendance-container">
        <div id="attendance-form-container">
            <div class="error-container"></div>
            <form action="" id="attendance-form" method="POST">
                <fieldset>
                    <label for="event"><?php _e( 'Event:', 'wcm' ) ?></label>
                    <select name="event" id="event" class="required">
                        <option>Please select an event</option>
						<?php foreach ( $events as $event ): ?>
                            <option
                                    value="<?php echo $event->event_id; ?>"
                                    data-start-datetime="<?php echo date( 'Y-m-d H:i', strtotime( $event->event_start_date . " " . $event->event_start_time ) ); ?>"
                                    data-end-datetime="<?php echo date( 'Y-m-d H:i', strtotime( $event->event_end_date . " " . $event->event_end_time ) ); ?>"
                            ><?php echo $event->event_start_date . " " . $event->event_name; ?></option>
						<?php endforeach; ?>
                    </select>
                </fieldset>

                <fieldset>
                    <label for="patroller"><?php _e( 'Patroller:', 'wcm' ) ?></label>

                    <input type="text" name="patroller" id="patroller" class="required"
                           autocomplete="off"/>
                    <input type="hidden" name="patroller-id" id="patroller-id" class="required"
                           autocomplete="off"/>
                </fieldset>

                <fieldset>
                    <label for="type"><?php _e( 'Type:', 'wcm' ) ?></label>

					<?php
					$pods = pods( 'wcm-attendance' );
					echo $pods->form( array(
						'fields_only' => true,
						'fields' => array(
							'type'
						)
					) );
					?>
                </fieldset>

                <fieldset>
                    <label for="time-start"><?php _e( 'Start time:', 'wcm' ) ?></label>

                    <input name="time-start" id="time-start" rows="8" cols="30"
                           class="required flatpickr-time"></input>
                </fieldset>

                <fieldset>
                    <label for="time-end"><?php _e( 'End time:', 'wcm' ) ?></label>

                    <input name="time-end" id="time-end" rows="8" cols="30"
                           class="required flatpickr-time"></input>
                </fieldset>

                <fieldset>
                    <label for="notes"><?php _e( 'Notes:', 'wcm' ) ?></label>

                    <textarea name="notes" id="notes" rows="8" cols="30" class="required"></textarea>
                </fieldset>

                <!-- 	<input type="hidden" name="event-start-date" id="event-start-date" value="" />
				<input type="hidden" name="event-end-date" id="event-end-date" value="" /> -->
                <input type="hidden" name="submitted" id="submitted" value="true"/>

				<?php wp_nonce_field( 'attendance-form', 'attendance-form-nonce' ); ?>
                <button id="attendance-submit" class="btn btn-default"
                        type="submit"><?php _e( 'Add Attendance', 'framework' ) ?></button>

            </form>

        </div>

        <div id="attendance-list">
            <table id="event-attendance">
                <thead>
                <tr>NAME OF EVENT</tr>
                <tr>
                    <td>Patroller</td>
                    <td>Type</td>
                    <td>In</td>
                    <td>Out</td>
                    <td>Total</td>
                    <td>Notes</td>
                    <td></td>
                </tr>
                </thead>
                <tbody>

                </tbody>
                <tfoot>
                <tr>
                    <td>Total number</td>
                    <td></td>
                    <td></td>
                    <td>Total cumulative time</td>
                    <td></td>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
<?php else: ?>
    <div>You are not authorized to manage attendances</div>
<?php endif; ?>


<!-- TODO: Move me to functions.php
<link rel="stylesheet" href="https://unpkg.com/flatpickr/dist/flatpickr.min.css">
<script src="https://unpkg.com/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/js-cookie@2/src/js.cookie.min.js"></script>
-->
    <?php
    // TODO Might be enqueued too late...
    wp_enqueue_style('flatpickr', 'https://unpkg.com/flatpickr/dist/flatpickr.min.css', null, false );
    wp_enqueue_script('flatpickr', "https://unpkg.com/flatpickr", null, false, true);
	wp_enqueue_script('jscookie', "https://cdn.jsdelivr.net/npm/js-cookie@2/src/js.cookie.min.js", null, false, true);

    ?>

<!-- End todo -->

<?php
}