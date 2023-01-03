<?php

class Route {

	/**
	 * @var array $routes
	 */
	private static array $routes = [
		0 => '',
		1 => 'archive',
		2 => 'upload',
	];
	
	/**
	 * @var string $route
	 */
	private static string $route;

	/**
	 * @param string $route
	 * @return bool
	 */
	public static function check(string $route): bool
	{
		return in_array($route, self::$routes);
	}

	/**
	 * @return void
	 */
	public static function make(): void
	{
		$route = array_search(explode('/', $_SERVER['REQUEST_URI'])[1], self::$routes);
		self::$route = (array_key_exists($route, self::$routes) ?? false) ? self::$routes[$route] : false;
	}

	/**
	 * @return void
	 */
	public static function view(): void
	{
		$viewPath = __DIR__ . '/../../resources/views/pages';
		switch (self::$route) {
			case '':
				include_once "$viewPath/dashboard.php";
				break;
			case 'archive':
				include_once "$viewPath/zip.php";
				break;
			case 'upload':
				include_once "$viewPath/upload.php";
				break;
			default:
				break;
		}
	}

}