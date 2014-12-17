<?php

class ParlKomisiiDokumenti extends Parliament
{

	protected $categoryId = 11;
	protected $categoryName = 'документи на комисии';
	protected $categoryURL = '';

	function execute($html)
	{

		$res = $this->db->query("SELECT committee_id, name FROM s_parliament_committees order by committee_id");
		while ($row = $res->fetch_array()) {
			$commName = $row[1];
			$html = $this->loadURL("http://parliament.bg/bg/parliamentarycommittees/members/" . $row[0] . "/documents");
			if (!$html) continue;
			$xpath = $this->xpathDoc($html);
			if (!$xpath) continue;
			$items = $xpath->query("//div[@class='markframe']//div[@class='MProw']/a");

			$query = array();
			foreach ($items as $item) {
				$url = 'http://parliament.bg' . $item->getAttribute("href");
				$hash = md5($url);
				if (!$this->checkHash($hash))
					continue;

				$title = $this->cleanText($item->textContent);
				$title = "Документ в комисия: $title";
				$query[] = [
					'title' => $title,
					'description' => null,
					'date' => Utils::now(),
					'url' => $url,
					'hash' => $hash,
				];
			}
			$this->logger->info('Възможни ' . count($query) . ' нови ' . $this->categoryName);

			$itemids = $this->saveItems($query);
			if (count($itemids) <= 4)
				$this->queueTweets($itemids, 'narodnosabranie');
			else
				$this->queueTextTweet("Качени са " . count($itemids) . " нови документа в $commName", "http://parliament.bg/bg/parliamentarycommittees/members/" . $row[0] . "/documents", 'narodnosabranie');
		}
		$res->free();
	}

	protected function loader($categoryId, $categoryURL)
	{
		return 'placeholder';
	}

} 