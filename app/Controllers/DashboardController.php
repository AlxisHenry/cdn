<?php

class DashboardController {

	public function __construct() {}

	/**
	 * @return void
	 */
	public function index(): void
	{
		$test = "abc1234567";
		View::show("pages.dashboard");
	}

	/**
	 * @return void
	 */
	public function upload(): void 
	{
		View::show("pages.upload");
	}

	/**
	 * @return void
	 */
	public function archive(): void 
	{
		View::show("pages.zip");
	}

}