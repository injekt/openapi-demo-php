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
// $tmpAuthCode = "efdb4481eb51327ab00fedb386ed60c5";
// $res = \api\Service::getPermanentCode($suiteAccessToken, $tmpAuthCode);
// i("getPermanentCode: " . json_encode($res));
// check($res, "getPermanentCode");

$permanetCode = "BkaTd2db5Myp6WWJrfu_Mk7EA2SXU8fg7jE77dT54xtbyFWCLhvjyGV0B3mXiI3v";
$authCorpId = "dingd38ff512778355ce";
$res = \api\Service::getCorpToken($suiteAccessToken, $authCorpId, $permanetCode);
i("getCorpToken: " . json_encode($res));
check($res);

$res = \api\Service::getAuthInfo($suiteAccessToken, $authCorpId, $permanetCode);
i("getAuthInfo: " . json_encode($res));
check($res);

$agentId = "4319226";
$res = \api\Service::getAgent($suiteAccessToken, $authCorpId, $permanetCode, $agentId);
i("getAgent: " . json_encode($res));
check($res);

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