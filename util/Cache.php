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
    
    private static function getMemcache()
    {
        $memcache = new Memcache; 
        $memcache->connect('localhost', 11211) or die ("Could not connect");
        
        return $memcache;
    }
}