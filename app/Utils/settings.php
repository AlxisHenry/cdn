<?php

use Symfony\Component\Yaml\Yaml;

/**
 * @return Dashboard
 */
function dashboard(): Dashboard
{
	/**
	 * @var array $settings
	 * @var Dashboard $dashboard
	 */
	$settings = Yaml::parseFile(__DIR__ . '/../../settings.yml');
	$dashboard = new Dashboard($settings['dashboard']);
	return $dashboard;
}

/**
 * @return User
 */
function user(): User
{
	/**
	 * @var array $settings
	 * @var User $user
	 */
	$settings = Yaml::parseFile(__DIR__ . '/../../settings.yml');
	$user = new User($settings['user']);
	return $user;
}