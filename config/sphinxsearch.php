<?php
return [
    'host'    => env('DB_SPHINXHOST', '127.0.0.1'),
    'port'    => env('DB_PORTSPHINX', 9312),
    'timeout' => 30,
    'indexes' => [
        env('DB_SPHINXINDEX','billboardIndex') => ['table' => env('DB_SPHINXTABLE','ads'), 'column' => env('DB_SPHINXIDCOLUMN','id')],
    ],
    'mysql_server' => [
        'host' => env('DB_SPHINXHOST', '127.0.0.1'),
        'port' => env('DB_PORTMYSQL41',9306)]
];

