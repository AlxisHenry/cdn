<?php

/**
 * Show a sweet alert message if the cookie is set or if you specify ²0
 * 
 * @param ?string $type
 * @return string
 */
function swal(?string $type = null): string
{
    $swal = $_COOKIE['swal'] ?? null;
    if ($swal === "logout") session_destroy(); 
    return match ($swal) {
        'connected' => "<script>
            Swal.fire({
                icon: 'success',
                title: 'Connected',
                text: 'You are now connected to the dashboard',
                showConfirmButton: true,
                timerProgressBar: true,
                timer: 3000
            })
        </script>",
        'connection_failed' => "<script>
            Swal.fire({
                icon: 'error',
                title: 'Connection failed',
                text: 'The username or password is incorrect',
                showConfirmButton: true,
                timerProgressBar: true,
                timer: 3000
            })
        </script>",
        'logout' => "<script>
            Swal.fire({
                icon: 'success',
                title: 'Logout',
                text: 'You are now disconnected from the dashboard',
                showConfirmButton: true,
                timerProgressBar: true,
                timer: 3000
            })
        </script>",
        'file_renamed' => "<script>
            Swal.fire({
                icon: 'success',
                title: 'File renamed',
                text: 'The file has been renamed',
                showConfirmButton: true,
                timerProgressBar: true,
                timer: 3000
            })
        </script>",
        default => '',
    };
}