<?php

namespace GovAlert\Tasks\Vss;

class VssProtokol extends Base
{

	protected $categoryId = 1;
	protected $categoryName = 'протоколи';
	protected $categoryURL = '';
	protected $categoryURLName = 'Протоколи';

	function execute($html)
	{
		$items = $this->xpathDoc($html, "//td//a[@class='link']");

		$query = array();
		foreach ($items as $item) {
			$date = trim($item->textContent);
			$date = "20" . mb_substr($date, -2) . "-" . mb_substr($date, -5, 2) . "-" . mb_substr($date, -8, 2);
			if (strtotime($date) < $this->timeDiff('-1 month'))
				continue;

			$title = $item->textContent;
			$title = $this->cleanText($title);
			$title = mb_ereg_replace("№ ", "№", $title, "im");
			$title = mb_ereg_replace(" ", " на ", $title, "im");
			$title = "Публикуван е протокол от заседание " . $title;

			$url = $this->categoryURL . $item->getAttribute("href");
			$hash = md5($url);

			$query[] = [
				'title' => $title,
				'description' => null,
				'date' => $this->db->now(),
				'url' => $url,
				'hash' => $hash,
			];
		}
		return array_reverse($query);
	}

}
