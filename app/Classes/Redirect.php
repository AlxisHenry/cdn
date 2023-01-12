<?php

class Redirect {

	/**
	 * @param string $route
	 */
	public static function to(string $route): void
	{
		header("Location: $route");
	}

	/**
	 * @return void
	 */
	public static function back(): void
	{
		$previous = explode('/', $_SERVER['HTTP_REFERER'])[3];
		header("Location: /$previous");
	}

}