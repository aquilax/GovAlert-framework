<?php

class VssDnevenRed extends Vss
{

	protected $categoryId = 0;
	protected $categoryName = 'дневен ред';
	protected $categoryURL = '';
	protected $categoryURLName = 'Дневен ред';

	function execute($html)
	{
		if (!$html) return;
		$items = $this->xpathDoc($html, "//td//a[@class='link']");
		$baseurl = substr($this->categoryURL, 0, strrpos($this->categoryURL, "/") + 1);

		$query = array();
		foreach ($items as $item) {
			$date = trim($item->textContent);
			$date = "20" . mb_substr($date, -2) . "-" . mb_substr($date, -5, 2) . "-" . mb_substr($date, -8, 2);
			if (strtotime($date) < strtotime("-1 week"))
				continue;

			$url = $baseurl . $item->getAttribute("href");
			$hash = md5($url);

			$html1 = $this->loadURL($url);
			if (!$html1) continue;
			$html1 = mb_convert_encoding($html1, 'UTF-8', 'cp1251');
			mb_ereg_search_init($html1);
			mb_ereg_search(">\s+(\d+)\. ");
			$points = "";
			while ($match = mb_ereg_search_regs())
				if (count($match) == 2)
					$points = intval($match[1]);
			if ($points != "")
				$points = "от $points точки ";

			$title = $item->textContent;
			$title = $this->cleanText($title);
			$title = mb_ereg_replace("№ ", "№", $title, "im");
			$title = mb_ereg_replace(" ", " на ", $title, "im");
			$title = "Публикуван е дневният ред " . $points . "за заседание " . $title;

			$query[] = [
				'title' => $title,
				'description' => null,
				'date' => Utils::now(),
				'url' => $url,
				'hash' => $hash,
			];

		}
		echo "Възможни " . count($query) . " нов запис за дневен ред\n";

		$itemids = $this->saveItems($query);
		$this->queueTweets($itemids);
	}

} 