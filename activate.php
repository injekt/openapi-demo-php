<?php

require_once(__DIR__ . "/api/Activate.php");

/**
 * 在已获取到permanent_code的情况下，手动触发套件激活，无须传入tmp_auth_code。
 */
Activate::autoActivateSuite(null);