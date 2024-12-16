<?php

if (!function_exists('dd')) {
    function dd(...$vars) {
        foreach ($vars as $var) {
            echo '<pre>';
            var_dump($var);
            echo '</pre>';
        }
        die();
    }
}

if (!function_exists('e')) {
    function e($value) {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8', false);
    }
}

if (!function_exists('asset')) {
    function asset($path) {
        return baseUrl() . '/assets/' . ltrim($path, '/');
    }
}

if (!function_exists('old')) {
    function old($key, $default = '') {
        return $_SESSION['old'][$key] ?? $default;
    }
}

if (!function_exists('session')) {
    function session($key, $default = null) {
        return $_SESSION[$key] ?? $default;
    }
}

if (!function_exists('redirect')) {
    function redirect($path) {
        header('Location: ' . baseUrl() . '/' . ltrim($path, '/'));
        exit;
    }
}

if (!function_exists('back')) {
    function back() {
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }
}

if (!function_exists('csrf_token')) {
    function csrf_token() {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
}

if (!function_exists('csrf_field')) {
    function csrf_field() {
        return '<input type="hidden" name="csrf_token" value="' . csrf_token() . '">';
    }
}

if (!function_exists('method_field')) {
    function method_field($method) {
        return '<input type="hidden" name="_method" value="' . $method . '">';
    }
}

if (!function_exists('flash')) {
    function flash($key, $value = null) {
        if ($value) {
            $_SESSION['flash'][$key] = $value;
        } else {
            $value = $_SESSION['flash'][$key] ?? null;
            unset($_SESSION['flash'][$key]);
            return $value;
        }
    }
}

if (!function_exists('has_error')) {
    function has_error($field) {
        return isset($_SESSION['errors'][$field]);
    }
}

if (!function_exists('get_error')) {
    function get_error($field) {
        $error = $_SESSION['errors'][$field] ?? null;
        unset($_SESSION['errors'][$field]);
        return $error;
    }
}

if (!function_exists('format_date')) {
    function format_date($date, $format = 'd/m/Y') {
        return date($format, strtotime($date));
    }
}

if (!function_exists('money')) {
    function money($value) {
        return 'R$ ' . number_format($value, 2, ',', '.');
    }
}

if (!function_exists('str_slug')) {
    function str_slug($text) {
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        $text = preg_replace('~[^-\w]+~', '', $text);
        $text = trim($text, '-');
        $text = preg_replace('~-+~', '-', $text);
        $text = strtolower($text);
        return $text;
    }
}
