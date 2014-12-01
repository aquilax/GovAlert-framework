<?php

/*

0: конкурси http://ar2.government.bg/ras/konkursi/index.html

*/

class Adminreg extends Task{

	public function arKonkursi() {
		$this->logger->info('> Проверявам за конкурси в АдминРег');
		$this->setSession(16,0);

		$html = loadURL("http://ar2.government.bg/ras/konkursi/index.html");
		if (!$html) return;
		$xpath = $this->xpath($html);
		if (!$xpath) {
			$this->db->reportError("Грешка при зареждане на страницата");
			return;
		}
		$items = $xpath->query("//a[contains(@href,'goToPage')]");
		if (!$items || $items->length==0){
			$this->db->reportError("Грешка при четене на страницата");
			return;
		}
		$pages = intval($items->item($items->length-2)->textContent);

		$query = [];
		for ($i=1; $i<=$pages; $i++) {
			if ($i > 1) {
				$html = loadURL("http://ar2.government.bg/ras/konkursi/index.html?current_page=$i&regTabs=5&menuTab=10&TypeStruct=");
				if (!$html) {
					return;
				}
				$xpath = $this->xpath($html);
				if (!$xpath) {
					$this->db->reportError("Грешка при зареждане на страница $i");
					return;
				}
			}

			$items = $xpath->query("//tr[@onclick]");
			foreach ($items as $item) {
				$url = $item->firstChild->firstChild->getAttribute("onclick");
				$urlPos = mb_strpos($url, "openWin('") + 9;
				$url = mb_substr($url, $urlPos, mb_strpos($url, "'",$urlPos) - $urlPos);
				$url = "http://ar2.government.bg/ras/konkursi/$url";

				$hash=md5($url);

				$title = "Конкурс (срок ".$item->childNodes->item(3)->textContent."): ";
				$title .= $item->childNodes->item(0)->textContent;
				$title .= " в ".$item->childNodes->item(2)->textContent;
				$title .= ", ".$item->childNodes->item(1)->textContent;
				$description = $title;
				$title = mb_ereg_replace("Дирекция:|Сектор:|Отдел:|Агенция:","",$title,"im");
				$title = Utils::cleanSpaces($title);
				$description = Utils::cleanSpaces($description);

				$query[]=array($title, $description, 'now', $url, $hash);
			}
		}

		echo "Възможни ".count($query)." нови конкурси\n";
		$itemIds = saveItems($query);

		if (count($itemIds) <= 3) {
			queueTweets($itemIds);
		} else {
			$pageNum=floor((count($query) - count($itemIds)) / 20) + 1;
			if ($pageNum < 1 || $pageNum > $pages) {
				$pageNum=$pages;
			}
			queueTextTweet("Публикувани са ".count($itemIds)." конкурса за свободни позиции в държавната администрация.","http://ar2.government.bg/ras/konkursi/index.html?current_page=$pageNum&regTabs=5&menuTab=10&TypeStruct=");
		}
	}

	/*
	-----------------------------------------------------------------
	*/


	private function xpath($html) {
		if (!$html)
			return null;
		$html = mb_convert_encoding($html, 'HTML-ENTITIES', "cp1251");
		$doc = new DOMDocument("1.0", "cp1251");
		$doc->preserveWhiteSpace=false;
		$doc->strictErrorChecking=false;
		$doc->encoding = 'UTF-8';
		$doc->loadHTML($html);
		return new DOMXpath($doc);
	}

}
