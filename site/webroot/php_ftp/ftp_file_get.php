﻿<?php
$GLOBALS['CONFIG_FTP'] = array(
	'server'    => "124.xxx.xx.xx",
	'user'      => "xxxx",
	'passwd'    => "xxxx",
	'port'      => 21,
	'passive'   => true,
	'timeout'	=> 90
);

include_once '/www/class/Ftp.class.php';
Ftp::debug();
$list = Ftp::getAll('/work/fun', '/www/Bak/website/en.sanyatour.com/code/site', true, 0777, array('/work/wox.Sql'));


