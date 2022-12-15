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
 * Custom JSON response
 * 
 * @param array $response
 * @param int $response_code
 * @param string $domain
 */
function JsonResponse(array $response, int $response_code, string $domain) {
	header('Content-Type: application/json; charset=utf-8');
	echo json_encode(
		array_merge(
			[
				"url" => $domain . $_SERVER["REQUEST_URI"],
				"response_code" => $response_code,
				"response_time" => date("Y-m-d H:i:s")
			],
			$response
		)
	);
	die();
}

/**
 * @var string $request
 */
$request = $_SERVER["REQUEST_URI"];

/**
 * Check if the no category is specified
 */
if ($request === "/") {
	JsonResponse([
		"status" => "The request can't be processed. Please specify a category.",
		"message" => "Please specify a category in the URL. For example: $domain/{files|images|videos}.",
		"category_specified" => null,
		"file_specified" => null
	], 400, $domain);
}

/**
 * @var string $category
 * @var string $file
 */
$category = strtolower(explode("/", $request)[2]);
$file = $_GET["file"] ?? null;

/**
 * Check if the file name is specified
 */
if (!$file) {
	JsonResponse([
		"status" => "The request can't be processed. Please specify a $category name.",
		"message" => "Please specify a file name in the URL. For example: $domain/$category/file-name.ext.",
		"category_specified" => $category,
		"file_specified" => null
	], 400, $domain);
}

/**
 * Check if the file exists
 */
$filename = __DIR__ ."/public/$category/$file";
if (!file_exists($filename)) {
	JsonResponse([
		"status" => "The requested file doesn't exist.",
		"message" => "The requested file doesn't exist. Please check the URL parameters specified and retry.",
		"category_specified" => $category,
		"file_specified" => $file
	], 404, $domain);
}

/**
 * Download the file
 */
download($category, $file, $domain);