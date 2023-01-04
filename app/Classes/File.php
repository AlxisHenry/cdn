<?php

class File {
	
	/**
	 * @var array<array<String>> ALLOWED_EXTENSIONS
	 */
	const ALLOWED_EXTENSIONS = [
		'files' => ['pdf', 'md', 'txt', 'yml', 'json', 'yaml'],
		'images' => ['jpg', 'jpeg', 'png', 'gif', 'svg'],
		'videos' => ['mp4', 'webm']
	];

	/**
	 * @var string SHARED
	 */
	const SHARED = './shared/';

	/**
	 * @var string $name
	 */
	private string $name;

	/**
	 * @var string $full_path
	 */
	private string $full_path;

	/**
	 * @var string $type
	 */
	private string $type;

	/**
	 * @var string $tmp_name
	 */
	private string $tmp_name;

	/**
	 * @var int $error
	 */
	private int $error;

	/**
	 * @var int $size
	 */
	private int $size;

	/**
	 * @var ?string $category
	 */
	private ?string $category = null;

	/**
	 * @param mixed $file
	 */
	public function __construct(mixed $file)
	{
		$this->setProperties($file);
	}

	/**
	 * @param mixed $file
	 * @return void
	 */
	private function setProperties($file): void 
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
		if (!$this->validateExtension()) {
			return false;
		} elseif (file_exists(self::SHARED . $this->category . '/' . $this->name)) {
			$this->name = uniqid() . '-' . $this->name;
		}
		return move_uploaded_file($this->tmp_name, self::SHARED . $this->category . '/' . $this->name);
	}

	
	/**
	 * @return void
	 */
	private function validateExtension(): bool
	{
		foreach (self::ALLOWED_EXTENSIONS as $category => $extensions) {
			if (in_array($this->getExtension(), $extensions)) {
				$this->category = $category;
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
		return pathinfo($this->name, PATHINFO_EXTENSION);
	}

	/**
	 * @return string
	 */
	public function getName(): string
	{
		return $this->name;
	}
	
	/**
	 * @return string
	 */
	public function getFullPath(): string
	{
		return $this->full_path;
	}

	/**
	 * @return string
	 */
	public function getType(): string
	{
		return $this->type;
	}

	/**
	 * @return string
	 */
	public function getTmpName(): string
	{
		return $this->tmp_name;
	}

	/**
	 * @return int
	 */
	public function getError(): int
	{
		return $this->error;
	}

	/**
	 * @return int
	 */
	public function getSize(): int
	{
		return $this->size;
	}

	/**
	 * @return string
	 */
	public function getCategory(): string
	{
		return $this->category;
	}

	/**
	 * @return bool
	 */
	public function isImage(): bool
	{
		return in_array($this->type, ['image/jpeg', 'image/png', 'image/gif']);
	}

	/**
	 * @return bool
	 */
	public function isVideo(): bool
	{
		return in_array($this->type, ['video/mp4', 'video/webm', 'video/ogg']);
	}

	/**
	 * @return bool
	 */
	public function isAudio(): bool
	{
		return in_array($this->type, ['audio/mp3', 'audio/mpeg', 'audio/ogg', 'audio/wav']);
	}

}