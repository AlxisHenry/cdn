<?php

declare(strict_types=1);

session_start();

use App\Classes\Helper;


require_once __DIR__ . '/../vendor/autoload.php';

if (in_array(Helper::config('APP_ENV'), ["dev", "development"])) {
	error_reporting(E_ALL);
	ini_set('display_errors', '1');
	ini_set('display_startup_errors', '1');
}