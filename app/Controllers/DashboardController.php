<?php

class DashboardController {

	public function __construct() {}

	/**
	 * @return void
	 */
	public function index(): void
	{
		View::show("pages.dashboard", [
			"searchResults" => searchResults(),
			"latestUploads" => latestUploads(),
			"categories" => getCategories()
		]);
	}

	/**
	 * @return void
	 */
	public function upload(): void 
	{
		View::show("pages.upload", [
			"latestUploads" => latestUploads()
		]);
	}

	/**
	 * @return void
	 */
	public function archive(): void 
	{
		View::show("pages.zip", [
			"items" => generateItems(getItems(), html: true)
		]);
	}

}