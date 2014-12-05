<?php

class Database extends mysqli
{

	private $config;
	private $logger;

	function __construct($config, Logger $logger)
	{
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

	/**
	 * @param string $query
	 * @param int $resultMode
	 * @return bool|mysqli_result
	 */
	function query($query, $resultMode = MYSQLI_STORE_RESULT)
	{
		$res = parent::query($query, $resultMode);
		if ($res === false) {
			$message = 'Грешка при запитване към базата данни: ' . $this->error;
			$this->logger->error($message);
			die($message);
		}
		return $res;
	}

}

/*
-------Utils----------------------------------------------------------
*/
//
//function updateHash($oldhash,$newhash) {
//	global $link;
//	$link->query("update item set hash='$newhash' where hash='$oldhash' limit 1");
//	echo "update hash $oldhash->$newhash ".($link->affected_rows>0?"ok":"fail")."\n";
//}
//
//function updateHashUrl($url,$newhash) {
//	global $link;
//	$link->query("update item set hash='$newhash' where url='$url'") or reportDBErrorAndDie();
//	echo "update hash $url->$newhash ".($link->affected_rows>0?"ok ".$link->affected_rows:"fail")."\n";
//}
//
//function updateHashTitle($title,$newhash) {
//	global $link;
//	$link->query("update item set hash='$newhash' where title='$title'") or reportDBErrorAndDie();
//	echo "update hash $title->$newhash ".($link->affected_rows>0?"ok ".$link->affected_rows:"fail")."\n";
//}
