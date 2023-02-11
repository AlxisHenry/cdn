<?php

declare(strict_types=1);

namespace App\Classes;

class Asset {

	/**
	 * @param string|array<string> $f
	 * @return string
	 */
	public static function new(string|array $f): string
	{
		$html = "";
		if (is_array($f)) {
			foreach ($f as $file) {
				$html .= self::generateAssetHtmlTag(filename: $file);
			}
		} else {	
			$html .= self::generateAssetHtmlTag(filename: $f);
		}
		return $html;
	}

	/**
	 * @param string $filename
	 * @return string
	 */
	private static function generateAssetHtmlTag(string $filename): string
	{
		$file = (($_SERVER['HTTPS'] ?? false) ? "https://" : "http://") . $_SERVER['HTTP_HOST'] . "/$filename";
		if (pathinfo($file, PATHINFO_EXTENSION) === "js") return "<script src='$file' async></script>";
		return "<link href='$file' rel='stylesheet'/>";
	}

}