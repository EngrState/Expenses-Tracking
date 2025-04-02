<?php

declare(strict_types=1);


use Framework\http;

function dd(mixed $value)
{
    echo "<pre>";
    var_dump($value);
    echo "</pre>";
    die();
}

function e(mixed $value): string{
    return htmlspecialchars((string) $value);
}

function redirectTo($path){
    header("Location: $path");
    http_response_code(http::REDIRECT_STATUS_CODE);
    exit;
}
