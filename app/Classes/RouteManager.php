<?php

declare(strict_types=1);

class RouteManager {

	/**
	 * @var array<string> METHODS
	 */
	protected const METHODS = ["GET", "POST"];

	public function __construct() {}

	/**
	 * @param string $method
	 * @return bool
	 */
	protected function availableMethod(string $method): bool
	{
		return in_array($method, $this::METHODS);
	}

}