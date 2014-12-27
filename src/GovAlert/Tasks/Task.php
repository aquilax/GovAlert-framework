<?php

/**
 * Class Task - Generic Task class
 */
namespace GovAlert\Tasks;

use GovAlert\Common\Images;
use GovAlert\Common\Utils;
use GovAlert\Config;


abstract class Task
{

	protected $db;
	protected $logger;
	protected $loader;
	protected $debug = false;
	protected $sourceId = null;
	protected $sourceName = null;
	protected $categoryId = null;
	protected $categoryName = null;
	protected $categoryURL = null;
	protected $error = false;
	protected $tweetAccount = 'govaleteu';
	protected $tweetReTweet = null;
	protected $linki = false;


	abstract protected function execute($html);

	public function __construct(\GovAlert\Common\Database $db,
								\GovAlert\Common\Logger $logger,
								\GovAlert\Common\Loader $loader,
								\GovAlert\Common\Processor $processor,
								$debug = false)
	{
		$this->db = $db;
		$this->logger = $logger;
		$this->loader = $loader;
		$this->processor = $processor;
		$this->debug = $debug;
	}

	public function run()
	{
		$this->logger->info(sprintf('Проверявам за %s %s', $this->sourceName, $this->categoryName));
		$html = 'placeholder';
		if (is_string($this->categoryURL)) {
			$html = $this->loadURL($this->categoryURL, $this->linki);
		}
		if ($html) {
			$items = $this->execute($html);
			if ($items) {
				$this->processItems($items);
			}
		}
	}

	protected function loadURL($url, $linki = null)
	{
		return $this->loader->loadURL($this->sourceId, $this->categoryId, $url, $linki);
	}

	protected function processItems(Array $query)
	{
		$this->logger->info('Възможни ' . count($query) . ' нови ' . $this->categoryName);
		$itemIds = $this->saveItems($query, $this);
		$this->queueTweets($itemIds, $this->tweetAccount, $this->tweetReTweet);
	}

	protected function saveItems(Array $query)
	{
		return $this->processor->saveItems($query, $this);
	}

	protected function cleanText($text)
	{
		$text = html_entity_decode($text);
		$text = Utils::cleanSpaces($text);
		$text = Utils::fixCase($text);
		return $text;
	}

	function checkHash($hash)
	{
		return $this->processor->checkHash($hash);
	}

	function checkTitle($title)
	{
		if (!$this->checkSession()) {
			return true;
		}
		return $this->processor->checkTitle($title, $this->sourceId);
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

	private function setPageLoad($url, $loadStart)
	{
		$this->loader->setPageLoad($this->sourceId, $this->categoryId, $url, $loadStart);
	}

	function loadItemImage($url, $type = null, $options = [])
	{
		if ($type === null) {
			$type = ".jpg";
		} else if (substr($type, 0, 1) != ".")
			$type = ".$type";

		if (strtolower($type) != ".jpg" && strtolower($type) != ".jpeg" && strtolower($type) != ".gif" && strtolower($type) != ".png" && strtolower($type) != ".bmp")
			return null;

		$filename = Config::get('mediaPath') . 'item_images/' . md5($url) . ($type == ".bmp" ? ".jpg" : $type);
		if (!file_exists($filename)) {
			$loadstart = microtime(true);
			exec("wget --header='Connection: keep-alive' --header='Cache-Control: max-age=0' --header='Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8' --header='User-Agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/33.0.1750.152 Safari/537.36' --header='Accept-Encoding: gzip,deflate,sdch' --header='Accept-Language: en-US,en;q=0.8,bg;q=0.6,de;q=0.4' -q -O '$filename' '$url'");
			$this->setPageLoad($url, $loadstart);
			if (filesize($filename) >= 1.5 * 1024 * 1024) {
				Images::resizeItemImage($filename, $type);
			} else {
				Images::fitinItemImage($filename, $type, $options);
			}
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

		$this->logger->info('Планирам tweet за srcid=' . $this->sourceId . ' текст=' . $text . ' и адреси ' . implode(', ', $urls));

		$position = 1;
		foreach ($urls as $url) {
			$res = $this->db->query("SELECT linkid FROM link WHERE url='$url'");
			if ($res->num_rows > 0) {
				$row = $res->fetch_assoc();
				$linkid = intval($row['linkid']);
			} else {
				$this->db->insert('link', ['url' => $url], 'LOW_PRIORITY');
				$linkid = $this->db->insert_id;
			}
			if (!$linkid)
				return;

			$urltext = "http://GovAlert.eu/-" . Utils::linkCode($linkid);
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
			'queued' => $this->db->now(),
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
				'queued' => $this->db->now(),
				'retweet' => $retweet
			];
		}
		$this->db->batchInsert('tweet', $query);
	}

	public function getError()
	{
		return $this->error;
	}

	/**
	 * @param $html
	 * @param string $encoding
	 * @return \DOMXpath
	 * @throws \Exception
	 */
	protected function getXPath($html, $encoding = 'UTF-8', $isHTML = true)
	{
		if (!trim($html)) {
			throw new \Exception('Empty document');
		}
		$html = mb_convert_encoding($html, 'HTML-ENTITIES', $encoding);
		$doc = new \DOMDocument('1.0', $encoding);
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

		return new \DOMXpath($doc);
	}

	/**
	 * @param DOMXpath $xpath
	 * @param string $query
	 * @param DOMNode $contextNode
	 * @return DOMNodeList
	 * @throws Exception
	 */
	protected function getXPathItems(\DOMXpath $xpath, $query, \DOMNode $contextNode = null, $silent = false)
	{
		$items = $xpath->query($query, $contextNode);
		if (is_null($items)) {
			throw new \Exception('Path not found: ' . $query);
		}
		if (!$silent) {
			$this->logger->info('Открити ' . $items->length . ' ' . $this->categoryName);
		}

		return $items;
	}

	function timeDiff($diff)
	{
		return strtotime(date('c', $this->db->time()) . ' ' . $diff);
	}

	/**
	 * @return null
	 */
	public function getCategoryId()
	{
		return $this->categoryId;
	}

	/**
	 * @return null
	 */
	public function getSourceId()
	{
		return $this->sourceId;
	}

	/**
	 * @return string
	 */
	public function getTweetAccount()
	{
		return $this->tweetAccount;
	}

	/**
	 * @return null
	 */
	public function getSourceName()
	{
		return $this->sourceName;
	}

	/**
	 * @return null
	 */
	public function getTweetReTweet()
	{
		return $this->tweetReTweet;
	}
}