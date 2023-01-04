<?php

class User {
	
	/**
	 * @var array $credentials
	 */
	private array $credentials;

	/**
	 * @var string $username
	 */
	public string $username;

	/**
	 * @var string $password (hashed)
	 */
	public string $password;

	/**
	 * @param array $credentials
	 */
	public function __construct(array $credentials)
	{
		$this->credentials = $credentials;
		$this->setAttributes();
	}

	/**
	 * @return void
	 */
	private function requirements(): void
	{
		if (!array_key_exists('username', $this->credentials) || !array_key_exists('password', $this->credentials)) 
			throw new Exception('Invalid credentials');
	}

	/**
	 * @param array $credentials
	 * @throws Exception
	 * @return void
	 */
	private function setAttributes(): void
	{
		$this->requirements();
		$this->username = $this->credentials['username'];
		$this->password = $this->credentials['password'];
	}

}