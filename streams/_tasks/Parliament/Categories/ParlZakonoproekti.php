<?php

class ParlZakonoproekti extends Parliament
{

	protected $categoryId = 0;
	protected $categoryName = 'законопроекти';
	protected $categoryURL = 'http://parliament.bg/bg/bills';

	function execute($html)
	{
		$xpath = $this->xpathDoc($html);
		if (!$xpath) return;
		$items = $xpath->query("//table[@class='billsresult']//tr[not(@class)]");
		if (is_null($items)) return;

		$queryGov = array();
		$query = array();
		foreach ($items as $item) {
			$hash = md5($item->childNodes->item(0)->childNodes->item(1)->getAttribute("href"));
			$date = trim($item->childNodes->item(4)->textContent);
			$date = substr($date, 6, 4) . "-" . substr($date, 3, 2) . "-" . substr($date, 0, 2);
			if (strtotime($date) < strtotime("-1 month"))
				continue;
			$url = $item->childNodes->item(0)->childNodes->item(1)->getAttribute("href");
			$url = "http://parliament.bg$url";
			$title = $item->childNodes->item(0)->textContent;
			if (mb_strlen($title) > 100) {
				$title = mb_ereg_replace("Законопроект за изменение и допълнение", "ЗпИД", $title, "im");
				$title = mb_ereg_replace("Законопроект", "Зп", $title, "im");
			}
			$title = mb_ereg_replace("ЗИД", "ЗпИД", $title, "im");
			$title = $this->cleanText($title);

			$importer = $this->cleanText($item->childNodes->item(6)->textContent);
			$importer = mb_convert_case($importer, MB_CASE_LOWER);

			if ($importer == "министерски съвет")
				$queryGov[] = array($title, null, $date, $url, $hash);
			else
				$query[] = array($title, null, $date, $url, $hash);
		}

		echo "Възможни " . (count($query) + count($queryGov)) . " нови законопроекта\n";

		$itemids = $this->saveItems($query);
		$this->queueTweets($itemids, 'narodnosabranie', true);

		$itemids = $this->saveItems($queryGov);
		$this->queueTweets($itemids, 'narodnosabranie', ['GovAlertEU', 'GovBulgaria']);

	}

} 