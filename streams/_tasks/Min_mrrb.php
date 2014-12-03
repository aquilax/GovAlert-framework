<?php

/*

0: обяви http://www.mrrb.government.bg/?controller=category&action=notice&catid=38
1: полезна информация http://www.mrrb.government.bg/?controller=category&catid=39

*/

class Min_mrrb extends Task{

	function mrrb_Obqvi() {
		echo "> Проверявам за обяви в МРРБ\n";
		$this->setSession(10,0);

		$html = $this->loadURL("http://www.mrrb.government.bg/?controller=category&action=notice&catid=38",0);
		if (!$html) return;
		$items = $this->xpathDoc($html,"//div[@class='listCategoryArticles']");

		$query=array();
		foreach ($items as $item) {
			$date = Utils::bgMonth(trim($item->childNodes->item(2)->textContent));
			$date = mb_substr($date,6,4)."-".mb_substr($date,3,2)."-".mb_substr($date,0,2);
			if (strtotime($date)<strtotime("-1 month"))
				continue;
			$title = $item->childNodes->item(4)->textContent;
			$title = "Обява: ".$this->cleanText($title);
			$url = "http://www.mrrb.government.bg/".$item->childNodes->item(4)->getAttribute("href");
			$hash = md5($url);
			$query[]=array($title,null,$date,$url,$hash);
		}

		echo "Възможни ".count($query)." нови обяви\n";

		$itemids = $this->saveItems($query);
		queueTweets($itemids);
	}

	function mrrb_Informaciq() {
		echo "> Проверявам за полезна информация в МРРБ\n";
		$this->setSession(10,1);

		$html = $this->loadURL("http://www.mrrb.government.bg/?controller=category&catid=39",1);
		if (!$html) return;
		$items = $this->xpathDoc($html,"//div[@class='listCategoryArticles']");

		$query=array();
		foreach ($items as $item) {
			$title = $item->childNodes->item(2)->textContent;
			$title = "Информация: ".$this->cleanText($title);
			$url = "http://www.mrrb.government.bg/".$item->childNodes->item(2)->getAttribute("href");
			$hash = md5($url);
			$query[]=array($title,null,null,$url,$hash);
		}

		echo "Възможни ".count($query)." нови съобщения за полезна информация\n";
		$itemids = $this->saveItems($query);
		queueTweets($itemids);
	}

	/*
	-----------------------------------------------------------------
	*/

	function xpathDoc($html,$q) {
		if (!$html)
			return array();
		$html = mb_convert_encoding($html, 'HTML-ENTITIES', "UTF-8");
		$doc = new DOMDocument("1.0", "UTF-8");
		$doc->preserveWhiteSpace=false;
		$doc->strictErrorChecking=false;
		$doc->encoding = 'UTF-8';
		$doc->loadHTML($html);
		$xpath = new DOMXpath($doc);

		$items = $xpath->query($q);
		return is_null($items)?array():$items;
	}

	function cleanText($text) {
		$text = html_entity_decode($text);
		$text = Utils::cleanSpaces($text);
		$text = Utils::fixCase($text);
		return $text;
	}

} 