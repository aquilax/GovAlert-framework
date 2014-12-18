<?php

namespace GovAlert\Common;

class Database extends \mysqli
{

	const DEFAULT_INSERT_PREFIX = 'LOW_PRIORITY IGNORE';

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
	 * @return mysqli_result
	 */
	function query($query, $resultMode = MYSQLI_STORE_RESULT)
	{
		$this->logger->debug($query);
		$res = parent::query($query, $resultMode);
		if ($res === false) {
			$message = 'Грешка при запитване към базата данни: ' . $this->error . ' : ' . $query;
			$this->logger->error($message);
			die($message);
		}
		return $res;
	}

	function insert($table, $fields, $prefix = self::DEFAULT_INSERT_PREFIX)
	{
		assert(empty($field));
		$keys = array_keys($fields);
		array_walk($fields, function (&$value) {
			if (is_null($value)) {
				$value = 'NULL';
			} else {
				$value = self::quote($this->real_escape_string($value));
			}
		});
		$query = sprintf(
			'INSERT %s INTO %s (%s) VALUES (%s);',
			$prefix,
			$table,
			implode(', ', $keys),
			implode(', ', $fields)
		);
		return $this->query($query);
	}

	// FIXME: This is sub-optimal
	function batchInsert($table, $rows, $prefix = self::DEFAULT_INSERT_PREFIX)
	{
		foreach ($rows as $row) {
			$this->insert($table, $row, $prefix);
		}
	}

	static function quote($text)
	{
		return '\'' . $text . '\'';
	}

	function time() {
		return time();
	}

	function now($timestamp = null)
	{
		if (is_null($timestamp)) {
			$timestamp = $this->time();
		}
		return date('c', $timestamp);
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
