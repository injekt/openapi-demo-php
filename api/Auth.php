<?php

require_once(__DIR__ . "/Service.php");
require_once(__DIR__ . "/../util/Cache.php");
require_once(__DIR__ . "/../util/Log.php");

class Auth
{
    public static function getAccessToken()
    {
        /**
         * 缓存accessToken。accessToken有效期为两小时，需要在失效前请求新的accessToken（注意：以下代码没有在失效前刷新缓存的accessToken）。
         */
        $accessToken = Cache::get('corp_access_token');
        if ($accessToken == '')
        {
            $response = Http::get('/gettoken', array('corpid' => CORPID, 'corpsecret' => SECRET));
            $accessToken = $response->access_token;
            Cache::set('access_token', $accessToken);
        }
        return $accessToken;
    }
    
     /**
      * 缓存jsTicket。jsTicket有效期为两小时，需要在失效前请求新的jsTicket（注意：以下代码没有在失效前刷新缓存的jsTicket）。
      */
    public static function getTicket($accessToken)
    {
        $jsticket = Cache::getJsTicket();
        if ($jsticket === "")
        {
            $response = Http::get('/get_jsapi_ticket', array('type' => 'jsapi', 'access_token' => $accessToken));
            self::check($response);
            $ticket = $response->ticket;
            Cache::setJsTicket($ticket);
        }
        return $jsticket;
    }
    
    
    public static function getConfig($corpId)
    {
        $nonceStr = 'abcdefg';
        $timeStamp = time();
        // $url = self::getCurrentUrl();
        $url = 'https://'.$_SERVER['SERVER_NAME'].$_SERVER["REQUEST_URI"];
        
        $corpAccessToken = Cache::getCorpAccessToken();
        if (!$corpAccessToken)
        {
            Log::e("[getConfig] ERR: no corp access token");
        }
        $ticket = Service::getJsTicket($corpAccessToken);
        $signature = self::sign($ticket, $nonceStr, $timeStamp, $url);
        
        $config = array(
            'url' => $url,
            'nonceStr' => $nonceStr,
            'timeStamp' => $timeStamp,
            'corpId' => $corpId,
            'signature' => $signature);
        return json_encode($config, JSON_UNESCAPED_SLASHES);
    }
    
    
    public static function sign($ticket, $nonceStr, $timeStamp, $url)
    {
        $plain = 'jsapi_ticket=' . $ticket .
            '&noncestr=' . $nonceStr .
            '&timestamp=' . $timeStamp .
            '&url=' . $url;
        return sha1($plain);
    }
    
    
    private static function getCurrentUrl() 
    {
        $url = "http";
        if ($_SERVER["HTTPS"] == "on") 
        {
            $url .= "s";
        }
        $url .= "://";
    
        if ($_SERVER["SERVER_PORT"] != "80") 
        {
            $url .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
        } 
        else 
        {
            $url .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
        }
        return $url;
    }
    
    
    static function check($res)
    {
        if ($res->errcode != 0)
        {
            Log.e("FAIL: " . json_encode($res));
            exit("Failed: " . json_encode($res));
        }
    }
}