<?php
if ( isset( $_REQUEST['hub_challenge'] ) ) {
$challenge        = $_REQUEST['hub_challenge'];
$hub_verify_token = $_REQUEST['hub_verify_token'];
}


if ( $hub_verify_token === $verify_token ) {
echo $challenge;
}
