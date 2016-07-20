<?php

require_once(__DIR__ . "/ISVService.php");
require_once(__DIR__ . "/../util/Cache.php");
require_once(__DIR__ . "/../util/Log.php");
require_once(__DIR__ . "/ISVClass.php");

class Auth
{
     /**
      * 缓存jsTicket。jsTicket有效期为两小时，需要在失效前请求新的jsTicket（注意：以下代码没有在失效前刷新缓存的jsTicket）。
      */
    public static function getTicket($corpId,$accessToken)
    {
        $jsticket = Cache::getJsTicket('js_ticket_'.$corpId);
        if (!$jsticket)
        {
            $response = Http::get('/get_jsapi_ticket', array('type' => 'jsapi', 'access_token' => $accessToken));
            self::check($response);
            $jsticket = $response->ticket;
            Cache::setJsTicket('js_ticket_'.$corpId,$jsticket);
        }

        return $jsticket;
    }


    function curPageURL()
    {
        $pageURL = 'http';

        if (array_key_exists('HTTPS',$_SERVER)&&$_SERVER["HTTPS"] == "on")
        {
            $pageURL .= "s";
        }
        $pageURL .= "://";

        if ($_SERVER["SERVER_PORT"] != "80")
        {
            $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
        }
        else
        {
            $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
        }
        return $pageURL;
    }

    public static function isvConfig($corpId)
    {
        $corpInfo = ISVClass::getCorpInfo($corpId);
        $corpId = $corpInfo['corp_id'];
        $agentId = ISVService::getCurAgentId($corpId,APPID);
        $nonceStr = 'abcdefg';
        $timeStamp = time();
        $url = self::curPageURL();
        $ticket = self::getTicket($corpId,$corpInfo['corpAccessToken']);
        $signature = self::sign($ticket, $nonceStr, $timeStamp, $url);
        $arr = array();
        $arr['ticket'] = $ticket;
        $arr['nonceStr'] = $nonceStr;
        $arr['timeStamp'] = $timeStamp;
        $arr['url'] = $url;
        $arr['signature'] = $signature;

        $config = array(
            'url' => $url,
            'nonceStr' => $nonceStr,
            'agentId' => $agentId,
            'timeStamp' => $timeStamp,
            'corpId' => $corpId,
            'suite_key' => SUITE_KEY,
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

    /**
     * @param $accessToken
     * @param $code
     * @return 个人授权信息
     */
    public static function getPerson($accessToken, $code)
    {
        $response = Http::get("/user/get_private_info",
            array("access_token" => $accessToken, "tmp_auth_code" => $code));
        return json_encode($response);
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
