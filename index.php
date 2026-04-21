<?php
/**
 * CodeIgniter Bootstrap File
 *
 * This is the single entry-point for all HTTP requests.
 * Apache's .htaccess rewrites every URL to this file.
 */

// ── Environment ──────────────────────────────────────────────
// 'development' | 'testing' | 'production'
define('ENVIRONMENT', isset($_SERVER['CI_ENV']) ? $_SERVER['CI_ENV'] : 'development');

switch (ENVIRONMENT) {
    case 'development':
        error_reporting(-1);
        ini_set('display_errors', 1);
    break;
    case 'testing':
    case 'production':
        ini_set('display_errors', 0);
        error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT & ~E_USER_NOTICE & ~E_USER_DEPRECATED);
    break;
    default:
        header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);
        echo 'The application environment is not set correctly.';
        exit(1);
}

// ── Path constants ───────────────────────────────────────────
$system_path   = 'system';
$application_folder = 'application';

// ── Resolve paths ────────────────────────────────────────────
if (defined('STDIN')) {
    chdir(dirname(__FILE__));
}

if (($_temp = realpath($system_path)) !== FALSE) {
    $system_path = $_temp . DIRECTORY_SEPARATOR;
} else {
    $system_path = rtrim($system_path, '/\\') . DIRECTORY_SEPARATOR;
}

define('SELF',        pathinfo(__FILE__, PATHINFO_BASENAME));
define('BASEPATH',    $system_path);
define('FCPATH',      dirname(__FILE__) . DIRECTORY_SEPARATOR);
define('SYSPATH',     BASEPATH);
define('APPPATH',     $application_folder . DIRECTORY_SEPARATOR);
define('VIEWPATH',    APPPATH . 'views' . DIRECTORY_SEPARATOR);

// ── Load CodeIgniter ─────────────────────────────────────────
require_once BASEPATH . 'core/CodeIgniter.php';
