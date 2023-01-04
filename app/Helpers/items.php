<?php

declare(strict_types=1);

use Carbon\Carbon;

/**
 * Get all items uploaded
 * 
 * @return array<int, array<string,int|string|false>>
 */
function getItems(): array
{
	$items = [];
	foreach (getCategories() as $category) {
		foreach (getCategoryItems($category) as $item) {
			$items[] = $item;
		}
	}
	return $items;
}

/**
 * Generate items for the given array of items
 * 
 * @param array<int, array<string,int|string|false>> $items
 * @param bool $html
 * @return string
 */
function generateItems(array $items, bool $html =  false): string
{
	$list = "";
	$domain = (($_SERVER['HTTPS'] ?? false) ? "https://" : "http://") . $_SERVER['HTTP_HOST'];
	if (empty($items)) return htmlFormat("<li class='list-group-item'>No items found</li>");
	foreach ($items as $item) {
		$category = $item['category'];
		$filename = $item['filename'];
		/** @phpstan-ignore-next-line */
		$updated_at = Carbon::createFromTimestamp($item['updated_at'])->diffForHumans();
		$url = $domain . "/shared/$category/$filename";
		$downloadUrl = $domain . "/shared/$category/?file=$filename";
		$list .= "<li class='list-group-item item-container d-flex flex-column gap-2'
					data-filepath='/shared/$category/$filename'
					data-category='$category'
					data-filename='$filename'>
					<div class='d-flex justify-content-between item-description mt-1'>
						<div class='item-main-description'>
							<a class='item' target='_blank' href='/shared/$category/$filename'>$filename</a>
							<span>uploaded $updated_at</span>
						</div>";
		if (Auth::check()) {
			$list .= "
			<div class='d-flex gap-3' data-token=".$_SESSION['token'].">
				<div class='edit-item' data-action='edit'><i class='fa-solid fa-pen ml-2' style='cursor: pointer;'></i></div>
				<div class='delete-item' data-action='delete'><i class='fa-solid fa-trash ml-2' style='cursor: pointer;'></i></div>
			</div>";
		}
		$list .= "</div>
					<p class='d-flex flex-column text-decoration-none'>
						<a class='ml-auto p-1 text-decoration-none item' target='_blank' href='$url'><i class='fa-solid fa-link'></i> <span>$url</span></a>
						<a class='ml-auto p-1 text-decoration-none item' href='$downloadUrl'><i class='fas fa-download'></i> <span>$downloadUrl</span></a>
					</p>
				</li>";
	}
	return $html ? htmlFormat($list) : $list;
}

/**
 * Return an array of files matching with the research
 * 
 * @param string $search
 * @return array<int, array<string,int|string|false>>
 */
function getItemsCorrespondingToSearch(string $search): array
{
	$items = [];
	/**
	 * Make an array containing all the items corresponding to the search input in the shared folder
	 * An item have the following structure:
	 * [
	 * 	'category' => 'category',
	 * 	'filename' => 'filename',
	 * 	'updated_at' => 'timestamp'
	 * ]
	 */
	foreach (getCategories() as $category) {
		foreach (getCategoryItems($category) as $item) {
			/** @phpstan-ignore-next-line */
			if (strpos(strtolower($item['filename']), strtolower($search)) !== false) {
				$items[] = $item;
			}
		}
	}
	return $items;
}

/**
 * @param int $limit
 * @return array<int, array<string, int|string|false>>
 */
function getLatestItems(int $limit): array
{
	$items = getItems();
	usort($items, function ($a, $b) {
		return $b['updated_at'] <=> $a['updated_at'];
	});
	$latestItems = array_slice($items, 0, $limit);
	return $latestItems;
}

/**
 * Generate list of items that match the search
 * 
 * @param string $search
 * @return string
 */
function generateSearchContentList(string $search): string
{	
	$correspondingItems = getItemsCorrespondingToSearch($search);
	/** @phpstan-ignore-next-line */
	if (count($correspondingItems) <= 0 || !$correspondingItems) return false;
	return generateItems($correspondingItems);
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
	return htmlFormat($latestUploadedItems);
}

/**
 * Generate HTML for the results of the research
 * 
 * @return string
 */
function searchResults(): string
{
	$search = $_GET['search'] ?? null;
	if (!isset($search) || $search === "") {
		$results = "<li class='list-group-item'>No search term provided</li>";
	} else {
		$results = generateSearchContentList($search);
		if (!$results) $results = "<li class='list-group-item'>No result found</li>";
	}
	return htmlFormat($results);
}
