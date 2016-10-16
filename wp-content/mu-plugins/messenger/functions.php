<?php
function send( $data, $action = 'messages' ) {
	global $access_token;

	$url = 'https://graph.facebook.com/v2.6/me/' . $action . '?access_token=' . $access_token;

	//Initiate cURL.
	$ch = curl_init( $url );

	//Encode the array into JSON.
	$jsonDataEncoded = $data;

	//Tell cURL that we want to send a POST request.
	curl_setopt( $ch, CURLOPT_POST, 1 );

	//Attach our encoded JSON string to the POST fields.
	curl_setopt( $ch, CURLOPT_POSTFIELDS, $jsonDataEncoded );

	//Set the content type to application/json
	curl_setopt( $ch, CURLOPT_HTTPHEADER, array( 'Content-Type: application/json' ) );

	//Execute the request
	if ( ! empty( $data ) ) {
		$result = curl_exec( $ch );
	}
}

function isUserRegistered( $userID, $eventID ) {

	$args = array(
		'post_type'   => 'participants',
		'post_status' => 'private',
		'meta_query'  => array(
			array(
				'key'   => 'participant_fb_id',
				'value' => $userID,
			),
			array(
				'key'   => 'event_id',
				'value' => $eventID,
			)
		)
	);
	if ( ! empty( sizeof( get_posts( $args ) ) ) ) {
		return true;
	} else {
		return false;
	};
}

function getNextEvent() {
	$args   = array(
		'post_type'  => 'events',
		'offset'     => 0,
		'order'      => 'ASC',
		'meta_query' => array(
			array(
				'key'     => 'pm_end_date',
				'value'   => date( 'Y-m-d h:m' ),
				'compare' => '>',
			)
		)
	);
	$events = get_posts( $args );

	return $events[0];
}

function getUserData( $userID ) {

	global $access_token;

	$userUrl = 'https://graph.facebook.com/v2.6/' . $userID . '?fields=first_name,last_name,gender&access_token=' . $access_token;
	$json    = file_get_contents( $userUrl );

	return json_decode( $json );
}

function registerUser( $userID, $userData, $event ) {
	$fullName         = $userData->first_name . ' ' . $userData->last_name;
	$post_information = array(
		'post_title'  => $fullName,
		'post_type'   => 'participants',
		'post_status' => 'private'
	);

	$post_id = wp_insert_post( $post_information );

	add_post_meta( $post_id, 'event_id', $event->ID );
	add_post_meta( $post_id, 'participant_name', $userData->first_name );
	add_post_meta( $post_id, 'participant_surname', $userData->last_name );
	add_post_meta( $post_id, 'participant_gender', $userData->gender );
	add_post_meta( $post_id, 'participant_fb_id', $userID );
}

function sendText( $sender, $text ) {
	$jsonData = '{
        "recipient":{
            "id":"' . $sender . '"
        },
        "message":{
  	        "text":"' . $text . '"
        }
	}';
	send( $jsonData );
}

function sendInvitationCard( $sender, $event ) {
	$thumb_id  = get_post_thumbnail_id( $event->ID );
	$thumb_url = wp_get_attachment_image_src( $thumb_id )[0];

	$start = strtotime( get_post_meta( $event->ID, 'pm_start_date', true ) );
	$end   = strtotime( get_post_meta( $event->ID, 'pm_end_date', true ) );
	if ( date( 'm', $start ) == date( 'm', $end ) ) {
		$date = date( 'j.', $start ) . ' - ' . date( 'j. n. Y', $end );
	} else {
		$date = date( 'j. n.', $start ) . ' - ' . date( 'j. n. Y', $end );
	}

	if ( isUserRegistered( $sender, $event->ID ) ) {
		$registrationButton = 'Už jsi přihlášen';
	} else {
		$registrationButton = 'Přihlaš mě';
	}

	$jsonData = '{
    "recipient":{
        "id":"' . $sender . '"
    },
    "setting_type":"greeting",
    "greeting":{
        "text":"Timeless apparel for the masses."
    },
    "message":{
    "attachment":{
      "type":"template",
      "payload":{
        "template_type":"generic",
        "elements":[
          {
            "title":"' . $event->post_title . ' | ' . $date . '",
            "item_url":"' . get_home_url() . '",
            "image_url":"' . $thumb_url . '",
            "subtitle":"",
            "buttons":[
              {
                "type":"postback",
                "title":"' . $registrationButton . '",
                "payload":"REGISTER"
              },
              {
                "type":"web_url",
                "url":"https://beta-podebradska-mladez.evangnet.cz",
                "webview_height_ratio": "tall",
                "title":"Chci víc informací"
              },
			  {
				"type":"element_share",
              }
            ]
          }
        ]
      }
    }
  }
}';
	send( $jsonData );

}

function invitationRequest( $input ) {
	$message  = $input['entry'][0]['messaging'][0]['message']['text'];
	$postback = $input['entry'][0]['messaging'][0]['postback']['payload'];
	if ( $postback == 'START' || $postback == 'SEND_INVITATION' || strpos( $message, 'pozv' ) !== false ) {
		return true;
	} else {
		return false;
	}
}
