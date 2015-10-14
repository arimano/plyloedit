<?php

require_once (__DIR__ . '/constants.inc.php');

$debug = null;
if ($debug) {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
    ini_set('html_errors', '0');
} else {
    error_reporting(E_ALL);
    ini_set('display_errors', 0);
}
$mainDir = "http://localhost/diplomna/";

$mainPath = str_replace(DIRECTORY_SEPARATOR . "inc", "", dirname(__FILE__)) . "/";
$rootPath = str_replace(DIRECTORY_SEPARATOR . "inc", "", dirname(__FILE__)) . "/";
$libDir = $rootPath . "lib/";

session_start();
$session_id = session_id();

header("Content-Type: text/html; charset=utf-8");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
// always modified
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
// HTTP/1.1
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
// HTTP/1.0
header("Pragma: no-cache");
//error_reporting(-1);
//ini_set('display_errors', 1);
//path properties



define('MAIN_PATH', $mainPath);
define('ROOT_PATH', $rootPath);
define('MAIN_DIR', $mainDir);

//DB config
define('DB_HOST', "10.10.10.120");
define('DB_NAME', "tree_editor");
define('DB_USER', "root");
define('DB_PASS', "`12345';';");


//DB config ENSEMBL
define('DB_HOST_ENSEMBL', "gramenedb.gramene.org");
define('DB_NAME_ENSEMBL', "ensembl_compara_plants_40_74");
define('DB_USER_ENSEMBL', "anonymous");
define('DB_PASS_ENSEMBL', "gramene");

//includes
require_once($libDir . 'smarty/SmartyBC.class.php');

require_once($libDir . 'smarty/SmartyInstance.class.php');

// create object
$smarty = SmartyInstance::getInstance()->smarty;

require_once($libDir . "adodb/adodb.inc.php");
require_once($libDir . "adodb/DBConn.class.php");


require_once($libDir . "classes/all_classes.inc.php");
require_once($rootPath . "php/all_controllers.inc.php");

//database connect
$dbconn = DBConn::getInstance()->conn;


$system = system::getInstance($dbconn);

$smarty->assign('mainDir', $mainDir);

