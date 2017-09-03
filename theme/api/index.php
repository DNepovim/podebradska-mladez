<?php

// $ApiRequest = [ 'param1', 'param2', ... ]

$payload = [
	'status' => 'hello'
];

switch($ApiRequest[0]) {
	case 'load-fb-posts':
		$payload['status'] = fptc_load();
		break;
	case 'load-google-photos':
		$payload['status'] = load_google_photos($ApiRequest[1]);
		break;
}

sendPayload($payload);
