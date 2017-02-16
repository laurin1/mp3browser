<?php
require_once __DIR__."/library/bootstrap.php";

$sFolderName = $_REQUEST["sFolderName"];
$sFileName   = $_REQUEST["sFileName"];
$sFileType   = $_REQUEST["sFileType"];
$sFilePath   = dirname(__DIR__)."/files".$sFolderName.$sFileName;

header("Content-type: ".$sFileType);
header("Content-Disposition: attachment; filename='".$sFileName."'");

readfile(urldecode($sFilePath));
