<?php


class Bookings_List extends WP_Widget {

	var $defaults;

// class constructor
	public function __construct() {
		// TODO: Update defaults to what I really need
		$this->defaults = array(
			'title' => __( 'Bookings List', 'wcm' ),
			'scope' => 'future',
			'order' => 'ASC',
			'limit' => 5,
			'category' => 0,
			'format' => '<li>#_EVENTLINK<ul><li>#_EVENTDATES</li><li>#_LOCATIONTOWN</li></ul></li>',
			'nolistwrap' => false,
			'orderby' => 'event_start_date,event_start_time,event_name',
			'all_events' => 0,
			'all_events_text' => __( 'all events', 'dbem' ),
			'no_events_text' => '<li>' . __( 'No events', 'dbem' ) . '</li>'
		);

		//add_filter( 'em_bookings_build_sql_conditions', array( __CLASS__, 'sql_date_condition' ) );

		parent::__construct(
			'wcm_bookings_list',
			'Bookings list',
			array(
				'classname' => 'bookings_list',
				'description' => __( 'Widget to display patrollers bookings', 'wcm' ),
			) );
	}

	public function sql_date_condition( $conditions ) {

		return $conditions;
	}

// output the widget content on the front-end
	public function widget( $args, $instance ) {
		$instance = array_merge( $this->defaults, $instance );

		echo $args['before_widget'];

		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'];
			echo apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base );
			echo $args['after_title'];
		}


		$me = wp_get_current_user();
		$uid = 0;
		if ( function_exists( 'bp_is_active' ) ) {
			$uid = bp_displayed_user_id();
		}

		if ( 0 == $uid ) {
			$uid = wp_get_current_user()->ID;
		}


		$bookings = EM_Bookings::get( array(
			date_from => '2018-11-01',
			date_to => '2019-02-20',
			person => $uid,
			status => 1,
			limit => 10
		) );
		//$bookings = EM_


		global $wpdb;

		ob_start();?>

		SELECT
		b.person_id as uid,
		u.user_nicename as name,
		COUNT(b.booking_id) as count
		FROM
		<?php echo EM_BOOKINGS_TABLE; ?> as b,
		<?php echo EM_EVENTS_TABLE; ?> as e,
		<?php echo $wpdb->posts; ?> as p,
		<?php echo $wpdb->users; ?> as u,
		<?php echo $wpdb->term_relationships; ?> as t
		WHERE
		(b.event_id = e.event_id) AND
		(e.post_id = p.ID) AND
		(b.person_id = u.ID) AND
		(t.object_id = p.ID) AND
		(t.term_taxonomy_id = 123) AND
		(b.booking_date BETWEEN '2018-12-01' AND '2019-02-01') AND
		(b.booking_status = 1)
		GROUP BY b.person_id
        ORDER BY name ASC

		<?php
		$query = ob_get_clean();
		$query = trim(preg_replace('/\s+/', ' ', $query));

		$result = $wpdb->get_results($query, ARRAY_A);

		$admin_cap = current_user_can('administrator');
		?>

		<table>
			<thead>
				<th>Name</th>
				<th>Number of events</th>
			</thead>
			<?php foreach($result as $r) : ?>
				<?php if ( $admin_cap || $r['uid'] == $uid ): ?>
					<tr>
						<td><?php echo $r['name']; ?></td>
						<td><?php echo $r['count']; ?></td>
					</tr>
				<?php endif; ?>
			<?php endforeach; ?>
		</table>


		<?php

		echo $args['after_widget'];

	}

// output the option form field in admin Widgets screen
	public function form( $instance ) {
		/*
		 * TODO: Options for choosing which patroller (self, specific or all)
		 * Option to show date range
		 */
	}

// save options
	public function update( $new_instance, $old_instance ) {
	}
}

add_action( 'widgets_init', function () {
	register_widget( 'Bookings_List' );
} );
?>