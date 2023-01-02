<?php

class Dashboard {

	/**
	 * @var array
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

	public function __construct(array $settings)
	{
		$this->settings = $settings;
		$this->setAttributes();
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
	 * @param array $credentials
	 * @return void
	 */
	private function setAttributes(): void
	{
		$this->requirements();
		$this->title = $this->settings['title'];
		$this->description = $this->settings['description'];
	}

}