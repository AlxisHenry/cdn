<?php

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