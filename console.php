<?php

require_once(__DIR__ . "/util/Cache.php");

i("suite_ticket");
i("permanent_auth_code_info");
i("suite_access_token");
i("corp_access_token");
i("js_ticket");


function i($key)
{
    var_dump($key . " : " . Cache::get($key));
}