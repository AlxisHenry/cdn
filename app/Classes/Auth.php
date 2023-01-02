<?php

declare(strict_types=1);

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
        $this->username = user()->username;
        $this->password = user()->password;
    }

    /**
     * @return void
     */
    public static function check(): void
    {
        $auth = new Auth();
        if (isset($_GET['logout'])) {
            $auth->logout();
        } else if (isset($_POST['username']) && isset($_POST['password'])) {
            $auth->login();
        }
    }

    /**
     * @return void
     */
    private function login(): void
    {
        /**
         * @var string $username
         * @var string $password
         */
        $username = $_POST['username'] ?? null;
        $password = $_POST['password'] ?? null;
        
        if ($this->username === $username && password_verify($password, $this->password)) {
            $_SESSION['user'] = user();
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