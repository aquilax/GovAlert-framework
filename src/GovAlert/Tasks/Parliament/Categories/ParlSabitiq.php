<?php

class ParlSabitiq extends Parliament
{

	protected $categoryId = 6;
	protected $categoryName = 'събития';
	protected $categoryURL = 'http://parliament.bg/bg/calendar';

	function execute($html)
	{
		$xpath = $this->xpathDoc($html);
		if (!$xpath) return;
		$items = $xpath->query("//div[@class='markframe']//*[local-name()='div' or local-name()='li']");
		if (is_null($items)) return;

		$currentDateT = false;
		$currentDate = false;
		$query = array();
		foreach ($items as $item) {
			if ($item->nodeName == 'div') {
				if ($currentDate != false && count($query) > 0) {
					$query = array_reverse($query);
					$itemids = $this->saveItems($query);
					if (count($itemids) <= 3)
						$this->queueTweets($itemids, 'narodnosabranie');
					else
						$this->queueTextTweet("Планирани са " . count($itemids) . " нови събития за $currentDateT", "http://parliament.bg/bg/calendar", 'narodnosabranie');
				}

				$currentDate = $item->textContent;
				$currentDate = substr($currentDate, -10, 2) . "." . substr($currentDate, -7, 2);
				$currentDateT = $item->textContent;
				$currentDateT = str_replace("/", ".", $currentDateT);

				$query = array();
			} else {
				if ($currentDate == false) {
					$this->reportError("Грешка в събитията на парламента");
				}
				$time = trim($item->childNodes->item(1)->textContent);
				$date = "$currentDate $time";
				if (strtotime($date) < $this->db->time())
					continue;

				if ($item->childNodes->item(3)->nodeName == "a")
					$url = $item->childNodes->item(3)->getAttribute("href");
				else
					$url = "/bg/calendar#" . $item->childNodes->item(0)->getAttribute("name");
				$hash = md5($url);
				$item->removeChild($item->childNodes->item(1));
				$item->removeChild($item->childNodes->item(0));
				$title = $item->textContent;
				$title = $this->cleanText($title);
				$title = "Събитие [$date] $title";
				$description = $item->C14N();
				$description = mb_ereg_replace(" </", "</", mb_ereg_replace("> ", ">", $description));
				$description = mb_ereg_replace("\s?(title|name|style|class|id)=[\"'].*?[\"']\s?", "", $description);
				$description = mb_ereg_replace("<br>[  ]*</br>|<p>[  ]*</p>|<a>[  ]*</a>|<div>[  ]*</div>", "", $description);
				$description = $this->cleanText($description);
				$query[] = [
					'title' => $title,
					'description' => $description,
					'date' => \GovAlert\Common\Database::now(),
					'url' => 'http://parliament.bg' . $url,
					'hash' => $hash,
				];
			}
		}

		if ($currentDate != false && count($query) > 0) {
			$this->logger->info('Възможни ' . count($query) . ' нови събития');
			$query = array_reverse($query);
			$itemids = $this->saveItems($query);
			if (count($itemids) <= 5)
				$this->queueTweets($itemids, 'narodnosabranie');
			else
				$this->queueTextTweet("Планирани са " . count($itemids) . " нови събития за $currentDateT", "http://parliament.bg/bg/calendar", 'narodnosabranie');
		}
	}

	// TODO: Figure this out
	protected function loader($categoryId, $categoryURL)
	{
		return $this->loadURL($categoryURL, 7);
	}

} 