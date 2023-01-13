<?php

class View {

	/**
	 * @var string ROOT
	 */
	public const ROOT = __DIR__ . "/../../resources/views";

	/**
	 * @param string $view
	 * @return string
	 */
	public static function find(string $view): string
	{	
		$el = explode(".", $view);
		if (count($el) === 1) return $el[0];
		$view = self::ROOT;
		foreach ($el as $l) {
			$view .= "/$l";
		}
		$view .= ".php";
		return $view;
	}

	/**
	 * @param string $view
	 * @param array<string,mixed> $data
	 * @throws Exception
	 * @return void
	 */
	public static function show(string $view, array $data = []): void
	{
		try {
			foreach ($data as $k => $v) { $$k = $v; }
			include self::ROOT . "/layouts/head.php";
			include self::find($view);
			include self::ROOT . "/layouts/foot.php";
		} catch (\Throwable $th) {
			throw new Exception("Invalid path to view given to View::show() static function $th");
		}
	}

	/**
	 * @param string $view
	 * @param bool $header
	 * @param array<string,mixed> $data
	 * @return void
	 */
	public static function abort(string $view, bool $header = false, array $data = []): void
	{
		try {
			if ($header) include self::ROOT . "/layouts/head.php";
			include self::find("errors.$view");
			if ($header) include self::ROOT . "/layouts/foot.php";
		} catch (\Throwable $th) {
			throw new Exception("Invalid path to view given to View::show() static function $th");
		}
	}

}