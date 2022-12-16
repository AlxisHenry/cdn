<?php

declare(strict_types=1);

/**
 * @return array
 */
function getCategories(): array 
{
	$categories = [];
	$directories = scandir('./public/');
	$exclude = ['.', '..'];
	foreach ($directories as $directory) {
		if (!in_array($directory, $exclude)) {
			$categories[] = $directory;
		}
	}
	return $categories;
}

/**
 * @param string $category
 * @return array
 */
function getContent(string $category): array 
{
	$files = [];
	$categoryContent = scandir("./public/$category/");
	$exclude = ['.', '..', '.gitignore', 'index.php'];
	foreach ($categoryContent as $content) {
		if (!in_array($content, $exclude)) {
			$files[] = $content;
		}
	}
	return $files;
}

/**
 * @param string $category
 * @param string $domain
 * @return string
 */
function generateCategoryContentList(string $category): string
{
	$content = getContent($category);
	$list = "";
	$domain = ($_SERVER['HTTPS'] ? "https://" : "http://") . $_SERVER['HTTP_HOST'];
	foreach ($content as $item) {
		$url = $domain . "/public/$category/$item";
		$downloadUrl = $domain . "/public/$category/?file=$item";
		$list .= "<li class='list-group-item d-flex flex-column'>
					<p>
						<a class='' target='_blank' href='public/$category/$item'>$item</a>
					</p>
					<p class='d-flex flex-column text-decoration-none'>
						<a class='ml-auto p-1 text-decoration-none' target='_blank' href='$url'><i class='fa-solid fa-link'></i> <span>$url</span></a>
						<a class='ml-auto p-1 text-decoration-none' href='$downloadUrl'><i class='fas fa-download'></i> <span>$downloadUrl</span></a>
					</p>
				</li>";
	}
	if (empty($list)) $list = "<li class='list-group-item'>No content available</li>";
	return "<ul class='list-group'>$list</ul>";
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
				".generateCategoryContentList($category)."
			</div>
		</section>";
}