<?php
//  Note: This file should be included first in every php page.
error_reporting(E_ALL);
ini_set('display_errors', 'On');
define('BASE_PATH', dirname(__FILE__));
define('CURRENT_PAGE', basename($_SERVER['REQUEST_URI']));

require_once BASE_PATH.'/libraries/MysqliDb/MysqliDb.php';
require_once BASE_PATH.'/admin/helpers/helpers.php';

//  Global vars
$site_name = 'Peixes da Serra do Cipó - MG';

//  Meta vars
$metaAuthor = 'speciesDatabase';

// Some workaround so we have absolute paths both in local and remote enviroments
$base_dir  = __DIR__; // Absolute path to your installation, ex: /var/www/mywebsite
$doc_root  = preg_replace("!${_SERVER['SCRIPT_NAME']}$!", '', $_SERVER['SCRIPT_FILENAME']); # ex: /var/www
$base_url  = preg_replace("!^${doc_root}!", '', $base_dir); # ex: '' or '/mywebsite'
$protocol  = empty($_SERVER['HTTPS']) ? 'http' : 'https';
$port      = $_SERVER['SERVER_PORT'];
$disp_port = ($protocol == 'http' && $port == 80 || $protocol == 'https' && $port == 443) ? '' : ":$port";
$domain    = $_SERVER['SERVER_NAME'];
if($domain == 'localhost')
{
    $explode  = explode("\\", $base_dir);
    $base_url = "${protocol}://${domain}${disp_port}/${explode[3]}"; # Ex: 'http://localhost', 'https://localhost/folder', etc.
}
else
{
    $base_url = "${protocol}://${domain}${disp_port}${base_url}"; # Ex: 'http://example.com', 'https://example.com/mywebsite', etc.
}

$bootstrap_cdn = 1;
$bootstrap_vsn = '4.3.1';
$bootstrap_path = $base_url.'/assets/bootstrap-'.$bootstrap_vsn.'-dist';
$tinymce_vsn = '4.7.10';
$tinymce_path = $base_url.'/assets/tinymce_'.$tinymce_vsn;

// The Admin navbar, needs a better place to be in the future
define('ADMIN', $base_url.'/admin/');
define('SYSCS', ADMIN.'systematics/');
define('CAMPS', ADMIN.'camps/');
define('MSEUM', ADMIN.'museum/');
define('USERS', ADMIN.'users/');

/*  --------------------------
    DATABASE CONFIGURATION
--------------------------- */
define('DB_HOST', '');
define('DB_USER', '');
define('DB_PASS', '');
define('DB_NAME', '');

/**
 * Get instance of DB object
 */
function getDbInstance() {
	return new MysqliDb(DB_HOST, DB_USER, DB_PASS, DB_NAME);
}