<?php

declare(strict_types=1);

class Route extends RouteManager {

	/**
	 * @var string $method
	 */
	private string $method;

	/**
	 * @var ?string $name
	 */
	private ?string $name;

	/**
	 * @var string $callback
	 */
	private string $callback;
		
	/**
	 * @var string $uri
	 */
	private string $uri;

	/**
	 * @param string $uri
	 * @param string $callback
	 * 
	 * @return Route
	 */
	public static function get(string $uri, string $callback): Route 
	{
		return new Route(method: "GET", uri: $uri, callback: $callback);
	}

	/**
	 * @param string $uri
	 * @param string $callback
	 * 
	 * @return Route
	 */
	public static function post(string $uri, string $callback): Route 
	{
		return new Route(method: "POST", uri: $uri, callback: $callback);
	}

	/**
	 * @param array<string,string> $properties
	 */
	public function __construct(string ...$properties) /** @phpstan-ignore-line */
	{
		$this->setProperties($properties);
	}

	/**
	 * @param array<string, string>
	 * @return void
	 */
	private function setProperties(array $properties): void /** @phpstan-ignore-line */
	{
		$this->setMethod(method: $properties["method"]);
		$this->uri = $properties["uri"];
		$this->callback = $properties["callback"];
	}

	/**
	 * @param string $method
	 * @return void
	 */
	private function setMethod(string $method): void 
	{
		$this->method = $this->availableMethod($method) ? $method : "GET";
	}

	/**
	 * @throws Exception
	 * @return void
	 */
	public function call(): void
	{
		$method = $_SERVER["REQUEST_METHOD"];

		if ($method !== $this->getMethod()) {
			View::abort("405", header: false);
			return;		
		}

		try {
			$callback = explode('@', $this->callback);
			$controller = $callback[0];
			$method = $callback[1];
			$closure = new $controller();
			$closure->$method();
		} catch (\Throwable $th) {
			throw new Exception("Invalid controller name in route call method $th");
		}
	}

	/**
	 * @param string $name
	 * @return Route
	 */
	public function name(string $name): Route
	{
		$this->name = $name;
		return $this;
	}

	/**
	 * @return ?string
	 */
	public function getName(): ?string
	{
		return $this->name;
	}

	/**
	 * @return string
	 */
	public function getUri(): string
	{
		return $this->uri;
	}

	/**
	 * @return string
	 */
	public function getMethod(): string
	{
		return $this->method;
	}

}
