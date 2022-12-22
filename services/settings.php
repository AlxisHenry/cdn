<?php


use Symfony\Component\Yaml\Yaml;

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

	/**
	 * @var string $image
	 */
	public readonly string $image;

	/**
	 * @var Owner 
	 */
	public readonly Owner $owner;

	public function __construct(array $settings)
	{
		$this->settings = $settings;
		$this->setAttributes();
	}

	/**
	 * @param array $credentials
	 * @return void
	 */
	private function setAttributes(): void
	{
		$this->title = $this->settings['title'];
		$this->description = $this->settings['description'];
		$this->image = $this->settings['image'];
		$this->owner = new Owner($this->settings['owner']);
	}

}

class Owner {

	/**
	 * @var array $credentials
	 */
	private array $credentials;

	/**
	 * @var string $name
	 */
	public readonly string $name;

	/**
	 * @var string $firstname
	 */
	public readonly string $firstname;

	/**
	 * @var string $description
	 */
	public readonly string $description;

	/**
	 * @var string $fullName
	 */
	public readonly string $fullName;

	public function __construct(array $credentials) 
	{
		$this->credentials = $credentials;
		$this->setAttributes();
	}

	/**
	 * @param array $credentials
	 * @return void
	 */
	private function setAttributes(): void
	 {
		$this->name = $this->credentials['name'];
		$this->firstname = $this->credentials['firstname'];
		$this->description = $this->credentials['description'];
		$this->setFullName();
	}

	/**
	 * @return void
	 */
	private function setFullName(): void
	{
		$this->fullName = $this->firstname . ' ' . $this->name;
	}

}

class User {
	
	/**
	 * @var array $credentials
	 */
	private array $credentials;

	/**
	 * @var string $username
	 */
	public readonly string $username;

	/**
	 * @var string $password (hashed)
	 */
	public readonly string $password;

	public function __construct(array $credentials)
	{
		$this->credentials = $credentials;
		$this->setAttributes();
	}

	/**
	 * @param array $credentials
	 * @return void
	 */
	private function setAttributes(): void
	{
		$this->username = $this->credentials['username'];
		$this->password = $this->credentials['password'];
	}

}

/**
 * @return Dashboard
 */
function dashboard(): Dashboard
{
	/**
	 * @var array $settings
	 * @var Dashboard $dashboard
	 */
	$settings = Yaml::parseFile(__DIR__ . '/../settings.yml');
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
	$settings = Yaml::parseFile(__DIR__ . '/../settings.yml');
	$user = new User($settings['user']);
	return $user;
}