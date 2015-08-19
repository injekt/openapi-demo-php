<?php

require_once(__DIR__ . "/env.php");
require_once(__DIR__ . "/util/Http.php");

require_once(__DIR__ . "/api/Auth.php");
require_once(__DIR__ . "/api/User.php");

$accessToken = \api\Auth::getAccessToken();
$code = $_GET["code"];
$userInfo = \api\User::getUserInfo($accessToken, $code);
echo json_encode($userInfo);