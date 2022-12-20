<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/settings.php';

use Symfony\Component\Yaml\Yaml;

/**
 * @return array
 */
function getCategories(): array 
{
	/**
	 * @var array $categories
	 * @var array $directories
	 * @var array $exclude
	 */
	$categories = [];
	$directories = scandir('./shared');
	$exclude = ['.', '..', 'build', 'index.php'];
	foreach ($directories as $directory) {
		if (!in_array($directory, $exclude)) $categories[] = $directory;
	}
	return $categories;
}

/**
 * @param string $search
 * @return array
 */
function getItemsCorrespondingToSearch(string $search): array
{
	/**
	 * @var array $items
	 */
	$items = [];
	/**
	 * Make an array containing all the items corresponding to the search input in the shared folder
	 * An item have the following structure:
	 * [
	 * 	'category' => 'category',
	 * 	'filename' => 'filename'
	 * ]
	 */
	foreach (getCategories() as $category) {
		foreach (getCategoryItems($category) as $item) {
			if (strpos(strtolower($item), strtolower($search)) !== false) {
				$items[] = [
					'category' => $category,
					'filename' => $item
				];
			}
		}
	}
	return $items;
}

/**
 * @param string $category
 * @return array
 */
function getCategoryItems(string $category): array 
{
	/**
	 * @var array $files
	 * @var array $categoryItems
	 * @var array $exclude
	 */
	$files = [];
	$categoryItems = scandir("./shared/$category/");
	$exclude = ['.', '..', '.gitignore', 'index.php'];
	foreach ($categoryItems as $item) {
		if (!in_array($item, $exclude)) $files[] = $item;
	}
	return $files;
}

/**
 * @param array $items
 * @param ?string $category
 * @return string
 */
function generateItems(array $items, ?string $category = null): string
{
	/**
	 * @var string $list
	 * @var string $domain
	 */
	$list = "";
	$domain = (($_SERVER['HTTPS'] ?? false) ? "https://" : "http://") . $_SERVER['HTTP_HOST'];
	if (!$category) {
		$category = $items[0]['category'];
	}
	foreach ($items as $item) {
		if (!is_array($item)) {
			$item = [
				'category' => $category,
				'filename' => $item
			];
		}
		$category = $item['category'];
		$filename = $item['filename'];
		/**
		 * @var string $url
		 * @var string $downloadUrl
		 */
		$url = $domain . "/shared/$category/$filename";
		$downloadUrl = $domain . "/shared/$category/?file=$filename";
		$list .= "<li class='list-group-item d-flex flex-column'>
					<p>
						<a class='' target='_blank' href='/shared/$category/$filename'>$filename</a>
					</p>
					<p class='d-flex flex-column text-decoration-none'>
						<a class='ml-auto p-1 text-decoration-none' target='_blank' href='$url'><i class='fa-solid fa-link'></i> <span>$url</span></a>
						<a class='ml-auto p-1 text-decoration-none' href='$downloadUrl'><i class='fas fa-download'></i> <span>$downloadUrl</span></a>
					</p>
				</li>";
	}
	return $list;
}

/**
 * @param string $category
 * @param string $domain
 * @return string
 */
function generateCategoryItemsList(string $category): string
{
	/**
	 * @var array $items
	 * @var string $list
	 */
	$items = getCategoryItems($category);
	$list = generateItems($items, $category);
	if (empty($list)) $list = "<li class='list-group-item'>No content available</li>";
	return "<ul class='list-group'>$list</ul>";
}

/**
 * @param string $search
 * @return bool|string
 */
function generateSearchContentList(string $search): bool|string
{	
	/**
	 * @var array $correspondingItems
	 */
	$correspondingItems = getItemsCorrespondingToSearch($search);
	if (count($correspondingItems) <= 0 || !$correspondingItems) return false;
	return generateItems($correspondingItems);
}

/**
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
	$sectionClass = $background ? "bg-primary text-white mb-0" : "";
	$titleClass = $background ? "text-white" : "text-secondary";
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