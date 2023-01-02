<?php

include_once '../app/dashboard.php';

/**
 * Check HTTP method
 * 
 * @param array $items
 * @throws Exception
 * @return void
 */
function httpMethod(array $except): void
{
	if (!in_array($_SERVER['REQUEST_METHOD'], $except)) {
		header('HTTP/1.1 405 Method Not Allowed', true, 405);
		throw new Exception('Method not allowed.');
	}
}

/**
 * Check action validity
 * 
 * @param string $action
 * @throws Exception
 * @return void
 */
function actionConformity(string $action): void
{
	if (!in_array($action, ['delete', 'edit', 'zip'])) {
		header('HTTP/1.1 400 Bad Request', true, 400);
		throw new Exception('Invalid action specified.');
	}
}

/**
 * Check token validity
 *
 * @throws Exception
 * @return void
 */
function tokenConformity(): void
{
	$token = json_decode(file_get_contents('php://input'), true)['token'] ?? null;
	if (($_SESSION['token'] ?? false) !== $token) {
		header('HTTP/1.1 403 Forbidden', true, 403);
		throw new Exception('Invalid token.');
	}
}

$action = json_decode(file_get_contents('php://input'), true)['action'] ?? $_GET['action'] ?? null;

actionConformity($action);

switch ($action) {
	case 'delete':
		httpMethod(['POST']);
		tokenConformity();
		$filepath = json_decode(file_get_contents('php://input'), true)['filepath'];
		$path = "." . $filepath;
		unlink($path);
		break;
	case 'edit':
		httpMethod(['POST']);
		tokenConformity();
		$body = json_decode(file_get_contents('php://input'), true);
		$filepath = $body['filepath'];
		$path = "." . $filepath;
		$newFilename = $body['newFilename'];
		$pathWithoutFilename = substr($path, 0, strrpos($path, '/'));
		$newPath = $pathWithoutFilename . '/' . $newFilename;
		if (file_exists($newPath) || !file_exists($path) || !is_writable($path)) {
			throw new Exception('File already exists or is not writable.');
		} elseif ($newFilename === "" || !$newFilename || !preg_match('/^[a-zA-Z0-9-_\.]+$/', $newFilename)) {
			throw new Exception('Invalid filename.');
		} else {
			try {
				rename($path, $newPath);
				setcookie('swal', 'file_renamed', time() + 1, '/');
			} catch (Exception $e) {
				throw new Exception('Could not rename file.');
			}
		}
		break;
	case 'zip':
		httpMethod(['GET']);
		$items = $_GET["items"];
		$f = explode(',', $items);
		$files = [];
		foreach ($f as $r) {
			$files[] = trim($r, '"[]');
		}
		createZipAndDownload($files);
		break;
}