<?php

include_once __DIR__ . '/../app/dashboard.php'; 

Auth::check();

Route::make();

include_once __DIR__ . "/../resources/views/layout.php";