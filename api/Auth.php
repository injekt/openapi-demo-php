<?php

namespace api;

class Auth
{
    public static function getAccessToken()
    {
        $response = \util\Http::get('/gettoken', array('corpid' => CORPID, 'corpsecret' => SECRET));
        return $response->access_token;
    }
    
    public static function getTicket($accessToken)
    {
        $response = \util\Http::get('/get_jsapi_ticket', array('type' => 'jsapi', 'access_token' => $accessToken));
        return $response->ticket;
    }
    
    
    public static function getConfig()
    {
        $nonceStr = 'abcdefg';
        $timeStamp = time();
        $url = self::getCurrentUrl();
        // $url = 'https://'.$_SERVER['SERVER_NAME'].$_SERVER["REQUEST_URI"];
        
        $accessToken = self::getAccessToken();
        $ticket = self::getTicket($accessToken);
        $signature = self::sign($ticket, $nonceStr, $timeStamp, $url);
        
        $config = array(
            'url' => $url,
            'nonceStr' => $nonceStr,
            'timeStamp' => $timeStamp,
            'corpId' => CORPID,
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
}