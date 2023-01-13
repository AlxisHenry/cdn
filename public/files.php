<?php

include_once '../app/dashboard.php';

/**
 * Check HTTP method
 * 
 * @param array<string> $except
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
	if (!in_array($action, ['delete', 'edit', 'zip', 'upload'])) {
		header('HTTP/1.1 400 Bad Request', true, 400);
		throw new Exception('Invalid action specified.');
	}
}

/**
 * Check token validity
 *
 * @param string $token
 * @throws Exception
 * @return void
 */
function tokenConformity(string $token): void
{
	if (($_SESSION['token'] ?? false) !== $token) {
		header('HTTP/1.1 403 Forbidden', true, 403);
		throw new Exception('Invalid token.');
	}
}

if (isset($_POST['action'])) {
	$body = $_POST;
	// @phpstan-ignore-next-line
} elseif (isset(json_decode(file_get_contents('php://input'), true)['action'])) {
	// @phpstan-ignore-next-line
	$body = json_decode(file_get_contents('php://input'), true);
} else {	
	$body = $_GET;
}

// @phpstan-ignore-next-line
$action = $body['action'];

// @phpstan-ignore-next-line
actionConformity($action);

switch ($action) {
	case 'delete':
		httpMethod(['POST']);
		// @phpstan-ignore-next-line
		tokenConformity($body['token']);
		// @phpstan-ignore-next-line
		$filepath = $body['filepath'];
		$path = "." . $filepath;
		unlink($path);
		break;
	case 'edit':
		httpMethod(['POST']);
		// @phpstan-ignore-next-line
		tokenConformity($body['token']);
		// @phpstan-ignore-next-line
		$filepath = $body['filepath'];
		$path = "." . $filepath;
		// @phpstan-ignore-next-line
		$newFilename = $body['newFilename'];
		// @phpstan-ignore-next-line
		$pathWithoutFilename = substr($path, 0, strrpos($path, '/'));
		$newPath = $pathWithoutFilename . '/' . $newFilename;
		if (file_exists($newPath) || !file_exists($path) || !is_writable($path)) {
			throw new Exception('File already exists or is not writable.');
			// @phpstan-ignore-next-line
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
		// @phpstan-ignore-next-line
		$items = $body["items"];
		// @phpstan-ignore-next-line
		$f = explode(',', $items);
		$files = [];
		foreach ($f as $r) {
			$files[] = trim($r, '"[]');
		}
		createZipAndDownload($files);
		break;
	case 'upload':
		httpMethod(['POST']);
		// @phpstan-ignore-next-line
		tokenConformity($body['token']);
		$uploadedFiles = $_FILES["files"];
		if ($uploadedFiles["name"][0] === "") {
			setcookie('swal', 'files_not_send', time() + 1, '/');
			Redirect::back();
			break;
		}
		$files = [];
		foreach ($uploadedFiles as $v => $f) {
			foreach ($f as $k => $o) {
				$files[$k][$v] = $o;
			}
		}
		$statuses = [];
		foreach ($files as $k => $f) {
			// @phpstan-ignore-next-line
			$file = new File($f); 
			$statuses[] = $file->upload();
		}
		if (!in_array(true, $statuses)) {
			setcookie('swal', 'file_upload_failed', time() + 1, '/');
		} else {
			setcookie('swal', 'file_uploaded', time() + 1, '/');
		}
		Redirect::back();
		break;
}