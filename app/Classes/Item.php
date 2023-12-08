<?php

declare(strict_types=1);

namespace App\Classes;

use App\Classes\Auth;
use App\Classes\Category;
use App\Classes\Helper;
use Carbon\Carbon;

class Item
{

	/**
	 * Get all items uploaded
	 * 
	 * @return array<int, array<string,int|string|false>>
	 */
	public static function all(): array
	{
		$items = [];
		foreach (Category::all() as $category) {
			foreach (Category::items($category) as $item) {
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
	public static function create(array $items, bool $html =  false): string
	{
		$list = "";
		if (empty($items)) return Helper::htmlFormat("<li class='list-group-item'>No items found</li>");
		foreach ($items as $item) {
			$category = $item['category'];
			$filename = $item['filename'];
			$formattedFilename = Item::formatFilename($filename);
			[$url, $downloadUrl] = Item::getUrls($filename, $category);
			/** @phpstan-ignore-next-line */
			$updated_at = Carbon::createFromTimestamp($item['updated_at'])->diffForHumans();
			$list .= "<li class='list-group-item item-container d-flex flex-column gap-2'
						data-filepath='/shared/$category/$filename'
						data-category='$category'
						data-preview-url='$url'
						data-download-url='$downloadUrl'
						data-filename='$filename'>
						<div class='d-flex justify-content-between item-description mt-1'>
							<div class='item-main-description'>
								<a class='item' target='_blank' href='/shared/$category/$filename'>$formattedFilename</a>
								<span>uploaded $updated_at</span>
							</div>";
			$list .= "<div class='d-flex gap-3'". (Auth::check() ? "data-token=" . $_SESSION['token'] : null) .">";
			$list .= "<div class='copy icon' data-action='copy'><i class='fa-solid fa-copy ml-2'></i></div>";
			if (Auth::check()) {
				$list .= "<div class='edit-item icon' data-action='edit'><i class='fa-solid fa-pen ml-2' style='cursor: pointer;'></i></div>";
				$list .= "<div class='delete-item icon' data-action='delete'><i class='fa-solid fa-trash ml-2' style='cursor: pointer;'></i></div>";
			}
			$list .= "</div>";
			$list .= "</div>
						<p class='d-flex flex-column text-decoration-none'>
							<a class='ml-auto p-1 text-decoration-none item' target='_blank' href='$url'><i class='fa-solid fa-link'></i> <span>" . Item::formatUrl($url) . "</span></a>
							<a class='ml-auto p-1 text-decoration-none item' href='$downloadUrl'><i class='fas fa-download'></i> <span>" . Item::formatUrl($downloadUrl, isDownloadUrl: true) . "</span></a>
						</p>
					</li>";
		}
		return $html ? Helper::htmlFormat($list) : $list;
	}

	/**
	 * Return an array of files matching with the research
	 * 
	 * @param string $search
	 * @return array<int, array<string,int|string|false>>
	 */
	private static function getItemsCorrespondingToSearch(string $search): array
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
		foreach (Category::all() as $category) {
			foreach (Category::items($category) as $item) {
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
	private static function latestUploads(int $limit): array
	{
		$items = self::all();
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
	 * @return ?string
	 */
	private static function generateSearchContentList(string $search): ?string
	{
		$correspondingItems = self::getItemsCorrespondingToSearch($search);
		/** @phpstan-ignore-next-line */
		if (count($correspondingItems) <= 0 || !$correspondingItems) return null;
		return self::create($correspondingItems);
	}

	/**
	 * Generate HTML for the latests uploaded items
	 * 
	 * @param int $limit
	 * @return string
	 */
	public static function latest(int $limit = 6): string
	{
		$latestUploadedItems = self::create(self::latestUploads($limit));
		if (!$latestUploadedItems) $latestUploadedItems = "<li class='list-group-item'>No result found</li>";
		return Helper::htmlFormat($latestUploadedItems);
	}

	/**
	 * Generate HTML for the results of the research
	 * 
	 * @return string
	 */
	public static function search(): string
	{
		$search = $_GET['search'] ?? null;
		if (!isset($search) || $search === "") {
			$results = "<li class='list-group-item'>No search term provided</li>";
		} else {
			$results = self::generateSearchContentList($search) ?? null;
			if (!$results) $results = "<li class='list-group-item'>No result found</li>";
		}
		return Helper::htmlFormat($results);
	}

	/**
	 * @param string $filename
	 * @param string $category
	 * @return array<string>
	 */
	private static function getUrls(string $filename, string $category): array
	{
		$url = (($_SERVER['HTTPS'] ?? false) ? "https://" : "http://") . $_SERVER['HTTP_HOST'] . "/shared/$category";
		return [
			$url . "/$filename",
			$url . "?file=$filename"
		];
	}

	/**
	 * This use the formatFilename method to format only the filename inside the url
	 * $url = http://localhost:5050/shared/files/aaaaaabbbbbccc.txt will return http://localhost:5050/shared/files/ aaaa..c.txt
	 * 
	 * @param string $url
	 * @param bool $isDownloadUrl
	 * @return string
	 */
	private static function formatUrl(string $url, bool $isDownloadUrl = false)
	{
		$delimiter = $isDownloadUrl ? "=" : "files/";
		$parts = explode($delimiter, $url);
		return $parts[0] . $delimiter . Item::formatFilename($parts[1]);
	}

	/**
	 * Returns the filename formatted if he's too long, for example:
	 * If the filename is aaaaaabbbbbccc.txt, the function will return aaaa..c.txt
	 * 
	 * @param string $filename
	 * @return string
	 */
	private static function formatFilename(string $filename): string
	{
		$maxLength = 3;
		$extension = pathinfo($filename, PATHINFO_EXTENSION);
		$filenameWithoutExtension = pathinfo($filename, PATHINFO_FILENAME);

		if (strlen($filenameWithoutExtension) > $maxLength) {
			$shortenedFilename = substr($filenameWithoutExtension, 0, $maxLength) . '..' . substr($filenameWithoutExtension, -1);
			return $shortenedFilename . '.' . $extension;
		} else {
			return $filename;
		}
	}
}
