<?php
/*
    shortcode utils
*/

function linkCode($id) {
	$chars ="0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
	$code = "";
	while ($id>0) {
		$rest = $id%strlen($chars);
		$code = substr($chars,$rest,1).$code;
		$id = floor($id/strlen($chars));
	}
	return $code;
}

function codeToId($code) {
	$chars ="0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
	$id = "";
	for ($i=0;$i<strlen($code);$i++) {
		$pos = strpos($chars,substr($code,strlen($code)-1-$i,1));
		if ($pos==-1)
			return false;
		$id += $pos*pow(strlen($chars),$i);
	}
	return $id;
}

function codeToUrl($code) {
	global $link;
	if (!$code)
		return false;

	$id=false;
	if (is_nan(intval($code)))
		return false;
	if (substr($code,0,1)=="-") {
		$id=codeToId(substr($code,1));
		$query1="select url from link where linkid=$id limit 1";
		$codetype='link';
	} else {
		$id=codeToId($code);
		$query1="select url from item where itemid=$id limit 1";
		$codetype='item';
	}

	$res=$link->query($query1);
	if (!$res)
		return false;
	$row = $res->fetch_array();
	if (!$row)
		return false;
	if ($_SERVER['REMOTE_ADDR']) {
		$ip = explode('.',$_SERVER['REMOTE_ADDR']);
		$ip = sprintf("%02X%02X%02X%02X",intval($ip[0]),intval($ip[1]),intval($ip[2]),intval($ip[3]));
		$link->query("insert LOW_PRIORITY ignore into visit (id,type,ip) value ($id,'$codetype','$ip')");
	}
	return $row[0];
}

/*
-------Text tools----------------------------------------------------------
*/

function text_cleanSpaces($text) {
	$text = str_replace(" "," ",$text);
	$text = mb_ereg_replace("[\n\r\t ]+"," ",$text);
	$text = mb_ereg_replace("(^\s+)|(\s+$)", "", $text);
	return $text;
}

function text_fixCase($text) {
	if (mb_convert_case($text,MB_CASE_UPPER)==$text ||
		mb_convert_case($text,MB_CASE_LOWER)==$text)
		return mb_convert_case($text,MB_CASE_TITLE);
	return $text;
}

function text_bgMonth($text) {
	$text = mb_ereg_replace("Януари|януари|ЯНУАРИ","01",$text,"imsr");
	$text = mb_ereg_replace("Февруари|февруари|ФЕВРУАРИ","02",$text,"imsr");
	$text = mb_ereg_replace("Март|март|МАРТ","03",$text,"imsr");
	$text = mb_ereg_replace("Април|април|АПРИЛ","04",$text,"imsr");
	$text = mb_ereg_replace("Май|май|МАЙ","05",$text,"imsr");
	$text = mb_ereg_replace("Юни|юни|ЮНИ","06",$text,"imsr");
	$text = mb_ereg_replace("Юли|юли|ЮЛИ","07",$text,"imsr");
	$text = mb_ereg_replace("Август|август|АВГУСТ","08",$text,"imsr");
	$text = mb_ereg_replace("Септември|септември|СЕПТЕМВРИ","09",$text,"imsr");
	$text = mb_ereg_replace("Октомври|октомври|ОКТОМВРИ","10",$text,"imsr");
	$text = mb_ereg_replace("Ноември|ноември|НОЕМВРИ","11",$text,"imsr");
	$text = mb_ereg_replace("Декември|декември|ДЕКЕМВРИ","12",$text,"imsr");
	return $text;
}