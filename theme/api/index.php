<?php

// $ApiRequest = [ 'param1', 'param2', ... ]

$payload = [
	'status' => 'hello'
];

switch($ApiRequest[0]) {
	case 'load-fb-posts':
		$payload['status'] = fptc_load();
		break;
	case 'echo':
		$payload = [
			'status' => 'ok',
			'data' => $ApiRequest
		];
		break;
}

sendPayload($payload);
