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
        // if (class_exists("Memcache"))
        // {
        //     $memcache = new Memcache; 
        //     if ($memcache->connect('localhost', 11211))
        //     {
        //         return $memcache;   
        //     }
        // }
        return new FileCache;
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

class FileCache
{
	var $filedir = "tmp";
	var $filename = "tmp/cache";
	
	function set($key, $value)
	{
		$store = $this->r();
		if ($store == "")
		{
			$store = "{}";
		}
		$values = (array)json_decode($store);
		$values[$key] = $value;
		$this->w(json_encode($values));
	}
	
	function get($key)
	{
		$content = $this->r();
    	if ($content == "")
    	{
    		w("{}");
    		return "";
    	}
    	else{
    		$obj = (array)json_decode($content);
    		return $obj[$key];
    	}
	}
	
	function r()
	{
		if (!file_exists($this->filename)) {
    		$this->w("{}");
    	}
		$handle = fopen($this->filename, "r");
		$content = fread($handle, filesize ($this->filename));
    	fclose($handle);
    	return $content;
	}
	
	function w($content)
	{
		if (!file_exists($this->filedir)) {
    		mkdir($this->filedir);
    	}
        $handle = fopen($this->filename, "w");
        fwrite($handle, $content);
        fclose($handle); 
	}
}