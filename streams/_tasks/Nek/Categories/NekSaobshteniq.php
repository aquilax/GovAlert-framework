<?php

class NekSaobshteniq extends Nek
{

	protected $categoryId = 0;
	protected $categoryName = 'съобщения';
	protected $categoryURL = 'http://www.nek.bg/cgi?d=101';

	function execute($html)
	{
		// TODO: Figure this out
		$html = mb_convert_encoding($html, "utf8", "cp1251");
		$items = $this->getXPathItems($this->getXPath($html), "//div[@class='subpage']//li");

		$query = array();
		foreach ($items as $item) {
			if (count($query) > 15)
				break;
			$hash = md5($item->textContent);

			$date = $item->childNodes->item(0)->textContent;
			$date = mb_substr($date, 9, 4) . "-" . mb_substr($date, 5, 2) . "-" . mb_substr($date, 1, 2);
			if (strtotime($date) < strtotime("-1 month"))
				continue;
			$url = $item->childNodes->item(2)->getAttribute("href");
			$title = $item->childNodes->item(2)->textContent;
			$title = mb_ereg_replace("П Р Е С С Ъ О Б Щ Е Н И Е", "Прессъобщение", $title, "im");
			$title = mb_ereg_replace("О Б Я В Л Е Н И Е", "Обявление", $title, "im");
			$title = "Съобщение: " . $this->cleanText($title);
			$query[] = [
				'title' => $title,
				'description' => null,
				'date' => $date,
				'url' => 'http://www.nek.bg' . $url,
				'hash' => $hash,
			];
		}
		return $query;
	}
}
