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
	 * @param bool $error
	 * @throws Exception
	 * @return void
	 */
	public static function show(string $view, bool $error = false): void
	{
		try {
			if (!$error) {
				include self::ROOT . "/layouts/head.php";
				include self::find($view);
				include self::ROOT . "/layouts/foot.php";
			} else {
				include self::find("errors.$view");
			}
		} catch (\Throwable $th) {
			throw new Exception("Invalid path to view given to View::show() static function $th");
		}
	}

}