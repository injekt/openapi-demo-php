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

    public static function setIsvCorpAccessToken($accessToken)
    {
        $memcache = self::getMemcache();
        $memcache->set("isv_corp_access_token", $accessToken, 0, time() + 7000); // corp access token有效期为7200秒，这里设置为7000秒
    }

    public static function getIsvCorpAccessToken()
    {
        $memcache = self::getMemcache();
        return $memcache->get("isv_corp_access_token");
    }

    public static function setTmpAuthCode($tmpAuthCode){
        $memcache = self::getMemcache();
        $memcache->set("tmp_auth_code", $tmpAuthCode);
    }

    public static function getTmpAuthCode(){
        $memcache = self::getMemcache();
        $memcache->get("tmp_auth_code");
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
        /*if (class_exists("Memcache"))
        {
            $memcache = new Memcache; 
            if ($memcache->connect('localhost', 11211))
            {
                return $memcache;   
            }
        }*/

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

/**
 * fallbacks 
 */
class FileCache
{
	function set($key, $value)
	{
        if($key&&$value){
            $data = json_decode($this->get_file(DIR_ROOT ."filecache.php"),true);
            $item = array();
            $item["$key"] = $value;

            $keyList = array('isv_corp_access_token','suite_access_token','js_ticket','corp_access_token');
            if(in_array($key,$keyList)){
                $item['expire_time'] = time() + 7000;
            }else{
                $item['expire_time'] = 0;
            }
            $item['create_time'] = time();
            $data["$key"] = $item;
            $this->set_file("filecache.php",json_encode($data));
        }
	}

	function get($key)
	{
        if($key){
            $data = json_decode($this->get_file(DIR_ROOT ."filecache.php"),true);
            if($data&&array_key_exists($key,$data)){
                $item = $data["$key"];
                if(!$item){
                    return false;
                }
                if($item['expire_time']>0&&$item['expire_time'] < time()){
                    return false;
                }

                return $item["$key"];
            }else{
                return false;
            }

        }
	}

    function get_file($filename) {
        if (!file_exists($filename)) {
            $fp = fopen($filename, "w");
            fwrite($fp, "<?php exit();?>" . '');
            fclose($fp);
            return false;
        }else{
            $content = trim(substr(file_get_contents($filename), 15));
        }
        return $content;
    }

    function set_file($filename, $content) {
        $fp = fopen($filename, "w");
        fwrite($fp, "<?php exit();?>" . $content);
        fclose($fp);
    }
}