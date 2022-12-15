<?php

declare(strict_types=1);

/**
 * Url type : https://domain.com/{files|images|videos}?file={file-name}
 */

/**
 * Download files from the server
 * 
 * @var string $category
 * @var string $file
 */
function download(string $category, string $file) {
	header("Content-Description: File Transfer");
	header("Content-Type: application/octet-stream");
	header("Content-Disposition: attachment; filename=$file");
	header("Expires: 0");
	header("Cache-Control: must-revalidate");
	header("Pragma: public");
	header("Content-Length: " . filesize(__DIR__ . "/$category/$file"));
	readfile(__DIR__ . "/$category/$file");
	die();
}

/**
 * Custom JSON response
 * 
 * @var array $response
 * @var string $header
 */
function response(array $response, string $header = 'Content-Type: application/json; charset=utf-8') {
	header($header);
	echo json_encode($response);
	die();
}

/**
 * Get the domain of the current request
 */
function domain() {
	$domain = $_SERVER['HTTP_HOST'];
	$protocol = $_SERVER['HTTPS'] ? "https://" : "http://";
	return $protocol . $domain;
};
$domain = domain();

/**
 * Check if the category requested is valid
 * 
 * @return string
 */
enum Category: string 
{
	case File = "files";
	case Image = "images";
	case Video = "videos";
}

$request = $_SERVER["REQUEST_URI"];

/**
 * Check if the no category is specified
 */
if ($request === "/") {
	response([
		"url" => $domain . $_SERVER["REQUEST_URI"],
		"response_code" => 400,
		"status" => "The request can't be processed. Please specify a category.",
		"message" => "Please specify a category in the URL. For example: $domain/{files|images|videos}."
	]);
}

$category = strtolower(explode("/", $request)[1]);

/**
 * Check if the category is valid
 */
if (!Category::tryFrom($category)) {
	response([
		"url" => $domain . $_SERVER["REQUEST_URI"],
		"response_code" => 400,
		"status" => "The request can't be processed. Please specify a correct category.",
		"message" => "Please specify a correct category in the URL. For example: $domain/{files|images|videos}."
	]);
}

$file = $_GET["file"] ?? null;

/**
 * Check if the file name is specified
 */
if (!$file) {
	response([
		"url" => $domain . $_SERVER["REQUEST_URI"],
		"response_code" => 400,
		"status" => "The request can't be processed. Please specify a $category name.",
		"message" => "Please specify a file name in the URL. For example: $domain/$category/file-name.ext."
	]);
}

/**
 * Check if the file exists
 */
if (!file_exists(__DIR__ . "/$category/$file")) {
	response([
		"url" => $domain . $_SERVER["REQUEST_URI"],
		"response_code" => 404,
		"status" => "The requested file doesn't exist.",
		"message" => "The requested file doesn't exist. Please check the URL."
	]);
}

/**
 * Download the file
 */
download($category, $file);