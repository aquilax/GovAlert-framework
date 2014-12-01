<?php

class Task {

	protected $db;
	protected $logger;

	public function __construct(Database $db, Logger $logger) {
		$this->db = $db;
		$this->logger = $logger;
	}

	function setSession($sourceid,$category) {
		global $session;
		$session["sourceid"]=$sourceid;
		$session["category"]=$category;
		$session["error"]=false;
	}

	function loadURL($address, $linki=null) {
		return loadURL($address, $linki);
	}

	function checkHash($hash) {
		$res = $this->db->query("select hash from item where hash='$hash' limit 1");
		return $res->num_rows==0;
	}

	function saveItems($items) {
		global $session;
		if (!checkSession()) {
			return;
		}
		if (!$items || count($items)==0) {
			return;
		}

		$this->logger->info('Запазвам ' . count($items).'... ');
		$hashes=array();
		foreach ($items as $item) {
			$hashes[]="'".$item[4]."'";
		}
		$res = $this->db->query("select hash from item where hash in (".implode(",",$hashes).") limit ".count($hashes));
		$hashes=array();
		while ($row = $res->fetch_array()) {
			$hashes[]=$row[0];
		}
		$res->free();

		$query = array();
		foreach ($items as $item) {
			if (in_array($item[4],$hashes)) {
				continue;
			}
			$item[0]=$item[0]!==null?"'".$this->db->escape_string($item[0])."'":"null";
			$item[1]=$item[1]!==null?"'".$this->db->escape_string($item[1])."'":"null";
			$item[2]=$item[2]!==null? ($item[2]=='now'?'now()':"'".$this->db->escape_string($item[2])."'") :"null";
			$item[3]=$item[3]!==null?"'".$this->db->escape_string($item[3])."'":"null";
			$query[]=array("(${item[0]},${item[1]},${session['sourceid']},${session['category']},${item[2]},now(),${item[3]},'${item[4]}')",$item[5]);
		}
		$this->logger->info('от тях ' . count($query) . ' са нови... ');

		$changed = array();
		if (count($query)>0) {
			$query = array_reverse($query);
			foreach ($query as $value) {
				$this->db->query("insert LOW_PRIORITY ignore into item (title,description,sourceid,category,pubts,readts,url,hash) value ".$value[0]) or reportDBErrorAndDie();
				if ($this->db->affected_rows>0) {
					$changed[]=$this->db->insert_id;
					if ($value[1] && is_array($value[1])) {
						$mediaquery = array();
						foreach ($value[1] as $mediakey => $mediavalue) {
							if (!$mediavalue[0] || $mediavalue[0]==null)
								continue;
							if (is_array($mediavalue[0])) {
								foreach ($mediavalue as $mediavalueitem) {
									if (!$mediavalueitem[0] || $mediavalueitem[0]==null)
										continue;
									$mediavalueitem[0] = "'".$this->db->escape_string($mediavalueitem[0])."'";
									$mediavalueitem[1] = !$mediavalueitem[1] || $mediavalueitem[1]==null ? "null" : "'".$this->db->escape_string($mediavalueitem[1])."'";
									$mediaquery[]="(".$$this->db->insert_id.",'$mediakey',".$mediavalueitem[0].",".$mediavalueitem[1].")";
								}
							} else {
								$mediavalue[0] = "'".$this->db->escape_string($mediavalue[0])."'";
								$mediavalue[1] = !$mediavalue[1] || $mediavalue[1]==null ? "null" : "'".$this->db->escape_string($mediavalue[1])."'";
								$mediaquery[]="(".$$this->db->insert_id.",'$mediakey',".$mediavalue[0].",".$mediavalue[1].")";
							}
						}
						$this->db->query("insert LOW_PRIORITY ignore into item_media (itemid,type,value,description) values ".implode(",",$mediaquery)) or reportDBErrorAndDie();
					}
				}
			}
		}
		$this->logger->info('записани ' . count($changed));
		return $changed;
	}

}