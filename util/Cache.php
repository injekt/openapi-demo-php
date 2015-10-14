<?php

class Cache
{
    public static function setSuiteTicket($ticket)
    {
        $memcache = self::getMemcache();
        $memcache->set("suite_ticket", $ticket);
    }
    
    public static function getSuiteTicket()
    {
        $memcache = self::getMemcache();
        return $memcache->get("suite_ticket");
    }
    
    public static function setTmpAuthCode($code)
    {
        $memcache = self::getMemcache();
        $memcache->set("tmp_auth_code", $code);
    }
    
    public static function getTmpAuthCode()
    {
        $memcache = self::getMemcache();
        return $memcache->get("tmp_auth_code");
    }
    
    public static function setPermanentAuthCode($code)
    {
        $memcache = self::getMemcache();
        $memcache->set("permanent_auth_code", $code);
    }
    
    public static function getPermanentAuthCode()
    {
        $memcache = self::getMemcache();
        return $memcache->get("permanent_auth_code");
    }
    
    public static function setCorpAccessToken($token)
    {
        $memcache = self::getMemcache();
        $memcache->set("corp_access_token", $token);
    }
    
    public static function getCorpAccessToken()
    {
        $memcache = self::getMemcache();
        return $memcache->get("corp_access_token");
    }
    
    private static function getMemcache()
    {
        $memcache = new Memcache; 
        $memcache->connect('localhost', 11211) or die ("Could not connect");
        
        return $memcache;
    }
    
    public static function get($key)
    {
        return self::getMemcache()->get($key);
    }
    
    public static function set($key, $value)
    {
        self::getMemcache()->set($key, $value);
    }
}