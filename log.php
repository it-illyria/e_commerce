<?php
// Autoload Composer dependencies
require 'vendor/autoload.php';

// Create a Logger instance
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

$log = new Logger('name');
$log->pushHandler(new StreamHandler('app.log', Logger::WARNING));

// Now you can log messages
$log->warning('This is a warning');
$log->error('This is an error');
