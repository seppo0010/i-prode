<?php
session_start();
$prode = mysql_connect("localhost","prode","pr00d") or trigger_error("1".mysql_error(),E_USER_ERROR);
mysql_select_db("prode", $prode);

/*
$prode = mysql_connect("localhost","root","") or trigger_error("1".mysql_error(),E_USER_ERROR);
mysql_select_db("prode", $prode);
*/


function autolink($message) {
    $text = " " . $message;
    $text = preg_replace("#([\n ])([a-z]+?)://([a-z0-9\-\.,\?!%\*_\#:;~\\&$@\/=\+]+)#i", "\\1<a class='submenu' href=\"\\2://\\3\" target=\"_blank\">\\2://\\3</a>", $text);
    $text = preg_replace("#([\n ])www\.([a-z0-9\-]+)\.([a-z0-9\-.\~]+)((?:/[a-z0-9\-\.,\?!%\*_\#:;~\\&$@\/=\+]*)?)#i", "\\1<a class='submenu' href=\"http://www.\\2.\\3\\4\" target=\"_blank\">www.\\2.\\3\\4</a>", $text);
    $text = preg_replace("#([\n ])([a-z0-9\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)?[\w]+)#i", "\\1<a class='submenu' href=\"mailto:\\2@\\3\">\\2@\\3</a>", $text);
    $text = substr($text, 1);
    return(trim($text));
}

?>
