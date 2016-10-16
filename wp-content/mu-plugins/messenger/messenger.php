<?php
include '../../../wp-load.php';
include 'config.php';


if ( isset( $_REQUEST['hub_challenge'] ) ) {
	$challenge        = $_REQUEST['hub_challenge'];
	$hub_verify_token = $_REQUEST['hub_verify_token'];
}


if ( $hub_verify_token === $verify_token ) {
	echo $challenge;
}

$input = json_decode( file_get_contents( 'php://input' ), true );

$sender  = $input['entry'][0]['messaging'][0]['sender']['id'];
$message = $input['entry'][0]['messaging'][0]['message']['text'];


$args      = array(
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
$events    = get_posts( $args );
$nextEvent = $events[0];

$postback = $input['entry'][0]['messaging'][0]['postback']['payload'];
$userID   = $input['entry'][0]['messaging'][0]['sender']['id'];

if ( $postback == 'REGISTER' ) {


	$userUrl = 'https://graph.facebook.com/v2.6/' . $userID . '?fields=first_name,last_name,gender&access_token=' . $access_token;
	$json    = file_get_contents( $userUrl );
	$obj     = json_decode( $json );

	$post_id = $nextEvent->ID;

	$fullName = $obj->first_name . ' ' . $obj->last_name;

	$post_information = array(
		'post_title'  => $fullName,
		'post_type'   => 'participants',
		'post_status' => 'private'
	);

	$post_id = wp_insert_post( $post_information );

	add_post_meta( $post_id, 'event_id', $nextEvent->ID );
	add_post_meta( $post_id, 'participant_name', $obj->first_name );
	add_post_meta( $post_id, 'participant_surname', $obj->last_name );
	add_post_meta( $post_id, 'participant_gender', $obj->gender );
	add_post_meta( $post_id, 'participant_fb_id', $userID );

	$jsonData = '{
        "recipient":{
            "id":"' . $sender . '"
        },
        "message":{
  	        "text":"' . $userID . '"
        }
	}';
	send( $jsonData, $access_token );
} elseif ( $input['entry'][0]['messaging'][0]['message'] ) {
	$thumb_id  = get_post_thumbnail_id( $nextEvent->ID );
	$thumb_url = wp_get_attachment_image_src( $thumb_id )[0];

	$start = strtotime( get_post_meta( $nextEvent->ID, 'pm_start_date', true ) );
	$end   = strtotime( get_post_meta( $nextEvent->ID, 'pm_end_date', true ) );
	if ( date( 'm', $start ) == date( 'm', $end ) ) {
		$date = date( 'j.', $start ) . ' - ' . date( 'j. n. Y', $end );
	} else {
		$date = date( 'j. n.', $start ) . ' - ' . date( 'j. n. Y', $end );
	}


	$jsonData = '{
    "recipient":{
        "id":"' . $sender . '"
    },
    "message":{
    "attachment":{
      "type":"template",
      "payload":{
        "template_type":"generic",
        "elements":[
          {
            "title":"' . $nextEvent->post_title . ' | ' . $date . '",
            "item_url":"https://beta-podebradska-mladez.evangnet.cz",
            "image_url":"' . $thumb_url . '",
            "subtitle":"' . $userID . '",
            "buttons":[';
	if ( ! isUserRegistered( $userID, $nextEvent->ID ) ) {
		$jsonData .= '{
                "type":"postback",
                "title":"Přihlásit se",
                "payload":"REGISTER"
              },';
	}
	$jsonData .=
			'{
                "type":"web_url",
                "url":"https://beta-podebradska-mladez.evangnet.cz",
                "title":"Víc informací"
              }
            ]
          }
        ]
      }
    }
  }
}';
	send( $jsonData, $access_token );
}

function send( $data, $access_token ) {

	$url = 'https://graph.facebook.com/v2.6/me/messages?access_token=' . $access_token;

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
		'post_type'  => 'participants',
		'post_status' => 'private',
		'meta_query' => array(
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
