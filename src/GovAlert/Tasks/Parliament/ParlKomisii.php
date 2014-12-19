<?php

namespace GovAlert\Tasks\Parliament;

class ParlKomisii extends Base
{

	protected $categoryId = 8;
	protected $categoryName = 'комисии';
	protected $categoryURL = 'http://parliament.bg/bg/parliamentarycommittees';
	protected $tweetReTweet = true;

	function execute($html)
	{
		$xpath = $this->xpathDoc($html);
		if (!$xpath) return;
		$items = $xpath->query("//label[@for]/a");

		$commissionids = array();
		$res = $this->db->query("SELECT committee_id FROM s_parliament_committees order by committee_id");
		while ($row = $res->fetch_array()) {
			$commissionids[] = $row[0];
		}
		$res->free();

		$commissions = array();
		foreach ($items as $item) {
			$id = $item->getAttribute("href");
			$id = substr($id, strrpos($id, '/') + 1);
			$id = intval($id);
			if (in_array($id, $commissionids))
				continue;
			$title = $this->cleanText($item->textContent);
			$commissions[] = array($id, $title);
		}
		if (count($commissions) == 0)
			return;

		$this->logger->info('Има ' . count($commissions) . ' нови комисии');

		$query = array();
		foreach ($commissions as $commission) {
			$this->db->insert('s_parliament_committees', [
				'committee_id' => $commission[0],
				'name' => $commission[1]
			]);

			$title = "Нова комисия: " . $commission[1];
			$url = "http://parliament.bg/bg/parliamentarycommittees/members/" . $commission[0];
			$hash = md5($url);
			$query[] = [
				'title' => $title,
				'description' => null,
				'date' => $this->db->now(),
				'url' => $url,
				'hash' => $hash,
			];
		}
		return $query;
	}

	// TODO: Figure this out
	protected function loader($categoryId, $categoryURL)
	{
		return $this->loadURL($categoryURL, 10);
	}

} 