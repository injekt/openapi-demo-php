<?php
require_once(__DIR__ . "/config.php");
require_once(__DIR__ . "/util/Log.php");
require_once(__DIR__ . "/util/Cache.php");
require_once(__DIR__ . "/api/Auth.php");
require_once(__DIR__ . "/api/User.php");
require_once(__DIR__ . "/api/Message.php");
require_once(__DIR__ . "/api/ISVClass.php");

$code = $_GET['code'];
$corpId = $_GET['corpid'];
$corpInfo = ISVClass::getCorpInfo($corpId);
$accessToken = $corpInfo['corpAccessToken'];
$res = Auth::getPerson($accessToken,$code);
echo $res;
exit;
