<?php
return [
    'host'    => ENV('SPHINX_HOST',ENV('DB_HOST','127.0.0.1')),
    'port'    => ENV('SPHINX_PORTSPHINX',9312),
    'timeout' => 30,
    'user' => ENV('DB_USERNAME','forge'),
    'password' => ENV('DB_PASSWORD','forge'),
    'indexes' => [
        'billboardIndex' => ['table' => 'ads', 'column' => 'id'],
    ],
    'mysql_server' => [
        'host' => ENV('SPHINX_HOST',ENV('DB_HOST','127.0.0.1')),
        'user' => ENV('DB_USERNAME','forge'),
        'password' => ENV('DB_PASSWORD','forge'),
        'port' => ENV('SPHINX_PORTMYSQL41',9306) ]
];


