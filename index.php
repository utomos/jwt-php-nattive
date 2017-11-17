<?php

require 'config.php';

use Firebase\JWT\JWT;

Firebase\JWT\JWT::$leeway = 10;

if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    $tokenId = base64_encode(mcrypt_create_iv(32));
    $issuedAt = time();
    $notBefore = $issuedAt + 10;    //Adding 10 seconds
    $expire = $notBefore + 60;      // Adding 60 seconds
    $serverName = $static_setting['servername'];

    /*
     * CREATE THE TOKEN AS AN ARRAY
     */
    $data = [
        'iat' => $issuedAt,     // Issued at: time when the token was generated
        'jti' => $tokenId,      // Json Token Id: an unique identifier for the token
        'iss' => $serverName,   // Issuer
        'nbf' => $notBefore,    // Not before
        'exp' => $expire,       // Expire
        'data' => []            // data from database
    ];

    $sql = 'SELECT id, name FROM provinces';
    $query = mysqli_query($link_connection, $sql);
    $row = mysqli_fetch_assoc($query);

    $dump = array();
    do {
        /*
         * DECLARE DATA SET
         */
        $dump[] = array(
            'id' => $row['id'],
            'name' => utf8_encode($row['name'])
        );
    } while ($row = mysqli_fetch_assoc($query));
    $data['data'] = $dump;

    /*
     * DECODE THE JWT USING THE KEY FROM CONFIG
     */
    $secretKey = base64_decode($static_setting['jwt']['key']);
    $data = JWT::encode($data, $secretKey, $static_setting['jwt']['algorithm']);

    /*
     * RETURN DATA RESULT
     */
    header('Content-type: application/json');
    $data = ['jwt' => $data];
    echo json_encode($data);
} else {
    header('HTTP/1.0 405 Method Not Allowed');
}
