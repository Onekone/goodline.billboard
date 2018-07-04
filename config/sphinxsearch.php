<?php
return [
    'host'    => '127.0.0.1',
    'port'    => 9312,
    'timeout' => 30,
    'user' => ENV('DB_USERNAME','forge'),
    'password' => ENV('DB_PASSWORD','forge'),
    'indexes' => [
        'billboardIndex' => ['table' => 'ads', 'column' => 'id'],
    ],
    'mysql_server' => [
        'host' => '127.0.0.1',
        'user' => ENV('DB_USERNAME','forge'),
        'password' => ENV('DB_PASSWORD','forge'),
        'port' => 9306]
];


