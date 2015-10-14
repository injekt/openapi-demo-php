<?php

require_once(__DIR__ . "/env.php");
require_once(__DIR__ . "/util/Http.php");
require_once(__DIR__ . "/util/Cache.php");

require_once(__DIR__ . "/api/Service.php");


$suiteTicket = Cache::getSuiteTicket();
i("suiteTicket: " . $suiteTicket);
$res = \api\Service::getSuiteToken($suiteTicket);
i("getSuiteToken: " . json_encode($res));
check($res);

$suiteAccessToken = $res->suite_access_token;

if (Cache::getPermanentAuthCode() == '')
{
    $tmpAuthCode = json_decode(Cache::getTmpAuthCode())->AuthCode;
    $res = \api\Service::getPermanentCode($suiteAccessToken, $tmpAuthCode);
    i("getPermanentCode: " . json_encode($res));
    check($res, "getPermanentCode");
    
    Cache::setPermanentAuthCode(json_encode($res));
}

$permanetCodeInfo = json_decode(Cache::getPermanentAuthCode());
$permanetCode = $permanetCodeInfo->permanent_code;
$authCorpId = $permanetCodeInfo->auth_corp_info->corpid;
i("permanetCode: " . $permanetCode . ",  authCorpId: " . $authCorpId);

$res = \api\Service::getCorpToken($suiteAccessToken, $authCorpId, $permanetCode);
i("getCorpToken: " . json_encode($res));
check($res);

$corpAccessToken = $res->access_token;
Cache::setCorpAccessToken($corpAccessToken);

$res = \api\Service::getAuthInfo($suiteAccessToken, $authCorpId, $permanetCode);
i("getAuthInfo: " . json_encode($res));
check($res);

// $agentId = "4319226";
// $res = \api\Service::getAgent($suiteAccessToken, $authCorpId, $permanetCode, $agentId);
// i("getAgent: " . json_encode($res));
// check($res);

$res = \api\Service::activeSuite($suiteAccessToken, $authCorpId, $permanetCode);
i("activeSuite: " . json_encode($res));
check($res);



function i($msg)
{
    echo $msg . "<br/>";
}

function check($res)
{
    if ($res->errcode != 0)
    {
        exit("Failed: " . json_encode($res));
    }
}