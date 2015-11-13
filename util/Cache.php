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
    
    public static function setJsTicket($ticket)
    {
        $memcache = self::getMemcache();
        $memcache->set("js_ticket", $ticket, 0, time() + 7000); // js ticket有效期为7200秒，这里设置为7000秒
    }
    
    public static function getJsTicket()
    {
        $memcache = self::getMemcache();
        return $memcache->get("js_ticket");
    }
    
    public static function setSuiteAccessToken($accessToken)
    {
        $memcache = self::getMemcache();
        $memcache->set("suite_access_token", $accessToken, 0, time() + 7000); // suite access token有效期为7200秒，这里设置为7000秒
    }
    
    public static function getSuiteAccessToken()
    {
        $memcache = self::getMemcache();
        return $memcache->get("suite_access_token");
    }
    
    public static function setCorpAccessToken($accessToken)
    {
        $memcache = self::getMemcache();
        $memcache->set("corp_access_token", $accessToken, 0, time() + 7000); // corp access token有效期为7200秒，这里设置为7000秒
    }
    
    public static function getCorpAccessToken()
    {
        $memcache = self::getMemcache();
        return $memcache->get("corp_access_token");
    }
    
    public static function setPermanentAuthCodeInfo($code)
    {
        $memcache = self::getMemcache();
        $memcache->set("permanent_auth_code_info", $code);
    }
    
    public static function getPermanentAuthCodeInfo()
    {
        $memcache = self::getMemcache();
        return $memcache->get("permanent_auth_code_info");
    }
    
    
    private static function getMemcache()
    {
        if (class_exists("Memcache"))
        {
            $memcache = new Memcache; 
            if ($memcache->connect('localhost', 11211))
            {
                return $memcache;   
            }
        }
        // return new FileCache;
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

/**
 * fallbacks 
 */
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