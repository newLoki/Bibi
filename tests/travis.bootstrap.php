<?php
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'citest'));
require_once __DIR__.'/../vendor/autoload.php';

$app = require __DIR__.'/../app/app.php';

return $app;
