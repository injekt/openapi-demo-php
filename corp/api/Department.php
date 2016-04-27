<?php

class Department
{
    public static function createDept($accessToken, $dept)
    {
        $response = Http::post("/department/create", 
            array("access_token" => $accessToken), 
            json_encode($dept));
        return $response->id;
    }
    
    
    public static function listDept($accessToken)
    {
        $response = Http::get("/department/list", 
            array("access_token" => $accessToken));
        Log::i($accessToken."【--department/list--】".json_encode($response->department));
        return $response->department;
    }
    
    
    public static function deleteDept($accessToken, $id)
    {
        $response = Http::get("/department/delete", 
            array("access_token" => $accessToken, "id" => $id));
        return $response->errcode == 0;
    }
}