<?php

declare(strict_types=1);

namespace App\Classes;

use Exception;
use PhpParser\Node\Expr\Cast\Bool_;
use Symfony\Component\Yaml\Yaml;

class Dashboard
{

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
    public readonly string $subtitle;

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
        $this->subtitle = $this->settings['subtitle'];
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
     * @param string $key
     * @return Dashboard
     */
    public function check(string $key): bool
    {
        $status = (bool) array_key_exists($key, $this->settings);

        if ($status) {
            $value = $this->settings[$key];
            $status = (bool) $value;
        }

        return $status;
    }

    /**
     * @return void
     */
    private function requirements(): void
    {
        $keys = ['title', 'subtitle', 'description'];
        foreach ($keys as $key) {
            if (!array_key_exists($key, $this->settings))
                throw new Exception('Invalid yml file content');
        }
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
    public function getSubtitle(): string
    {
        return $this->subtitle;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return "{$this->title} | {$this->subtitle} | {$this->description}";
    }
}
