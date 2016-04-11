<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
chdir(dirname(__DIR__));
ini_set('xdebug.var_display_max_depth', 5);
ini_set('xdebug.var_display_max_children', 256);
ini_set('xdebug.var_display_max_data', 1024);

// Decline static file requests back to the PHP built-in webserver
if (php_sapi_name() === 'cli-server') {
    $path = realpath(__DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
    if (__FILE__ !== $path && is_file($path)) {
        return false;
    }
    unset($path);
}
defined('PASSWORD_COST_DEFAULT') || define('PASSWORD_COST_DEFAULT', 12);
defined('REDIRECT_PARAM_NAME') || define('REDIRECT_PARAM_NAME', 'destination');
defined('STEP_LENGTH') || define('STEP_LENGTH', 13);
defined('LOCALE') || define('LOCALE', 'en_AU');
defined('ENABLE_DEBUG_MODE') || define('ENABLE_DEBUG_MODE', 0);
defined('APPLICATION_CODE') || define('APPLICATION_CODE', 'EVAL1000');
defined('USER_INFO_CONTAINER_NAME') || define('USER_INFO_CONTAINER_NAME', 'EVAL1000STEP_LENGTHEVAL1000STEP_LENGTH');
defined('CSRF_TIMEOUT_SECONDS') || define('CSRF_TIMEOUT_SECONDS', 3600);
defined('ERROR_NEED_AUTHENTICATED_USER') || define('ERROR_NEED_AUTHENTICATED_USER', 'user must be logged in to continue');
defined('RESTRICTED_ACCESS_ERROR') || define('RESTRICTED_ACCESS_ERROR', 'resource_access_not_allowed');
defined('DATA_DIR') || define('DATA_DIR', './data/');
defined('ENC_CACHE_DIR') || define('ENC_CACHE_DIR', './data/enccache/');
defined('TEMP_ACL_DIR') || define('TEMP_ACL_DIR', './data/tempaclholder/');
defined('ENC_KEY') || define('ENC_KEY', 'amrit01946757638292ijfndsnfjikdjdfjfhjf');
defined('ENABLE_DATATABLES') || define('ENABLE_DATATABLES', 1);
// Setup autoloading
require 'init_autoloader.php';

// Run the application!
Zend\Mvc\Application::init(require 'config/application.config.php')->run();
