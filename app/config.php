<?php

return array(
    // database settings
    'db' => array(
        'dev' => array(
            'driver'    => 'pdo_mysql',
            'dbname'    => 'bibi',
            'host'      => '127.0.0.1',
            'user'      => 'root',
            'password'  => 'root',
            'port'      => '3306'
        ),
        'test' => array(
            'driver'    => 'pdo_mysql',
            'dbname'    => 'bibi',
            'host'      => '127.0.0.1',
            'user'      => 'root',
            'password'  => 'root',
            'port'      => '3306'
        ),

        'citest' => array(
            'driver'    => 'pdo_mysql',
            'dbname'    => 'bibi',
            'host'      => '127.0.0.1',
            'user'      => 'root',
            'password'  => '',
            'port'      => '3306'
        ),
    ),
);