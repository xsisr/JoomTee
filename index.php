<?php
//echo "a";
//exit;
// absolute filesystem path to this web root
$params['wwwDir'] = __DIR__;

// absolute filesystem path to the application root
$params['appDir'] = realpath(__DIR__ . '/app');

// absolute filesystem path to the library root
$params['libsDir'] = realpath(__DIR__ . '/libs');

// load bootstrap file
require $params['appDir'] . '/bootstrap.php';
