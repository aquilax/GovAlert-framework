<?php

class Task {

	protected $db;
	protected $logger;
	protected $debug = false;

	public function __construct(Database $db, Logger $logger, $debug) {
		$this->db = $db;
		$this->logger = $logger;
		$this->debug = $debug;
	}

	function setSession($sourceid,$category) {
		global $session;
		$session["sourceid"]=$sourceid;
		$session["category"]=$category;
		$session["error"]=false;
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

	function loadURL($address,$linki=null) {
		global $session;
		if (!checkSession())
			return false;

		echo "Зареждам $address... ";

		$address=str_replace(" ","%20",$address);
		$hashdata=false;
		$hashdatadirty=false;

		if (!$this->debug && $linki!==null) {
			$res = $this->db->query("select hash,lastchanged,etag,headpostpone,ignorehead from scrape where sourceid=".$session["sourceid"]." and url=$linki limit 1");
			if ($res->num_rows>0)
				$hashdata=$res->fetch_assoc();
			$res->free();
			if ($hashdata && !$hashdata['ignorehead']) {
				$hashdatadirty=array(0,0,0);

				if ($hashdata['lastchanged']!=null || $hashdata['etag']!=null || $hashdata['headpostpone']==null || strtotime($hashdata['headpostpone'])<time()) {
					$context  = stream_context_create(array('http' =>array('method'=>'HEAD')));
					$fd = fopen($address, 'rb', false, $context);
					$headdata = stream_get_meta_data($fd);
					fclose($fd);
					$foundlc=false;
					$foundet=false;
					foreach ($headdata as $header) {
						if ($hashdata['lastchanged']!=null && substr($header,0,strlen("Last-Modified: "))=="Last-Modified: ") {
							$foundlc=true;
							if (strtotime(substr($header,strlen("Last-Modified: ")))==strtotime($hashdata['lastchanged'])) {
								echo "страницата не е променена [Last-Modified]\n";
								return false;
							} else {
								$hashdata['lastchanged']="'".$this->db->escape_string(substr($header,strlen("Last-Modified: ")))."'";
								$hashdatadirty[0]=1;
							}
						}
						if ($hashdata['etag']!=null && substr($header,0,strlen("ETag: "))=="ETag: ") {
							$foundet=true;
							if (substr($header,strlen("ETag: "))==$hashdata['etag'] || substr($header,strlen("ETag: ")+2)==$hashdata['etag']) {
								echo "страницата не е променена [ETag]\n";
								return false;
							} else {
								$hashdata['etag']=substr($header,strlen("ETag: "));
								if (substr($header,0,strlen("W/"))=="W/")
									$hashdata['etag']=substr($hashdata['etag'],2);
								$hashdata['etag']="'".$this->db->escape_string($hashdata['etag'])."'";
								$hashdatadirty[1]=1;
							}
						}
					}
					if (!$foundlc && $hashdata['lastchanged']!=null) {
						$hashdata['lastchanged']='null';
						$hashdatadirty[0]=1;
					}
					if (!$foundet && $hashdata['etag']!=null) {
						$hashdata['etag']='null';
						$hashdatadirty[1]=1;
					}
					if (!$foundlc && !$foundet) {
						$hashdata['headpostpone']='DATE_ADD(NOW(),INTERVAL 1 MONTH)';
						$hashdatadirty[2]=1;
					} else if ($hashdata['headpostpone']!=null) {
						$hashdata['headpostpone']='null';
						$hashdatadirty[2]=1;
					}
				}
			}
		}

		$loadstart=microtime(true);
		$html = file_get_contents($address);
		setPageLoad($linki!==null?$linki:$address,$loadstart);
		if ($html===false || $html===null) {
			sleep(2);
			echo "втори опит... ";
			$loadstart=microtime(true);
			$html = file_get_contents($address);
			setPageLoad($linki!==null?$linki:$address,$loadstart);
		}

		if ($html===false || $html===null) {
			$this->db->reportError("Грешка при зареждане на сайта");
			echo "грешка при зареждането\n";
			return false;
		}

		if (!$this->debug && $linki!==null) {
			if ($hashdata===false) {
				// TODO: Figure this out
				$this->db->query("replace scrape (sourceid,url,hash,loadts) value (".$session["sourceid"].",$linki,'$hash',now())");
			} else {
				$hash = md5($html);
				if ($hashdata['hash']!=null && $hashdata['hash']==$hash) {
					echo "страницата не е променена [hash]\n";
					if (!$hashdata['ignorehead']) {
						if ($hashdata['headpostpone']===null)
							$this->db->query("update scrape set ignorehead=1 where sourceid=".$session["sourceid"]." and url=$linki limit 1");
						else if ($hashdatadirty[0] || $hashdatadirty[1] || $hashdatadirty[2]) {
							$setters = array();
							if ($hashdatadirty[0])
								$setters[]='lastchanged='.$hashdata['lastchanged'];
							if ($hashdatadirty[1])
								$setters[]='etag='.$hashdata['etag'];
							if ($hashdatadirty[2])
								$setters[]='headpostpone='.$hashdata['headpostpone'];
							$this->db->query("update scrape set ".implode(", ",$setters)." where sourceid=".$session["sourceid"]." and url=$linki limit 1");
						}
					}
					return false;
				}

				$this->db->query("update scrape set ".
					($hashdatadirty[0]?'lastchanged='.$hashdata['lastchanged'].', ':'').
					($hashdatadirty[1]?'etag='.$hashdata['etag'].', ':'').
					($hashdatadirty[2]?'headpostpone='.$hashdata['headpostpone'].', ':'').
					"hash='$hash', loadts=now() where sourceid=".$session["sourceid"]." and url=$linki limit 1");
			}
		}

		echo "готово\n";
		return $html;
	}

}