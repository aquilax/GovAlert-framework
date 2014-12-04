<?php

/*

0: новини http://www.prb.bg/main/bg/News/
1: документи http://www.prb.bg/main/bg/Documents/
2: конкурс http://www.prb.bg/main/bg/konkursi
3: галерия http://www.prb.bg/main/bg/gallery/

*/


class Prokuratura extends Task
{

	function prok_Novini()
	{
		echo "> Проверявам за новини в Прокуратурата\n";
		$this->setSession(13, 0);

		$html = $this->loadURL("http://www.prb.bg/main/bg/News/", 0);
		if (!$html) return;
		$items = $this->xpathDoc($html, "//div[@class='list-inner']");

		$query = array();
		foreach ($items as $item) {
			$hasimage = $item->childNodes->item(1)->nodeName == "a";

			$date = trim($item->childNodes->item(3 + ($hasimage ? 2 : 0))->textContent);
			$date = mb_substr($date, 6, 4) . "-" . mb_substr($date, 3, 2) . "-" . mb_substr($date, 0, 2);
			if (strtotime($date) < strtotime("-2 weeks"))
				continue;
			$description = trim($item->childNodes->item(5 + ($hasimage ? 2 : 0))->textContent);
			$description = $this->cleanText($description);

			$title = trim($item->childNodes->item(1 + ($hasimage ? 2 : 0))->textContent);
			$title = $this->cleanTitle($title);
			$title = $this->cleanText($title);

			$url = "http://www.prb.bg" . $item->childNodes->item(1)->firstChild->getAttribute("href");
			$hash = md5($url);
			$media = null;
			if ($hasimage) {
				$imageurl = $item->childNodes->item(1)->firstChild->getAttribute("src");
				$imageurl = mb_ereg_replace("logo", "big", $imageurl, "im");
				$imageurl = "http://www.prb.bg$imageurl";
				$imagetitle = trim($item->childNodes->item(3)->textContent);
				$imagetitle = $this->cleanTitle($imagetitle);
				$imagetitle = $this->cleanText($imagetitle);
				$media = array("image" => array($this->loadItemImage($imageurl, []), $imagetitle));
			}

			$query[] = array($title, $description, $date, $url, $hash, $media);
		}

		echo "Възможни " . count($query) . " нови новини\n";
		$itemids = $this->saveItems($query);
		$this->queueTweets($itemids);
	}

	function prok_Dokumenti()
	{
		echo "> Проверявам за документи в Прокуратурата\n";
		$this->setSession(13, 1);

		$html = $this->loadURL("http://www.prb.bg/main/bg/Documents/", 1);
		if (!$html) return;
		$items = $this->xpathDoc($html, "//div[@class='list-inner']");

		$query = array();
		foreach ($items as $item) {

			$date = trim($item->childNodes->item(3)->textContent);
			$date = mb_substr($date, 6, 4) . "-" . mb_substr($date, 3, 2) . "-" . mb_substr($date, 0, 2);
			if (strtotime($date) < strtotime("-2 months"))
				continue;
			$description = trim($item->childNodes->item(5)->textContent);
			$description = $this->cleanText($description);

			$title = trim($item->childNodes->item(1)->textContent);
			$title = $this->cleanTitle($title);
			$title = "Документ: " . $this->cleanText($title);

			$url = "http://www.prb.bg" . $item->childNodes->item(1)->firstChild->getAttribute("href");
			$hash = md5($url);

			$query[] = array($title, $description, $date, $url, $hash);
		}

		echo "Възможни " . count($query) . " нови документи\n";
		$itemids = $this->saveItems($query);
		$this->queueTweets($itemids);
	}

	function prok_Konkursi()
	{
		echo "> Проверявам за конкурси в Прокуратурата\n";
		$this->setSession(13, 2);

		$html = $this->loadURL("http://www.prb.bg/main/bg/konkursi", 2);
		if (!$html) return;
		$items = $this->xpathDoc($html, "//div[@class='list-inner']");

		$query = array();
		foreach ($items as $item) {

			$date = trim($item->childNodes->item(3)->textContent);
			$date = mb_substr($date, 6, 4) . "-" . mb_substr($date, 3, 2) . "-" . mb_substr($date, 0, 2);
			if (strtotime($date) < strtotime("-2 weeks"))
				continue;
			$description = trim($item->childNodes->item(5)->textContent);
			$description = $this->cleanText($description);

			$title = trim($item->childNodes->item(1)->textContent);
			$title = $this->cleanTitle($title);
			$title = "Конкурс: " . $title;
			// TODO: Figure this out
			$url = "http://www.prb.bg" . $item->childNodes->item(1 + ($hasimage ? 2 : 0))->firstChild->getAttribute("href");
			$hash = md5($url);

			$query[] = array($title, $description, $date, $url, $hash);
		}

		echo "Възможни " . count($query) . " нови конкурса\n";
		$itemids = $this->saveItems($query);
		$this->queueTweets($itemids);
	}

	function prok_Snimki()
	{
		echo "> Проверявам за галерии в Прокуратурата\n";
		$this->setSession(13, 3);

		$html = $this->loadURL("http://www.prb.bg/main/bg/gallery/", 3);
		if (!$html) return;
		$items = $this->xpathDoc($html, "//div[@class='list-inner']");

		$query = array();
		foreach ($items as $item) {
			$date = trim($item->childNodes->item(5)->textContent);
			$date = mb_substr($date, 6, 4) . "-" . mb_substr($date, 3, 2) . "-" . mb_substr($date, 0, 2);
			if (strtotime($date) < strtotime("-2 weeks"))
				continue;

			$title = trim($item->childNodes->item(3)->textContent);
			$title = $this->cleanTitle($title);
			$title = "Снимки: " . $title;
			$title = $this->cleanText($title);

			$url = "http://www.prb.bg" . $item->childNodes->item(1)->getAttribute("href");
			$hash = md5($url);
			$media = array("image" => array());
			$mhtml = $this->loadURL($url);
			if (!$mhtml)
				continue;

			$mitems = $this->xpathDoc($mhtml, "//a[@class='thumb']");
			foreach ($mitems as $mitem) {
				$imageurl = $mitem->getAttribute("href");
				$imageurl = "http://www.prb.bg$imageurl";
				$imageurl = str_replace(array("logo", "pic"), "big", $imageurl);
				$imageurl = $this->loadItemImage($imageurl, []);
				if ($imageurl)
					$media["image"][] = array($imageurl);
			}

			if (count($media["image"]) == 0)
				$media = null;

			$query[] = array($title, null, $date, $url, $hash, $media);
		}

		echo "Възможни " . count($query) . " нови галерии\n";
		$itemids = $this->saveItems($query);
		$this->queueTweets($itemids);
	}

	/*
	-----------------------------------------------------------------
	*/

	function xpathDoc($html, $q)
	{
		if (!$html)
			return array();
		$html = mb_convert_encoding($html, 'HTML-ENTITIES', "UTF-8");
		$doc = new DOMDocument("1.0", "UTF-8");
		$doc->preserveWhiteSpace = false;
		$doc->strictErrorChecking = false;
		$doc->encoding = 'UTF-8';
		$doc->loadHTML($html);
		$xpath = new DOMXpath($doc);

		$items = $xpath->query($q);
		return is_null($items) ? array() : $items;
	}


	function cleanTitle($title)
	{
		if (mb_substr($title, -1) == ".")
			$title = mb_substr($title, 0, mb_strlen($title) - 1);
		$title = mb_ereg_replace("Република България", "РБ", $title, "im");
		$title = mb_ereg_replace("Р България", "РБ", $title, "im");
		$title = mb_ereg_replace("„|“", "", $title, "im");
		$title = mb_ereg_replace("Народно(то)? събрание", "НС", $title, "im");
		$title = mb_ereg_replace("Министерски(ят)? съвет", "МС", $title, "im");
		$title = mb_ereg_replace("(ИЗБИРАТЕЛНИ КОМИСИИ)|(избирателна комисия)", "ИК", $title, "im");
		$title = mb_ereg_replace("ОБЯВЛЕНИЕОТНОСНО:?|ОТНОСНО:?|С Ъ О Б Щ Е Н И Е|СЪОБЩЕНИЕ|г\.|ч\.|\\\\|„|\"|'", "", $title, "im");
		return $title;
	}

	function cleanText($text)
	{
		$text = Utils::cleanSpaces($text);
		$text = html_entity_decode($text);
		return $text;
	}
}

