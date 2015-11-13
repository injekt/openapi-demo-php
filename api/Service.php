<?php

require_once(__DIR__ . "/../util/Log.php");
require_once(__DIR__ . "/../util/Http.php");
require_once(__DIR__ . "/../util/Cache.php");

class Service
{
    public static function getSuiteAccessToken($suiteTicket)
    {
        $suiteAccessToken = Cache::getSuiteAccessToken();
        if (!$suiteAccessToken)
        {
            $response = Http::post("/service/get_suite_token", 
                null, 
                json_encode(array(
                    "suite_key" => SUITE_KEY,
                    "suite_secret" => SUITE_SECRET,
                    "suite_ticket" => $suiteTicket    
                )));
            self::check($response);
            $suiteAccessToken = $response->suite_access_token;
            Cache::setSuiteAccessToken($suiteAccessToken);
        }
        return $suiteAccessToken;
    }
    
    
    /**
     * permanent auth code info:
     * {
     *  "auth_corp_info":{
     *      "corp_name":"MyTest",
     *      "corpid":"ding2b54cf1498a7abcd"
     *  },
     *  "auth_user_info":{
     *      "userId":"manager1601"
     *  },
     *  "errcode":0,
     *  "errmsg":"ok",
     *  "permanent_code":"TExBsMfqpr_abcdlDv9H6Mk27tIg7oIA9R3_8YGhabcdCqb7wFebiAkAmJWabcde"
     * }
     */
    public static function getPermanentCodeInfo($suiteAccessToken, $tmpAuthCode)
    {
        $permanentCodeInfo = json_decode(Cache::getPermanentAuthCodeInfo());
        if (!$permanentCodeInfo)
        {
            $permanentCodeInfo = Http::post("/service/get_permanent_code", 
                array(
                    "suite_access_token" => $suiteAccessToken
                ), 
                json_encode(array(
                    "tmp_auth_code" => $tmpAuthCode
                )));
            self::check($permanentCodeInfo);
            Cache::setPermanentAuthCodeInfo(json_encode($permanentCodeInfo));
        }
        return $permanentCodeInfo;
    }
    
    
    public static function getCorpAccessToken($suiteAccessToken, $authCorpId, $permanentCode)
    {
        $corpAccessToken = Cache::getCorpAccessToken();
        if (!$corpAccessToken) 
        {
            $response = Http::post("/service/get_corp_token", 
                array(
                    "suite_access_token" => $suiteAccessToken
                ), 
                json_encode(array(
                    "auth_corpid" => $authCorpId,
                    "permanent_code" => $permanentCode
                )));
            self::check($response);
            $corpAccessToken = $response->access_token;
            Cache::setCorpAccessToken($corpAccessToken);
        }
        return $corpAccessToken;
    }
    
    
    /**
     * auth info:
     * {
     *  "auth_corp_info":
     *  {
     *      "corp_logo_url":"",
     *      "corp_name":"MyHpmTest",
     *      "corpid":"ding129a1bb2ec7f0bb4",
     *      "invite_code":""
     *  },
     *  "auth_info":
     *  {
     *      "agent":
     *      [
     *          {
     *              "agent_name":"phptest3",
     *              "agentid":6678890,
     *              "appid":495,
     *              "logo_url":"http:\/\/i01.lw.aliimg.com\/media\/lADOAnnInMy0zLQ_180_180.jpg"
     *          }
     *      ]
     *  },
     *  "auth_user_info":
     *  {
     *      "userId":"manager8061"
     *  },
     *  "errcode":0,
     *  "errmsg":"ok"
     * }
     */
    public static function getAuthInfo($suiteAccessToken, $authCorpId, $permanentCode)
    {
        $response = Http::post("/service/get_auth_info", 
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
        $response = Http::post("/service/get_agent", 
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
        $response = Http::post("/service/activate_suite", 
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
    
    
    public static function getJsTicket($corpAccessToken)
    {
        $jsticket = Cache::getJsTicket();
        if (!$jsticket)
        {
            $response = Http::get('/get_jsapi_ticket', array('type' => 'jsapi', 'access_token' => $corpAccessToken));
            self::check($response);
            $ticket = $response->ticket;
            Cache::setJsTicket($ticket);
        }
        return $jsticket;
    }
    
    
    static function check($res)
    {
        if ($res->errcode != 0)
        {
            Log::e("FAIL: " . json_encode($res));
            exit("Failed: " . json_encode($res));
        }
    }
}