<?php
require_once(__DIR__ . "/env.php");
require_once(__DIR__ . "/util/Log.php");
require_once(__DIR__ . "/util/Cache.php");
require_once(__DIR__ . "/crypto/DingtalkCrypt.php");

$signature = $_GET["signature"];
$timeStamp = $_GET["timestamp"];
$nonce = $_GET["nonce"];

$encrypt = json_decode($GLOBALS['HTTP_RAW_POST_DATA'])->encrypt;

$crypt = new DingtalkCrypt(TOKEN, ENCODING_AES_KEY, SUITE_KEY);

$msg = "";
$errCode = $crypt->DecryptMsg($signature, $timeStamp, $nonce, $encrypt, $msg);
if ($errCode == 0)
{
    Log::i(json_encode($_GET) . "  " . $msg);
    $eventMsg = json_decode($msg);
    $eventType = $eventMsg->EventType;
    if ("suite_ticket" === $eventType)
    {
        Cache::setSuiteTicket($eventMsg->SuiteTicket);
    }
    else if ("tmp_auth_code" === $eventType)
    {
        //handle temporary auth code
    }
    else if ("change_auth" === $eventType)
    {
        //handle auth change event
    }
    else
    {
        //should never happen
    }
}
else 
{
    Log::e(json_encode($_GET) . "  ERR:" . $errCode);
}

$plain = "success";
$encryptMsg = "";
$errCode = $crypt->EncryptMsg($plain, $timeStamp, $nonce, $encryptMsg);
if ($errCode == 0) 
{
    echo $encryptMsg;
    Log::i("RESPONSE: " . $encryptMsg);
} 
else 
{
    Log::e("RESPONSE ERR: " . $errCode);
}
