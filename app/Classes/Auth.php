<?php

declare(strict_types=1);

use Symfony\Component\Yaml\Yaml;

class Auth {
    
    /**
     * @var string $username
     */
    private string $username;
  
    /** 
     * @var string $password
     */
    private string $password;

    public function __construct()
    {
        $this->username = self::user()->getUsername();
        $this->password = self::user()->getPassword();
    }

    public static function user(): User
    {
        /**
         * @var array<array<string,string>> $settings
         */
        $settings = Yaml::parseFile(__DIR__ . '/../../settings.yml');
        return new User($settings['user']);
    }

    /**
     * @return void
     */
    public static function load(): void
    {
        $auth = new Auth();
        if (isset($_GET['logout'])) {
            $auth->logout();
        } else if (isset($_POST['username']) && isset($_POST['password'])) {
            $auth->login();
        }
    }

    /**
     * @return bool
     */
    public static function check(): bool
    {
        return ($_SESSION['connected'] ?? false);
    }

    /**
     * @return void
     */
    private function login(): void
    {
        $username = $_POST['username'] ?? null;
        $password = $_POST['password'] ?? null;   
        if ($this->username === $username && password_verify($password, $this->password)) {
            $_SESSION['user'] = Auth::user();
            $_SESSION['connected'] = true;
            $_SESSION['token'] = bin2hex(random_bytes(32));
            setcookie('swal', 'connected', time() + 1, '/');
            Redirect::back();
        } else {
            setcookie('swal', 'connection_failed', time() + 1, '/');
            Redirect::back();
        }
    }

    /**
     * @return void
     */
    private function logout(): void
    {
        $_SESSION['user'] = null;
        $_SESSION['connected'] = false;
        session_destroy();
        setcookie('swal', 'logout', time() + 1, '/');
        Redirect::back();
    }

}