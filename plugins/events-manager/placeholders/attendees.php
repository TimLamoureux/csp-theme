<?php
require_once( get_stylesheet_directory() . '/functions/events-manager-attendees.php' );
do_action('wcm_attendees_script');

$can_book1 = (
        is_user_logged_in() ||
        ( get_option( 'dbem_bookings_anonymous' ) && ! is_user_logged_in() )
);
$can_book2 = (
        get_option('dbem_bookings_double') ||
        !$EM_Event->get_bookings()->has_booking(get_current_user_id())
);
$can_book = $can_book1 && $can_book2;

?>
<?php if ( $EM_Event->event_rsvp ): ?>
    <form name='booking-form' method='post'
          action='<?php echo apply_filters( 'em_booking_form_action_url', '' ); ?>#em-booking'>
        <input type='hidden' name='event_id' value='<?php echo $EM_Event->get_bookings()->event_id; ?>'/>
        <input type='hidden' name='_wpnonce' value='<?php echo wp_create_nonce( 'booking_add' ); ?>'/>

		<?php foreach ( $EM_Event->get_tickets() as $ticket ): ?>
			<?php
			$bookings = $ticket->get_bookings();
			?>
            <h3 title="<?php echo( $ticket->description ); ?>">
				<?php
				printf( '%s (%d/%d)',
					$ticket->ticket_name,
					count($bookings->bookings),
                    $ticket->get_spaces()
				);
				?>
            </h3>
            <ul>
				<?php if ( count($bookings->bookings) <= 0 ) : ?>
                    <li style="list-style-type:none"><?php printf( __( 'No %s has registered yet', 'ysp' ), $ticket->ticket_name ); ?></li>
				<?php else: ?>
					<?php foreach ( $bookings as $booking ): ?>
                        <li style="list-style-type:none"><?php printf( "%s %s",
								get_avatar( $booking->get_person()->ID, 50 ),
								$booking->person->data->display_name ); ?></li>
					<?php endforeach; ?>
				<?php endif; ?>
				<?php if ( $ticket->is_available() && $can_book ) : ?>
                    <li style="list-style-type:none">
                        <input
                                type="submit"
                                class="button-primary em-booking-submit"
                                data-ticket-id="<?php echo $ticket->id; ?>"
                                id="em-booking-submit-<?php echo $ticket->id; ?>"
                                value="<?php printf( 'Signup as %s (%d left)', $ticket->ticket_name, $ticket->get_available_spaces() ); ?>"
                        />
                    </li>
				<?php endif; ?>
            </ul>

		<?php endforeach; ?>
    </form>

	<?php
	if ( count( $EM_Bookings->bookings ) > 0 ) {
		?>
        <ul class="event-attendees">
			<?php
//			foreach ( $EM_Bookings as $EM_Booking ) {
//				/* @var $EM_Booking EM_Booking */
//				if ( $EM_Booking->booking_status == 1 && ! in_array( $EM_Booking->get_person()->ID, $people ) ) {
//					$people[] = $EM_Booking->get_person()->ID;
//					echo '<li>' . get_avatar( $EM_Booking->get_person()->ID, 50 ) . ' ' . $EM_Booking->get_person()->first_name . " " . $EM_Booking->get_person()->last_name . '</li>';
//				} elseif ( $EM_Booking->booking_status == 1 && $EM_Booking->is_no_user() ) {
//					echo '<li>' . get_avatar( $EM_Booking->get_person()->ID, 50 ) . '</li>';
//				}
//			}
			?>
        </ul>
		<?php
	}
	?>
<?php endif; ?>
