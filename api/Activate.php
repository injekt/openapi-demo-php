<?php

require_once(__DIR__ . "/../util/Log.php");
require_once(__DIR__ . "/../util/Http.php");
require_once(__DIR__ . "/Service.php");

class Activate
{
    /**
     * 某个企业的临时授权码在成功换取永久授权码后，开放平台将不再推送该企业临时授权码。
     */
    public static function autoActivateSuite($tmpAuthCode)
    {
        Cache::setTmpAuthCode($tmpAuthCode);
        $suiteTicket = Cache::get('suite_ticket');
        $suiteAccessToken = Service::getSuiteAccessToken($suiteTicket);
        Log::i("[Activate] getSuiteToken: " . $suiteAccessToken);
        
        /**
         * 企业永久授权码信息须持久化保持，在demo中只做缓存处理。
         */
        $permanetCodeInfo = json_decode(Cache::getPermanentAuthCode());
        if (!$permanetCodeInfo)
        {
            $suiteTicket = Cache::getSuiteTicket();
            Log::i("[Activate] getSuiteTicket: " . $suiteTicket);
            
            /**
             * 获取企业永久授权码
             */
            $permanetCodeInfo = Service::getPermanentCodeInfo($suiteAccessToken, $tmpAuthCode);
        }
        
        Log::i("[Activate] getPermanentCodeInfo: " . json_encode($permanetCodeInfo));
        
        $permanetCode = $permanetCodeInfo->permanent_code;
        $authCorpId = $permanetCodeInfo->auth_corp_info->corpid;
        Log::i("[Activate] permanetCode: " . $permanetCode . ",  authCorpId: " . $authCorpId);
        
        /**
         * 获取企业access token
         */
        $corpAccessToken = Service::getIsvCorpAccessToken($suiteAccessToken, $authCorpId, $permanetCode);
        Log::i("[Activate] getCorpToken: " . $corpAccessToken);
        
        /**
         * 获取企业授权信息
         */
        $res = Service::getAuthInfo($suiteAccessToken, $authCorpId, $permanetCode);
        Log::i("[Activate] getAuthInfo: " . json_encode($res));
        self::check($res);
        
        /**
         * 激活套件
         */
        $res = Service::activeSuite($suiteAccessToken, $authCorpId, $permanetCode);
        Log::i("[Activate] activeSuite: " . json_encode($res));
        self::check($res);
    }
    
    
    static function check($res)
    {
        if ($res->errcode != 0)
        {
            exit("Failed: " . json_encode($res));
        }
    }
}