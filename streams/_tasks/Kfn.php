<?php

/*
links:
0: новини http://www.fsc.bg/Novini-bg
2: анализи http://www.fsc.bg/Analizi-na-KFN-bg-29
*/

class Kfn extends Task {

	function kfn_Novini() {
		$this->setSession(22,0);

		echo "> Проверявам за новини в КФН\n";

		$html = $this->loadURL("http://www.fsc.bg/Novini-bg",0);
		if (!$html) return;
		$items = $this->xpathDoc($html,"//div[@class='s_news_listing']//h3/a");

		$query=array();
		foreach ($items as $item) {
			if (count($query)>10)
				break;
			$url = "http://www.fsc.bg".$item->getAttribute("href");
			$hash = md5($url);

			$title = $item->textContent;
			$title = $this->cleanText($title);
			$query[]=array($title,null,'now',$url,$hash);
		}

		echo "Възможни ".count($query)." нови новини\n";

		$itemids = $this->saveItems($query);
		queueTweets($itemids);
	}

	function kfn_Analizi() {
		$this->setSession(22,1);

		echo "> Проверявам за анализи в КФН\n";

		$html = $this->loadURL("http://www.fsc.bg/Analizi-na-KFN-bg-29",1);
		if (!$html) return;
		$items = $this->xpathDoc($html,"//div[@id='page_29_files']//li/a");

		$query=array();
		foreach ($items as $item) {
			if (count($query)>10)
				break;
			$url = "http://www.fsc.bg".$item->getAttribute("href");
			$hash = md5($url);

			$title = $item->childNodes->length>0? $item->firstChild->textContent : $item->textContent;
			$title = "Анализ: ".$this->cleanText($title);
			$query[]=array($title,null,'now',$url,$hash);
		}

		echo "Възможни ".count($query)." нови анализи\n";

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
		$text = str_replace(" "," ",$text);
		$text = mb_ereg_replace("[\n\r\t ]+"," ",$text);
		$text = mb_ereg_replace("(^\s+)|(\s+$)", "", $text);
		$text = html_entity_decode($text);
		return $text;
	}

} 