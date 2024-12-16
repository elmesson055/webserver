<?php
// Funções auxiliares globais

if (!function_exists('dd')) {
    function dd($data) {
        echo '<pre>';
        var_dump($data);
        echo '</pre>';
        die();
    }
}

if (!function_exists('redirect')) {
    function redirect($url, $message = null, $type = 'success') {
        if ($message) {
            $_SESSION['flash'] = [
                'message' => $message,
                'type' => $type
            ];
        }
        
        if (!headers_sent()) {
            header("Location: $url");
        } else {
            echo "<script>window.location.href='$url';</script>";
            echo "<noscript><meta http-equiv='refresh' content='0;url=$url'></noscript>";
        }
        exit();
    }
}

if (!function_exists('session_flash')) {
    function session_flash($key, $value = null) {
        if ($value === null) {
            if (isset($_SESSION['flash'][$key])) {
                $value = $_SESSION['flash'][$key];
                unset($_SESSION['flash'][$key]);
                return $value;
            }
            return null;
        }
        $_SESSION['flash'][$key] = $value;
    }
}

if (!function_exists('is_ajax_request')) {
    function is_ajax_request() {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
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

if (!function_exists('verify_csrf_token')) {
    function verify_csrf_token($token) {
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }
}

if (!function_exists('baseUrl')) {
    function baseUrl($path = '') {
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
        $host = $_SERVER['HTTP_HOST'];
        $scriptName = dirname($_SERVER['SCRIPT_NAME']);
        return rtrim($protocol . $host . $scriptName, '/') . '/' . ltrim($path, '/');
    }
}

if (!function_exists('asset')) {
    function asset($path) {
        return baseUrl('assets/' . ltrim($path, '/'));
    }
}

if (!function_exists('flash')) {
    function flash($message = null, $type = null) {
        if ($message !== null) {
            $_SESSION['flash'] = [
                'message' => $message,
                'type' => $type ?? 'info'
            ];
            return;
        }
        
        if (isset($_SESSION['flash'])) {
            $flash = $_SESSION['flash'];
            unset($_SESSION['flash']);
            return $flash;
        }
        
        return null;
    }
}
