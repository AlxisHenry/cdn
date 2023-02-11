<?php

declare(strict_types=1);

namespace App\Classes;

use Exception;

class Router {

	/**
	 * @var array $routes<int,Route>
	 */
	public array $routes; /** @phpstan-ignore-line */
	
	/**
	 * @params array<Route> $routes
	 * @return Router
	 */
	public static function load(array $routes): Router /** @phpstan-ignore-line */
	{
		foreach ($routes as $route) {
			if (!is_object($route) && !($route instanceof Route)) { /** @phpstan-ignore-line */
				throw new Exception("Route given to Router isn't instance of Route class.");
			}
		}
		return new Router($routes);
	}

	/**
	 * @param array<int,Route> $routes
	 */
	public function __construct(array $routes)
	{
		$this->routes = $routes;
	}

	/**
	 * @return void
	 */
	public function run(): void
	{
		foreach ($this->routes as $route) {
			if (explode('?', $_SERVER["REQUEST_URI"])[0] === $route->getUri()) {
				$route->call();
				return;
			}
		}
		View::abort("404", header: true);
	}

}