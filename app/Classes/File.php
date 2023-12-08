<?php

declare(strict_types=1);

namespace App\Classes;

class File {
	
	/**
	 * @var array<String,array<String>> ALLOWED_EXTENSIONS
	 */
	const ALLOWED_EXTENSIONS = [
		'files' => ['pdf', 'md', 'txt', 'yml', 'json', 'yaml', 'css', 'js', "zip"],
		'images' => ['jpg', 'jpeg', 'png', 'gif', 'svg', 'jfif', 'webp', 'jif'],
		'videos' => ['mp4', 'webm']
	];

	/**
	 * @var string SHARED
	 */
	const SHARED = './shared/';

	/**
	 * @var string|int $name
	 */
	private string|int $name;

	/**
	 * @var string|int $full_path
	 */
	private string|int $full_path;

	/**
	 * @var string|int $type
	 */
	private string|int $type;

	/**
	 * @var string|int $tmp_name
	 */
	private string|int $tmp_name;

	/**
	 * @var string|int $error
	 */
	private string|int $error;

	/**
	 * @var string|int $size
	 */
	private string|int $size;

	/**
	 * @var ?string $category
	 */
	private ?string $category = null;

	/**
	 * @param array<string,string|int> $file
	 */
	public function __construct(array $file)
	{
		$this->setProperties($file);
	}

	/**
	 * @param array<string,string|int> $file
	 * @return void
	 */
	private function setProperties(array $file): void 
	{
		$this->name = $file['name'];
		$this->full_path = $file['tmp_name'];
		$this->type = $file['type'];
		$this->tmp_name = $file['tmp_name'];
		$this->error = $file['error'];
		$this->size = $file['size'];
	}

	/**
	 * @return bool
	 */
	public function upload(): bool
	{
		if (!$this->validateExtension()) return false;
		while (file_exists(self::SHARED . $this->category . '/' . $this->name)) {
			$this->name = uniqid() . $this->name;
		}
		/**
		 * @var string $tmp
		 */
		$tmp = (string) $this->tmp_name;
		return move_uploaded_file($tmp, self::SHARED . $this->category . '/' . $this->name);
	}

	/**
	 * @return bool
	 */
	private function validateExtension(): bool
	{
		foreach (self::ALLOWED_EXTENSIONS as $category => $extensions) {
			if (in_array($this->getExtension(), $extensions)) {
				if ($this->getExtension() === "pdf") {
					$this->category = "pdf";
				} else {
					$this->category = $category;
				}
				return true;
			}
		}
		return false;
	}

	/**
	 * @return string
	 */
	private function getExtension(): string
	{
		return pathinfo((string) $this->name, PATHINFO_EXTENSION);
	}

	/**
	 * @return string|int
	 */
	public function getName(): string|int
	{
		return $this->name;
	}
	
	/**
	 * @return string|int
	 */
	public function getFullPath(): string|int
	{
		return $this->full_path;
	}

	/**
	 * @return string|int
	 */
	public function getType(): string|int
	{
		return $this->type;
	}

	/**
	 * @return string|int
	 */
	public function getTmpName(): string|int
	{
		return $this->tmp_name;
	}

	/**
	 * @return string|int
	 */
	public function getError(): string|int
	{
		return $this->error;
	}

	/**
	 * @return string|int
	 */
	public function getSize(): string|int
	{
		return $this->size;
	}

	/**
	 * @return ?string
	 */
	public function getCategory(): ?string
	{
		return $this->category;
	}

}