<?php

class Bnb_Saobshtenia extends Bnb
{

	protected $categoryId = 0;
	protected $categoryName = 'съобщения';
	protected $categoryURL = 'http://bnb.bg/PressOffice/POPressReleases/POPRDate/index.htm';

	protected function execute($html)
	{
		$items = $this->xpathDoc($html, "//div[@id='main']//h3/a");
		var_dump($items);
		if (!$items || $items->length == 0) {
			$this->reportError('Грешка при зареждане на страницата');
			return;
		}

		$query = array();
		foreach ($items as $item) {
			$date = trim($item->textContent);
			$date = mb_substr($date, 6, 4) . "-" . mb_substr($date, 3, 2) . "-" . mb_substr($date, 0, 2);
			if (strtotime($date) < strtotime("-3 day")) {
				continue;
			}
			$url = "http://bnb.bg/PressOffice/POPressReleases/POPRDate/" . $item->getAttribute("href");
			$hash = md5($url);

			$html1 = $this->loadURL($url);
			if (!$html1) {
				return;
			}
			$items1 = $this->xpathDoc($html1, "//div[@class='doc_entry']");
			if (!$items1 || $items1->length == 0) {
				$this->reportError("Грешка при зареждане на отделно съобщение");
				return;
			}
			$title = $items1->item(0)->textContent;
			$title = $this->cleanText($title);
			if (mb_strpos($title, "ПРЕССЪОБЩЕНИЕ") !== false) {
				if (mb_strpos($title, "г.") !== null && mb_strpos($title, "г.") < 50) {
					$title = mb_substr($title, mb_strpos($title, "г.") + 3);
				} else {
					$title = mb_substr($title, 14);
				}
			}
			$title = "Съобщение: $title";

			$description = $items1->item(0)->C14N();
			$description = $this->cleanDescr($description);

			$query[] = [
				'title' => $title,
				'description' => $description,
				'date' => $date,
				'url' => $url,
				'hash' => $hash,
			];
		}
		$this->logger->info('Възможни ' . count($query) . ' нови съобщения');
		$itemIds = $this->saveItems($query);
		$this->queueTweets($itemIds);
	}

} 