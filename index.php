<?php

declare(strict_types=1);

/**
 * Url type : https://cdn.alexishenry.eu/public/{files|images|videos}?file={file-name}
 */

/**
 * Get the domain
 * @var string $domain
 */
$domain = ($_SERVER['HTTPS'] ? "https://" : "http://") . $_SERVER['HTTP_HOST'];

/**
 * Download files from the server
 * 
 * @param string $category
 * @param string $file
 * @param string $domain
 */
function download(string $category, string $file, string $domain) {
	header("Content-Description: File Transfer");
	header("Content-Type: application/octet-stream");
	header("Content-Disposition: attachment; filename=$file");
	header("Expires: 0");
	header("Cache-Control: must-revalidate");
	header("Pragma: public");
	header("Content-Length: " . filesize(__DIR__ . "/public/$category/$file"));
	readfile(__DIR__ . "/public/$category/$file");
	die();
}

/**
 * @var string $request
 * @var string $category
 * @var string $file
 */
$request = $_SERVER["REQUEST_URI"];
$category = strtolower(explode("/", $request)[2]);
$file = $_GET["file"] ?? null;

download($category, $file, $domain);