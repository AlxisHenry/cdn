<?php

declare(strict_types=1);

function auth()
{
	if (isset($_GET['logout'])) {
		$_SESSION['user'] = null;
		$_SESSION['connected'] = false;
        setcookie('swal', 'logout', time() + 1, '/');
		header("Location: /");
		return true;
	}

	if (!isset($_POST['username']) && !isset($_POST['password'])) return false;

	/**
	 * @var string $username
	 * @var string $password
	 */
	$username = $_POST['username'] ?? null;
	$password = $_POST['password'] ?? null;
	
	if (user()->username === $username && password_verify($password, user()->password)) {
		$_SESSION['user'] = user();
		$_SESSION['connected'] = true;
        $_SESSION['token'] = bin2hex(random_bytes(32));
		setcookie('swal', 'connected', time() + 1, '/');
		header("Location: /");
		return true;
	} else {
        setcookie('swal', 'connection_failed', time() + 1, '/');
		header("Location: /");
		return false;
	}

}

function Swal(): string
{
	$swal = $_COOKIE['swal'] ?? null;
	if ($swal === "connected") {
        return "<script>
            Swal.fire({
                icon: 'success',
                title: 'Connected',
                text: 'You are now connected to the dashboard',
                showConfirmButton: true,
                timerProgressBar: true,
                timer: 3000
            })
        </script>";
    } else if ($swal === "connection_failed") { 
        return "<script>
            Swal.fire({
                icon: 'error',
                title: 'Connection failed',
                text: 'The username or password is incorrect',
                showConfirmButton: true,
                timerProgressBar: true,
                timer: 3000
            })
        </script>";
    } else if ($swal === "logout") { 
        session_destroy();
        return "<script>
            Swal.fire({
                icon: 'success',
                title: 'Logout',
                text: 'You are now disconnected from the dashboard',
                showConfirmButton: true,
                timerProgressBar: true,
                timer: 3000
            })
        </script>";
    }
	return '';
}