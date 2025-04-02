<?php

declare(strict_types=1);

namespace App\Middleware;

use Framework\Contracts\MiddlewareInterface;
use App\Exceptions\SessionException;
use Random\Engine\Secure;

class SessionMiddleware
{
    public function process(callable $next)
    {
        if (session_status() ===  PHP_SESSION_ACTIVE) {
            throw new SessionException("session already active");
        }

        

        if (headers_sent($filename, $line)){
            throw new SessionException("Headers already sent consider output buffering data of {$filename} and {$line} ");
        }

        session_set_cookie_params([
            'secure' => $_ENV['APP_ENV'] === "production",
            'httponly' => false,
            'samesite' => 'lax'
        ]);
        session_start();
        $next();
        session_write_close();
    }
}
