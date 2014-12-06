<?php

class ParlKomisii extends Parliament
{

	protected $categoryId = 8;
	protected $categoryName = 'комисии';
	protected $categoryURL = 'http://parliament.bg/bg/parliamentarycommittees';

	function execute($html)
	{
		if (!$html) return;
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
			$title = $this->db->escape_string($title);
			$commissions[] = array($id, $title);
		}
		if (count($commissions) == 0)
			return;

		echo "Има " . count($commissions) . " нови комисии\n";

		$query = array();
		foreach ($commissions as $commission) {
			$this->db->query("insert LOW_PRIORITY ignore into s_parliament_committees (committee_id,name) value (" . $commission[0] . ",'" . $commission[1] . "')");
			$title = "Нова комисия: " . $commission[1];
			$url = "http://parliament.bg/bg/parliamentarycommittees/members/" . $commission[0];
			$hash = md5($url);
			$query[] = array($title, null, 'now', $url, $hash);
		}
		$query = array_reverse($query);
		$itemids = $this->saveItems($query);
		$this->queueTweets($itemids, 'narodnosabranie', true);
	}

	// TODO: Figure this out
	protected function loader($categoryId, $categoryURL)
	{
		return $this->loadURL($categoryURL, 10);
	}

} 