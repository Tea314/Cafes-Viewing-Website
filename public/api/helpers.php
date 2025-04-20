<?php

/**
 * Display a view
 */
function viewName(string $filename, array $data = []): void
{
    foreach ($data as $key => $value) {
        $$key = $value;
    }
    require_once __DIR__.'/../../app/views/layouts/'.$filename.'.php';
}

/**
 * Return the error class if error is found in the array $errors
 */
function error_class(array $errors, string $field): string
{
    return isset($errors[$field]) ? 'error' : '';
}

/**
 * Return true if the request method is POST
 */
function is_post_request(): bool
{
    return strtoupper($_SERVER['REQUEST_METHOD']) === 'POST';
}

/**
 * Return true if the request method is GET
 */
function is_get_request(): bool
{
    return strtoupper($_SERVER['REQUEST_METHOD']) === 'GET';
}

/**
 * Redirect to another URL
 */
function redirect_to(string $url): void
{
    header('Location:'.$url);
    exit;
}

/**
 * Redirect to a URL with data stored in the items array
 */
function redirect_with(string $url, array $items): void
{
    foreach ($items as $key => $value) {
        $_SESSION[$key] = $value;
    }

    redirect_to($url);
}

/**
 * Redirect to a URL with a flash message
 */
function redirect_with_message(string $url, string $message, string $type = FLASH_SUCCESS)
{
    flash('flash_'.uniqid(), $message, $type);
    redirect_to($url);
}

/**
 * Flash data specified by $keys from the $_SESSION
 */
function session_flash(...$keys): array
{
    $data = [];
    foreach ($keys as $key) {
        if (isset($_SESSION[$key])) {
            $data[] = $_SESSION[$key];
            unset($_SESSION[$key]);
        } else {
            $data[] = [];
        }
    }

    return $data;
}
