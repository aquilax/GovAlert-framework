<?php

namespace GovAlert\Common;


class Loader {

	public $error = false;

	public function __construct(Database $db,
								Logger $logger,
								$debug = false) {
		$this->db = $db;
		$this->logger = $logger;
		$this->debug = $debug;
	}

	public function loadURL($sourceId, $categoryId, $address, $linki = null)
	{
		$this->logger->info('Зареждам ' . $address . '...');

		$address = str_replace(" ", "%20", $address);
		$hashData = false;
		$hashDataDirty = false;

		if (!$this->debug && $linki !== null) {
			$res = $this->db->query(sprintf('SELECT hash, lastchanged, etag, headpostpone, ignorehead FROM scrape WHERE sourceid= %d  AND url= %d LIMIT 1', $sourceId, $linki));
			if ($res->num_rows > 0) {
				$hashData = $res->fetch_assoc();
			}
			$res->free();
			if ($hashData && !$hashData['ignorehead']) {
				$hashDataDirty = array(0, 0, 0);

				if ($hashData['lastchanged'] != null || $hashData['etag'] != null || $hashData['headpostpone'] == null || strtotime($hashData['headpostpone']) < Database::time()) {
					$context = stream_context_create(array('http' => array('method' => 'HEAD')));
					$fd = fopen($address, 'rb', false, $context);
					$headdata = stream_get_meta_data($fd);
					fclose($fd);
					$foundlc = false;
					$foundet = false;
					foreach ($headdata as $header) {
						if ($hashData['lastchanged'] != null && substr($header, 0, strlen("Last-Modified: ")) == "Last-Modified: ") {
							$foundlc = true;
							if (strtotime(substr($header, strlen("Last-Modified: "))) == strtotime($hashData['lastchanged'])) {
								$this->logger->info('страницата не е променена [Last-Modified]');
								return false;
							} else {
								$hashData['lastchanged'] = "'" . $this->db->escape_string(substr($header, strlen("Last-Modified: "))) . "'";
								$hashDataDirty[0] = 1;
							}
						}
						if ($hashData['etag'] != null && substr($header, 0, strlen("ETag: ")) == "ETag: ") {
							$foundet = true;
							if (substr($header, strlen("ETag: ")) == $hashData['etag'] || substr($header, strlen("ETag: ") + 2) == $hashData['etag']) {
								$this->logger->info('страницата не е променена [ETag]');
								return false;
							} else {
								$hashData['etag'] = substr($header, strlen("ETag: "));
								if (substr($header, 0, strlen("W/")) == "W/")
									$hashData['etag'] = substr($hashData['etag'], 2);
								$hashData['etag'] = "'" . $this->db->escape_string($hashData['etag']) . "'";
								$hashDataDirty[1] = 1;
							}
						}
					}
					if (!$foundlc && $hashData['lastchanged'] != null) {
						$hashData['lastchanged'] = 'null';
						$hashDataDirty[0] = 1;
					}
					if (!$foundet && $hashData['etag'] != null) {
						$hashData['etag'] = 'null';
						$hashDataDirty[1] = 1;
					}
					if (!$foundlc && !$foundet) {
						$hashData['headpostpone'] = 'DATE_ADD(NOW(),INTERVAL 1 MONTH)';
						$hashDataDirty[2] = 1;
					} else if ($hashData['headpostpone'] != null) {
						$hashData['headpostpone'] = 'null';
						$hashDataDirty[2] = 1;
					}
				}
			}
		}

		$loadstart = microtime(true);
		$html = file_get_contents($address);
		$this->setPageLoad($sourceId, $categoryId, $linki !== null ? $linki : $address, $loadstart);
		if ($html === false || $html === null) {
			sleep(2);
			$this->logger->info('...втори опит...');
			$loadstart = microtime(true);
			if (empty($address)) {
				throw new \Exception('Empty address passed');
			}
			$html = file_get_contents($address);
			$this->setPageLoad($sourceId, $categoryId, $linki !== null ? $linki : $address, $loadstart);
		}

		if ($html === false || $html === null) {
			$this->reportError($sourceId, $categoryId, 'Грешка при зареждане на сайта');
			$this->logger->error('грешка при зареждането');
			return false;
		}

		if (!$this->debug && !empty($linki)) {
			$hash = md5($html);
			if ($hashData === false) {
				$this->db->query("replace scrape (sourceid,url,hash,loadts) value (" . $sourceId . ",$linki,'$hash',now())");
			} else {
				if ($hashData['hash'] != null && $hashData['hash'] == $hash) {
					$this->logger->info('страницата не е променена [hash]');
					if (!$hashData['ignorehead']) {
						if ($hashData['headpostpone'] === null)
							$this->db->query('UPDATE scrape SET ignorehead = 1 WHERE sourceid=' . $sourceId . ' AND url = ' . $linki . ' LIMIT 1');
						else if ($hashDataDirty[0] || $hashDataDirty[1] || $hashDataDirty[2]) {
							$setters = array();
							if ($hashDataDirty[0])
								$setters[] = 'lastchanged=' . $hashData['lastchanged'];
							if ($hashDataDirty[1])
								$setters[] = 'etag=' . $hashData['etag'];
							if ($hashDataDirty[2])
								$setters[] = 'headpostpone=' . $hashData['headpostpone'];
							$this->db->query('UPDATE scrape SET ' . implode(', ', $setters) . ' WHERE sourceid=' . $sourceId . ' AND url= ' . $linki . ' LIMIT 1');
						}
					}
					return false;
				}

				$this->db->query("UPDATE scrape SET " .
					($hashDataDirty[0] ? 'lastchanged=' . $hashData['lastchanged'] . ', ' : '') .
					($hashDataDirty[1] ? 'etag=' . $hashData['etag'] . ', ' : '') .
					($hashDataDirty[2] ? 'headpostpone=' . $hashData['headpostpone'] . ', ' : '') .
					"hash='$hash', loadts=now() where sourceid=" . $sourceId . " and url=$linki limit 1");
			}
		}

		$this->logger->info('...готово');
		return $html;
	}

	public function setPageLoad($sourceId, $categoryId, $url, $loadStart)
	{
		$row = [
			'sourceid' => $sourceId,
			'category' => $categoryId,
			'url' => $url,
			'loadtime' => round((microtime(true) - $loadStart) * 1000)
		];
		$this->db->insert('scrape_load', $row);
	}

	public function reportError($sourceId, $categoryId, $message)
	{
		if ($message === null) {
			return;
		}
		if (is_array($message) || is_object($message)) {
			$message = json_encode($message);
		}
		$e = new \Exception();
		$trace = str_replace("/home/yurukov1/public_html/govalert/", "", $e->getTraceAsString());
		$message = $message . PHP_EOL . $trace;
		$this->logger->error("Запазвам грешка [$sourceId,$categoryId]: $message");
		if ($this->debug){
			return;
		}
		$this->db->insert('error', [
			'sourceid' => $sourceId,
			'category' => $categoryId,
			'descr' => $message,
		]);
		$this->error = true;
	}
} 