<?php
import(_GMODULES, 'Ftp');

Ftp::debug();
//$put = Ftp::put("c:\\1.txt", '/1.txt', 'auto', '0622');
//$get = Ftp::get('1.txt', 'E:\\Users\\Ison\\Desktop\sanya\\');
//$list = Ftp::getAll('/2/', 'E:\\Users\\Ison\\Desktop\\sanya');
$delete = Ftp::rmdir('/22/');
var_dump($delete);

