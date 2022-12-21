<?php

/**
 * Return an array with the differents categories
 * 
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
 * Return an array with the items of a category passed in parameter
 * 
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
		if (!in_array($item, $exclude)) {
			$files[] = [
				'category' => $category,
				'filename' => $item,
				'updated_at' => filemtime("./shared/$category/$item")
			];
		}
	}
	return $files;
}

/**
 * Generate list of items of a given category
 * 
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