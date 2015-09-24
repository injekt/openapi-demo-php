<?php
require_once(__DIR__ . "/env.php");
require_once(__DIR__ . "/util/Log.php");
require_once(__DIR__ . "/util/Cache.php");
require_once(__DIR__ . "/crypto/DingtalkCrypt.php");

$signature = $_GET["signature"];
$timeStamp = $_GET["timestamp"];
$nonce = $_GET["nonce"];

$encrypt = json_decode($GLOBALS['HTTP_RAW_POST_DATA'])->encrypt;

$crypt = new DingtalkCrypt(TOKEN, ENCODING_AES_KEY, CREATE_SUITE_KEY);

$msg = "";
$random = "";
$errCode = $crypt->DecryptMsg($signature, $timeStamp, $nonce, $encrypt, $msg);
if ($errCode == 0)
{
    Log::i(json_encode($_GET) . "  " . $msg);
    $eventMsg = json_decode($msg);
    $eventType = $eventMsg->EventType;
    if ("check_create_suite_url" === $eventType)
    {
        $random = $eventMsg->Random;
        $testSuiteKey = $eventMsg->TestSuiteKey;
        //do something with test suite key
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

$plain = $random;
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
