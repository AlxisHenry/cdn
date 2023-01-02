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
	return $list;
}

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