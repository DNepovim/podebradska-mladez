<?php

use juno_okyo\Chatfuel;

// $ApiRequest = [ 'param1', 'param2', ... ]

$payload = [];

switch($ApiRequest[0]) {
	case 'load-fb-posts':
		$payload['status'] = fptc_load();
		break;
	case 'load-google-photos':
		$payload['status'] = load_google_photos($ApiRequest[1]);
		break;
	case 'register':
		$payload['status'] = process_registration_form($Req->getPost());
		break;
	case 'bot-invite':
		$event = getNextEvent();
		$elements = [[
			'title' => $event->post_title,
			'image_url' => get_the_post_thumbnail_url($event->ID),
			'subtitle' => get_daterange($event),
			'buttons' => [
				[
					'type' => 'show_block',
					'block_name' => 'Přihlásit',
					'title' => 'Přihlásti'
				]
			]
		]];
		$chatfuel = new Chatfuel(TRUE);
		$chatfuel->sendGallery($elements);
		break;
	case 'bot-register':
		$chatfuel = new Chatfuel(TRUE);
		$event = getNextEvent();
		if(process_registration_form($Req->getPost(), $event->ID)) {
			$chatfuel->sendText('Přihlásil jsem tě na ' . $event->post_title . '. Těším se na viděnou.');
		} else {
			$chatfuel->sendText('Něco se pokazilo. Zkus to znovu, nebo počkej, až si toho někdo všimne.');
		}
		break;
}

sendPayload($payload);
