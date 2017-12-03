<?php
/* Template Name: Přihlašování */

$eventsArgs= [
	'post_type'  => 'events',
	'orderby' => 'meta_value',
	'meta_key' => 'pm_start_date',
	'meta_query' => [
		[
			'key'     => 'pm_end_date',
			'value'   => current_time( 'Y-m-d H:i' )
		]
	]
];

if (isset($_GET['archive'])) {
	$eventsArgs['numberposts'] = -1;
	$eventsArgs['order'] = 'DESC';
	$eventsArgs['meta_query'][0]['compare'] = '<';
} else {
	$eventsArgs['numberposts'] = 1;
	$eventsArgs['order'] = 'ASC';
	$eventsArgs['meta_query'][0]['compare'] = '>';
}

$events = get_posts($eventsArgs);

$args['events'] = $events;

foreach ($events as $i => $event) {
	$args['events'][$i]->additional_fields = get_post_meta($event->ID, 'pm_additional_fields')[0];
	$args['events'][$i]->participants = $participants = getEventParticipants($event->ID);

	$keys = [];
	foreach ($args['events'][$i]->additional_fields as $key => $field) {
		if (in_array($field['pm_type'], ['radio', 'checkboxlist'])) {
			$keys[$key + 1] = $field;
		}
	}

	$stats = [];
	$som = 0;
	foreach ($participants as $participant) {
		$mails[] = meta($participant->ID, 'participant_mail', true);
		$additional_answers = meta($participant->ID, 'participant_additional');

		if (meta($participant->ID, 'participant_som')) {
			$som++;
		}

		foreach ($keys as $key => $value) {
			if (is_array($additional_answers[$key])) {
				foreach ($additional_answers[$key] as $label) {
					$stats[$value['pm_label']][$label]++;
				}
			} else {
				$stats[$value['pm_label']][$additional_answers[$key]]++;
			}
		}
	}

	$args['events'][$i]->mails = $mails;
	$args['events'][$i]->stats = $stats;
	$args['events'][$i]->participantsCountSom = $som;
	$args['events'][$i]->participantsCountOthers = count($participants) - $som;
}

view($args);
