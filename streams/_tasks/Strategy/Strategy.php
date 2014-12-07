<?php

class Strategy extends Task
{

	protected $sourceId = 2;
	protected $categoryId = 0;
	protected $tweetReTweet = 'GovBulgaria';

	function mailStrategy($mail)
	{
		$mail = strstr($mail, "\n\n");
		$mail = str_replace("\n", "", $mail);
		$mail = base64_decode($mail);
		$linkstart = strpos($mail, "<a href=\"") + strlen("<a href=\"");
		$url = substr($mail, $linkstart, strpos($mail, "\">") - $linkstart);
		if ($url) {
			$this->strategy_processUrl($url);
		};
	}

	function strategy_processUrl($url)
	{
		$html = $this->loadURL($url);
		$items = $this->getXPathItems($this->getXPath($html), "//div[@class='public_info_strategic']");

		if ($items->length == 0)
			return;
		$item = $items->item(0);

		$hash = md5($item->textContent);
		$title = $item->childNodes->item(1)->textContent;
		$title = $this->cleanText($title);
		$title = Utils::fixCase($title);
		$item->removeChild($item->childNodes->item(0));
		$description = $item->C14N();
		$description = mb_ereg_replace(" </", "</", mb_ereg_replace("> ", ">", $description));
		$description = mb_ereg_replace("\s?(title|name|style|id|class|xml\:lang)=[\"'].*?[\"']", "", $description);
		$description = $this->cleanText($description);
		$description = str_replace("\\r ", "", $description);
		$description = mb_ereg_replace("<p>[  ]*</p>|<a>[  ]*</a>|<div>[  ]*</div>", "", $description);
		return [[$title, $description, "now", $url, $hash]];
	}

	function cleanText($text)
	{
		$text = str_replace(" ", " ", $text);
		$text = mb_ereg_replace("[\n\r\t ]+", " ", $text);
		$text = mb_ereg_replace("(^\s+)|(\s+$)", "", $text);
		$text = html_entity_decode($text);
		return $text;
	}

	function execute($html) {
		// placeholder
	}
}
