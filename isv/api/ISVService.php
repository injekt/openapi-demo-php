<?php

require_once(__DIR__ . "/../util/Log.php");
require_once(__DIR__ . "/../util/Http.php");
require_once(__DIR__ . "/../util/Cache.php");

/**
 * ISV授权方法类
 */
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

    public static function getCorpInfoByCorId($corpId){
        $corpList = json_decode(Cache::getCorpInfo(),true);
        if(!is_array($corpList)){
            return false;
        }

        foreach($corpList as $corp){
            if($corp['corp_id']==$corpId){
                return $corp;
            }
        }
    }

    public static function getCorpInfoByTmpCode($code){
        $corpList = json_decode(Cache::getCorpInfo(),true);
        if(!is_array($corpList)){
            return false;
        }

        foreach($corpList as $corp){
            if($corp['tmp_auth_code']==$code){
                return $corp;
            }
        }
    }

    public static function getPermanentCodeInfo($suiteAccessToken,$tmpAuthCode)
    {
        $permanentCodeInfo = json_decode(Service::getCorpInfoByTmpCode($tmpAuthCode));

        if (!$permanentCodeInfo)
        {
            $permanentCodeResult = Http::post("/service/get_permanent_code",
                array(
                    "suite_access_token" => $suiteAccessToken
                ), 
                json_encode(array(
                    "tmp_auth_code" => $tmpAuthCode
                )));
            self::check($permanentCodeResult);
            Log::i("[permanentCodeInfo]".json_encode($permanentCodeResult));
            $permanentCodeInfo = self::savePermanentCodeInfo($permanentCodeResult,$tmpAuthCode);
        }
        return $permanentCodeInfo;
    }

    public static function savePermanentCodeInfo($permanentCodeInfo,$tmpAuthCode){
        $arr = array();
        $arr['corp_name'] = $permanentCodeInfo->auth_corp_info->corp_name;
        $arr['corp_id'] = $permanentCodeInfo->auth_corp_info->corpid;
        $arr['permanent_code'] = $permanentCodeInfo->permanent_code;
        $arr['tmp_auth_code'] = $tmpAuthCode;
        $corpInfo = json_decode(Cache::getCorpInfo());
        if(!$corpInfo){
            $corpInfo = array();
        }
        $corpInfo[] = $arr;
        Cache::setCorpInfo(json_encode($corpInfo));
        return $arr;
    }

    public static function getCurAgentId($appId){
        $authInfo = json_decode(Cache::getAuthInfo());
        $agents = $authInfo->agent;
        $agentId = 0;
        foreach($agents as $agent){
            if($agent->appid==$appId){
                $agentId = $agent->agentid;
                break;
            }
        }
        return $agentId;
    }

    public static function getIsvCorpAccessToken($suiteAccessToken, $authCorpId, $permanentCode)
    {
        $key = "IsvCorpAccessToken_".$authCorpId;
        $corpAccessToken = Cache::getIsvCorpAccessToken($key);
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
            Cache::setIsvCorpAccessToken($key,$corpAccessToken);
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
        $authInfo = json_decode(Cache::getAuthInfo());
        if (!$authInfo)
        {
            $authInfo = Http::post("/service/get_auth_info",
                array(
                    "suite_access_token" => $suiteAccessToken
                ),
                json_encode(array(
                    "suite_key" => SUITE_KEY,
                    "auth_corpid" => $authCorpId,
                    "permanent_code" => $permanentCode
                )));
            self::check($authInfo);
            $authInfo = $authInfo;
            Cache::setAuthInfo(json_encode($authInfo->auth_info));
        }

        return $authInfo;
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
        self::check($response);
        return $response;
    }
    
    
    public static function activeSuite($suiteAccessToken, $authCorpId, $permanentCode)
    {
        $key = "dingdingActive_".$authCorpId;
        $response = Http::post("/service/activate_suite", 
            array(
                "suite_access_token" => $suiteAccessToken
            ), 
            json_encode(array(
                "suite_key" => SUITE_KEY,
                "auth_corpid" => $authCorpId,
                "permanent_code" => $permanentCode
            )));
        if($response->errcode==0){
            Cache::setActiveStatus($key);
        }
        self::check($response);
        return $response;
    }

    static function check($res)
    {
        if ($res->errcode != 0)
        {
            Log::e("[FAIL]: " . json_encode($res));
            exit("Failed: " . json_encode($res));
        }
    }
}