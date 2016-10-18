<?php

require_once(__DIR__ . "/../config.php");
require_once(__DIR__ . "/../vendor/nategood/httpful/bootstrap.php");

Class Http
{
    public static function get($path, $params)
    {
        $url = self::joinParams($path, $params);
        $response = \Httpful\Request::get($url)->send();
        if ($response->hasErrors())
        {
            var_dump($response);
        }
        if ($response->body->errcode != 0)
        {
            var_dump($response->body);
        }
        return $response->body;
    }
    
    
    public static function post($path, $params, $data)
    {
        $url = self::joinParams($path, $params);
        $response = \Httpful\Request::post($url)
            ->body($data)
            ->sendsJson()
            ->send();
        if ($response->hasErrors())
        {
            var_dump($response);
        }
        if ($response->body->errcode != 0)
        {
            var_dump($response->body);
        }
        return $response->body;
    }
    
    
    private static function joinParams($path, $params)
    {
        $url = OAPI_HOST . $path;
        (count($params) > 0) && ($url = sprintf("%s?%s", $url, http_build_query($params)));
        return $url;
    }
}
