<?php

namespace GovAlert\Tasks\Parliament;

class ParlKomisiiStenogrami extends Base
{

	protected $categoryId = 13;
	protected $categoryName = 'стенограми на комисии';
	protected $categoryURL = null;

	function execute($html)
	{
		$checks = [];
		$res = $this->db->query("SELECT committee_id, name FROM s_parliament_committees order by committee_id");
		while ($row = $res->fetch_array()) {
			$checks[] = array("http://parliament.bg/bg/parliamentarycommittees/members/" . $row[0] . "/steno/period/" . date("Y-m"), $row[01]);
			$checks[] = array("http://parliament.bg/bg/parliamentarycommittees/members/" . $row[0] . "/steno/period/" . date("Y-m", $this->timeDiff('-1 month')), $row[1]);
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

				$title = "Стенограма от заседанието на $dateP на " . $check[1];
				$query[] = [
					'title' => $title,
					'description' => null,
					'date' => $this->db->now(),
					'url' => $url,
					'hash' => $hash,
				];
			}
		}
		return array_reverse($query);
	}
}
