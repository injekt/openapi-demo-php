<?php

namespace api;

class Department
{
    public static function createDept($accessToken, $dept)
    {
        $response = \util\Http::post("/department/create", 
            array("access_token" => $accessToken), 
            json_encode($dept));
        return $response->id;
    }
    
    
    public static function listDept($accessToken)
    {
        $response = \util\Http::get("/department/list", 
            array("access_token" => $accessToken));
        return $response->department;
    }
    
    
    public static function deleteDept($accessToken, $id)
    {
        $response = \util\Http::get("/department/delete", 
            array("access_token" => $accessToken, "id" => $id));
        return $response->errcode == 0;
    }
}