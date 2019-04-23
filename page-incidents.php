<?php
/**
 * Page to retrieve specific incidents
 */

/**
 * TODO: Prevent unauthorized (public) access. Implement additional role verification
 */

if ( !is_user_logged_in() ) {
	die('You must be logged in to retrieve incidents');
}

function validateDate($date, $format = 'Y-m-d')
{
	$d = DateTime::createFromFormat($format, $date);
	// The Y ( 4 digits year ) returns TRUE for any integer with any number of digits so changing the comparison from == to === fixes the issue.
	return $d && $d->format($format) === $date;
}

// Retrieve start and end
$format = 'Y-m-d';
$start = (
	validateDate($_GET['start']) ?
		filter_var($_GET['start'], FILTER_SANITIZE_STRING) :
		date_format(date_modify(new DateTime(), '-1 month'), $format )
);
$end = (
validateDate($_GET['end']) ?
	filter_var($_GET['end'], FILTER_SANITIZE_STRING) :
	date_format(new DateTime(), $format )
);

$incidents = pods(
	'wcm_incidents',
		array(
			'orderby' => 't.date asc',
			'limit' => -1,
			'where' => "t.date BETWEEN '${start}' AND '${end}'"
		)
);

$all_incidents = $incidents->export_data();

if ( !empty( $all_incidents ) ) {
	die(json_encode($all_incidents));
}else{
	die(json_encode(array('error' => 'No incident found.')));
}