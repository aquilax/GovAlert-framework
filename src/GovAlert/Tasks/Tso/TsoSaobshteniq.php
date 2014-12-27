<?php

namespace GovAlert\Tasks\Tso;

class TsoSaobshteniq extends Base
{

	protected $categoryId = 1;
	protected $categoryName = 'съобщения';
	protected $categoryURL = 'http://www.tso.bg/default.aspx/saobshtenija/bg';

	function execute($html)
	{
		$xpath = $this->xpathDoc($html);
		if (!$xpath) {
			return;
		}
		$items = $xpath->query("//table[@id='ctl7_myDataList']//td");
		if (!$items) {
			return;
		}

		$query = [];
		foreach ($items as $item) {
			$title = $item->childNodes->item(1)->textContent;
			$title = "Съобщение: " . $this->cleanText($title);

			$description = $item->childNodes->item(4)->C14N();
			$description = mb_ereg_replace(" </", "</", mb_ereg_replace("> ", ">", $description));
			$description = mb_ereg_replace("\s?(title|name|style|class|id)=[\"'].*?[\"']\s?", "", $description);
			$description = mb_ereg_replace("<p>[  ]*</p>|<a>[  ]*</a>|<div>[  ]*</div>", "", $description);
			$description = $this->cleanText($description);

			$urlItems = $xpath->query(".//a", $item);
			if ($urlItems->length > 0) {
				$url = $urlItems->item(0)->getAttribute("href");
				$url = mb_strpos($url, "http") != 0 ? "http://www.tso.bg$url" : $url;
				$hash = md5($url);
			} else {
				$url = "http://www.tso.bg/default.aspx/saobshtenija/bg";
				$hash = md5($item->textContent);
			}

			$query[] = [
				'title' => $title,
				'description' => $description,
				'date' => $this->db->now(),
				'url' => $url,
				'hash' => $hash,
			];
		}
		return $query;
	}
}
