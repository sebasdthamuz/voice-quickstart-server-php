<?php
/*
 * Makes a call to the specified client using the Twilio REST API.
 */
include('./vendor/autoload.php');
include('./config.php');

$identity = 'bob';
$callerNumber = '1234567890';
$callerId = 'client:quick_start';
$to = isset($_GET["to"]) ? $_GET["to"] : "";
if (!isset($to) || empty($to)) {
  $to = isset($POST["to"]) ? $_POST["to"] : "";
}


$client = new Twilio\Rest\Client($API_KEY, $API_KEY_SECRET, $ACCOUNT_SID);

$call = NULL;
if (!isset($to) || empty($to)) {
  $call = $client->calls->create(
    'client:bob', // Call this number
    $callerId,      // From a valid Twilio number
    array(
      'url' => 'http://demo.twilio.com/docs/voice.xml'
    )
  );
} else if (is_numeric($to)) {
  $call = $client->calls->create(
    $to,           // Call this number
    $callerNumber, // From a valid Twilio number
    array(
      'url' => 'http://demo.twilio.com/docs/voice.xml'
    )
  );
} else {
  $call = $client->calls->create(
    'client:'.$to, // Call this number
    $callerId,     // From a valid Twilio number
    array(
      'url' => 'http://demo.twilio.com/docs/voice.xml'
    )
  );
}

print $call.sid;
