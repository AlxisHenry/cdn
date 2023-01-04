<?php

declare(strict_types=1);

class Redirect {

	/**
	 * @param string $url
	 * 
	 * @return void
	 */
	public static function to(string $url): void
	{
		if (!Route::check($url)) $url = '/';
		header("Location: $url");
	}

	/**
	 * @return void
	 */
	public static function back(): void
	{
		$previous = explode('/', $_SERVER['HTTP_REFERER'])[3];
		if (Route::check($previous)) {
			header("Location: /$previous");
		} else {
			header("Location: /");
		}
	}

}