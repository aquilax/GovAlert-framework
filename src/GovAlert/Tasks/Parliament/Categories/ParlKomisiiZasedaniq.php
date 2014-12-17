<?php

class ParlKomisiiZasedaniq extends Parliament
{

	protected $categoryId = 9;
	protected $categoryName = 'заседания на комисии';
	protected $categoryURL = '';
	protected $tweetReTweet = true;

	function execute($html)
	{
		$checkUrls = array();
		$res = $this->db->query("SELECT committee_id FROM s_parliament_committees order by committee_id");
		while ($row = $res->fetch_array()) {
			$checkUrls[] = "http://parliament.bg/bg/parliamentarycommittees/members/" . $row[0] . "/sittings/period/" . date("Y-m");
			$checkUrls[] = "http://parliament.bg/bg/parliamentarycommittees/members/" . $row[0] . "/sittings/period/" . date("Y-m", strtotime("+1 month"));
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

				$html1 = $this->loadURL($url);
				if (!$html1) continue;
				$xpath1 = $this->xpathDoc($html1);
				if (!$xpath1) continue;

				$items1 = $xpath1->query("//div[@class='marktitle']");
				$title = $this->cleanText($items1->item(0)->firstChild->textContent);
				$items1 = $xpath1->query("//div[@class='marktitle']/div[@class='dateclass']");
				$dateF = $this->cleanText($items1->item(0)->firstChild->textContent);
				$dateF = str_replace("/", ".", str_replace(", ", " от ", $dateF));

				$title = "Заседание на $dateF на $title";
				$query[] = [
					'title' => $title,
					'description' => null,
					'date' => \GovAlert\Common\Database::now(),
					'url' => $url,
					'hash' => $hash,
				];
			}
		}
		return array_reverse($query);
	}


	protected function loader($categoryId, $categoryURL)
	{
		return 'placeholder';
	}

} 