<?php

class MvrBurgas extends Mvr
{

	protected $channelPrefix = '[Бургас] ';
	protected $sourceName = 'Бургас';
	protected $channelName = 'новини';
	protected $channelId = 4;
	protected $channelURL = 'http://www.rdvr-burgas.org/Bul/Suobshtenie/Realno.htm';
	protected $channelURLBase = '';
	protected $channelReTweet = false;
	protected $channelExpectEmpty = false;

	function execute($html)
	{
		$html = mb_convert_encoding($html, 'UTF-8', 'cp1251');
		$xpath = $this->xpath($html);
		$items = $xpath ? $xpath->query("//table[@id='AutoNumber1']//td[1]//p") : false;
		if (!$items || $items->length == 0) {
			$this->reportError("Грешка при зареждане на отделно съобщение");
			return;
		}

		echo "Открити " . $items->length . " параграфа\n";

		$skip = true;
		$query = array();
		foreach ($items as $item) {
			$fulltext = Utils::cleanSpaces($item->textContent);
			$item_1 = $xpath->query(".//img", $item);
			if ($skip || ($fulltext == "" && $item_1->length == 0)) {
				if ("СЪОБЩЕНИЕ" == $fulltext)
					$skip = false;
			} else
				if (mb_substr($fulltext, -7) == date("Y") . " г." && $item_1->length == 0) {
					$date = substr($fulltext, 6, 4) . "-" . substr($fulltext, 3, 2) . "-" . substr($fulltext, 0, 2);
					if (strtotime($date) < time() - 3600 * 24 * 5)
						break;
					$query[] = array($this->channelPrefix, "", $date, $this->channelURL, null, null);
				} else
					if (count($query) > 0) {
						if (mb_strlen($query[count($query) - 1][0]) < 100) {
							$title = $fulltext;
							$title = Utils::fixCase($title);
							if (mb_strrpos($title, ".") > 120) {
								$stoppos = 0;
								while (($stoppos = mb_strpos($title, ".", $stoppos + 1)) <= 120) ;
								$title = mb_substr($title, 0, $stoppos);
							}
							$query[count($query) - 1][0] .= (mb_strlen($query[count($query) - 1][0]) != 0 ? " " : "") . $title;
						}

						$description = $item->C14N();
						$description = mb_ereg_replace(" </", "</", mb_ereg_replace("> ", ">", $description));
						$description = mb_ereg_replace("\s?(title|name|style|class|id|alt|target|align|dir|lang)=[\"'].*?[\"']\s?", " ", $description);
						$description = mb_ereg_replace("<p>[  ]*</p>|<br>[  ]*</br>|<a>[  ]*</a>|<div>[  ]*</div>", " ", $description);
						$description = Utils::cleanSpaces($description);
						$description = mb_ereg_replace(" >", ">", $description);
						$description = mb_ereg_replace("</?span>|&#xD;", "", $description);
						$description = Utils::cleanSpaces($description);
						$description = mb_ereg_replace("> <", "><", $description);
						$query[count($query) - 1][1] .= $description;

						if ($query[count($query) - 1][4] == null)
							$query[count($query) - 1][4] = md5($fulltext);
// TODO: FIXME
						foreach ($item_1 as $itemimg) {
							$imageurl = "http://www.rdvr-burgas.org/Bul/Suobshtenie/" . $itemimg->getAttribute("src");
							$imageurl = $this->loadItemImage($imageurl, []);
							if ($imageurl) {
								if ($query[count($query) - 1][5] == null)
									$query[count($query) - 1][5] = array("image" => array());
								$query[count($query) - 1][5]["image"][] = array($imageurl);
							}
						}
					}
		}

		echo "Възможни " . count($query) . " нови новини\n";

		$itemids = $this->saveItems($query);
		$this->queueTweets($itemids, "mibulgaria");
	}

} 