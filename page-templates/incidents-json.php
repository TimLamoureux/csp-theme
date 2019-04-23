<?php
/**
 * Template Name: Output incidents as JSON
 * Page to retrieve specific incidents
 */

/**
 * TODO: Prevent unauthorized (public) access. Implement additional role verification
 */

if ( ! is_user_logged_in() ) {
	die( 'You must be logged in to retrieve incidents' );
}

class Incident {
	public $date;
	public $time;
	public $age;
	public $gender;
	public $activity;
	public $ability;
	public $ticket_type;
	public $injury_location = array();
	public $treatment = array();
	public $transport_base;
	public $transport_out;
	public $location;
	public $destination;
	public $patrollers = array();
	public $created;
	public $modified;

}

$hierarchical_locations = function ( $arr, $loc, $parent ) {
	$arr2 = [];

	return $arr2;
};

function validateDate( $date, $format = 'Y-m-d' ) {
	$d = DateTime::createFromFormat( $format, $date );

	// The Y ( 4 digits year ) returns TRUE for any integer with any number of digits so changing the comparison from == to === fixes the issue.
	return $d && $d->format( $format ) === $date;
}

// Retrieve start and end
$format = 'Y-m-d';
$start = (
validateDate( $_GET['start'] ) ?
	filter_var( $_GET['start'], FILTER_SANITIZE_STRING ) :
	date_format( date_modify( new DateTime(), '-1 month' ), $format )
);
$end = (
validateDate( $_GET['end'] ) ?
	filter_var( $_GET['end'], FILTER_SANITIZE_STRING ) :
	date_format( new DateTime(), $format )
);


$incidents = pods( 'wcm_incidents', [
	"orderby" => "t.date ASC",
	"limit" => -1,
	'where' => "t.date BETWEEN '${start}' AND '${end}'",
	"cache_mode" => "cache"
] );

$incidents_array = Array();

while ( $incidents->fetch() ) {
	$incident = new Incident();

	$incident->created = $incidents->field( 'created' );
	$incident->modified = $incidents->field( 'modified' );
	$incident->date = $incidents->field( 'date' );
	$incident->time = $incidents->field( 'time' );
	$incident->age = $incidents->field( 'age' );
	$incident->gender = $incidents->field( 'gender' );
	$incident->activity = $incidents->field( 'activity' );
	$incident->ability = $incidents->field( 'ability' );
	$incident->ticket_type = $incidents->field( 'ticket_type' );
	$incident->transport_base = $incidents->field( 'trp_first_aid' );
	$incident->transport_out = $incidents->field( 'trp_from_base' );
	$incident->destination = $incidents->field( 'trp_destination' );


	// Omit left or right in injury location
	$incident->injury_location = array_reduce(
		$incidents->field( 'injury_location' ),
		function ( $arr, $locations ) {
			if ( $locations['name'] != "Left" && $locations['name'] != "Right" ) {
				return $locations['name'];
			}

			return $arr;
		}, [] );

	// Array of patrollers
	$patrollers = $incidents->field( 'responder' );
	if ( is_array($patrollers) ) {
		$incident->patrollers = array_map( function ( $patroller ) {
			return $patroller['display_name'];
		}, $patrollers );
	}

	// Array of treatments
	$incident->treatment = array_map( function ( $treatment ) {
		return $treatment['name'];
	}, $incidents->field( 'treatment' ) );

	/*
	 * Manage locations, they have to be organized hierarchically
	 * Call to a recurring function
	 * $sima_location = get_term_by( 'name', 'Sima', 'locations' );
	$incident->location = array_reduce($incidents->field('incident_location'), function ($arr, $locations) {
		if ( $locations['term_id'] == $arr->term_id ) {
			return $arr;
		}

		// array walk? recursive array_filter

		$arr[$locations['parent']];


	}, ["exclude" => $sima_location] );
	$incident->location["exclude"] = null;*/


	$incidents_array[] = $incident;
}

die( json_encode( $incidents_array ) );
