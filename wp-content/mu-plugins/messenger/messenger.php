<?php
include '../../../wp-load.php';
include 'config.php';
include 'verify.php';
include 'functions.php';

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
		$message = 'Výborně! Přihlásil jsem tě a těšíme se na tebe.';
		sendText( $sender, $message );
	}

} elseif ( $input['entry'][0]['messaging'][0]['message'] ) {
	sendInvitationCard($sender, $nextEvent);
}
