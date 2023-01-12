<?php

include_once __DIR__ . '/../app/dashboard.php'; 

Auth::load();

Router::load([

	/**
	 * To add new route, follow the steps below:
	 * 	- Create a new function in a controller
	 * 	- Add this route here (by set the properties you want)
	 */
	Route::get("/", "DashboardController@index")->name("dashboard.index"),
	Route::get("/upload", "DashboardController@upload")->name("dashboard.upload"),
	Route::get("/archive", "DashboardController@archive")->name("dashboard.archive")

])->run();
