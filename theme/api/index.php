<?php

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
	case 'messengerbot':
		messengerResp();
		break;
	case 'messengerbot-set':
		messengerbot_set_thread();
		break;
	case 'messengerbot-verify':
		messengerbot_process();
		break;
}

sendPayload($payload);
