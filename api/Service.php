<?php

namespace api;

class Service
{
    public static function getSuiteToken($suiteTicket)
    {
        $response = \util\Http::post("/service/get_suite_token", 
            null, 
            json_encode(array(
                "suite_key" => SUITE_KEY,
                "suite_secret" => SUITE_SECRET,
                "suite_ticket" => $suiteTicket    
            )));
        return $response;
    }
    
    public static function getPermanentCode($suiteAccessToken, $tmpAuthCode)
    {
        $response = \util\Http::post("/service/get_permanent_code", 
            array(
                "suite_access_token" => $suiteAccessToken
            ), 
            json_encode(array(
                "tmp_auth_code" => $tmpAuthCode
            )));
        return $response;
    }
    
    public static function getCorpToken($suiteAccessToken, $authCorpId, $permanentCode)
    {
        $response = \util\Http::post("/service/get_corp_token", 
            array(
                "suite_access_token" => $suiteAccessToken
            ), 
            json_encode(array(
                "auth_corpid" => $authCorpId,
                "permanent_code" => $permanentCode
            )));
        return $response;
    }
    
    public static function getAuthInfo($suiteAccessToken, $authCorpId, $permanentCode)
    {
        $response = \util\Http::post("/service/get_auth_info", 
            array(
                "suite_access_token" => $suiteAccessToken
            ), 
            json_encode(array(
                "suite_key" => SUITE_KEY,
                "auth_corpid" => $authCorpId,
                "permanent_code" => $permanentCode
            )));
        return $response;
    }
    
    public static function getAgent($suiteAccessToken, $authCorpId, $permanentCode, $agentId)
    {
        $response = \util\Http::post("/service/get_agent", 
            array(
                "suite_access_token" => $suiteAccessToken
            ), 
            json_encode(array(
                "suite_key" => SUITE_KEY,
                "auth_corpid" => $authCorpId,
                "permanent_code" => $permanentCode,
                "agentid" => $agentId
            )));
        return $response;
    }
    
    public static function activeSuite($suiteAccessToken, $authCorpId, $permanentCode)
    {
        $response = \util\Http::post("/service/activate_suite", 
            array(
                "suite_access_token" => $suiteAccessToken
            ), 
            json_encode(array(
                "suite_key" => SUITE_KEY,
                "auth_corpid" => $authCorpId,
                "permanent_code" => $permanentCode
            )));
        return $response;
    }
}