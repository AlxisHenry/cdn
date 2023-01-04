<?php

declare(strict_types=1);

session_start();

/**
 * Utils
 */
require_once __DIR__ . '/Utils/functions.php';

if (config('APP_ENV') === 'development') {
	error_reporting(E_ALL);
	ini_set('display_errors', '1');
	ini_set('display_startup_errors', '1');
}

require_once __DIR__ . '/../vendor/autoload.php';

/**
 * Classes
 */
require_once __DIR__ . '/Classes/Redirect.php';
require_once __DIR__ . '/Classes/Route.php';
require_once __DIR__ . '/Classes/Auth.php';
require_once __DIR__ . '/Classes/Dashboard.php';
require_once __DIR__ . '/Classes/User.php';
require_once __DIR__ . '/Classes/File.php';

/**
 * Helpers
 */
require_once __DIR__ . '/Helpers/items.php';
require_once __DIR__ . '/Helpers/categories.php';