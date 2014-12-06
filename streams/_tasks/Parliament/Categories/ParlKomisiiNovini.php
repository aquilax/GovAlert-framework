<?php

class ParlKomisiiNovini extends Parliament
{

	protected $categoryId = 10;
	protected $categoryName = 'новини на комисии';
	protected $categoryURL = '';

	function execute($html)
	{
		$checkUrls = array();
		$res = $this->db->query("SELECT committee_id FROM s_parliament_committees order by committee_id");
		while ($row = $res->fetch_array()) {
			$checkUrls[] = "http://parliament.bg/bg/parliamentarycommittees/members/" . $row[0] . "/news/period/" . date("Y-m");
			$checkUrls[] = "http://parliament.bg/bg/parliamentarycommittees/members/" . $row[0] . "/news/period/" . date("Y-m", strtotime("-1 month"));
		}
		$res->free();

		$query = array();
		foreach ($checkUrls as $checkUrl) {
			$html = $this->loadURL($checkUrl);
			if (!$html) continue;
			$xpath = $this->xpathDoc($html);
			if (!$xpath) continue;
			$items = $xpath->query("//div[@id='monthview']//li/a");

			foreach ($items as $item) {
				$url = 'http://parliament.bg' . $item->getAttribute("href");
				$hash = md5($url);
				if (!$this->checkHash($hash))
					continue;

				$title = $this->cleanText($item->textContent);
				$title = "Новина от комисия: $title";
				$query[] = [
					'title' => $title,
					'description' => null,
					'date' => Utils::now(),
					'url' => $url,
					'hash' => $hash,
				];
			}
		}

		echo "Възможни " . count($query) . " нови новини\n";
		$query = array_reverse($query);

		$itemids = $this->saveItems($query);
		$this->queueTweets($itemids, 'narodnosabranie', true);
	}

	protected function loader($categoryId, $categoryURL)
	{
		return 'placeholder';
	}

} 