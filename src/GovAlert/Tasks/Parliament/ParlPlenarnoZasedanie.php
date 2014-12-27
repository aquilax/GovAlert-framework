<?php

namespace GovAlert\Tasks\Parliament;

class ParlPlenarnoZasedanie extends Base
{

	protected $categoryId = 2;
	protected $categoryName = 'пленарно заседание';
	protected $categoryURL = 'http://parliament.bg/bg/plenaryprogram';

	function execute($html)
	{
		if (mb_strpos($html, "Програмата ще бъде публикувана") !== false)
			return;

		$xpath = $this->xpathDoc($html);
		if (!$xpath) return;

		$items = $xpath->query("//div[@class='marktitle']/div[@class='dateclass']");
		if (is_null($items) || $items->length != 1) return;
		$dates = $items->item(0)->textContent;
		$dates = $this->cleanText(str_replace("/", ".", $dates));
		$dates = substr($dates, 0, 5) . "-" . substr($dates, 13);

		$items = $xpath->query("//div[@class='markframe']//ol[@class='frontList']/li");
		if (is_null($items)) return;
		$count = $items->length;
		if ($count == 0)
			$count = "";
		elseif ($count == 1)
			$count = " от една точка";
		else
			$count = " oт $count точки";
		$title = "Програма за работата на Народното събрание в периода $dates$count";

		$items = $xpath->query("//div[@class='markframe']");
		if (is_null($items) || $items->length == 0) return;
		$description = $items->item(0)->C14N();
		$hash = md5($description);

		$description = mb_ereg_replace(" </", "</", mb_ereg_replace("> ", ">", $description));
		$description = mb_ereg_replace("\s?(title|name|style|class|id)=[\"'].*?[\"']\s?", "", $description);
		$description = mb_ereg_replace("<p>[  ]*</p>|<a>[  ]*</a>|<div>[  ]*</div>|</?img.*?>", "", $description);
		$description = $this->cleanText($description);

		$items = $xpath->query("//div[@class='rightinfo']/ul[@class='frontList']/li/a");
		if (is_null($items) || $items->length == 0) return;
		$url = $items->item(0)->getAttribute("href");

		return [[$title, $description, "now", "http://parliament.bg$url", $hash]];
	}

} 