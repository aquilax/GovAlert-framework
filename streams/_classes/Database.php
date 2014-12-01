<?php

class Database extends mysqli{

	private $config;
	private $logger;

	function __construct($config, Logger $logger) {
		$this->config = $config;
		$this->logger = $logger;
		parent::__construct(
			$this->config['host'],
			$this->config['user'],
			$this->config['pass'],
			$this->config['name']);
		if ($this->connect_error) {
			die('Не мога да се свържа с базата данни. ' . $this->connect_error);
		}
		$this->set_charset($this->config['encoding']);
	}

	function query($query, $resultMode = MYSQLI_STORE_RESULT) {
		$res = parent::query($query, $resultMode);
		if ($res === false) {
			$message = 'Грешка при запитване към базата данни: ' . $this->error;
			$this->logger->error($message);
			$this->reportError($message);
			die($message);
		}
		return $res;
	}

	private function reportError($message) {
		global $session,$debug;
		if ($message === null)
			return;
		$sourceId = $session["sourceid"] != null ? $session["sourceid"] : 0;
		$category = $session["category"] != null ? $session["category"] : 0;
		if (is_array($message) || is_object($message))
			$message = json_encode($message);
		$e = new Exception();
		$trace = str_replace("/home/yurukov1/public_html/govalert/","",$e->getTraceAsString());
		echo "Запазвам грешка [$sourceId,$category]: $message\n$trace\n";
		if ($debug)
			return;
		$message = $this->escape_string("$message\n$trace");
		$this->query("insert LOW_PRIORITY ignore into error (sourceid, category, descr) value ($sourceId,$category,'$message')");
		$session["error"]=true;
	}
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
