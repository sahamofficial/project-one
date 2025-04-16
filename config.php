<?php
if (!defined('BASE_PATH')) {
    define('BASE_PATH', dirname(__DIR__));
}

if (!defined('BASE_URL')) {
    define('BASE_URL', '/project-one');
}

if (!defined('ADMIN_PATH')) {
    define('ADMIN_PATH', BASE_PATH . '/admin');
}

if (!defined('INCLUDES_PATH')) {
    define('INCLUDES_PATH', ADMIN_PATH . '/includes');
}

if (!defined('DB_HOST')) {
    define('DB_HOST', 'localhost');
}

if (!defined('DB_USER')) {
    define('DB_USER', 'root');
}

if (!defined('DB_PASS')) {
    define('DB_PASS', '');
}

if (!defined('DB_NAME')) {
    define('DB_NAME', 'project-one');
}

if (!defined('DISPLAY_ERROR')) {
    define('DISPLAY_ERROR', true);
}

if (!function_exists('current_domain')) {
    function current_domain()
    {
        return protocol() . $_SERVER['HTTP_HOST'] . '/project-one';
    }
}

if (!function_exists('currentUrl')) {
    function currentUrl()
    {
        return current_domain() . $_SERVER['REQUEST_URI'];
    }
}

if (!function_exists('protocol')) {
    function protocol()
    {
        return stripos($_SERVER['SERVER_PROTOCOL'], 'https') === true ? 'https://' : 'http://';
    }
}

if (!function_exists('url')) {
    function url($url)
    {
        $domain = trim(CURRENT_DOMAIN, '/ ');
        $url = $domain . '/' . trim($url, '/ ');
        return $url;
    }
}

if (!function_exists('displayError')) {
    function displayError($displayError)
    {
        if ($displayError) {
            ini_set('display_errors', 1);
            ini_set('display_startup_errors', 1);
            error_reporting(E_ALL);
        } else {
            ini_set('display_errors', 0);
            ini_set('display_startup_errors', 0);
            error_reporting(0);
        }
    }
}

if (!defined('CURRENT_DOMAIN')) {
    define('CURRENT_DOMAIN', current_domain());
}

try {
    $dbh = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
} catch (PDOException $e) {
    exit("Error: " . $e->getMessage());
}

displayError(DISPLAY_ERROR);

global $flashMessage;
if (isset($_SESSION['flash_message'])) {
    $flashMessage = $_SESSION['flash_message'];
    unset($_SESSION['flash_message']);
}

if (!function_exists('flash')) {
    function flash($name, $value = null)
    {
        if ($value === null) {
            global $flashMessage;
            $message = isset($flashMessage[$name]) ? $flashMessage[$name] : '';
            return $message;
        } else {
            $_SESSION['flash_message'][$name] = $value;
        }
    }
}