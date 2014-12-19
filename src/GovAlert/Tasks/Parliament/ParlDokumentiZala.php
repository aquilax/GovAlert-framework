<?php

namespace GovAlert\Tasks\Parliament;

class ParlDokumentiZala extends Base
{
	protected $categoryId = 4;
	protected $categoryName = 'документи в зала';
	protected $categoryURL = 'http://parliament.bg/bg/doc';

	function execute($html)
	{
		$xpath = $this->xpathDoc($html);
		$items = $xpath->query("//ul[@class='frontList1']/li/a");
		if (is_null($items)) {
			return;
		}

		$query = array();
		foreach ($items as $item) {
			$hash = md5($item->getAttribute("href"));
			$url = $item->getAttribute("href");
			$title = $item->textContent;
			$title = $this->cleanText($title);
			$title = str_replace("/", ".", $title);
			$title = "Качени са документите за пленарна зала за $title";

			$date = mb_substr($item->textContent, -10);
			$date = substr($date, 6, 4) . "-" . substr($date, 3, 2) . "-" . substr($date, 0, 2);
			if (strtotime($date) > $this->timeDiff('-1 week')) {
				$conn_id = ftp_connect("193.109.55.85");
				if (!$conn_id) continue;
				$login_result = ftp_login($conn_id, "anonymous", "");
				if (!$login_result) continue;
				$contents = ftp_nlist($conn_id, substr($url, -11));
				if (!$contents || count($contents) == 0)
					continue;
			} else {
				continue;
			}
			$query[] = [
				'title' => $title,
				'description' => null,
				'date' => $date,
				'url' => 'http://parliament.bg' . $url,
				'hash' => $hash,
			];
		}
		return $query;
	}

} 