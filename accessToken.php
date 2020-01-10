<?php
/*
 * Creates an access token with VoiceGrant using your Twilio credentials.
 */
include('./vendor/autoload.php');
include('./config.php');

use Medoo\Medoo;
use Twilio\Jwt\AccessToken;
use Twilio\Jwt\Grants\VoiceGrant;

// Use identity and room from query string if provided
$identity = isset($_GET["identity"]) ? $_GET["identity"] : NULL;
if (!isset($identity) || empty($identity)) {
  $identity = isset($_POST["identity"]) ? $_POST["identity"] : "";
}

// Use identity and room from query string if provided
$code_number = isset($_GET["code_number"]) ? $_GET["code_number"] : NULL;
if (!isset($code_number) || empty($code_number)) {
  $code_number = isset($_POST["code_number"]) ? $_POST["code_number"] : "";
}

if($identity === '' || $code_number === '') {
  exit('NO TOKEN FOR YOU!');
}


// Database
$database = new Medoo([
    'database_type' => $DATABASE_TYPE,
    'database_name' => $DATABASE_NAME,
    'server' => $DATABASE_SERVER,
    'username' => $DATABASE_USERNAME,
    'password' => $DATABASE_PASSWORD
]);

$result = $database->select('clients', [
    'id'
], [
    'code_number' => $code_number
]);

if(empty($result)){
  $database->insert('clients', [
    'identity' => $identity,
    'code_number' => $code_number,
    'created_at' => date('Y-m-d H:i:s')
  ]);
}

// Create access token, which we will serialize and send to the client
$token = new AccessToken($ACCOUNT_SID,
                         $API_KEY,
                         $API_KEY_SECRET,
                         3600,
                         $identity
);

// Grant access to Video
$grant = new VoiceGrant();
$grant->setOutgoingApplicationSid($APP_SID);
$grant->setPushCredentialSid($PUSH_CREDENTIAL_SID);
$token->addGrant($grant);

echo $token->toJWT();
