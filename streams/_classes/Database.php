<?php

class Database {

	private $config;
	// TODO: Fix visibility
	public $link;

	function __construct($config, Logger $logger) {
		$this->config = $config;
		$this->logger = $logger;
	}

	function connect() {
		$this->link = mysqli_connect(
			$this->config['host'],
			$this->config['user'],
			$this->config['pass'],
			$this->config['name']) or die("Не мога да се свържа с базата данни. " . $this->link->error);
		$this->link->set_charset($this->config['encoding']);
		return $this->link;
	}

	function query ($sql) {
		$res = $this->link->query($sql);
		if ($res === false) {
			$message = 'Грешка при запитване към базата данни: ' . $this->link->error;
			$this->logger->error($message);
			reportError($message);
			die($message);
		}
		return $res;
	}

	function escape_string($value) {
		return $this->link->escape_string($value);
	}
}

function reportError($descr) {
	global $link,$session,$debug;
	if ($descr===null)
		return;
	$sourceid=$session["sourceid"]!=null ? $session["sourceid"] : 0;
	$category=$session["category"]!=null ? $session["category"] : 0;
	if (is_array($descr) || is_object($descr))
		$descr = json_encode($descr);
	$e = new Exception();
	$trace = str_replace("/home/yurukov1/public_html/govalert/","",$e->getTraceAsString());
	echo "Запазвам грешка [$sourceid,$category]: $descr\n$trace\n";
	if ($debug)
		return;
	$descr = $link->escape_string("$descr\n$trace");
	$link->query("insert LOW_PRIORITY ignore into error (sourceid, category, descr) value ($sourceid,$category,'$descr')") or reportDBErrorAndDie();
	$session["error"]=true;
}


/*
    Saving data
*/

function saveItem($title,$description,$pubts,$url,$hash,$media=null) {
	return saveItems(array(array($title,$description,$pubts,$url,$hash,$media)));
}

function saveItems($items) {
	global $link,$session;
	if (!checkSession())
		return;
	if (!$items || count($items)==0)
		return;

	echo "Запазвам ".count($items)."... ";
	$hashes=array();
	foreach ($items as $item)
		$hashes[]="'".$item[4]."'";
	$res=$link->query("select hash from item where hash in (".implode(",",$hashes).") limit ".count($hashes)) or reportDBErrorAndDie();
	$hashes=array();
	while ($row = $res->fetch_array())
		$hashes[]=$row[0];
	$res->free();

	$query = array();
	foreach ($items as $item) {
		if (in_array($item[4],$hashes))
			continue;
		$item[0]=$item[0]!==null?"'".$link->escape_string($item[0])."'":"null";
		$item[1]=$item[1]!==null?"'".$link->escape_string($item[1])."'":"null";
		$item[2]=$item[2]!==null? ($item[2]=='now'?'now()':"'".$link->escape_string($item[2])."'") :"null";
		$item[3]=$item[3]!==null?"'".$link->escape_string($item[3])."'":"null";
		$query[]=array("(${item[0]},${item[1]},${session['sourceid']},${session['category']},${item[2]},now(),${item[3]},'${item[4]}')",$item[5]);
	}
	echo "от тях ".count($query)." са нови... ";

	$changed = array();
	if (count($query)>0) {
		$query = array_reverse($query);
		foreach ($query as $value) {
			$link->query("insert LOW_PRIORITY ignore into item (title,description,sourceid,category,pubts,readts,url,hash) value ".$value[0]) or reportDBErrorAndDie();
			if ($link->affected_rows>0) {
				$changed[]=$link->insert_id;
				if ($value[1] && is_array($value[1])) {
					$mediaquery = array();
					foreach ($value[1] as $mediakey => $mediavalue) {
						if (!$mediavalue[0] || $mediavalue[0]==null)
							continue;
						if (is_array($mediavalue[0])) {
							foreach ($mediavalue as $mediavalueitem) {
								if (!$mediavalueitem[0] || $mediavalueitem[0]==null)
									continue;
								$mediavalueitem[0] = "'".$link->escape_string($mediavalueitem[0])."'";
								$mediavalueitem[1] = !$mediavalueitem[1] || $mediavalueitem[1]==null ? "null" : "'".$link->escape_string($mediavalueitem[1])."'";
								$mediaquery[]="(".$link->insert_id.",'$mediakey',".$mediavalueitem[0].",".$mediavalueitem[1].")";
							}
						} else {
							$mediavalue[0] = "'".$link->escape_string($mediavalue[0])."'";
							$mediavalue[1] = !$mediavalue[1] || $mediavalue[1]==null ? "null" : "'".$link->escape_string($mediavalue[1])."'";
							$mediaquery[]="(".$link->insert_id.",'$mediakey',".$mediavalue[0].",".$mediavalue[1].")";
						}
					}
					$link->query("insert LOW_PRIORITY ignore into item_media (itemid,type,value,description) values ".implode(",",$mediaquery)) or reportDBErrorAndDie();
				}
			}
		}
	}
	echo "записани ".count($changed)."\n";
	return $changed;
}

function checkHash($hash) {
	global $link;
	$res=$link->query("select hash from item where hash='$hash' limit 1") or reportDBErrorAndDie();
	return $res->num_rows==0;
}

function checkTitle($title) {
	global $link,$session;
	if (!checkSession())
		return true;
	$res=$link->query("select hash from item where title='$title' and sourceid=${session['sourceid']} limit 1") or reportDBErrorAndDie();
	return $res->num_rows==0;
}

/*
-------Utils----------------------------------------------------------
*/

function updateHash($oldhash,$newhash) {
	global $link;
	$link->query("update item set hash='$newhash' where hash='$oldhash' limit 1") or reportDBErrorAndDie();
	echo "update hash $oldhash->$newhash ".($link->affected_rows>0?"ok":"fail")."\n";
}

function updateHashUrl($url,$newhash) {
	global $link;
	$link->query("update item set hash='$newhash' where url='$url'") or reportDBErrorAndDie();
	echo "update hash $url->$newhash ".($link->affected_rows>0?"ok ".$link->affected_rows:"fail")."\n";
}

function updateHashTitle($title,$newhash) {
	global $link;
	$link->query("update item set hash='$newhash' where title='$title'") or reportDBErrorAndDie();
	echo "update hash $title->$newhash ".($link->affected_rows>0?"ok ".$link->affected_rows:"fail")."\n";
}
