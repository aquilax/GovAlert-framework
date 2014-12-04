<?php

/*

0: съобщения http://www.cik.bg/
1: решения http://www.cik.bg/reshenie
2: дневен ред http://www.cik.bg/406
3: протоколи http://www.cik.bg/405
4: жалби http://www.cik.bg/jalbi
5: принципни решения http://www.cik.bg/reshenie_principni

*/

class Cik extends Task
{

	function cikSaobshteniq()
	{
		$this->logger->info('> Проверявам за ЦИК съобщения');
		$this->setSession(1, 0);

		$html = $this->loadURL('http://www.cik.bg/', 0);
		if (!$html) {
			return;
		}
		$items = $this->xpathDoc($html, "//div[@class='item']");

		$query = [];
		foreach ($items as $item) {
			$hash = md5($item->textContent);
			$date = trim($item->childNodes->item(1)->textContent);
			$date = mb_substr($date, 6, 4) . "-" . mb_substr($date, 3, 2) . "-" . mb_substr($date, 0, 2);
			if (strtotime($date) < strtotime("-1 month"))
				continue;
			$item->removeAttribute("class");
			$item->removeChild($item->childNodes->item(1));
			$item->removeChild($item->childNodes->item(0));
			$description = $item->C14N();
			$description = mb_ereg_replace(" </", "</", mb_ereg_replace("> ", ">", $description));
			$description = mb_ereg_replace("\s?(title|name|style|class|id)=[\"'].*?[\"']\s?", "", $description);
			$description = mb_ereg_replace("<p>[  ]*</p>|<a>[  ]*</a>|<div>[  ]*</div>", "", $description);
			$description = $this->cleanText($description);
			$title = $item->textContent;
			$title = $this->cleanTitle($title);
			$title = "Съобщение: " . $this->cleanText($title);
			$query[] = array($title, $description, $date, 'http://www.cik.bg/', $hash);
		}

		$this->logger->info('Възможни ' . count($query) . ' нови съобщения');

		$itemIds = $this->saveItems($query);
		$this->queueTweets($itemIds);
	}

	function cikResheniq()
	{
		$this->logger->info('> Проверявам за ЦИК решения');
		$this->setSession(1, 1);

		$html = $this->loadURL("http://www.cik.bg/reshenie", 1);
		if (!$html) return;
		$items = $this->xpathDoc($html, "//div[@class='block main-block']//li");

		$query = array();
		foreach ($items as $item) {
			$hash = md5($item->childNodes->item(0)->textContent);
			$date = $item->childNodes->item(0)->textContent;
			$date = mb_substr($date, mb_strpos($date, "/ ") + 2);
			$date = substr($date, 6, 4) . "-" . substr($date, 3, 2) . "-" . substr($date, 0, 2);
			if (strtotime($date) < strtotime("-1 month"))
				continue;
			$description = $item->childNodes->item(2)->textContent;
			$description = mb_ereg_replace("ОТНОСНО:? ?", "", $description, "im");
			$description = $this->cleanText($description);
			$title = $item->childNodes->item(0)->textContent;
			$title = $this->cleanTitle($title);
			$title = $this->cleanText($title);
			$title = $title . " - " . $description;
			$url = $item->childNodes->item(0)->getAttribute("href");
			$query[] = array($title, $description, $date, "http://www.cik.bg$url", $hash);
		}

		echo "Възможни " . count($query) . " нови решения\n";

		$itemids = $this->saveItems($query);
		if (count($itemids) <= 5)
			$this->queueTweets($itemids);
		else
			$this->queueTextTweet("Преди минути са публикувани " . count($itemids) . " нови решения ", "http://www.cik.bg/reshenie");
	}

	function cikDnevenRed()
	{
		echo "> Проверявам за ЦИК дневен ред\n";
		$this->setSession(1, 2);

		$html = $this->loadURL("http://www.cik.bg/406", 2);
		if (!$html) return;
		$items = $this->xpathDoc($html, "//div[@class='block main-block']//li");

		$query = array();
		foreach ($items as $item) {
			$hash = md5($item->childNodes->item(0)->textContent);
			$date = $item->childNodes->item(0)->textContent;
			$date = mb_substr($date, mb_strpos($date, "/ ") + 2);
			$date = substr($date, 6, 4) . "-" . substr($date, 3, 2) . "-" . substr($date, 0, 2);
			if (strtotime($date) < strtotime("-1 month"))
				continue;
			$title = $item->childNodes->item(0)->textContent;
			$title = $this->cleanText($title);
			$title = mb_ereg_replace("/", "за", $title, "im");
			$url = $item->childNodes->item(0)->getAttribute("href");
			$query[] = array($title, null, null, "http://www.cik.bg$url", $hash);
		}

		echo "Възможни " . count($query) . " нови записа за дневен ред\n";

		$itemids = $this->saveItems($query);
		$this->queueTweets($itemids);
	}

	function cikProtokol()
	{
		echo "> Проверявам за ЦИК протоколи\n";
		$this->setSession(1, 3);

		$html = $this->loadURL("http://www.cik.bg/405", 3);
		$items = $this->xpathDoc($html, "//div[@class='block main-block']//li");

		$query = array();
		foreach ($items as $item) {
			$hash = md5($item->childNodes->item(0)->textContent);
			$title = $item->childNodes->item(0)->textContent;
			$title = $this->cleanText($title);
			$title = mb_ereg_replace("/", "за", $title, "im");
			$url = $item->childNodes->item(0)->getAttribute("href");
			$query[] = array($title, null, null, "http://www.cik.bg$url", $hash);
		}

		echo "Възможни " . count($query) . " нови протокола\n";

		$itemids = $this->saveItems($query);
		$this->queueTweets($itemids);
	}

	function cikJalbi()
	{
		echo "> Проверявам за ЦИК жалби\n";
		$this->setSession(1, 4);

		$html = $this->loadURL("http://www.cik.bg/jalbi", 4);
		if (!$html) return;
		$items = $this->xpathDoc($html, "//div[@class='block main-block']//td/a");

		$query = array();
		foreach ($items as $item) {
			$hash = md5($item->textContent);
			$title = $item->textContent;
			$title = $this->cleanText($title);
			$url = $item->getAttribute("href");
			if (mb_strpos($url, "http") === false)
				$url = "http://www.cik.bg$url";
			$query[] = array($title, null, null, $url, $hash);
		}

		echo "Възможни " . count($query) . " нови жалби\n";

		$itemids = $this->saveItems($query);
		if (count($itemids) <= 5)
			$this->queueTweets($itemids);
		else
			$this->queueTextTweet("Публикувани са " . count($itemids) . " нови документа във връзка с жалби", "http://www.cik.bg/reshenie");
	}

	function cikPrincipniResheniq()
	{
		echo "> Проверявам за ЦИК принципни решения\n";
		$this->setSession(1, 5);

		$html = $this->loadURL("http://www.cik.bg/reshenie_principni", 5);
		if (!$html) return;
		$items = $this->xpathDoc($html, "//div[@class='block main-block']//li");

		$query = array();
		foreach ($items as $item) {
			$hash = md5($item->childNodes->item(0)->textContent);
			$date = trim($item->childNodes->item(0)->textContent);
			$date = mb_substr($date, mb_strpos($date, "/ ") + 2);
			$date = substr($date, 6, 4) . "-" . substr($date, 3, 2) . "-" . substr($date, 0, 2);
			if (strtotime($date) < strtotime("-1 month"))
				continue;
			$description = $item->childNodes->item(2)->textContent;
			$description = mb_ereg_replace("ОТНОСНО:? ?", "", $description, "im");
			$description = $this->cleanText($description);
			$title = $item->childNodes->item(0)->textContent;
			$title = $this->cleanTitle($title);
			$title = $this->cleanText($title);
			$title = $title . " - " . $description;
			$url = $item->childNodes->item(0)->getAttribute("href");
			$query[] = array($title, $description, $date, "http://www.cik.bg$url", $hash);
		}

		echo "Възможни " . count($query) . " нови принципни решения\n";

		$itemids = $this->saveItems($query);
		$this->queueTweets($itemids);
	}

	/*
	-----------------------------------------------------------------
	*/

	private function xpathDoc($html, $q)
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


	private function cleanTitle($title)
	{
		if (mb_substr($title, -1) == ".") {
			$title = mb_substr($title, 0, mb_strlen($title) - 1);
		}
		$title = mb_ereg_replace("Централната избирателна комисия", "ЦИК", $title, "im");
		$title = mb_ereg_replace("Република България", "РБ", $title, "im");
		$title = mb_ereg_replace("Народно(то)? събрание", "НС", $title, "im");
		$title = mb_ereg_replace("Министерски(ят)? съвет", "МС", $title, "im");
		$title = mb_ereg_replace("(ИЗБИРАТЕЛНИ КОМИСИИ)|(избирателна комисия)", "ИК", $title, "im");
		$title = mb_ereg_replace("№ ", "№", $title, "im");
		$title = mb_ereg_replace(" ?/ ?", "/", $title, "im");
		$title = mb_ereg_replace("ОБЯВЛЕНИЕОТНОСНО:?|ОТНОСНО:?|С Ъ О Б Щ Е Н И Е|СЪОБЩЕНИЕ|г\.|ч\.|\\\\|„|\"|'", "", $title, "im");
		return $title;
	}

	private function cleanText($text)
	{
		$text = Utils::cleanSpaces($text);
		$text = html_entity_decode($text);
		return $text;
	}

}
