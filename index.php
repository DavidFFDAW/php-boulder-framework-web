<?php
session_start();
// define('HOST', '/predicciones-framework');
define('HOST', '');
define('URL_BASE', '/app');
define('VIEWS', 'pages');
define('SHARED', 'partials');
define('ROOT', __DIR__ . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR);
define('LOGS', ROOT . 'logs' . DIRECTORY_SEPARATOR);
define('FILE', __FILE__ . DIRECTORY_SEPARATOR);
define('URI', str_replace(HOST, '', explode('?', strtolower(trim($_SERVER['REQUEST_URI'])))[0]));
$actual_link = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
define('CURRENT_PAGE', $actual_link);
date_default_timezone_set('Europe/Madrid');
define('PAGES', dirname(__FILE__) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'pages' . DIRECTORY_SEPARATOR);
define('API', dirname(__FILE__) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'api' . DIRECTORY_SEPARATOR);
$isMobile = is_numeric(strpos(strtolower($_SERVER["HTTP_USER_AGENT"]), "mobile"));
define('IS_MOBILE', $isMobile);
header('X-Powered-By: BoulderPHP');

require_once ROOT . 'global-functions.php';
require_once ROOT . 'Autoload.php';

if (getenv('APP_ENV') === 'dev') {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

Autoload::register();
Middlewares::refreshToken();
$request = new Request();
$router = new Router($request);
$router->handle($request);

die('You must add your env_vars in the htaccess file');
