<?php

use Symfony\Component\Yaml\Yaml;

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
 * @return Dashboard
 */
function dashboard(): Dashboard
{
	/**
	 * @var array $settings
	 * @var Dashboard $dashboard
	 */
	$settings = Yaml::parseFile(__DIR__ . '/../../settings.yml');
	$dashboard = new Dashboard($settings['dashboard']);
	return $dashboard;
}

/**
 * @return User
 */
function user(): User
{
	/**
	 * @var array $settings
	 * @var User $user
	 */
	$settings = Yaml::parseFile(__DIR__ . '/../../settings.yml');
	$user = new User($settings['user']);
	return $user;
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
				title: 'File uploaded',
				text: 'The file has been uploaded',
				showConfirmButton: true,
				timerProgressBar: true,
				timer: 3000
			})
		</script>",
		'file_upload_failed' => "<script>
			Swal.fire({
				icon: 'error',
				title: 'File upload failed',
				text: 'The file has not been uploaded',
				showConfirmButton: true,
				timerProgressBar: true,
				timer: 3000
			})
		</script>",
        default => '',
    };
}

/**
 * @param array $files
 * @throws Exception
 * @return void
 */
function createZipAndDownload(array $files): void
{
	/**
	 * Create instance of ZipArchive. and open the zip folder.
	 * 
	 * @var ZipArchive $zip
	 */
	$zip = new ZipArchive();

	/**
	 * Create the zip file name and path
	 * 
	 * @var string $zipFilename
	 * @var string $zipFilepath
	 * @var string $zipFile
	 */
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
 * @return string|int
 */
function config(string $key): string|int
{
	/**
	 * @var array $settings
	 */
	$config = require __DIR__ . '/../../config.php';
	if (isset($config[$key])) return $config[$key];
	throw new Exception("The key $key does not exist in the config file.");
}

/**
 * @param string $size
 * @param bool $round
 * @throws Exception
 * @return string|int
 */
function formatFilesize(string $size, bool $round = true): string
{
	$b = $round ? 1000 : 1024;
	$units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];
	for ($i = 0; $size > $b; $i++) $size /= $b;
	return round($size, 2) . ' ' . $units[$i];
}
