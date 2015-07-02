<?php

namespace api;

class Auth
{
    public static function getAccessToken()
    {
        $response = \util\Http::get('/gettoken', array("corpid" => CORPID, "corpsecret" => SECRET));
        return $response->access_token;
    }
}