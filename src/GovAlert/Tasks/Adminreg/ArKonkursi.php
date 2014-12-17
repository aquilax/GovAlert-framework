<?php

/*

0: конкурси http://ar2.government.bg/ras/konkursi/index.html

*/

namespace GovAlert\Tasks\Adminreg;
use \GovAlert\Common\Utils;
use \GovAlert\Common\Database;

class ArKonkursi extends Base
{

	protected $categoryId = 0;
	protected $categoryName = 'конкурси';
	protected $categoryURL = 'http://ar2.government.bg/ras/konkursi/index.html';
	private $pages = 0;

	protected function execute($html)
	{
		$xpath = $this->getXPath($html, 'cp1251');
		$items = $xpath->query("//a[contains(@href,'goToPage')]");
		if (!$items || $items->length == 0) {
			$this->reportError("Грешка при четене на страницата");
			return;
		}
		$this->pages = intval($items->item($items->length - 2)->textContent);

		$query = [];
		for ($i = 1; $i <= $this->pages; $i++) {
			if ($i > 1) {
				$html = $this->loadURL("http://ar2.government.bg/ras/konkursi/index.html?current_page=$i&regTabs=5&menuTab=10&TypeStruct=");
				if (!$html) {
					return false;
				}
				$xpath = $this->getXPath($html, 'cp1251');
				if (!$xpath) {
					$this->reportError("Грешка при зареждане на страница $i");
					return false;
				}
			}

			$items = $xpath->query("//tr[@onclick]");
			foreach ($items as $item) {
				$url = $item->firstChild->firstChild->getAttribute("onclick");
				$urlPos = mb_strpos($url, "openWin('") + 9;
				$url = mb_substr($url, $urlPos, mb_strpos($url, "'", $urlPos) - $urlPos);
				$url = "http://ar2.government.bg/ras/konkursi/$url";

				$hash = md5($url);

				$title = "Конкурс (срок " . $item->childNodes->item(3)->textContent . "): ";
				$title .= $item->childNodes->item(0)->textContent;
				$title .= " в " . $item->childNodes->item(2)->textContent;
				$title .= ", " . $item->childNodes->item(1)->textContent;
				$description = $title;
				$title = mb_ereg_replace("Дирекция:|Сектор:|Отдел:|Агенция:", "", $title, "im");
				$title = Utils::cleanSpaces($title);
				$description = Utils::cleanSpaces($description);

				$query[] = [
					'title' => $title,
					'description' => $description,
					'date' => Database::Now(),
					'url' => $url,
					'hash' => $hash
				];
			}
		}
		return $query;
	}

	protected function processItems(Array $items) {
		$this->logger->info('Възможни ' . count($items) . ' нови ' . $this->categoryName);
		$itemIds = $this->saveItems($items);

		if (count($itemIds) <= 3) {
			$this->queueTweets($itemIds);
		} else {
			$pageNum = floor((count($items) - count($itemIds)) / 20) + 1;
			if ($pageNum < 1 || $pageNum > $this->pages) {
				$pageNum = $this->pages;
			}
			$this->queueTextTweet("Публикувани са " . count($itemIds) . " конкурса за свободни позиции в държавната администрация.", "http://ar2.government.bg/ras/konkursi/index.html?current_page=$pageNum&regTabs=5&menuTab=10&TypeStruct=");
		}

		$itemIds = $this->saveItems($items);
		$this->queueTweets($itemIds);
	}

	// TODO: Figure out why this is different
	protected function loader($categoryId, $categoryURL)
	{
		return $this->loadURL($categoryURL, null);
	}
}
