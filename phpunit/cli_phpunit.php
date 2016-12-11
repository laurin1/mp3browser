<?php
if(substr($_SERVER["USERNAME"], -1) === "$")
	$_SERVER["USERNAME"] = "keithdavis";

$_SERVER["DOCUMENT_ROOT"]     = dirname(__DIR__);
$_SERVER["REMOTE_USER"]       = "pridedallas\\".$_SERVER["USERNAME"];
$_SERVER["REMOTE_ADDR"]       = gethostbyname($_SERVER["COMPUTERNAME"]);
$_SERVER["REMOTE_HOST"]       = $_SERVER["REMOTE_ADDR"];
$_SERVER["SERVER_PORT"]       = 9100;
$_SERVER["QUERY_STRING"]      = null;
$_SERVER["SERVER_NAME"]       = "localhost";
$_SERVER["REQUEST_URI"]       = "placeholder";
$GLOBALS["sPHPUnitErrorTest"] = null;

define("_PHPUNITTEST", true);


require_once $_SERVER["DOCUMENT_ROOT"]."/src/library/bootstrap.php";