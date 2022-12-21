<?php

declare(strict_types=1);

$config = require_once __DIR__ . '/../config.php';

if ($config['APP_ENV'] === 'development') {
	error_reporting(E_ALL);
	ini_set('display_errors', '1');
	ini_set('display_startup_errors', '1');
}

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/settings.php';

use Symfony\Component\Yaml\Yaml;

/**
 * @return Dashboard
 */
function dashboard(): Dashboard
{
	/**
	 * @var array $settings
	 * @var Dashboard $dashboard
	 */
	$settings = Yaml::parseFile(__DIR__ . '/../settings.yml');
	$dashboard = new Dashboard($settings['dashboard']);
	return $dashboard;
}

require_once __DIR__ . '/Helpers/items.php';
require_once __DIR__ . '/Helpers/categories.php';

/**
 * Generate HTML of the section of a given category
 * 
 * @param string $category
 * @param bool $background
 * @return string
 */
function generateCategorySection(string $category, bool $background): string
{	
	/**
	 * @var string $sectionClass
	 * @var string $titleClass
	 * @var string $dividerClass
	 */
	$sectionClass = $background ? "bg-primary-light text-white mb-0" : "";
	$titleClass = $background ? "text-white" : "";
	$dividerClass = $background ? "divider-light" : "";
	return "<section class='page-section $sectionClass' id='$category'>
			<div class='container'>
				<h2 class='page-section-heading text-center text-uppercase $titleClass'>".ucfirst($category)."</h2>
				<!-- Icon Divider-->
				<div class='divider-custom $dividerClass'>
					<div class='divider-custom-line'></div>
					<div class='divider-custom-icon'><i class='fas fa-star'></i></div>
					<div class='divider-custom-line'></div>
				</div>
				".generateCategoryItemsList($category)."
			</div>
		</section>";
}

/**
 * Generate HTML for the results of the research
 * 
 * @return string
 */
function searchResults(): string
{
	/**
	 * @var string $search
	 * @var string $results
	 */
	$search = $_GET['search'] ?? null;
	if (!isset($search) || $search === "") {
		$results = "<li class='list-group-item'>No search term provided</li>";
	} else {
		$results = generateSearchContentList($search);
		if (!$results) $results = "<li class='list-group-item'>No result found</li>";
	}
	return "<ul class='list-group'>$results</ul>";
}

/**
 * Generate HTML for the latests uploaded items
 * 
 * @param int $limit
 * @return string
 */
function latestUploads(int $limit = 6): string
{
	$latestUploadedItems = generateItems(getLatestItems($limit));
	if (!$latestUploadedItems) $latestUploadedItems = "<li class='list-group-item'>No result found</li>";
	return "<ul class='list-group'>$latestUploadedItems</ul>";
}