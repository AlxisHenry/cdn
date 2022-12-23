<?php

include_once '../services/dashboard.php';

if (!in_array($_SERVER['REQUEST_METHOD'], ['POST', 'DELETE', 'PUT'])) {
	header('HTTP/1.1 500 Internal Server Error - Invalid Request Method', true, 500);
	die();
}

$body = json_decode(file_get_contents('php://input'), true);

if (isset($body['action'])) {

	$action = $body['action'];
	$token = $body['token'] ?? null;

	if (!isset($_SESSION['token']) || ($token !== $_SESSION['token'])) {
		header('HTTP/1.1 500 Internal Server Error - Invalid Token', true, 500);
		die();
	}

	$path = "." . $body['filepath'];
			
	switch ($body['action']) {
		case 'delete':
			unlink($path);
			break;
		case 'edit':
			$newFilename = $body['newFilename'];
			$pathWithoutFilename = substr($path, 0, strrpos($path, '/'));
			$newPath = $pathWithoutFilename . '/' . $newFilename;
			if (file_exists($newPath) || !file_exists($path) || !is_writable($path)) {
				header('HTTP/1.1 500 Internal Server Error - File', true, 500);
				die();
			} elseif ($newFilename === "" || !$newFilename || !preg_match('/^[a-zA-Z0-9-_\.]+$/', $newFilename)) {
				header('HTTP/1.1 500 Internal Server Error - Filename', true, 500);
				die();
			} else {
				try {
					rename($path, $newPath);
					setcookie('swal', 'file_renamed', time() + 1, '/');
				} catch (Exception $e) {
					header("HTTP/1.1 500 Internal Server Error - Rename $e", true, 500);
					die();
				}
			}
			break;
	}

} else {
	header('HTTP/1.1 500 Internal Server Error - no action provided', true, 500);
	die();
}