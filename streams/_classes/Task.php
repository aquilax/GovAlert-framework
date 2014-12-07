<?php

/**
 * Class Task - Generic Task class
 */
abstract class Task
{

	protected $db;
	protected $logger;
	protected $debug = false;
	protected $sourceId = null;
	protected $sourceName = null;
	protected $categoryId = null;
	protected $categoryName = null;
	protected $categoryURL = null;
	protected $error = false;
	protected $tweetAccount = 'govaleteu';
	protected $tweetReTweet = null;


	abstract protected function execute($html);

	public function __construct(Database $db, Logger $logger, $debug = false)
	{
		$this->db = $db;
		$this->logger = $logger;
		$this->debug = $debug;
	}

	public function run()
	{
		$this->logger->info(sprintf('Проверявам за %s %s', $this->sourceName, $this->categoryName));
		$html = $this->loader($this->categoryId, $this->categoryURL);
		if ($html) {
			$items = $this->execute($html);
			if ($items) {
				$this->processItems($items);
			}
		}
	}

	protected  function processItems(Array $query) {
		$this->logger->info('Възможни ' . count($query) . ' нови ' . $this->categoryName);
		$itemIds = $this->saveItems($query);
		$this->queueTweets($itemIds, $this->tweetAccount, $this->tweetReTweet);
	}

	protected function cleanText($text)
	{
		$text = html_entity_decode($text);
		$text = Utils::cleanSpaces($text);
		$text = Utils::fixCase($text);
		return $text;
	}


	protected function loader($categoryId, $categoryURL)
	{
		return $this->loadURL($categoryURL, $categoryId);
	}

	function checkHash($hash)
	{
		$res = $this->db->query("SELECT hash FROM item WHERE hash='$hash' LIMIT 1");
		return $res->num_rows == 0;
	}

	private function getHashes(Array $hashes)
	{
		array_walk($hashes, function (&$hash) {
			$hash = Database::quote($hash);
		});
		$res = $this->db->query('SELECT hash FROM item WHERE hash IN (' . implode(', ', $hashes) . ') limit ' . count($hashes));
		$hashes = [];
		while ($row = $res->fetch_array()) {
			$hashes[] = $row[0];
		}
		$res->free();
		return $hashes;
	}

	private function saveMedia($media, $itemId)
	{
		$rows = [];
		foreach ($media as $mediaKey => $mediaValue) {
			if (!$mediaValue[0] || $mediaValue[0] == null)
				continue;
			if (is_array($mediaValue[0])) {
				foreach ($mediaValue as $mediaValueItem) {
					if (!$mediaValueItem[0] || $mediaValueItem[0] == null)
						continue;
					$rows[] = [
						'itemid' => $itemId,
						'type' => $mediaKey,
						'value' => $mediaValueItem[0],
						'description' => $mediaValueItem[0]
					];
				}
			} else {
				$rows[] = [
					'itemid' => $itemId,
					'type' => $mediaKey,
					'value' => $mediaValue[0],
					'description' => $mediaValue[1]
				];
			}
		}
		$this->db->batchInsert('item_media', $rows, 'LOW_PRIORITY IGNORE');
	}

	private function saveItem(Array $item)
	{
		$itemId = 0;
		$media = false;

		// Remove media first
		if (array_key_exists('media', $item)) {
			if (is_array($item['media'])) {
				$media = $item['media'];
			}
			unset($item['media']);
		}

		$this->db->insert('item', $item);
		if ($this->db->affected_rows > 0) {
			$itemId = $this->db->insert_id;
			if ($media) {
				$this->saveMedia($media, $itemId);
			}
		}
		return $itemId;
	}

	function saveItems(Array $items)
	{
		if (!$this->checkSession() || !$items || count($items) == 0) {
			return false;
		}

		$this->logger->info('Запазвам ' . count($items) . '... ');

		$hashes = $this->getHashes(array_column($items, 'hash'));

		$query = array();
		foreach ($items as $item) {
			if (in_array($item['hash'], $hashes)) {
				continue;
			}
			$query[] = [
				'title' => $item['title'],
				'description' => $item['description'],
				'sourceid' => $this->sourceId,
				'category' => $this->categoryId,
				'pubts' => $item['date'],
				'readts' => Utils::now(),
				'url' => $item['url'],
				'hash' => $item['hash'],
				'media' => isset($item['media']) ? $item['media'] : null,
			];
		}

		$this->logger->info('...oт тях ' . count($query) . ' са нови... ');
		$changed = [];

		if ($query) {
			$query = array_reverse($query);
			foreach ($query as $row) {
				$res = $this->saveItem($row);
				if ($res) {
					$changed[] = $res;
				}
			}
		}
		$this->logger->info('записани ' . count($changed));
		return $changed;
	}

	function loadURL($address, $linki = null)
	{
		if (!$this->checkSession())
			return false;

		$this->logger->info('Зареждам ' . $address . '...');

		$address = str_replace(" ", "%20", $address);
		$hashdata = false;
		$hashdatadirty = false;

		if (!$this->debug && $linki !== null) {
			$res = $this->db->query(sprintf('SELECT hash, lastchanged, etag, headpostpone, ignorehead FROM scrape WHERE sourceid= %d  AND url= %d LIMIT 1', $this->sourceId, $linki));
			if ($res->num_rows > 0)
				$hashdata = $res->fetch_assoc();
			$res->free();
			if ($hashdata && !$hashdata['ignorehead']) {
				$hashdatadirty = array(0, 0, 0);

				if ($hashdata['lastchanged'] != null || $hashdata['etag'] != null || $hashdata['headpostpone'] == null || strtotime($hashdata['headpostpone']) < time()) {
					$context = stream_context_create(array('http' => array('method' => 'HEAD')));
					$fd = fopen($address, 'rb', false, $context);
					$headdata = stream_get_meta_data($fd);
					fclose($fd);
					$foundlc = false;
					$foundet = false;
					foreach ($headdata as $header) {
						if ($hashdata['lastchanged'] != null && substr($header, 0, strlen("Last-Modified: ")) == "Last-Modified: ") {
							$foundlc = true;
							if (strtotime(substr($header, strlen("Last-Modified: "))) == strtotime($hashdata['lastchanged'])) {
								$this->logger->info('страницата не е променена [Last-Modified]');
								return false;
							} else {
								$hashdata['lastchanged'] = "'" . $this->db->escape_string(substr($header, strlen("Last-Modified: "))) . "'";
								$hashdatadirty[0] = 1;
							}
						}
						if ($hashdata['etag'] != null && substr($header, 0, strlen("ETag: ")) == "ETag: ") {
							$foundet = true;
							if (substr($header, strlen("ETag: ")) == $hashdata['etag'] || substr($header, strlen("ETag: ") + 2) == $hashdata['etag']) {
								$this->logger->info('страницата не е променена [ETag]');
								return false;
							} else {
								$hashdata['etag'] = substr($header, strlen("ETag: "));
								if (substr($header, 0, strlen("W/")) == "W/")
									$hashdata['etag'] = substr($hashdata['etag'], 2);
								$hashdata['etag'] = "'" . $this->db->escape_string($hashdata['etag']) . "'";
								$hashdatadirty[1] = 1;
							}
						}
					}
					if (!$foundlc && $hashdata['lastchanged'] != null) {
						$hashdata['lastchanged'] = 'null';
						$hashdatadirty[0] = 1;
					}
					if (!$foundet && $hashdata['etag'] != null) {
						$hashdata['etag'] = 'null';
						$hashdatadirty[1] = 1;
					}
					if (!$foundlc && !$foundet) {
						$hashdata['headpostpone'] = 'DATE_ADD(NOW(),INTERVAL 1 MONTH)';
						$hashdatadirty[2] = 1;
					} else if ($hashdata['headpostpone'] != null) {
						$hashdata['headpostpone'] = 'null';
						$hashdatadirty[2] = 1;
					}
				}
			}
		}

		$loadstart = microtime(true);
		$html = file_get_contents($address);
		$this->setPageLoad($linki !== null ? $linki : $address, $loadstart);
		if ($html === false || $html === null) {
			sleep(2);
			$this->logger->info('...втори опит...');
			$loadstart = microtime(true);
			if (empty($address)) {
				throw new Exception('Empty address passed');
			}
			$html = file_get_contents($address);
			$this->setPageLoad($linki !== null ? $linki : $address, $loadstart);
		}

		if ($html === false || $html === null) {
			$this->reportError("Грешка при зареждане на сайта");
			$this->logger->error('грешка при зареждането');
			return false;
		}

		if (!$this->debug && $linki !== null) {
			$hash = md5($html);
			if ($hashdata === false) {
				$this->db->query("replace scrape (sourceid,url,hash,loadts) value (" . $this->sourceId . ",$linki,'$hash',now())");
			} else {
				if ($hashdata['hash'] != null && $hashdata['hash'] == $hash) {
					$this->logger->info('страницата не е променена [hash]');
					if (!$hashdata['ignorehead']) {
						if ($hashdata['headpostpone'] === null)
							$this->db->query('UPDATE scrape SET ignorehead = 1 WHERE sourceid=' . $this->sourceId . ' AND url = ' . $linki . ' LIMIT 1');
						else if ($hashdatadirty[0] || $hashdatadirty[1] || $hashdatadirty[2]) {
							$setters = array();
							if ($hashdatadirty[0])
								$setters[] = 'lastchanged=' . $hashdata['lastchanged'];
							if ($hashdatadirty[1])
								$setters[] = 'etag=' . $hashdata['etag'];
							if ($hashdatadirty[2])
								$setters[] = 'headpostpone=' . $hashdata['headpostpone'];
							$this->db->query('UPDATE scrape SET ' . implode(', ', $setters) . ' WHERE sourceid=' . $this->sourceId . ' AND url= ' . $linki . ' LIMIT 1');
						}
					}
					return false;
				}

				$this->db->query("UPDATE scrape SET " .
					($hashdatadirty[0] ? 'lastchanged=' . $hashdata['lastchanged'] . ', ' : '') .
					($hashdatadirty[1] ? 'etag=' . $hashdata['etag'] . ', ' : '') .
					($hashdatadirty[2] ? 'headpostpone=' . $hashdata['headpostpone'] . ', ' : '') .
					"hash='$hash', loadts=now() where sourceid=" . $this->sourceId . " and url=$linki limit 1");
			}
		}

		$this->logger->info('...готово');
		return $html;
	}


	function checkTitle($title)
	{
		if (!$this->checkSession())
			return true;
		$res = $this->db->query("SELECT hash FROM item WHERE title='$title' AND sourceid=" . $this->sourceId . " LIMIT 1");
		return $res->num_rows == 0;
	}

	function checkPageChanged($html, $linki)
	{
		if (!$this->checkSession())
			return false;
		$hash = md5($html);
		$res = $this->db->query("SELECT hash FROM scrape WHEREhash='$hash' AND sourceid=" . $this->sourceId . " AND url=$linki LIMIT 1");
		if ($res->num_rows > 0) {
			$res->free();
			return false;
		}

		$res->free();
		$this->db->query("replace scrape (sourceid,url,hash,loadts) value (" . $this->sourceId . ",$linki,'$hash',now())");
		return true;
	}


	function setPageLoad($url, $loadstart)
	{
		if ($this->debug || !$this->checkSession()) {
			return;
		}
		$row = [
			'sourceid' => $this->sourceId,
			'category' => $this->categoryId,
			'url' => $url,
			'loadtime' => round((microtime(true) - $loadstart) * 1000)
		];
		$this->db->insert('scrape_load', $row);
	}


	function loadGeoImage($lat, $lng, $zoom)
	{
		$filename = "/www/govalert/media/maps/static/" . str_replace(".", "_", $lat . "_" . $lng) . "_$zoom.png";
		if (!file_exists($filename)) {
			$url = "http://api.tiles.mapbox.com/v3/yurukov.i6nmgf1c/pin-l-star+ff0000($lng,$lat,$zoom)/$lng,$lat,$zoom/640x480.png";
			$loadstart = microtime(true);
			exec("wget --header='Connection: keep-alive' --header='Cache-Control: max-age=0' --header='Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8' --header='User-Agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/33.0.1750.152 Safari/537.36' --header='Accept-Encoding: gzip,deflate,sdch' --header='Accept-Language: en-US,en;q=0.8,bg;q=0.6,de;q=0.4' -q -O '$filename' '$url'");
			$this->setPageLoad($url, $loadstart);
			usleep(500000);
		}

		if (!file_exists($filename) || filesize($filename) == 0) {
			$this->reportError("Грешка при зареждане на геоснимка $lat,$lng,$zoom.");
			return null;
		}

		return $filename;
	}

	function loadItemImage($url, $type = null, $options = [])
	{
		if ($type === null) {
			$type = ".jpg";
		} else if (substr($type, 0, 1) != ".")
			$type = ".$type";

		if (strtolower($type) != ".jpg" && strtolower($type) != ".jpeg" && strtolower($type) != ".gif" && strtolower($type) != ".png" && strtolower($type) != ".bmp")
			return null;

		$filename = Config::get('mediaPath') .'item_images/'. md5($url) . ($type == ".bmp" ? ".jpg" : $type);
		if (!file_exists($filename)) {
			$loadstart = microtime(true);
			exec("wget --header='Connection: keep-alive' --header='Cache-Control: max-age=0' --header='Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8' --header='User-Agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/33.0.1750.152 Safari/537.36' --header='Accept-Encoding: gzip,deflate,sdch' --header='Accept-Language: en-US,en;q=0.8,bg;q=0.6,de;q=0.4' -q -O '$filename' '$url'");
			$this->setPageLoad($url, $loadstart);
			if (filesize($filename) >= 1.5 * 1024 * 1024)
				Images::resizeItemImage($filename, $type);
			else
				Images::fitinItemImage($filename, $type, $options);

			usleep(500000);
		}

		if (!file_exists($filename) || filesize($filename) == 0) {
			if (file_exists($filename))
				unlink($filename);
			if (!array_key_exists("doNotReportError", $options))
				$this->reportError("Грешка при зареждане на снимка: $url");
			return null;
		}

		return $filename;
	}

	function checkSession()
	{
		if ($this->sourceId == null) {
			$this->reportError('Не е заредена сесията');
			return false;
		}
		return true;
	}

	function queueTextTweet($text, $urls, $account = 'govalerteu', $retweet = false)
	{
		if (!$this->checkSession())
			return;
		if (!$text || mb_strlen($text) == 0)
			return;

		if ($urls && !is_array($urls))
			$urls = array($urls);

		$this->logger->info('Планирам tweet за srcid=' . $this->sourceId . ' текст=' . $text. ' и адреси ' . implode(', ', $urls));

		$position = 1;
		foreach ($urls as $url) {
			$res = $this->db->query("SELECT linkid FROM link WHERE url='$url'");
			if ($res->num_rows > 0) {
				$row = $res->fetch_assoc();
				$linkid = intval($row['linkid']);
			} else {
				$this->db->insert('link',['url' => $url], 'LOW_PRIORITY');
				$linkid = $this->db->insert_id;
			}
			if (!$linkid)
				return;
			$urltext = "http://GovAlert.eu/-" . linkCode($linkid);
			if (mb_strpos($text, "$" . $position))
				$text = mb_ereg_replace("\\$" . $position, $urltext, $text);
			else
				$text .= " " . $urltext;
			$position++;
		}

		if (!$retweet)
			$retweet = "null";
		else if (is_string($retweet))
			$retweet = "'$retweet'";
		else if (is_array($retweet))
			$retweet = "'" . implode(",", $retweet) . "'";
		else
			$retweet = "'govalerteu'";
		$fields = [
			'account' => $account,
			'queued' => Utils::now(),
			'text' => $text,
			'sourceid' => $this->sourceId,
			'priority' => 1,
			'retweet' => $retweet,
		];
		$this->db->insert('tweet', $fields, 'LOW_PRIORITY ignore');
	}

	/**
	 * @param $itemids
	 * @param string $account
	 * @param mixed $retweet
	 */
	function queueTweets($itemids, $account = 'govalerteu', $retweet = false)
	{
		if (!$itemids || count($itemids) == 0)
			return;
		$this->logger->info('Планирам ' . count($itemids) . ' tweet-а');

		if (!$retweet)
			$retweet = "null";
		else if (is_string($retweet))
			$retweet = "'$retweet'";
		else if (is_array($retweet))
			$retweet = "'" . implode(",", $retweet) . "'";
		else
			$retweet = "govalerteu";

		$query = array();
		foreach ($itemids as $id) {
			$query[] = [
				'itemid' => $id,
				'account' => $account,
				'queued' => Utils::now(),
				'retweet' => $retweet
			];
		}
		$this->db->batchInsert('tweet', $query);
	}

	public function reportError($message)
	{
		if ($message === null)
			return;
		$sourceId = $this->sourceId != null ? $this->sourceId : 0;
		$category = $this->categoryId != null ? $this->categoryId : 0;
		if (is_array($message) || is_object($message))
			$message = json_encode($message);
		$e = new Exception();
		$trace = str_replace("/home/yurukov1/public_html/govalert/", "", $e->getTraceAsString());
		$this->logger->error("Запазвам грешка [$sourceId,$category]: $message\n$trace");
		if ($this->debug)
			return;
		$message = $message . PHP_EOL . $trace;
		$this->db->insert('error', [
			'sourceid' => $sourceId,
			'category' => $category,
			'descr' => $message,
		]);
		$this->error = true;
	}

	public function getError()
	{
		return $this->error;
	}

	/**
	 * @param $html
	 * @param string $encoding
	 * @return DOMXpath
	 * @throws Exception
	 */
	protected function getXPath($html, $encoding = 'UTF-8', $isHTML = true)
	{
		if (!trim($html)) {
			throw new Exception('Empty document');
		}
		$html = mb_convert_encoding($html, 'HTML-ENTITIES', $encoding);
		$doc = new DOMDocument('1.0', $encoding);
		$doc->preserveWhiteSpace = false;
		$doc->strictErrorChecking = false;
		$doc->encoding = 'UTF-8';
		// TODO: handle this better
		libxml_use_internal_errors(true);
		if ($isHTML) {
			$doc->loadHTML($html);
		} else {
			$doc->loadXML($html);
		}
		libxml_clear_errors();

		return new DOMXpath($doc);
	}

	/**
	 * @param DOMXpath $xpath
	 * @param string $query
	 * @param DOMNode $contextNode
	 * @return DOMNodeList
	 * @throws Exception
	 */
	protected function getXPathItems(DOMXpath $xpath, $query, DOMNode $contextNode = null)
	{
		$items = $xpath->query($query, $contextNode);
		if (is_null($items)) {
			throw new Exception('Path not found: ' . $query);
		}
		$this->logger->info('Открити ' . $items->length . ' ' . $this->categoryName);

		return $items;
	}
}