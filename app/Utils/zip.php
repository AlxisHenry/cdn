<?php

/**
 * @param array $items
 * @throws Exception
 * @return void
 */
function createZipAndDownload(array $items): void
{
	// Create instance of ZipArchive. and open the zip folder.
	$zip = new ZipArchive();

	$zipFilename = "cdn-" . time() . ".zip";
	$zipFilepath = "./archives/";

	$zipFile = "$zipFilepath.$zipFilename";

	if (!$zip->open($zipFile, ZipArchive::CREATE)) {
		throw new Exception('Could not open zip file.');	
	}

	// Adding files to the zip
	foreach ($items as $item) {
		$filepath = "." . $item['filepath'];
		if (file_exists($filepath)) {
			$zip->addFile($filepath, explode("/", $zipFile)[count(explode("/", $zipFile)) - 1]);
		}
	}

	$zip->close();
	
	// Download the created zip file
	header("Content-type: application/zip");
	header("Content-Disposition: attachment; filename = $zipFilename");
	header("Pragma: no-cache");
	header("Expires: 0");
	readfile("$zipFile");
	
	// Delete the zip file after download
	unlink($zipFile);

	die();
}