<?php

class VssNovini extends Vss
{

	protected $categoryId = 2;
	protected $categoryName = 'новини';
	protected $categoryURL = '';

	function __construct(Database $db, Logger $logger, $debug = false)
	{
		parent::__construct($db, $logger, $debug);
		$this->categoryURL = 'http://www.vss.justice.bg/bg/press/' . date('Y') . '/' . date('Y') . '.htm';
	}

	function execute($html)
	{
		$baseurl = dirname($this->categoryURL);
		$xpath = $this->xpath($html);
		if (!$xpath) return;
		$items = $xpath->query("//td/div");

		$query = array();
		foreach ($items as $item) {
			$date = trim($item->childNodes->item(1)->textContent);
			$date = $this->cleanText($date);
			$date = Utils::bgMonth($date);
			$date = mb_substr($date, 6, 4) . "-" . mb_substr($date, 3, 2) . "-" . mb_substr($date, 0, 2);
			if (strtotime($date) < strtotime("-1 week"))
				continue;

			$item->removeChild($item->childNodes->item(1));
			$title = $item->textContent;
			$title = mb_ereg_replace("“|„|”", "", $title);
			$title = $this->cleanText($title);

			if (mb_strpos($title, 'юлетин за дейността') !== false) {
				$links = $xpath->query(".//a", $item);
				if ($links->length > 0) {
					$url = $baseurl . $links->item(0)->getAttribute("href");
					$hash = md5($url);
					$query[] = [
						'title' => $title,
						'description' => null,
						'date' => $date,
						'url' => $url,
						'hash' => $hash,
					];
					continue;
				}
			}

			$hash = md5($this->categoryURL . $title);
			if (!$this->checkHash($hash))
				continue;

			$description = trim($item->C14N());
			$description = mb_ereg_replace(" </", "</", mb_ereg_replace("> ", ">", $description));
			$description = mb_ereg_replace("\s?(title|name|style|class|id|face|align|img)=[\"'].*?[\"']\s?", "", $description);
			$description = mb_ereg_replace("<p>[  ]*</p>|<a>[  ]*</a>|<div>[  ]*</div>", "", $description);
			$description = Utils::cleanSpaces($description);
			$description = html_entity_decode($description);

			$media = array("image" => array());
			$imgs = $xpath->query(".//img[not(src)]", $item);
			if ($imgs->length > 0) {
				$name = $imgs->item(0)->getAttribute("name");
				$name = str_replace("show", "images", $name);
				if (mb_strpos($html, "$name=new Array(") !== false) {
					$medialiststart = mb_strpos($html, "$name=new Array(") + mb_strlen("$name=new Array(");
					$medialist = mb_substr($html, $medialiststart, mb_strpos($html, ");", $medialiststart) - $medialiststart);
					$medialist = explode(",", str_replace(array('"', "'"), "", $medialist));
					foreach ($medialist as $mediafile) {
						$imageurl = $this->loadItemImage($baseurl . $mediafile, []);
						if ($imageurl)
							$media["image"][] = array($imageurl);
					}
				}
			}
			if (count($media["image"]) == 0)
				$media = null;

			$query[] = [
				'title' => $title,
				'description' => $description,
				'date' => $date,
				'url' => $this->categoryURL,
				'hash' => $hash,
			];

		}
		echo "Възможни " . count($query) . " нови новини\n";

		$itemids = $this->saveItems($query);
		$this->queueTweets($itemids);
	}

} 