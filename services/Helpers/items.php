<?php

use Carbon\Carbon;

/**
 * Get all items uploaded
 */
function getItems(): array
{
	/**
	 * @var array $items
	 */
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
 * @param array $items
 * @return string
 */
function generateItems(array $items): string
{
	/**
	 * @var string $list
	 * @var string $domain
	 */
	$list = "";
	$domain = (($_SERVER['HTTPS'] ?? false) ? "https://" : "http://") . $_SERVER['HTTP_HOST'];
	foreach ($items as $item) {
		$category = $item['category'];
		$filename = $item['filename'];
		$updated_at = Carbon::createFromTimestamp($item['updated_at'])->diffForHumans();
		/**
		 * @var string $url
		 * @var string $downloadUrl
		 */
		$url = $domain . "/shared/$category/$filename";
		$downloadUrl = $domain . "/shared/$category/?file=$filename";
		$list .= "<li class='list-group-item d-flex flex-column' 
					data-filepath='/shared/$category/$filename'
					data-category='$category'
					data-filename='$filename'>
					<div class='d-flex justify-content-between mt-1'>
						<div>
							<a class='item' target='_blank' href='/shared/$category/$filename'>$filename</a>
							<span>uploaded $updated_at</span>
						</div>";
		if ($_SESSION['connected']) {
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
	return $list;
}

/**
 * Return an array of files matching with the research
 * 
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
	 * 	'filename' => 'filename',
	 * 	'updated_at' => 'timestamp'
	 * ]
	 */
	foreach (getCategories() as $category) {
		foreach (getCategoryItems($category) as $item) {
			if (strpos(strtolower($item['filename']), strtolower($search)) !== false) {
				$items[] = $item;
			}
		}
	}
	return $items;
}

/**
 * @param int $limit
 * @return array
 */
function getLatestItems(int $limit): array
{
	/**
	 * @var array $items
	 */
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