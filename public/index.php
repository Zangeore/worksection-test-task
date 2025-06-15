<?php

require __DIR__ . '/../vendor/autoload.php';

use Core\App;

$config = require __DIR__ . '/../App/Config/config.php';
if (file_exists(__DIR__ . '/../App/Config/config-local.php')) {
    $config = array_merge_recursive($config, require __DIR__ . '/../App/Config/config-local.php');
}
$app = new App($config);
$app->run();

