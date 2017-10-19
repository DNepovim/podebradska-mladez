<?php

$Payload = new Nette\Utils\ArrayHash;

function sendPayload($params = array()) {
	global $Payload;
	$payloadArray = (array) $Payload;
	Tracy\Debugger::$productionMode = TRUE;
	header('Content-Type:application/json;charset=utf-8');
	if (empty($params)) {
		print('') and die();
	} else {
		print(json_encode($params + $payloadArray)) and die();
	}
}
