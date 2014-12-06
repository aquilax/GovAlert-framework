<?php

class ParlKomisiiDokladi extends Parliament
{

	protected $categoryId = 12;
	protected $categoryName = 'доклади на комисии';
	protected $categoryURL = '';


	function execute($html)
	{
		$checks = array();
		$res = $this->db->query("SELECT committee_id, name FROM s_parliament_committees order by committee_id");
		while ($row = $res->fetch_array()) {
			$checks[] = array("http://parliament.bg/bg/parliamentarycommittees/members/" . $row[0] . "/reports/period/" . date("Y-m"), $row[01]);
			$checks[] = array("http://parliament.bg/bg/parliamentarycommittees/members/" . $row[0] . "/reports/period/" . date("Y-m", strtotime("-1 month")), $row[1]);
		}
		$res->free();

		$query = array();
		foreach ($checks as $check) {
			$html = $this->loadURL($check[0]);
			if (!$html) continue;
			$xpath = $this->xpathDoc($html);
			if (!$xpath) continue;
			$items = $xpath->query("//div[@id='monthview']//li");

			foreach ($items as $item) {
				$url = 'http://parliament.bg' . $item->firstChild->getAttribute("href");
				$hash = md5($url);
				if (!$this->checkHash($hash))
					continue;

				$dateP = $this->cleanText($item->lastChild->textContent);
				$dateP = substr(str_replace("/", ".", $dateP), 2);

				$title = "Доклад от заседанието на $dateP на " . $check[1];

				$query[] = [
					'title' => $title,
					'description' => null,
					'date' => Utils::now(),
					'url' => $url,
					'hash' => $hash,
				];
			}
		}

		echo "Възможни " . count($query) . " нови доклади\n";
		$query = array_reverse($query);

		$itemids = $this->saveItems($query);
		$this->queueTweets($itemids, 'narodnosabranie');
	}

	protected function loader($categoryId, $categoryURL)
	{
		return 'placeholder';
	}

} 