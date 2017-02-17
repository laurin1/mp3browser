<?php
require_once dirname(dirname(dirname(__FILE__)))."/vendor/autoload.php";

session_set_save_handler(
	new mp3browser\MemcacheSession());
session_start();