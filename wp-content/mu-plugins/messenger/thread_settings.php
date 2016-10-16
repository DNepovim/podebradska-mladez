<?php
include '../../../wp-load.php';
include 'config.php';
include 'verify.php';
include 'functions.php';

$jsonMenu = '{
"setting_type" : "call_to_actions",
  "thread_state" : "existing_thread",
  "call_to_actions":[
    {
      "type":"postback",
      "title":"Pošli mi pozvánku",
      "payload":"SEND_INVITATION"
    },
    {
      "type":"web_url",
      "title":"Náš web",
      "url":"https://podebradska-mladez.evangnet.cz",
      "webview_height_ratio": "full",
      "messenger_extensions": true
    }
  ]
}';
$jsonButton = '{
"setting_type":"call_to_actions",
  "thread_state":"new_thread",
  "call_to_actions":[
    {
      "payload":"START"
    }
  ]
}';
$jsonWhitelist = '{
"setting_type" : "domain_whitelisting",
  "whitelisted_domains" : ["https://podebradska-mladez.evangnet.cz"],
  "domain_action_type": "add"
}';

sendJson($jsonWhitelist, $access_token);

send($jsonMenu, 'thread_settings');
