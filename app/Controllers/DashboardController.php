<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Classes\Category;
use App\Classes\View;
use App\Classes\Item;

class DashboardController {

	public function __construct() {}

	/**
	 * @return void
	 */
	public function index(): void
	{
		View::show("pages.dashboard", [
			"searchResults" => Item::search(),
			"latestUploads" => Item::latest(),
			"categories" => Category::all()
		]);
	}

	/**
	 * @return void
	 */
	public function upload(): void 
	{
		View::show("pages.upload", [
			"latestUploads" => Item::latest()
		]);
	}

	/**
	 * @return void
	 */
	public function archive(): void 
	{
		View::show("pages.zip", [
			"items" => Item::create(Item::all(), html: true)
		]);
	}

}