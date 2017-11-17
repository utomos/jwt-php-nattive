<?php
date_default_timezone_set('Asia/Jakarta');
require_once('vendor/autoload.php');

use Firebase\JWT\JWT;

$static_setting = array(
    'jwt' => array(
        'key' => '111222333444555',
        'algorithm' => 'HS512'
    ),
    'servername' => 'http://localhost/riset/jwt-php-nattive'
);

$database_setting = array(
    'host' => 'localhost',
    'username' => 'root',
    'password' => '',
    'database' => 'jwt_nattive',
);


$link_connection = new mysqli($database_setting['host'], $database_setting['username'], $database_setting['password'], $database_setting['database']);
