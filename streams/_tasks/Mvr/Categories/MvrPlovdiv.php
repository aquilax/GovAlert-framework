<?php

class MvrPlovdiv extends Mvr
{

	protected $channelPrefix = '[Пловдив] ';
	protected $sourceName = 'Пловдив';
	protected $channelName = 'новини';
	protected $channelId = 28;
	protected $channelURL = 'http://plovdiv.mvr.bg/news.php';
	protected $channelURLBase = '';
	protected $channelReTweet = false;
	protected $channelExpectEmpty = false;


	function execute($html)
	{
		$html = mb_convert_encoding($html, 'UTF-8', 'cp1251');
		$xpath = $this->xpath($html);
		$items = $xpath ? $xpath->query("//td[@nowrap='nowrap']") : false;
		if (!$items || $items->length == 0) {
			$this->reportError("Грешка при зареждане на отделно съобщение");
			return;
		}

		echo "Открити " . $items->length . " новини\n";

		$query = array();
		foreach ($items as $item) {
			$date = $item->childNodes->item(0)->textContent;
			$date = substr($date, 6, 4) . "-" . substr($date, 3, 2) . "-" . substr($date, 0, 2);

			$title = $item->childNodes->item(2)->textContent;
			$title = Utils::cleanSpaces($title);
			$title = Utils::fixCase($title);
			if (mb_strrpos($title, ".") > 120) {
				$stoppos = 0;
				while (($stoppos = mb_strpos($title, ".", $stoppos + 1)) <= 120) ;
				$title = mb_substr($title, 0, $stoppos);
			}
			$title = $this->channelPrefix . $title;
			if (!$this->checkTitle($title))
				continue;

			$hash = md5($title);

			$description = $item->childNodes->item(2)->C14N();
			$description = mb_ereg_replace(" </", "</", mb_ereg_replace("> ", ">", $description));
			$description = mb_ereg_replace("\s?(title|name|style|class|id|alt|target|align|dir|lang)=[\"'].*?[\"']\s?", " ", $description);
			$description = mb_ereg_replace("<p>[  ]*</p>|<br>[  ]*</br>|<a>[  ]*</a>|<div>[  ]*</div>", " ", $description);
			$description = mb_ereg_replace(" >", ">", $description);
			$description = Utils::cleanSpaces($description);

			$query[] = [
				'title' => $title,
				'description' => $description,
				'date' => $date,
				'url' => $this->channelURL,
				'hash' => $hash,
			];
		}

		echo "Възможни " . count($query) . " нови новини\n";

		$itemids = $this->saveItems($query);
		$this->queueTweets($itemids, "mibulgaria");
	}

} 