<?php

declare(strict_types=1);

/** 
 * @param string $element
 * @param string $type
 * @return string
 */
function htmlFormat(string $element, string $type = "ul"): string
{
	return "<$type class='list-group'>$element</$type>";
}

/**
 * Show a sweet alert message if the cookie is set or if you specify ²0
 * 
 * @param ?string $type
 * @return string
 */
function swal(?string $type = null): string
{
    $swal = $_COOKIE['swal'] ?? null;
    if ($swal === "logout") session_destroy(); 
    return match ($swal) {
        'connected' => "<script>
            Swal.fire({
                icon: 'success',
				iconColor: '#464aa6',
                title: 'Connected',
                text: 'You are now connected to the dashboard',
                showConfirmButton: true,
                timerProgressBar: true,
                timer: 3000
            })
        </script>",
        'connection_failed' => "<script>
            Swal.fire({
                icon: 'error',
				iconColor: '#242b40',
                title: 'Connection failed',
                text: 'The username or password is incorrect',
                showConfirmButton: true,
                timerProgressBar: true,
                timer: 3000
            })
        </script>",
        'logout' => "<script>
            Swal.fire({
                icon: 'success',
				iconColor: '#464aa6',
                title: 'Logout',
                text: 'You are now disconnected from the dashboard',
                showConfirmButton: true,
                timerProgressBar: true,
                timer: 3000
            })
        </script>",
        'file_renamed' => "<script>
            Swal.fire({
                icon: 'success',
				iconColor: '#464aa6',
                title: 'File renamed',
                text: 'The file has been renamed',
                showConfirmButton: true,
                timerProgressBar: true,
                timer: 3000
            })
        </script>",
		'file_uploaded' => "<script>
			Swal.fire({
				icon: 'success',
				iconColor: '#464aa6',
				title: 'File(s) uploaded',
				text: 'File(s) have been uploaded',
				showConfirmButton: true,
				timerProgressBar: true,
				timer: 3000
			})
		</script>",
		'file_upload_failed' => "<script>
			Swal.fire({
				icon: 'error',
				iconColor: '#242b40',
				title: 'File(s) upload failed',
				text: 'Files could not be uploaded',
				showConfirmButton: true,
				timerProgressBar: true,
				timer: 3000
			})
		</script>",
		'files_not_send' => "<script>
			Swal.fire({
				icon: 'error',
				iconColor: '#242b40',
				title: 'Upload failed',
				text: 'Please select at least one file to upload',
				showConfirmButton: true,
				timerProgressBar: true,
				timer: 3000
			})
		</script>",
        default => '',
    };
}

/**
 * @param array<string> $files
 * @throws Exception
 * @return void
 */
function createZipAndDownload(array $files): void
{

	$zip = new ZipArchive();

	$zipFilename = "cdn-" . time() . ".zip";
	$zipFilepath = "./archives";
	$zipFile = "$zipFilepath/$zipFilename";

	if (!$zip->open($zipFile, ZipArchive::CREATE)) throw new Exception('Could not open zip file.');	

	// Adding files to the zip
	foreach ($files as $file) {
		$file = ".$file";
		if (file_exists($file)) $zip->addFile($file, basename($file));
	}

	$zip->close();
	
	// Download the created zip file
	header('Content-Type: application/zip');
	header('Content-disposition: attachment; filename=' . $zipFilename);
	header('Content-Length: ' . filesize($zipFile));
	readfile($zipFile);

	unlink($zipFile);

	die();
}

/**
 * @param string $key
 * @throws Exception
 * @return string
 */
function config(string $key): string
{
	$config = require __DIR__ . '/../../config.php';
	if (isset($config[$key])) return $config[$key];
	throw new Exception("The key $key does not exist in the config file.");
}

/**
 * @param string $size
 * @param bool $round
 * @throws Exception
 * @return string
 */
function formatFilesize(string $size, bool $round = true): string
{
	$b = $round ? 1000 : 1024;
	$units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];
	for ($i = 0; $size > $b; $i++) $size /= $b;
	/** @phpstan-ignore-next-line */
	return round($size, 2) . ' ' . $units[$i];
}

/**
 * @param string $filename
 * @return string
 */
function generateAssetHtmlTag(string $filename): string
{
	$file = (($_SERVER['HTTPS'] ?? false) ? "https://" : "http://") . $_SERVER['HTTP_HOST'] . "/$filename";
	if (pathinfo($file, PATHINFO_EXTENSION) === "js") return "<script src='$file' async></script>";
	return "<link href='$file' rel='stylesheet'/>";
}


/**
 * @param string|array<string> $f
 * @return string
 */
function asset(string|array $f): string
{
	$html = "";
	if (is_array($f)) {
		foreach ($f as $file) {
			$html .= generateAssetHtmlTag(filename: $file);
		}
	} else {	
		$html .= generateAssetHtmlTag(filename: $f);
	}
	return $html;
}
