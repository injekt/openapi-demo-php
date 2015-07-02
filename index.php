<?php

require_once(__DIR__ . "/env.php");
require_once(__DIR__ . "/util/Http.php");

require_once(__DIR__ . "/api/Auth.php");
require_once(__DIR__ . "/api/Department.php");


//get access token
$accessToken = \api\Auth::getAccessToken();
if ($accessToken != null)
{
    i("Success to get acess token: " . $accessToken);
    
    $dept = array(
        "name" => "TestDept.php6",
        "parentid" => 1,
        "order" => 1);

    //create department
    $departmentId = \api\Department::createDept($accessToken, $dept);
    if ($departmentId != null)
    {
        i("Success to create department: id=" . $departmentId);
        
        //list departments
        $list = \api\Department::listDept($accessToken);
        if ($list != null)
        {
            i("Success to get list of departments: size=" . count($list));
            // var_dump($list);
        }
        
        //delete department
        $isDeleted = \api\Department::deleteDept($accessToken, $departmentId);
        if ($isDeleted)
        {
            i("Success to delete department: id=" . $departmentId);
        }
        else
        {
            i("Fail to delete department");
        }
    }
    else
    {
        i("Fail to create department");
    }
}
else{
    i("Fail to get access token");
}


function i($msg)
{
    echo $msg . "<br/>";
}