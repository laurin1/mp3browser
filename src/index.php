<?php
require_once dirname(__FILE__)."/library/bootstrap.php";

$oIndex = new \mp3browser\Index();

echo $oIndex->getHTML();