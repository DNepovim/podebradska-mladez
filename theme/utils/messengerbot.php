<?php

use juno_okyo\Chatfuel;

function messengerResp() {
	$chatfuel = new Chatfuel(TRUE);
	$chatfuel->sendText('Hello, World!');
}





require_once __DIR__ . '/../../public/wp-core/wp-load.php';
require_once __DIR__ . '/messengerbot/functions.php';

function messengerbot_process() {
	messengerbot_verify ();
	$input = json_decode( file_get_contents( 'php://input' ), true );

	$sender  = $input['entry'][0]['messaging'][0]['sender']['id'];
	$message = $input['entry'][0]['messaging'][0]['message']['text'];

	$nextEvent = getNextEvent();

	$postback = $input['entry'][0]['messaging'][0]['postback']['payload'];
	$userID   = $input['entry'][0]['messaging'][0]['sender']['id'];

	if ( $postback == 'REGISTER' ) {

		$userData = getUserData($sender);

		if (isUserRegistered($userID,$nextEvent->ID)) {
			if ($userData->gender == 'female') {
				$message = 'Už jsi přihlášená. Těšíme se na tebe.';
			} else {
				$message = 'Už jsi přihlášený. Těšíme se na tebe.';
			}
			sendText( $sender, $message );
		} else {
			registerUser( $userID, $userData, $nextEvent );
			$message = 'Výborně! Přihlásil jsem tě a těším se na tebe.';
			sendText( $sender, $message );
		}

	} elseif ( invitationRequest($input) ) {

		sendInvitationCard($sender, $nextEvent);

	} elseif ( $postback == 'SEND_INV') {

		$thumb_id  = get_post_thumbnail_id( $nextEvent->ID );
		$thumb_url = wp_get_attachment_image_src( $thumb_id, 'invitation' )[0];

		sendImage($sender, $thumb_url);

	} else {
		sendInvitationCard($sender, $nextEvent);
	}
}


function messengerbot_set_thread() {
	messengerbot_verify ();
	$json = '{
		"get_started":{
			"payload":"START"
		},
		"persistent_menu":[
			{
				"locale":"default",
				"call_to_actions":[
					{
						"title":"Pošli mi pozvánku",
						"type":"postback",
						"payload":"SEND_INVITATION"
					},
					{
						"type":"web_url",
						"title":"Náš web",
						"url":"https://podebradska-mladez.evangnet.cz",
						"webview_height_ratio":"full"
					}
				]
			}
		],
		"whitelisted_domains":[
			"https://podebradska-mladez.evangnet.cz"
		],
		"greeting":[
			{
				"locale":"default",
				"text":"Ahoj, jsem SOMBot. Budu ti posílat informace o našich akcích a můžeš se na ně přese mě i přihlásit."
			}
		]
	}';
	send($json, 'messenger_profile');
}

function messengerbot_verify () {
	global $App;
	$access_token = $App->parameters['messengerbot']['verify_token'];
	if ( isset( $_REQUEST['hub_challenge'] ) ) {
		$challenge        = $_REQUEST['hub_challenge'];
		$hub_verify_token = $_REQUEST['hub_verify_token'];
	}
	if ( $hub_verify_token === $verify_token ) {
		echo $challenge;
	}
}
