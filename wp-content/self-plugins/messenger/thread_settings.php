<?php
include '../../../wp-load.php';
include 'config.php';
include 'verify.php';
include 'functions.php';

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
