<?php

declare(strict_types=1);

use Symfony\Component\Yaml\Yaml;

class Dashboard {

	/**
	 * @var array<string, string>
	 */
	private array $settings;

	/**
	 * @var string
	 */
	public readonly string $title;

	/**
	 * @var string
	 */
	public readonly string $description;

	/**
	 * @param array<string, string> $settings
	 */
	public function __construct(array $settings)
	{
		$this->settings = $settings;
		$this->requirements();
		$this->title = $this->settings['title'];
		$this->description = $this->settings['description'];
	}
	/**
	 * @return Dashboard
	 */
	public static function settings(): Dashboard
	{
		/**
		 * @var array<array<string,string>> $settings
		 */
		$settings = Yaml::parseFile(__DIR__ . '/../../settings.yml');
		return new Dashboard($settings['dashboard']);
	}

	/**
	 * @return void
	 */
	private function requirements(): void
	{
		if (!array_key_exists('title', $this->settings) || !array_key_exists('description', $this->settings)) 
			throw new Exception('Invalid yml file content');
	}

	/**
	 * @return string
	 */
	public function getTitle(): string
	{
		return $this->title;
	}

	/**
	 * @return string
	 */
	public function getDescription(): string
	{
		return $this->description;
	}

}