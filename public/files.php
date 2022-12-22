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
	}

	switch ($body['action']) {
		case 'delete':
			$path = "." . $body['filepath'];
			var_dump(".$path");
			unlink($path);
			break;
	}

} else {
	header('HTTP/1.1 500 Internal Server Error - no action provided', true, 500);
	die();
}