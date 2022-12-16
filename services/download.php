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
	$url = "$domain/public/$category/$file";
	header("Content-Description: File Transfer");
	header("Content-Type: application/octet-stream");
	header("Content-Disposition: attachment; filename=$file");
	header("Expires: 0");
	header("Cache-Control: must-revalidate");
	header("Pragma: public");
	header("Content-Length: " . filesize($url));
	readfile($url);
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