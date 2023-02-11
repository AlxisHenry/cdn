<?php

declare(strict_types=1);

namespace App\Classes;

use Exception;

class User {
	
	/**
	 * @var array<string,string> $credentials
	 */
	private array $credentials;

	/**
	 * @var string $username
	 */
	public string $username;

	/**
	 * @var string $password
	 */
	public string $password;

	/**
	 * @param array<string,string> $credentials
	 */
	public function __construct(array $credentials)
	{
		$this->credentials = $credentials;
		$this->requirements();
		$this->username = $this->credentials['username'];
		$this->password = $this->credentials['password'];
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
	 * @return string
	 */
	public function getUsername(): string
	{
		return $this->username;
	}

	/**
	 * @return string
	 */
	public function getPassword(): string
	{
		return $this->password;
	}

}