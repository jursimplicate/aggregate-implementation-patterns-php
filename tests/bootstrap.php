<?php

require dirname(__DIR__).'/vendor/autoload.php';

error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);

if (file_exists(__DIR__ . '/config/bootstrap.php')) {
    require __DIR__ . '/config/bootstrap.php';
}
