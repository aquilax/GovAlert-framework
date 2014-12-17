<?php

//ne e napraveno

/*
links:
0: документи и поръчки http://rop3-app1.aop.bg:7778/portal/page?_pageid=93,662251&_dad=portal&_schema=PORTAL
1: публична покана http://rop3-app1.aop.bg:7778/portal/page?_pageid=93,1488254&_dad=portal&_schema=PORTAL&url=687474703A2F2F7777772E616F702E62672F657365617263685F7070322E706870
*/

namespace GovAlert\Tasks\Aop;

class Aop_Saobshteniq extends Aop
{
	protected $categoryId = 0;
	protected $categoryName = 'документи';
	protected $categoryURL = 'http://rop3-app1.aop.bg:7778/portal/page?_pageid=93,662251&_dad=portal&_schema=PORTAL';
	protected $categoryPostData = 'go_page=0&doc_description=&u_id=&key_word=&btn_pressed=%D0%A2%D1%8A%D1%80%D1%81%D0%B8+...';

	protected function execute($html)
	{
		if (!$this->checkPageChanged($html, 12, 0))
			return;
		$items = $this->getXPathItems($this->getXPath($html), "//table[@id='resultaTable']//tr");
// TODO: Figure this out
		echo $items->length;
		exit;
//
//		$info = array();
//		$query=array();
//		foreach ($items as $item) {
//			$hash = md5($item->textContent);
//			$date = trim($item->childNodes->item(1)->textContent);
//			$date = substr($date,6,4)."-".substr($date,3,2)."-".substr($date,0,2);
//			$date = $link->escape_string($date);
//			$item->removeAttribute("class");
//			$item->removeChild($item->childNodes->item(1));
//			$item->removeChild($item->childNodes->item(0));
//			$description = $item->C14N();
//			$description = mb_ereg_replace(" </","</",mb_ereg_replace("> ",">",$description));
//			$description = mb_ereg_replace("\s?(title|name|style|class|id)=[\"'].*?[\"']\s?","",$description);
//			$description = mb_ereg_replace("<p>[  ]*</p>|<a>[  ]*</a>|<div>[  ]*</div>","",$description);
//			$description = cik_cleanText($description);
//			$description = $link->escape_string($description);
//			$title = $item->textContent;
//			$title = cik_cleanTitle($title);
//			$title = "Съобщение: ".cik_cleanText($title);
//			$title = $link->escape_string($title);
//			$query[]=array($title,$description,1,$date,'http://www.cik.bg/',$hash);
//		}
//
//		$itemids = $this->saveItems($query);
//		queueTweets($itemids);
	}

	/*
	-----------------------------------------------------------------
	*/

	protected function loader($categoryId, $categoryURL)
	{
		return $this->httpPost($this->categoryURL, $this->$categoryPostData);
	}

} 